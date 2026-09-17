<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\Contact;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Slider;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\FlashSale;
use App\Models\Advertisement;
use App\Models\FlashSaleItem;
use App\Models\GeneralSetting;
use App\Models\HomepageSetting;
use App\Models\EmailConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
  private const CACHE_FIVE_MINUTES = 5;
  private const CACHE_TEN_MINUTES = 10;
  private const CACHE_THIRTY_MINUTES = 30;
  private const TYPE_PRODUCT_LIMIT = 8;

  public function index()
  {
    $flashSale = Cache::remember(
      'home.flash_sale',
      now()->addMinutes(self::CACHE_FIVE_MINUTES),
      fn() => FlashSale::first()
    );

    $brands = Cache::remember(
      'home.brands',
      now()->addMinutes(self::CACHE_THIRTY_MINUTES),
      fn() => Brand::where('status', 1)->where('is_featured', 1)->get()
    );

    $sliders = Cache::rememberForever('sliders', function () {
      return Slider::where('status', 1)->orderBy('serial', 'asc')->get();
    });

    $homepageSettingKeys = [
      'product-section-one',
      'product-section-two',
      'product-section-three',
      'popular_category_section',
    ];

    $homepageSettings = Cache::remember(
      'home.homepage_settings',
      now()->addMinutes(self::CACHE_THIRTY_MINUTES),
      fn() => HomepageSetting::whereIn('key', $homepageSettingKeys)->get()->keyBy('key')
    );

    $sliderOne = $homepageSettings->get('product-section-one');
    $sliderTwo = $homepageSettings->get('product-section-two');
    $sliderThree = $homepageSettings->get('product-section-three');
    $popularCategory = $homepageSettings->get('popular_category_section');

    $flashSaleItems = Cache::remember(
      'home.flash_sale_items',
      now()->addMinutes(self::CACHE_FIVE_MINUTES),
      fn() => FlashSaleItem::with(['product.variants', 'product.productImagesGallery', 'product.reviews'])
        ->where('show_at_home', 1)
        ->where('status', 1)
        ->take(12)
        ->get()
    );

    $blogs = Cache::remember(
      'home.blogs',
      now()->addMinutes(self::CACHE_TEN_MINUTES),
      fn() => Blog::with('category')->where('status', 1)->latest('id')->take(8)->get()
    );

    $typeProduct = $this->getTypeProduct();

    $bannerKeys = [
      'homepage_banner_one',
      'homepage_banner_two',
      'homepage_banner_three',
      'homepage_banner_four',
    ];

    $homepageBanners = Cache::remember(
      'home.advertisements',
      now()->addMinutes(self::CACHE_THIRTY_MINUTES),
      fn() => Advertisement::whereIn('key', $bannerKeys)->get()->keyBy('key')
    );

    $homepage_banner_one = $this->decodeBanner($homepageBanners->get('homepage_banner_one'));
    $homepage_banner_two = $this->decodeBanner($homepageBanners->get('homepage_banner_two'));
    $homepage_banner_three = $this->decodeBanner($homepageBanners->get('homepage_banner_three'));
    $homepage_banner_four = $this->decodeBanner($homepageBanners->get('homepage_banner_four'));

    return view(
      'frontend.home.home',
      compact(
        'blogs',
        'brands',
        'sliders',
        'sliderOne',
        'sliderTwo',
        'sliderThree',
        'flashSale',
        'typeProduct',
        'flashSaleItems',
        'popularCategory',
        'homepage_banner_one',
        'homepage_banner_two',
        'homepage_banner_three',
        'homepage_banner_four'
      )
    );
  }

  public function getTypeProduct()
  {
    $productTypes = [
      'new_arrival',
      'top_product',
      'best_product',
      'featured_product',
    ];

    $typeProduct = [];

    foreach ($productTypes as $type) {
      $cacheKey = "home.products.{$type}";
      $typeProduct[$type] = Cache::remember(
        $cacheKey,
        now()->addMinutes(self::CACHE_TEN_MINUTES),
        fn() => Product::with('category')
          ->where([
            'product_type' => $type,
            'is_approved' => 1,
            'status' => 1,
          ])
          ->latest('id')
          ->take(self::TYPE_PRODUCT_LIMIT)
          ->get()
      );
    }

    return $typeProduct;
  }

  private function decodeBanner(?Advertisement $banner): ?array
  {
    if (!$banner?->value) {
      return null;
    }

    $decoded = json_decode($banner->value, true);

    return is_array($decoded) ? $decoded : null;
  }

  public function vendorPage()
  {
    $vendors = Vendor::where('status', 1)->paginate(12);

    return view('frontend.pages.vendor', compact('vendors'));
  }

  public function vendorProductPage(string $id)
  {
    $products = Product::where(['status' => 1, 'is_approved' => 1, 'vendor_id' => $id])->paginate(12);
    $vendor = Vendor::findOrFail($id);

    return view('frontend.pages.vendor-product', compact('products', 'vendor'));
  }

  public function contact()
  {
    $setting = GeneralSetting::first();

    return view('frontend.pages.contact', compact('setting'));
  }

  public function handleContact(Request $request)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email'],
      'subject' => ['required', 'max:255'],
      'message' => ['required', 'max:1000'],
    ]);

    $setting = EmailConfiguration::first();
    Mail::to($setting->email)->send(new Contact($request->subject, $request->message, $request->email));

    return response(['status' => 'success', 'message' => 'Tin nhắn đã được gửi thành công']);
  }
}
