<?php

namespace App\Services\Chatbot;

use App\Models\User;
use App\Models\Product;
use App\Models\ChatbotMessage;
use App\Services\Chatbot\Providers\GeminiProvider;
use App\Services\Chatbot\Providers\OpenAIProvider;
use App\Services\Chatbot\Providers\ChatbotProvider;
use App\Services\Chatbot\Context\ChatbotDictionary;
use App\Services\Chatbot\Context\ChatbotContextBuilder;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

class ChatbotService
{
  public function __construct(
    private readonly ChatbotSettings $settings,
    private readonly ChatbotContextBuilder $contextBuilder,
    private readonly ?ChatbotProvider $provider = null
  ) {}

  // Xử lý tin nhắn và trả lời chatbot
  public function reply(string $message, array $history = [], ?User $user = null): string
  {
    $message = trim($message);
    if ($message === '') {
      throw new RuntimeException('Empty message');
    }

    $context = $this->contextBuilder->build($message, $user);
    $cartActionReply = $this->handleCartAction($message, $history, $context);
    if (is_string($cartActionReply)) {
      return $cartActionReply;
    }

    $unsupported = $this->classifyUnsupportedRequest($message, $context);

    if (($unsupported['hard_block'] ?? false) === true) {
      return (string) ($unsupported['message'] ?? 'Xin lỗi, hiện yêu cầu này chưa được hỗ trợ.');
    }

    $messages = $this->buildMessages($message, $history, $context, $user, $unsupported);
    $settings = $this->settings->get();

    if (!$settings['enabled'] ?? false) {
      return $this->fallbackAnswer($message, $context, 'disabled');
    }

    $providerName = $settings['provider'] ?? 'gemini';
    $keyMissing = match ($providerName) {
      'openai' => empty($settings['openai']['api_key'] ?? null),
      'gemini' => empty($settings['gemini']['api_key'] ?? null),
      default => true,
    };

    if ($keyMissing) {
      return $this->fallbackAnswer($message, $context, 'missing_key');
    }

    $provider = $this->provider ?? $this->makeProvider();

    try {
      $answer = $provider->chat($messages);
      if ($unsupported !== null && !empty($unsupported['message'])) {
        $answer = trim($answer) . "\n\n" . $unsupported['message'];
      }

      return $answer;
    } catch (\Throwable $e) {
      return $this->fallbackAnswer($message, $context, 'provider_error');
    }
  }

  // Tạo provider AI theo cấu hình
  private function makeProvider(): ChatbotProvider
  {
    $settings = $this->settings->get();
    $provider = $settings['provider'] ?? 'gemini';

    return match ($provider) {
      'openai' => new OpenAIProvider($settings['openai'] ?? []),
      'gemini' => new GeminiProvider($settings['gemini'] ?? []),
      default => throw new RuntimeException("Unsupported chatbot provider [{$provider}]"),
    };
  }

  // Tạo câu trả lời dự phòng khi AI không khả dụng
  private function fallbackAnswer(string $message, array $context, string $reason = 'disabled'): string
  {
    $intro = match ($reason) {
      'missing_key' => 'Hệ thống AI tạm thời không khả dụng, mình sẽ trả lời theo dữ liệu sẵn có.',
      'provider_error' => 'AI đang gặp sự cố tạm thời, mình sẽ trả lời theo dữ liệu sẵn có.',
      default => 'AI đang tạm tắt, mình sẽ trả lời theo dữ liệu sẵn có.',
    };

    $lines = [$intro];

    if ($this->contextBuilder->shouldIncludeCoupons($message)) {
      $lines[] = '';
      if (!empty($context['coupons'])) {
        $lines[] = 'Mã giảm giá có thể áp dụng:';
        foreach ($context['coupons'] as $coupon) {
          $status = !empty($coupon['status_label']) ? " ({$coupon['status_label']})" : '';
          $date = !empty($coupon['end_date']) ? " - HSD: {$coupon['end_date']}" : '';
          $lines[] = "- {$coupon['code']}: {$coupon['discount']}{$date}{$status}";
        }
        $lines[] = '';
        $lines[] = 'Cách dùng: vào giỏ hàng ' . url('/cart-detail') . ' -> nhập mã -> áp dụng.';
      } else {
        $lines[] = 'Chưa có mã giảm giá phù hợp trong hệ thống hiện tại.';
      }
    }

    if (!empty($context['products'])) {
      $lines[] = '';
      $lines[] = 'Sản phẩm liên quan:';
      foreach ($context['products'] as $product) {
        $lines[] = "- {$product['name']} ({$product['price']}) - {$product['url']}";
      }
    }

    if (count($lines) === 1) {
      $lines[] = '';
      $lines[] = 'Bạn thử mô tả rõ hơn nhu cầu để mình tìm chính xác hơn.';
    }

    return implode("\n", $lines);
  }

