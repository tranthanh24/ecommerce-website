<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;

class HomepageSettingController extends Controller
{
  public function index()
  {
    $categories = Category::where('status', 1)->get();
    $popularCategory = HomepageSetting::where('key', 'popular_category_section')->first();
    $sliderOne = HomepageSetting::where('key', 'product-section-one')->first();
    $sliderTwo = HomepageSetting::where('key', 'product-section-two')->first();
    $sliderThree = HomepageSetting::where('key', 'product-section-three')->first();

    return view('admin.homepage-setting.index', compact('categories', 'popularCategory', 'sliderOne', 'sliderTwo', 'sliderThree'));
  }

  public function updatePopularCategorySection(Request $request)
  {
    $data = [];

    if (is_array($request->category)) {
      foreach ($request->category as $i => $itemId) {
        $data[] = [
          'category' => $itemId,
          'sub_category' => $request->sub_category[$i] ?? null,
          'child_category' => $request->child_category[$i] ?? null,
        ];
      }
    }

    HomepageSetting::updateOrCreate(
      [
        'key' => 'popular_category_section'
      ],
      [
        'value' => json_encode($data)
      ]
    );

    toastr()->success('Danh mục phổ biến đã được cập nhật thành công');

    return redirect()->back();
  }

  public function updateProductSliderOne(Request $request)
  {
    $data = [
      'category' => $request->category,
      'sub_category' => $request->sub_category,
      'child_category' => $request->child_category,
    ];

    HomepageSetting::updateOrCreate(
      [
        'key' => 'product-section-one'
      ],
      [
        'value' => json_encode($data)
      ]
    );

    toastr()->success('Silder danh mục sản phẩm 1 đã được cập nhật thành công');

    return redirect()->back();
  }

  public function updateProductSliderTwo(Request $request)
  {
    $data = [
      'category' => $request->category,
      'sub_category' => $request->sub_category,
      'child_category' => $request->child_category,
    ];

    HomepageSetting::updateOrCreate(
      [
        'key' => 'product-section-two'
      ],
      [
        'value' => json_encode($data)
      ]
    );

    toastr()->success('Silder danh mục sản phẩm 2 đã được cập nhật thành công');

    return redirect()->back();
  }

  public function updateProductSliderThree(Request $request)
  {
    $data = [
      [
        'category' => $request->category_one,
        'sub_category' => $request->sub_category_one,
        'child_category' => $request->child_category_one,
      ],
      [
        'category' => $request->category_two,
        'sub_category' => $request->sub_category_two,
        'child_category' => $request->child_category_two,
      ]
    ];

    HomepageSetting::updateOrCreate(
      [
        'key' => 'product-section-three'
      ],
      [
        'value' => json_encode($data)
      ]
    );

    toastr()->success('Silder danh mục sản phẩm 3 đã được cập nhật thành công');

    return redirect()->back();
  }
}
