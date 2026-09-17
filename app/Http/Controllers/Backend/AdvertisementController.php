<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Traits\ImageUpload;

class AdvertisementController extends Controller
{
  use ImageUpload;

  public function index()
  {
    $homepage_banner_one = Advertisement::where('key', 'homepage_banner_one')->first();
    $homepage_banner_one = json_decode($homepage_banner_one?->value, true);
    $homepage_banner_two = Advertisement::where('key', 'homepage_banner_two')->first();
    $homepage_banner_two = json_decode($homepage_banner_two?->value, true);
    $homepage_banner_three = Advertisement::where('key', 'homepage_banner_three')->first();
    $homepage_banner_three = json_decode($homepage_banner_three?->value, true);
    $homepage_banner_four = Advertisement::where('key', 'homepage_banner_four')->first();
    $homepage_banner_four = json_decode($homepage_banner_four?->value, true);
    $product_banner = Advertisement::where('key', 'product_banner')->first();
    $product_banner = json_decode($product_banner?->value, true);
    $cart_banner = Advertisement::where('key', 'cart_banner')->first();
    $cart_banner = json_decode($cart_banner?->value, true);

    return view(
      'admin.advertisement.index',
      compact(
        'homepage_banner_one',
        'homepage_banner_two',
        'homepage_banner_three',
        'homepage_banner_four',
        'product_banner',
        'cart_banner',
      )
    );
  }

  public function homepageBannerOne(Request $request)
  {
    $request->validate([
      'image_1' => ['nullable', 'image', 'max:2048'],
      'url_1' => ['nullable', 'url'],
      'image_2' => ['nullable', 'image', 'max:2048'],
      'url_2' => ['nullable', 'url'],
    ]);

    $record = Advertisement::where('key', 'homepage_banner_one')->first();
    $oldImageOne = $record?->value ? json_decode($record->value, true)['banner_one']['image'] ?? null : null;
    $oldImageTwo = $record?->value ? json_decode($record->value, true)['banner_two']['image'] ?? null : null;

    $imagePathOne = $this->updateImage($request, 'image_1', 'uploads/banner', $oldImageOne);
    $imagePathTwo = $this->updateImage($request, 'image_2', 'uploads/banner', $oldImageTwo);

    $value = [
      'banner_one' => [
        'url' => $request->url_1,
      ],
      'banner_two' => [
        'url' => $request->url_2,
      ]
    ];

    if (!empty($imagePathOne)) {
      $value['banner_one']['image'] = $imagePathOne;
    }

    if (!empty($imagePathTwo)) {
      $value['banner_two']['image'] = $imagePathTwo;
    }

    Advertisement::updateOrCreate(
      ['key' => 'homepage_banner_one'],
      ['value' => json_encode($value)]
    );

    toastr()->success('Banner trang chủ 1 đã được cập nhật thành công');

    return redirect()->back();
  }

  public function homepageBannerTwo(Request $request)
  {
    $request->validate([
      'image_1' => ['nullable', 'image', 'max:2048'],
      'url_1' => ['nullable', 'url'],
      'image_2' => ['nullable', 'image', 'max:2048'],
      'url_2' => ['nullable', 'url'],
    ]);

    $record = Advertisement::where('key', 'homepage_banner_two')->first();
    $oldImageOne = $record?->value ? json_decode($record->value, true)['banner_one']['image'] ?? null : null;
    $oldImageTwo = $record?->value ? json_decode($record->value, true)['banner_two']['image'] ?? null : null;

    $imagePathOne = $this->updateImage($request, 'image_1', 'uploads/banner', $oldImageOne);
    $imagePathTwo = $this->updateImage($request, 'image_2', 'uploads/banner', $oldImageTwo);

    $value = [
      'banner_one' => [
        'url' => $request->url_1,
      ],
      'banner_two' => [
        'url' => $request->url_2,
      ]
    ];

    if (!empty($imagePathOne)) {
      $value['banner_one']['image'] = $imagePathOne;
    }

    if (!empty($imagePathTwo)) {
      $value['banner_two']['image'] = $imagePathTwo;
    }

    Advertisement::updateOrCreate(
      ['key' => 'homepage_banner_two'],
      ['value' => json_encode($value)]
    );

    toastr()->success('Banner trang chủ 2 đã được cập nhật thành công');

    return redirect()->back();
  }