  // Gộp prompt hệ thống, memory, context và lịch sử chat
  private function buildMessages(string $message, array $history, array $context, ?User $user, ?array $unsupported): array
  {
    [$recentHistory, $memorySummary, $preferences] = $this->buildMemoryLayers($history, $user);

    $messages = [
      ['role' => 'system', 'content' => $this->systemPrompt($user)],
    ];

    $memoryLines = [];
    if ($memorySummary !== '') {
      $memoryLines[] = 'MEMORY_SUMMARY:';
      $memoryLines[] = $memorySummary;
    }
    if ($preferences !== '') {
      if (!empty($memoryLines)) {
        $memoryLines[] = '';
      }
      $memoryLines[] = 'USER_PREFERENCES:';
      $memoryLines[] = $preferences;
    }

    $intentMeta = $context['intent_meta'] ?? [];
    if (!empty($intentMeta['intents'])) {
      if (!empty($memoryLines)) {
        $memoryLines[] = '';
      }
      $memoryLines[] = 'INTENT_ROUTING:';
      $memoryLines[] = 'intents=' . implode(',', $intentMeta['intents']);
      $memoryLines[] = 'execution=' . ($intentMeta['execution'] ?? 'sequential');
    }

    if ($unsupported !== null && !empty($unsupported['reason'])) {
      if (!empty($memoryLines)) {
        $memoryLines[] = '';
      }
      $memoryLines[] = 'UNSUPPORTED_REASON=' . $unsupported['reason'];
    }

    if (!empty($memoryLines)) {
      $messages[] = ['role' => 'system', 'content' => implode("\n", $memoryLines)];
    }

    $messages[] = ['role' => 'system', 'content' => $this->contextToText($context)];
    $messages = array_merge($messages, $recentHistory);
    $messages[] = ['role' => 'user', 'content' => $message];

    return $messages;
  }

  // Tạo system prompt cho chatbot
  private function systemPrompt(?User $user): string
  {
    $authLine = $user
      ? 'Người dùng đã đăng nhập. Nếu hỏi về đơn hàng thì chỉ dùng dữ liệu đơn trong context.'
      : 'Người dùng chưa đăng nhập. Không được suy đoán dữ liệu đơn hàng.';

    return implode("\n", [
      'Bạn là chatbot hỗ trợ khách hàng cho website thương mại điện tử.',
      'Trả lời bằng tiếng Việt, ngắn gọn, dễ thao tác ngay.',
      'Chỉ được dùng dữ liệu trong NGỮ CẢNH HỆ THỐNG. Không được bịa.',
      'Nếu thông tin thiếu, hỏi lại tối đa 1-2 câu để làm rõ.',
      'Không sử dụng Markdown.',
      'Với truy vấn tìm sản phẩm, nếu có danh sách thì ưu tiên liệt kê nhiều lựa chọn kèm link.',
      'Với logic số học, so sánh giá, sắp xếp: phải tuân theo kết quả deterministic trong context.',
      'Nếu gặp tính năng không hỗ trợ, phải nói rõ chưa hỗ trợ và đề xuất cách thay thế.',
      $authLine,
    ]);
  }

