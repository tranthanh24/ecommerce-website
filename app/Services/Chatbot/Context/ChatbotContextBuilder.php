<?php

namespace App\Services\Chatbot\Context;

use App\Models\User;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ShippingRule;
use App\Services\Chatbot\ChatbotSettings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ChatbotContextBuilder
{
  public function __construct(
    private readonly ChatbotSettings $settings
  ) {}

  // Xây dựng context tổng cho một tin nhắn
  public function build(string $message, ?User $user): array
  {
    $settings = $this->settings->get();
    $productLimit = max(3, min(20, (int) ($settings['max_product_results'] ?? 10)));
    $intentMeta = $this->analyzeIntents($message, $user);

    $products = in_array('product_discovery', $intentMeta['intents'], true) || in_array('cart_action', $intentMeta['intents'], true)
      ? $this->searchProducts($message, $productLimit)
      : [];
    $orders = in_array('order_lookup', $intentMeta['intents'], true) ? $this->getRecentOrders($user) : [];
    $shippingRules = in_array('shipping_lookup', $intentMeta['intents'], true) ? $this->getShippingRules() : [];
    $coupons = in_array('coupon_lookup', $intentMeta['intents'], true) ? $this->getCouponsForMessage($message) : [];

    return [
      'products' => $products,
      'orders' => $orders,
      'shipping_rules' => $shippingRules,
      'coupons' => $coupons,
      'intent_meta' => $intentMeta,
    ];
  }

  // Phân tích intent và cách route xử lý
  public function analyzeIntents(string $message, ?User $user): array
  {
    $intents = [];

    if ($this->shouldSuggestProducts($message)) {
      $intents[] = 'product_discovery';
    }
    if ($this->shouldIncludeOrders($message, $user)) {
      $intents[] = 'order_lookup';
    }
    if ($this->shouldIncludeShipping($message)) {
      $intents[] = 'shipping_lookup';
    }
    if ($this->shouldIncludeCoupons($message)) {
      $intents[] = 'coupon_lookup';
    }
    if ($this->isCartActionMessage($message)) {
      $intents[] = 'cart_action';
    }
    if ($this->isOrderActionMessage($message)) {
      $intents[] = 'order_action';
    }

    if (empty($intents)) {
      $intents[] = 'general_qa';
    }

    $execution = 'sequential';
    $parallelSafePairs = [
      'product_discovery+coupon_lookup',
      'product_discovery+shipping_lookup',
      'coupon_lookup+shipping_lookup',
    ];

    if (count($intents) === 2) {
      $pair = implode('+', array_values($intents));
      $reversePair = implode('+', array_reverse(array_values($intents)));
      if (in_array($pair, $parallelSafePairs, true) || in_array($reversePair, $parallelSafePairs, true)) {
        $execution = 'parallel';
      }
    }

    return [
      'intents' => array_values(array_unique($intents)),
      'execution' => $execution,
    ];
  }

  // Xác định có cần lấy mã giảm giá hay không
  public function shouldIncludeCoupons(string $message): bool
  {
    $mes = Str::lower($message);
    $keywords = ChatbotKeywords::COUPON;

    foreach ($keywords as $keyword) {
      if (Str::contains($mes, $keyword)) {
        return true;
      }
    }

    if (Str::contains($mes, ['ma', 'code']) && preg_match('/\b[A-Z0-9][A-Z0-9_-]{2,}\b/i', $message)) {
      return true;
    }

    return $this->extractCouponCode($message) !== null;
  }

  // Tìm và rank sản phẩm phù hợp
  private function searchProducts(string $message, int $limit): array
  {
    $terms = $this->extractSearchTerms($message);
    if ($terms->isEmpty() && !$this->shouldSuggestProducts($message)) {
      return [];
    }

    $expandedTerms = $this->expandQueryTerms($message, $terms);
    $products = $this->runRankedProductSearch($expandedTerms, $limit, $message);

    $topScore = (float) ($products[0]['relevance_score'] ?? 0);
    if ($topScore < 2.5) {
      $retryTerms = $this->expandQueryTerms($this->simplifyQuery($message), $terms);
      $retryProducts = $this->runRankedProductSearch($retryTerms, $limit, $message);
      $retryTopScore = (float) ($retryProducts[0]['relevance_score'] ?? 0);

      if ($retryTopScore > $topScore) {
        $products = $retryProducts;
      }
    }

    return collect($products)
      ->take($limit)
      ->map(function ($p) {
        $price = (float) ($p['price_value'] ?? 0);
        $priceText = number_format($price, 0, ',', '.') . ' VND';

        return [
          'name' => (string) ($p['name'] ?? ''),
          'price' => $priceText,
          'price_value' => $price,
          'url' => url('/product-detail/' . ($p['slug'] ?? '')),
          'short_description' => (string) ($p['short_description'] ?? ''),
          'long_description' => (string) ($p['long_description'] ?? ''),
          'relevance_score' => (float) ($p['relevance_score'] ?? 0),
        ];
      })
      ->values()
      ->all();
  }

  // Kiểm tra tin nhắn có ý định tìm sản phẩm
  private function shouldSuggestProducts(string $message): bool
  {
    $mes = Str::lower($message);
    $keywords = ChatbotKeywords::PRODUCT;

    foreach ($keywords as $keyword) {
      if (Str::contains($mes, $keyword)) {
        return true;
      }
    }

    return false;
  }

  // Tách từ khóa tìm kiếm từ câu người dùng
  private function extractSearchTerms(string $message): Collection
  {
    $clean = Str::of($message)
      ->lower()
      ->replaceMatches('/[^\p{L}\p{N}\s]/u', ' ')
      ->replaceMatches('/\s+/u', ' ')
      ->trim();

    $stop = ChatbotKeywords::STOP_WORDS;

    return collect(preg_split('/\s+/u', (string) $clean) ?: [])
      ->filter(function ($word) {
        if (!is_string($word)) {
          return false;
        }

        $word = trim($word);
        if ($word === '') {
          return false;
        }

        if (ctype_digit($word)) {
          return strlen($word) >= 2;
        }

        return mb_strlen($word) >= 3;
      })
      ->reject(fn($word) => in_array($word, $stop, true))
      ->unique()
      ->take(6)
      ->values();
  }

  // Mở rộng query bằng synonym và catalog
  private function expandQueryTerms(string $message, Collection $terms): Collection
  {
    $expanded = collect();
    $normalizedMessage = $this->normalizeSearchText($message);

    if ($normalizedMessage !== '') {
      $expanded->push($normalizedMessage);
    }
    foreach ($terms as $term) {
      $expanded->push($term);
    }

    $synonyms = $this->querySynonyms();
    foreach ($synonyms as $needle => $items) {
      if (!Str::contains($normalizedMessage, $needle)) {
        continue;
      }

      foreach ($items as $item) {
        $expanded->push($item);
      }
    }

    $catalogBoost = Product::query()
      ->where('status', 1)
      ->where('is_approved', 1)
      ->when($terms->isNotEmpty(), function ($query) use ($terms) {
        $query->where(function ($builder) use ($terms) {
          foreach ($terms as $term) {
            $builder->orWhere('name', 'like', '%' . $term . '%');
          }
        });
      })
      ->orderByDesc('id')
      ->limit(30)
      ->pluck('name');

    foreach ($catalogBoost as $name) {
      $cleanName = $this->normalizeSearchText((string) $name);
      if ($cleanName === '') {
        continue;
      }
      $expanded->push($cleanName);
    }

    return $expanded
      ->map(fn($term) => $this->normalizeSearchText((string) $term))
      ->filter(fn($term) => $term !== '' && mb_strlen($term) >= 3)
      ->unique()
      ->take(20)
      ->values();
  }

  // Truy vấn và sắp xếp kết quả tìm sản phẩm
  private function runRankedProductSearch(Collection $terms, int $limit, string $message): array
  {
    $products = Product::query()
      ->where('status', 1)
      ->where('is_approved', 1)
      ->when($terms->isNotEmpty(), function ($query) use ($terms) {
        $query->where(function ($builder) use ($terms) {
          foreach ($terms as $term) {
            $builder->orWhere('name', 'like', '%' . $term . '%')
              ->orWhere('short_description', 'like', '%' . $term . '%')
              ->orWhere('long_description', 'like', '%' . $term . '%');
          }
        });
      })
      ->orderByDesc('id')
      ->limit(max($limit * 8, 40))
      ->get(['name', 'slug', 'price', 'offer_price', 'short_description', 'long_description']);

    if ($products->isEmpty()) {
      return [];
    }

    $rows = $products
      ->map(function ($product) use ($terms, $message) {
        $price = (float) ($product->offer_price ?? $product->price ?? 0);
        $score = $this->scoreProduct($product, $terms, $message);

        return [
          'name' => (string) $product->name,
          'slug' => (string) $product->slug,
          'price_value' => $price,
          'short_description' => $this->normalizeDescription($product->short_description ?? '', 280),
          'long_description' => $this->normalizeDescription($product->long_description ?? '', 800),
          'relevance_score' => $score,
        ];
      })
      ->values();

    $wantsMostExpensive = $this->containsAny($message, ChatbotDictionary::RANK_MOST_EXPENSIVE_HINTS);
    $wantsCheapest = $this->containsAny($message, ChatbotDictionary::RANK_CHEAPEST_HINTS);

    if ($wantsMostExpensive) {
      return $rows->sortByDesc('price_value')->take($limit)->values()->all();
    }
    if ($wantsCheapest) {
      return $rows->sortBy('price_value')->take($limit)->values()->all();
    }

    return $rows
      ->sort(function ($a, $b) {
        $scoreCmp = ((float) ($b['relevance_score'] ?? 0)) <=> ((float) ($a['relevance_score'] ?? 0));
        if ($scoreCmp !== 0) {
          return $scoreCmp;
        }

        return ((float) ($b['price_value'] ?? 0)) <=> ((float) ($a['price_value'] ?? 0));
      })
      ->take($limit)
      ->values()
      ->all();
  }

  // Chấm điểm độ liên quan của từng sản phẩm
  private function scoreProduct(Product $product, Collection $terms, string $message): float
  {
    $name = $this->normalizeSearchText((string) ($product->name ?? ''));
    $short = $this->normalizeSearchText((string) ($product->short_description ?? ''));
    $long = $this->normalizeSearchText((string) ($product->long_description ?? ''));
    $query = $this->normalizeSearchText($message);

    $score = 0.0;

    if ($query !== '' && Str::contains($name, $query)) {
      $score += 3.0;
    }

    foreach ($terms as $term) {
      if (!is_string($term) || $term === '') {
        continue;
      }

      if ($term === $name) {
        $score += 3.0;
      }
      if (Str::contains($name, $term)) {
        $score += 2.0;
      }
      if (Str::contains($short, $term)) {
        $score += 1.0;
      }
      if (Str::contains($long, $term)) {
        $score += 0.5;
      }
    }

    return round($score, 3);
  }

  // Rút gọn query khi cần retry
  private function simplifyQuery(string $message): string
  {
    $clean = $this->normalizeSearchText($message);
    if ($clean === '') {
      return '';
    }

    foreach (ChatbotDictionary::QUERY_SIMPLIFY_MODIFIERS as $modifier) {
      $clean = str_replace($modifier, ' ', $clean);
    }

    $tokens = collect(preg_split('/\s+/u', $clean) ?: [])
      ->filter(fn($token) => is_string($token) && $token !== '')
      ->reject(fn($token) => in_array($token, ChatbotKeywords::STOP_WORDS, true))
      ->take(5)
      ->values();

    return $tokens->implode(' ');
  }

  // Chuẩn hóa text để so khớp ổn định
  private function normalizeSearchText(string $value): string
  {
    return (string) Str::of(Str::ascii($value))
      ->lower()
      ->replaceMatches('/[^\p{L}\p{N}\s]/u', ' ')
      ->replaceMatches('/\s+/u', ' ')
      ->trim();
  }

  // Lấy bộ synonym từ cache và catalog
  private function querySynonyms(): array
  {
    $settings = $this->settings->get();
    $cacheMinutes = max(5, (int) ($settings['synonyms_cache_minutes'] ?? 60));

    return Cache::remember('chatbot.query_synonyms.v3', now()->addMinutes($cacheMinutes), function () {
      $seed = $this->seedSynonyms();
      $fromCatalog = $this->buildSynonymsFromCatalog();

      foreach ($fromCatalog as $key => $values) {
        $seed[$key] = array_merge($seed[$key] ?? [], $values);
      }

      return $this->normalizeSynonymMap($seed);
    });
  }

  // Bộ synonym cơ bản khởi tạo ban đầu
  private function seedSynonyms(): array
  {
    return [
      'dien thoai' => ['smartphone', 'iphone', 'samsung', 'oppo', 'xiaomi', 'iphone'],
      'smartphone' => ['dien thoai', 'iphone', 'samsung', 'oppo', 'xiaomi', 'iphone'],
      'laptop' => ['macbook', 'lenovo', 'asus', 'acer', 'hp', 'laptop gaming'],
      'laptop gaming' => ['lenovo legion', 'lenovo loq', 'asus rog strix', 'acer nitro', 'hp victus'],
      'tai nghe' => ['airpods', 'galaxy buds', 'sony', 'true wireless', 'chup tai', 'bluetooth'],
      'phu kien' => ['sac du phong', 'pin du phong', 'op lung', 'mieng dan'],
      'tablet' => ['xiaomi pad', 'samsung galaxy tab'],
      'ipad' => ['tablet'],
    ];
  }

  // Tự động sinh synonym từ category subcategory brand
  private function buildSynonymsFromCatalog(): array
  {
    $map = [];
    $settings = $this->settings->get();
    $maxPerKey = max(4, min(30, (int) ($settings['synonyms_max_per_key'] ?? 12)));

    $categories = Category::query()->get(['id', 'name']);
    foreach ($categories as $category) {
      $categoryKey = $this->normalizeSearchText((string) $category->name);
      $names = Product::query()
        ->where('status', 1)
        ->where('is_approved', 1)
        ->where('category_id', $category->id)
        ->orderByDesc('id')
        ->limit(120)
        ->pluck('name')
        ->all();

      $this->appendSynonyms($map, $categoryKey, $this->topKeywordsFromNames($names, $maxPerKey));
    }

    $subCategories = SubCategory::query()->get(['id', 'name']);
    foreach ($subCategories as $subCategory) {
      $subCategoryKey = $this->normalizeSearchText((string) $subCategory->name);
      $names = Product::query()
        ->where('status', 1)
        ->where('is_approved', 1)
        ->where('sub_category_id', $subCategory->id)
        ->orderByDesc('id')
        ->limit(120)
        ->pluck('name')
        ->all();

      $this->appendSynonyms($map, $subCategoryKey, $this->topKeywordsFromNames($names, $maxPerKey));
    }

    $brands = Brand::query()->get(['id', 'name']);
    foreach ($brands as $brand) {
      $brandKey = $this->normalizeSearchText((string) $brand->name);
      $names = Product::query()
        ->where('status', 1)
        ->where('is_approved', 1)
        ->where('brand_id', $brand->id)
        ->orderByDesc('id')
        ->limit(120)
        ->pluck('name')
        ->all();

      $this->appendSynonyms($map, $brandKey, $this->topKeywordsFromNames($names, $maxPerKey));
    }

    return $map;
  }

  // Lấy keyword phổ biến nhất từ tên sản phẩm
  private function topKeywordsFromNames(array $names, int $limit): array
  {
    $freq = [];
    foreach ($names as $name) {
      foreach ($this->extractCatalogKeywords((string) $name) as $keyword) {
        $freq[$keyword] = ($freq[$keyword] ?? 0) + 1;
      }
    }

    arsort($freq);

    return array_slice(array_keys($freq), 0, $limit);
  }

  // Tách keyword có ý nghĩa từ tên sản phẩm
  private function extractCatalogKeywords(string $name): array
  {
    $normalized = $this->normalizeSearchText($name);
    if ($normalized === '') {
      return [];
    }

    $tokens = collect(preg_split('/\s+/u', $normalized) ?: [])
      ->filter(fn($token) => is_string($token) && $token !== '')
      ->reject(fn($token) => in_array($token, ChatbotKeywords::STOP_WORDS, true))
      ->reject(fn($token) => $this->isNoiseToken($token))
      ->values();

    $keywords = $tokens
      ->filter(fn($token) => mb_strlen($token) >= 3)
      ->unique()
      ->take(8)
      ->values()
      ->all();

    $pairs = [];
    for ($i = 0; $i < max(0, $tokens->count() - 1); $i++) {
      $a = (string) $tokens->get($i);
      $b = (string) $tokens->get($i + 1);
      if ($a === '' || $b === '') {
        continue;
      }
      if ($this->isNoiseToken($a) || $this->isNoiseToken($b)) {
        continue;
      }
      $pairs[] = trim($a . ' ' . $b);
      if (count($pairs) >= 4) {
        break;
      }
    }

    return collect(array_merge($keywords, $pairs))
      ->map(fn($v) => $this->normalizeSearchText((string) $v))
      ->filter(fn($v) => $v !== '' && mb_strlen($v) >= 3)
      ->unique()
      ->values()
      ->all();
  }

  // Lọc token nhiễu như số đơn vị ký hiệu
  private function isNoiseToken(string $token): bool
  {
    $token = $this->normalizeSearchText($token);
    if ($token === '') {
      return true;
    }

    if (preg_match('/^\d+([a-z]+)?$/', $token)) {
      return true;
    }

    return in_array($token, ChatbotDictionary::CATALOG_NOISE_TOKENS, true);
  }

  // Thêm synonym vào map theo key
  private function appendSynonyms(array &$map, string $key, array $values): void
  {
    $key = $this->normalizeSearchText($key);
    if ($key === '' || mb_strlen($key) < 3) {
      return;
    }

    foreach ($values as $value) {
      $value = $this->normalizeSearchText((string) $value);
      if ($value === '' || $value === $key || mb_strlen($value) < 3) {
        continue;
      }
      $map[$key][] = $value;
    }
  }

  // Chuẩn hóa và giới hạn map synonym
  private function normalizeSynonymMap(array $map): array
  {
    $settings = $this->settings->get();
    $maxPerKey = max(4, min(30, (int) ($settings['synonyms_max_per_key'] ?? 12)));
    $normalized = [];

    foreach ($map as $key => $values) {
      $key = $this->normalizeSearchText((string) $key);
      if ($key === '' || mb_strlen($key) < 3) {
        continue;
      }

      $cleanValues = collect($values)
        ->map(fn($value) => $this->normalizeSearchText((string) $value))
        ->filter(fn($value) => $value !== '' && $value !== $key && mb_strlen($value) >= 3)
        ->unique()
        ->take($maxPerKey)
        ->values()
        ->all();

      if (!empty($cleanValues)) {
        $normalized[$key] = $cleanValues;
      }
    }

    ksort($normalized);

    return $normalized;
  }

  // Làm sạch và cắt ngắn mô tả sản phẩm
  private function normalizeDescription(?string $value, int $limit): string
  {
    $text = trim((string) $value);
    if ($text === '') {
      return '';
    }

    $text = strip_tags($text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', $text) ?? '';
    $text = trim($text);

    if ($text === '') {
      return '';
    }

    return Str::limit($text, $limit, '...');
  }

  // Xác định có cần lấy dữ liệu đơn hàng
  private function shouldIncludeOrders(string $message, ?User $user): bool
  {
    if (!$user) {
      return false;
    }

    $mes = Str::lower($message);
    $keywords = ChatbotKeywords::ORDER;

    foreach ($keywords as $keyword) {
      if (Str::contains($mes, $keyword)) {
        return true;
      }
    }

    return false;
  }

  // Lấy các đơn hàng gần đây của người dùng
  private function getRecentOrders(User $user): array
  {
    return Order::query()
      ->where('user_id', $user->id)
      ->orderByDesc('created_at')
      ->limit(5)
      ->get(['invoice_id', 'amount', 'currency_name', 'order_status', 'created_at'])
      ->map(function ($o) {
        return [
          'invoice_id' => (string) $o->invoice_id,
          'status' => (string) $o->order_status,
          'amount' => number_format((float) $o->amount, 0, ',', '.') . ' ' . $o->currency_name,
          'created_at' => $o->created_at?->format('Y-m-d H:i') ?? '',
        ];
      })->all();
  }

  // Xác định có cần lấy dữ liệu phí ship
  private function shouldIncludeShipping(string $message): bool
  {
    $mes = Str::lower($message);
    $keywords = ChatbotKeywords::SHIPPING;

    foreach ($keywords as $keyword) {
      if (Str::contains($mes, $keyword)) {
        return true;
      }
    }

    return false;
  }

  // Lấy danh sách rule vận chuyển đang bật
  private function getShippingRules(): array
  {
    return ShippingRule::query()
      ->where('status', 1)
      ->orderBy('min_cost')
      ->limit(10)
      ->get(['name', 'type', 'min_cost', 'cost'])
      ->map(function ($rule) {
        return [
          'name' => $rule->name,
          'type' => $rule->type,
          'min_cost' => $rule->min_cost === null ? null : number_format((float) $rule->min_cost, 0, ',', '.') . ' VND',
          'cost' => number_format((float) $rule->cost, 0, ',', '.') . ' VND',
        ];
      })->all();
  }

  // Lấy mã giảm giá phù hợp theo tin nhắn
  private function getCouponsForMessage(string $message): array
  {
    $today = now()->toDateString();

    $code = $this->extractCouponCode($message);
    if (is_string($code) && $code !== '') {
      $coupon = Coupon::query()
        ->whereRaw('LOWER(code) = ?', [strtolower($code)])
        ->first(['name', 'code', 'quantity', 'total_used', 'start_date', 'end_date', 'discount_type', 'discount_value', 'status']);

      if ($coupon) {
        return [$this->mapCouponForChat($coupon, $today)];
      }
    }

    $coupons = Coupon::query()
      ->where('status', 1)
      ->whereDate('start_date', '<=', $today)
      ->whereDate('end_date', '>=', $today)
      ->whereColumn('total_used', '<', 'quantity')
      ->orderByDesc('discount_value')
      ->limit(5)
      ->get(['name', 'code', 'quantity', 'total_used', 'start_date', 'end_date', 'discount_type', 'discount_value', 'status']);

    return $coupons->map(fn($c) => $this->mapCouponForChat($c, $today))->all();
  }

  // Tách mã giảm giá từ nội dung người dùng
  private function extractCouponCode(string $message): ?string
  {
    if (!preg_match_all('/\b[A-Z0-9][A-Z0-9_-]{2,}\b/i', $message, $matches)) {
      return null;
    }

    $candidates = array_values(array_unique($matches[0] ?? []));
    foreach ($candidates as $candidate) {
      $candidate = trim((string) $candidate);
      if ($candidate === '') {
        continue;
      }

      if (Coupon::query()->whereRaw('LOWER(code) = ?', [strtolower($candidate)])->exists()) {
        return $candidate;
      }
    }

    return null;
  }

  // Chuyển dữ liệu coupon về format chatbot
  private function mapCouponForChat(Coupon $coupon, string $today): array
  {
    $statusKey = 'valid';

    if (($coupon->status ?? 0) !== 1) {
      $statusKey = 'inactive';
    } else {
      $start = $coupon->start_date ?? '';
      $end = $coupon->end_date ?? '';

      if ($start !== '' && $start > $today) {
        $statusKey = 'not_started';
      } elseif ($end !== '' && $end < $today) {
        $statusKey = 'expired';
      } elseif (($coupon->total_used ?? 0) >= ($coupon->quantity ?? 0)) {
        $statusKey = 'out_of_uses';
      }
    }

    $statusLabel = match ($statusKey) {
      'valid' => 'Hợp lệ',
      'not_started' => 'Chưa bắt đầu',
      'expired' => 'Hết hạn',
      'out_of_uses' => 'Hết lượt',
      'inactive' => 'Tạm tắt',
      default => $statusKey,
    };

    $value = $coupon->discount_value ?? 0;
    $type = $coupon->discount_type ?? '';

    $discountText = $type === 'percent'
      ? rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.') . ' %'
      : number_format($value, 0, ',', '.') . ' VND';

    return [
      'name' => $coupon->name ?? '',
      'code' => $coupon->code ?? '',
      'discount' => $discountText,
      'start_date' => $coupon->start_date ?? '',
      'end_date' => $coupon->end_date ?? '',
      'status' => $statusKey,
      'status_label' => $statusLabel,
      'remaining' => max(0, ($coupon->quantity ?? 0) - ($coupon->total_used ?? 0)),
    ];
  }

  // Kiểm tra câu có chứa từ khóa nào trong danh sách
  private function containsAny(string $message, array $keywords): bool
  {
    $mes = $this->normalizeSearchText($message);

    foreach ($keywords as $keyword) {
      if (Str::contains($mes, $this->normalizeSearchText((string) $keyword))) {
        return true;
      }
    }

    return false;
  }

  // Nhận diện câu có ý định liên quan đến giỏ hàng
  private function isCartActionMessage(string $message): bool
  {
    $lower = Str::lower($message);
    $normalized = $this->normalizeSearchText($message);
    $hasCartNoun = Str::contains($lower, ChatbotDictionary::CART_NOUNS_LOWER) || Str::contains($normalized, ChatbotDictionary::CART_NOUNS_ASCII);
    $hasAddVerb = Str::contains($lower, ChatbotDictionary::CART_ADD_VERBS_LOWER) || Str::contains($normalized, ChatbotDictionary::CART_ADD_VERBS_ASCII);

    if ($hasCartNoun && $hasAddVerb) {
      return true;
    }

    return Str::contains($lower, ChatbotDictionary::ADD_TO_CART_PHRASES) || Str::contains($normalized, ChatbotDictionary::ADD_TO_CART_PHRASES);
  }

  // Nhận diện câu có ý định liên quan đến đơn hàng như hủy, đổi trả
  private function isOrderActionMessage(string $message): bool
  {
    $lower = Str::lower($message);
    $normalized = $this->normalizeSearchText($message);

    if (Str::contains($lower, ChatbotDictionary::ORDER_ACTION_LOWER)) {
      return true;
    }

    return Str::contains($normalized, ChatbotDictionary::ORDER_ACTION_ASCII);
  }
}