  public function homepageBannerThree(Request $request)
  {
    $request->validate([
      'image_1' => ['nullable', 'image', 'max:2048'],
      'url_1' => ['nullable', 'url'],
      'image_2' => ['nullable', 'image', 'max:2048'],
      'url_2' => ['nullable', 'url'],
      'image_3' => ['nullable', 'image', 'max:2048'],
      'url_3' => ['nullable', 'url'],
    ]);

    $record = Advertisement::where('key', 'homepage_banner_three')->first();
    $oldImageOne = $record?->value ? json_decode($record->value, true)['banner_one']['image'] ?? null : null;
    $oldImageTwo = $record?->value ? json_decode($record->value, true)['banner_two']['image'] ?? null : null;
    $oldImageThree = $record?->value ? json_decode($record->value, true)['banner_three']['image'] ?? null : null;

    $imagePathOne = $this->updateImage($request, 'image_1', 'uploads/banner', $oldImageOne);
    $imagePathTwo = $this->updateImage($request, 'image_2', 'uploads/banner', $oldImageTwo);
    $imagePathThree = $this->updateImage($request, 'image_3', 'uploads/banner', $oldImageThree);

    $value = [
      'banner_one' => [
        'url' => $request->url_1,
      ],
      'banner_two' => [
        'url' => $request->url_2,
      ],
      'banner_three' => [
        'url' => $request->url_3,
      ]
    ];

    if (!empty($imagePathOne)) {
      $value['banner_one']['image'] = $imagePathOne;
    }

    if (!empty($imagePathTwo)) {
      $value['banner_two']['image'] = $imagePathTwo;
    }

    if (!empty($imagePathThree)) {
      $value['banner_three']['image'] = $imagePathThree;
    }

    Advertisement::updateOrCreate(
      ['key' => 'homepage_banner_three'],
      ['value' => json_encode($value)]
    );

    toastr()->success('Banner trang chủ 3 đã được cập nhật thành công');

    return redirect()->back();
  }

  public function homepageBannerFour(Request $request)
  {
    $request->validate([
      'image' => ['nullable', 'image', 'max:2048'],
      'url' => ['nullable', 'url'],
    ]);

    $record = Advertisement::where('key', 'homepage_banner_four')->first();
    $oldImage = $record?->value ? json_decode($record->value, true)['banner_one']['image'] ?? null : null;

    $imagePath = $this->updateImage($request, 'image', 'uploads/banner', $oldImage);

    $value = [
      'banner_one' => [
        'url' => $request->url
      ]
    ];

    if (!empty($imagePath)) {
      $value['banner_one']['image'] = $imagePath;
    }

    Advertisement::updateOrCreate(
      ['key' => 'homepage_banner_four'],
      ['value' => json_encode($value)]
    );

    toastr()->success('Banner trang chủ 4 đã được cập nhật thành công');

    return redirect()->back();
  }

  public function productBanner(Request $request)
  {
    $request->validate([
      'image' => ['nullable', 'image', 'max:2048'],
      'url' => ['nullable', 'url'],
    ]);

    $record = Advertisement::where('key', 'product_banner')->first();
    $oldImage = $record?->value ? json_decode($record->value, true)['banner_one']['image'] ?? null : null;

    $imagePath = $this->updateImage($request, 'image', 'uploads/banner', $oldImage);

    $value = [
      'banner_one' => [
        'url' => $request->url
      ]
    ];

    if (!empty($imagePath)) {
      $value['banner_one']['image'] = $imagePath;
    }

    Advertisement::updateOrCreate(
      ['key' => 'product_banner'],
      ['value' => json_encode($value)]
    );

    toastr()->success('Banner trang sản phẩm đã được cập nhật thành công');

    return redirect()->back();
  }

  public function cartBanner(Request $request)
  {
    $request->validate([
      'image_1' => ['nullable', 'image', 'max:2048'],
      'url_1' => ['nullable', 'url'],
      'image_2' => ['nullable', 'image', 'max:2048'],
      'url_2' => ['nullable', 'url'],
    ]);

    $record = Advertisement::where('key', 'cart_banner')->first();
    $oldImageOne = $record?->value ? json_decode($record->value, true)['banner_one']['image'] ?? null : null;
    $oldImageTwo = $record?->value ? json_decode($record->value, true)['banner_two']['image'] ?? null : null;

    $imagePathOne = $this->updateImage($request, 'image_1', 'uploads/banner', $oldImageOne);
    $imagePathTwo = $this->updateImage($request, 'image_2', 'uploads/banner', $oldImageTwo);

    $value = [
      'banner_one' => [
        'url' => $request->url_1,
      ],
      'banner_two' => [
        'url' => $request->url_2,
      ]
    ];

    if (!empty($imagePathOne)) {
      $value['banner_one']['image'] = $imagePathOne;
    }

    if (!empty($imagePathTwo)) {
      $value['banner_two']['image'] = $imagePathTwo;
    }

    Advertisement::updateOrCreate(
      ['key' => 'cart_banner'],
      ['value' => json_encode($value)]
    );

    toastr()->success('Banner trang giỏ hàng đã được cập nhật thành công');

    return redirect()->back();
  }
}