  // Chuyển context thành text để đưa vào prompt
  private function contextToText(array $context): string
  {
    $lines = ['NGỮ CẢNH HỆ THỐNG (chỉ được dùng dữ liệu này để trả lời):'];

    if (!empty($context['products'])) {
      $lines[] = '';
      $lines[] = 'SẢN PHẨM:';
      foreach ($context['products'] as $product) {
        $score = isset($product['relevance_score']) ? ' | score=' . $product['relevance_score'] : '';
        $lines[] = "- {$product['name']} | {$product['price']} | {$product['url']}{$score}";
      }

      $detailLines = [];
      foreach ($context['products'] as $product) {
        $short = trim((string) ($product['short_description'] ?? ''));
        $long = trim((string) ($product['long_description'] ?? ''));
        if ($short === '' && $long === '') {
          continue;
        }

        $detailLines[] = "- {$product['name']}";
        if ($short !== '') {
          $detailLines[] = "  short: {$short}";
        }
        if ($long !== '') {
          $detailLines[] = "  long: {$long}";
        }
      }

      if (!empty($detailLines)) {
        $lines[] = '';
        $lines[] = 'CHI TIẾT SẢN PHẨM:';
        foreach ($detailLines as $line) {
          $lines[] = $line;
        }
      }
    }

    if (!empty($context['orders'])) {
      $lines[] = '';
      $lines[] = 'ĐƠN HÀNG GẦN ĐÂY:';
      foreach ($context['orders'] as $order) {
        $lines[] = "- {$order['invoice_id']} | {$order['status']} | {$order['amount']} | {$order['created_at']}";
      }
    }

    if (!empty($context['shipping_rules'])) {
      $lines[] = '';
      $lines[] = 'PHÍ VẬN CHUYỂN:';
      foreach ($context['shipping_rules'] as $rule) {
        $min = $rule['min_cost'] ? " (đơn tối thiểu {$rule['min_cost']})" : '';
        $lines[] = "- {$rule['name']} | {$rule['type']}{$min} | {$rule['cost']}";
      }
    }

    if (!empty($context['coupons'])) {
      $lines[] = '';
      $lines[] = 'COUPON / KHUYẾN MÃI:';
      foreach ($context['coupons'] as $coupon) {
        $status = !empty($coupon['status_label']) ? " | {$coupon['status_label']}" : '';
        $range = (!empty($coupon['start_date']) && !empty($coupon['end_date']))
          ? " | {$coupon['start_date']} - {$coupon['end_date']}"
          : '';
        $lines[] = "- {$coupon['code']} | {$coupon['discount']}{$range}{$status}";
      }
    }

    if (count($lines) === 1) {
      $lines[] = '';
      $lines[] = 'Không có dữ liệu context phù hợp.';
    }

    return implode("\n", $lines);
  }

  // Tạo bộ nhớ 3 lớp: recent, summary, preferences
  private function buildMemoryLayers(array $history, ?User $user): array
  {
    $settings = $this->settings->get();
    $maxAll = max(8, min(40, (int) ($settings['max_history_messages'] ?? 12)));
    $hotSize = max(4, min(8, (int) ($settings['memory_hot_messages'] ?? 8)));

    $sanitized = $this->sanitizeHistory($history, $maxAll);
    $recentHistory = collect($sanitized)->take(-$hotSize)->values()->all();
    $older = collect($sanitized)->slice(0, max(0, count($sanitized) - count($recentHistory)))->values();

    $memorySummary = $this->summarizeOlderHistory($older);
    $preferences = $this->buildUserPreferenceProfile($user);

    return [$recentHistory, $memorySummary, $preferences];
  }

  // Tóm tắt các tin nhắn cũ
  private function summarizeOlderHistory(Collection $older): string
  {
    if ($older->isEmpty()) {
      return '';
    }

    $userTexts = $older
      ->filter(fn($item) => ($item['role'] ?? null) === 'user')
      ->pluck('content')
      ->filter(fn($v) => is_string($v) && trim($v) !== '')
      ->values();

    if ($userTexts->isEmpty()) {
      return '';
    }

    $prefs = $this->inferPreferencesFromTexts($userTexts->all());
    if (empty($prefs)) {
      return Str::limit(implode(' | ', $userTexts->take(6)->all()), 400, '...');
    }

    return implode('; ', $prefs);
  }

  // Suy ra sở thích người dùng từ lịch sử
  private function buildUserPreferenceProfile(?User $user): string
  {
    if (!$user) {
      return '';
    }

    $messages = ChatbotMessage::query()
      ->where('user_id', $user->id)
      ->where('role', 'user')
      ->orderByDesc('id')
      ->limit(40)
      ->pluck('content')
      ->filter(fn($v) => is_string($v) && trim($v) !== '')
      ->values()
      ->all();

    if (empty($messages)) {
      return '';
    }

    $prefs = $this->inferPreferencesFromTexts($messages);

    return empty($prefs) ? '' : implode('; ', $prefs);
  }

  // Rút trích preference từ danh sách câu hỏi
  private function inferPreferencesFromTexts(array $texts): array
  {
    $joined = Str::lower(implode(' ', $texts));
    $joinedNormalized = Str::lower(Str::ascii($joined));
    $preferences = [];

    if (preg_match('/(duoi|toi da|khong qua)\s*([0-9]+)\s*(k|trieu|nghin|vnd)?/u', $joinedNormalized, $match)) {
      $preferences[] = 'thường đặt ngân sách: ' . trim(($match[2] ?? '') . ' ' . ($match[3] ?? ''));
    }

    $tags = [
      'điện thoại' => ['dien thoai', 'smartphone', 'iphone', 'samsung', 'galaxy', 'oppo', 'xiaomi', 'redmi', 'poco', 'reno', 'find'],
      'laptop' => ['laptop', 'macbook', 'lenovo', 'asus', 'acer', 'hp', 'rog', 'legion', 'loq', 'ideapad', 'thinkbook', 'victus', 'nitro'],
      'tai nghe' => ['tai nghe', 'airpods', 'galaxy buds', 'sony', 'bluetooth', 'true wireless', 'chup tai', 'chong on'],
      'phụ kiện' => ['phu kien', 'sac du phong', 'pin du phong', 'op lung', 'mieng dan'],
      'máy tính bảng' => ['tablet', 'may tinh bang', 'xiaomi pad', 'galaxy tab', 'ipad'],
      'gaming' => ['gaming', 'rog', 'legion', 'loq', 'nitro', 'victus'],
    ];

    foreach ($tags as $label => $needles) {
      foreach ($needles as $needle) {
        if (Str::contains($joinedNormalized, $needle)) {
          $preferences[] = 'quan tâm nhóm: ' . $label;
          break;
        }
      }
    }

    return array_values(array_unique($preferences));
  }

  // Lọc và giới hạn lịch sử chat trước khi gửi AI
  private function sanitizeHistory(array $history, int $max): array
  {
    $history = array_slice($history, max(0, count($history) - $max));

    $out = [];
    foreach ($history as $item) {
      $role = Arr::get($item, 'role');
      $content = Arr::get($item, 'content');

      if (!is_string($role) || !in_array($role, ['user', 'assistant'], true)) {
        continue;
      }
      if (!is_string($content)) {
        continue;
      }

      $content = trim($content);
      if ($content === '') {
        continue;
      }

      $out[] = ['role' => $role, 'content' => Str::limit($content, 2000, '...')];
    }

    return $out;
  }

  // Phân loại các yêu cầu chưa được hỗ trợ
  private function classifyUnsupportedRequest(string $message, array $context): ?array
  {
    $normalized = Str::lower(Str::ascii($message));
    $intents = $context['intent_meta']['intents'] ?? [];

    $asksCartAction = in_array('cart_action', $intents, true);
    $asksOrderAction = in_array('order_action', $intents, true);
    $asksReview = Str::contains($normalized, ['review 5 sao', 'điểm đánh giá', 'bao nhiêu sao']);
    $asksStoreInfo = Str::contains($normalized, ['địa chỉ cửa hàng', 'hotline', 'số điện thoại shop']);

    if ($asksCartAction || $asksOrderAction) {
      $hasOtherSupportedIntent = count(array_diff($intents, ['cart_action', 'order_action'])) > 0;

      return [
        'reason' => 'no_cart_order_write_api',
        'hard_block' => !$hasOtherSupportedIntent,
        'message' => 'Lưu ý: chatbot hiện chỉ tư vấn, chưa thao tác trực tiếp giỏ hàng/đặt đơn. Bạn có thể mở ' . url('/cart-detail') . ' để thêm và thanh toán nhanh.',
      ];
    }

    if ($asksReview) {
      return [
        'reason' => 'no_review_system',
        'hard_block' => true,
        'message' => 'Hiện chatbot chưa có dữ liệu review chi tiết theo điểm sao. Bạn có thể xem mô tả sản phẩm và liên hệ shop để được tư vấn thêm.',
      ];
    }

    if ($asksStoreInfo) {
      return [
        'reason' => 'no_store_info',
        'hard_block' => true,
        'message' => 'Hiện chatbot chưa có dữ liệu liên hệ của từng shop trong context này. Bạn vui lòng vào trang liên hệ hoặc nhắn tin cho shop trên website.',
      ];
    }

    return null;
  }

  // Xử lý các yêu cầu liên quan đến giỏ hàng như thêm sản phẩm, cập nhật số lượng
  private function handleCartAction(string $message, array $history, array $context): ?string
  {
    $intents = $context['intent_meta']['intents'] ?? [];
    if (!in_array('cart_action', $intents, true) && !$this->looksLikeCartAction($message)) {
      return null;
    }

    $selectionMode = $this->parseCartSelectionMode($message);
    $product = $this->resolveProductForCartAction($message, $history, $context, $selectionMode);
    if (!$product) {
      return 'Xin lỗi, mình chưa xác định được sản phẩm nào phù hợp để thêm vào giỏ hàng dựa trên thông tin hiện tại. Bạn có thể mô tả rõ hơn hoặc chọn sản phẩm cụ thể từ danh sách kết quả tìm kiếm.';
    }

    if ((int) ($product->status ?? 0) !== 1 || (int) ($product->is_approved ?? 0) !== 1) {
      return "Sản phẩm {$product->name} hiện chưa sẵn sàng để thêm vào giỏ hàng.";
    }

    $requestQty = $this->parseRequestedQuantity($message);
    $inCartQty = $this->getCurrentCartQty((int) $product->id);
    $totalQty = $inCartQty + $requestQty;

    if ((int) $product->qty <= 0) {
      return "Sản phẩm {$product->name} hiện đã hết hàng.";
    }
    if ((int) $product->qty < $totalQty) {
      return "Sản phẩm {$product->name} chỉ còn {$product->qty} cái, mình chưa thể thêm {$requestQty} cái.";
    }

    $productPrice = checkDiscount($product) ? (float) $product->offer_price : (float) $product->price;
    Cart::add([
      'id' => $product->id,
      'name' => $product->name,
      'qty' => $requestQty,
      'price' => $productPrice,
      'weight' => $product->weight ?? 0,
      'options' => [
        'variants' => [],
        'variant_total' => 0,
        'image' => $product->thumb_image,
        'slug' => $product->slug,
      ],
    ]);

    return "Đã thêm {$requestQty} sản phẩm [{$product->name}] vào giỏ hàng. Bạn có thể xem giỏ hàng tại: " . url('/cart-detail');
  }

  // Lấy số lượng người dùng muốn thêm (mặc định 1)
  private function parseRequestedQuantity(string $message): int
  {
    $lower = Str::lower($message);
    $normalized = Str::lower(Str::ascii($message));

    if (preg_match('/\b(\d{1,2})\s*(cái|chiếc|sp|sản phẩm|con)\b/u', $lower, $match)) {
      return max(1, min(99, (int) ($match[1] ?? 1)));
    }
    if (preg_match('/\b(\d{1,2})\s*(cai|chiec|sp|san pham|con)\b/u', $normalized, $match)) {
      return max(1, min(99, (int) ($match[1] ?? 1)));
    }
    if (preg_match('/(?:thêm|mua|đặt|cho)\s*(\d{1,2})\b/u', $lower, $match)) {
      return max(1, min(99, (int) ($match[1] ?? 1)));
    }
    if (preg_match('/(?:them|mua|dat|cho)\s*(\d{1,2})\b/u', $normalized, $match)) {
      return max(1, min(99, (int) ($match[1] ?? 1)));
    }

    return 1;
  }

  // Tìm sản phẩm cần thêm dựa vào context, tin nhắn hiện tại, và lịch sử chat
  private function resolveProductForCartAction(string $message, array $history, array $context, ?string $selectionMode = null): ?Product
  {
    if ($selectionMode !== null) {
      $byMode = $this->resolveProductBySelectionMode($message, $context, $selectionMode);
      if ($byMode) {
        return $byMode;
      }
    }

    $fromMessage = $this->findProductByMessage($message);
    if ($fromMessage) {
      return $fromMessage;
    }

    $fromHistory = $this->findProductFromHistory($history);
    if ($fromHistory) {
      return $fromHistory;
    }

    // Cuối cùng mới thử tách từ khóa để tìm sản phẩm phù hợp trong context
    $terms = $this->extractCartCandidateTerms($message);
    if (empty($terms)) {
      return null;
    }

    $contextName = trim((string) ($context['products'][0]['name'] ?? ''));
    if ($contextName === '') {
      return null;
    }

    return Product::query()
      ->where('status', 1)
      ->where('is_approved', 1)
      ->whereRaw('LOWER(name) = ?', [Str::lower($contextName)])
      ->first();
  }

  // Chọn sản phẩm theo tiêu chí giá
  private function resolveProductBySelectionMode(string $message, array $context, string $selectionMode): ?Product
  {
    if (!in_array($selectionMode, ['most_expensive', 'cheapest'], true)) {
      return null;
    }

    if (!empty($context['products'])) {
      $sorted = collect($context['products'])
        ->filter(fn($p) => isset($p['name']) && isset($p['price_value']))
        ->sortBy('price_value', SORT_REGULAR, $selectionMode === 'most_expensive')
        ->values();

      $candidateName = (string) ($sorted->first()['name'] ?? '');
      if ($candidateName !== '') {
        $product = Product::query()
          ->where('status', 1)
          ->where('is_approved', 1)
          ->whereRaw('LOWER(name) = ?', [Str::lower($candidateName)])
          ->first();
        if ($product) {
          return $product;
        }
      }
    }

    $terms = $this->extractCartCandidateTerms($message);
    $query = Product::query()
      ->where('status', 1)
      ->where('is_approved', 1);

    if (!empty($terms)) {
      $query->where(function ($builder) use ($terms) {
        foreach ($terms as $term) {
          $builder->orWhere('name', 'like', '%' . $term . '%')
            ->orWhere('short_description', 'like', '%' . $term . '%');
        }
      });
    }

    $products = $query
      ->orderByDesc('id')
      ->limit(200)
      ->get(['id', 'name', 'slug', 'price', 'offer_price', 'qty', 'thumb_image', 'status', 'is_approved', 'offer_start_date', 'offer_end_date']);

    if ($products->isEmpty()) {
      return null;
    }

    $sortedProducts = $products->sort(function ($a, $b) use ($selectionMode) {
      $aPrice = $this->resolveEffectivePrice($a);
      $bPrice = $this->resolveEffectivePrice($b);

      return $selectionMode === 'most_expensive'
        ? ($bPrice <=> $aPrice)
        : ($aPrice <=> $bPrice);
    })->values();

    return $sortedProducts->first();
  }

  // Tách từ khóa để lọc nhóm sản phẩm trong lệnh thêm giỏ hàng
  private function extractCartCandidateTerms(string $message): array
  {
    $normalized = Str::lower(Str::ascii($message));
    $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $normalized) ?? $normalized;
    $clean = preg_replace('/\s+/u', ' ', trim($clean)) ?? '';

    if ($clean === '') {
      return [];
    }

    return collect(preg_split('/\s+/u', $clean) ?: [])
      ->filter(fn($t) => is_string($t) && $t !== '' && mb_strlen($t) >= 2)
      ->reject(fn($t) => in_array((string) $t, ['ok', 'oke', 'okay'], true))
      ->reject(fn($t) => in_array((string) $t, ChatbotDictionary::CART_CANDIDATE_NOISE, true))
      ->unique()
      ->take(6)
      ->values()
      ->all();
  }

  // Tìm sản phẩm theo từ khóa trong câu hiện tại
  private function findProductByMessage(string $message): ?Product
  {
    $normalized = Str::lower(Str::ascii($message));
    $clean = str_replace(ChatbotDictionary::CART_MESSAGE_NOISE, ' ', $normalized);
    $clean = preg_replace('/\s+/u', ' ', trim((string) $clean)) ?? '';
    if ($clean === '') {
      return null;
    }

    $terms = collect(preg_split('/\s+/u', $clean) ?: [])
      ->filter(fn($t) => is_string($t) && $t !== '' && mb_strlen($t) >= 2)
      ->unique()
      ->take(6)
      ->values();

    if ($terms->isEmpty()) {
      return null;
    }

    $products = Product::query()
      ->where('status', 1)
      ->where('is_approved', 1)
      ->where(function ($query) use ($terms) {
        foreach ($terms as $term) {
          $query->orWhere('name', 'like', '%' . $term . '%');
        }
      })
      ->orderByDesc('id')
      ->limit(25)
      ->get(['id', 'name', 'slug', 'price', 'offer_price', 'qty', 'thumb_image', 'status', 'is_approved', 'offer_start_date', 'offer_end_date']);

    if ($products->isEmpty()) {
      return null;
    }

    $scored = $products->map(function ($product) use ($clean, $terms) {
      $name = Str::lower(Str::ascii((string) $product->name));
      $score = Str::contains($name, $clean) ? 6.0 : 0.0;
      foreach ($terms as $term) {
        if (Str::contains($name, (string) $term)) {
          $score += 1.5;
        }
      }

      return ['score' => $score, 'product' => $product];
    })->sortByDesc('score')->values();

    return $scored->first()['product'] ?? null;
  }

  // Ưu tiên lấy sản phẩm từ link gần nhất trong lịch sử assistant
  private function findProductFromHistory(array $history): ?Product
  {
    $reversed = array_reverse($history);

    foreach ($reversed as $item) {
      if (!is_array($item)) {
        continue;
      }
      if (($item['role'] ?? null) !== 'assistant') {
        continue;
      }

      $content = (string) ($item['content'] ?? '');
      if ($content === '') {
        continue;
      }

      if (preg_match('/product-detail\/([a-z0-9\-]+)/i', $content, $match)) {
        $slug = (string) ($match[1] ?? '');
        if ($slug === '') {
          continue;
        }

        $product = Product::query()
          ->where('status', 1)
          ->where('is_approved', 1)
          ->where('slug', $slug)
          ->first(['id', 'name', 'slug', 'price', 'offer_price', 'qty', 'thumb_image', 'status', 'is_approved', 'offer_start_date', 'offer_end_date']);

        if ($product) {
          return $product;
        }
      }
    }

    // Fallback: try to resolve product name from recent conversation text.
    foreach ($reversed as $item) {
      if (!is_array($item)) {
        continue;
      }

      $content = trim((string) ($item['content'] ?? ''));
      if ($content === '') {
        continue;
      }

      $product = $this->findProductByMessage($content);
      if ($product) {
        return $product;
      }
    }

    return null;
  }

  // Tính tổng số lượng một sản phẩm đã có trong giỏ hàng hiện tại
  private function getCurrentCartQty(int $productId): int
  {
    $qty = 0;
    foreach (Cart::content() as $item) {
      if ((int) ($item->id ?? 0) === $productId) {
        $qty += (int) ($item->qty ?? 0);
      }
    }

    return max(0, $qty);
  }

  // Tính giá hiệu lực của sản phẩm để so sánh
  private function resolveEffectivePrice(Product $product): float
  {
    return checkDiscount($product)
      ? (float) ($product->offer_price ?? 0)
      : (float) ($product->price ?? 0);
  }

  // Nhận diện nhanh các câu có gợi ý chọn sản phẩm
  private function parseCartSelectionMode(string $message): ?string
  {
    $lower = Str::lower($message);
    $normalized = Str::lower(Str::ascii($message));
    $compact = str_replace(' ', '', preg_replace('/\s+/u', ' ', $normalized) ?? $normalized);

    if (Str::contains($lower, ChatbotDictionary::PRICE_MOST_EXPENSIVE_HINTS) || Str::contains($normalized, ChatbotDictionary::PRICE_MOST_EXPENSIVE_HINTS) || Str::contains($compact, ChatbotDictionary::PRICE_MOST_EXPENSIVE_COMPACT_HINTS)) {
      return 'most_expensive';
    }
    if (Str::contains($lower, ChatbotDictionary::PRICE_CHEAPEST_HINTS) || Str::contains($normalized, ChatbotDictionary::PRICE_CHEAPEST_HINTS) || Str::contains($compact, ChatbotDictionary::PRICE_CHEAPEST_COMPACT_HINTS)) {
      return 'cheapest';
    }

    return null;
  }

  // Nhận diện nhanh các câu có gợi ý liên quan đến thao tác giỏ hàng
  private function looksLikeCartAction(string $message): bool
  {
    $lower = Str::lower($message);
    $normalized = Str::lower(Str::ascii($message));
    $hasCartNoun = Str::contains($lower, ChatbotDictionary::CART_NOUNS_LOWER) || Str::contains($normalized, ChatbotDictionary::CART_NOUNS_ASCII);
    $hasAddVerb = Str::contains($lower, ChatbotDictionary::CART_ADD_VERBS_LOWER) || Str::contains($normalized, ChatbotDictionary::CART_ADD_VERBS_ASCII);

    if ($hasCartNoun && $hasAddVerb) {
      return true;
    }

    return Str::contains($lower, ChatbotDictionary::ADD_TO_CART_PHRASES) || Str::contains($normalized, ChatbotDictionary::ADD_TO_CART_PHRASES);
  }
}
