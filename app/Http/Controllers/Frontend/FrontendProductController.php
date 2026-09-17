<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontendProductController extends Controller
{
  public function showProduct(string $slug)
  {
    $product = Product::with(['vendor', 'productImagesGallery', 'variants', 'brand', 'category'])
      ->where('slug', $slug)
      ->where('status', 1)
      ->firstOrFail();

    $categoryId = optional($product->category)->id;
    $relatedProducts = Product::with(['vendor', 'productImagesGallery', 'variants', 'brand'])
      ->when($categoryId, fn($query) => $query->where('category_id', $categoryId))
      ->where('id', '!=', $product->id)
      ->where('status', 1)
      ->where('is_approved', 1)
      ->take(8)
      ->orderByDesc('id')
      ->get();

    $reviews = Review::where('product_id', $product->id)->orderByDesc('id')->paginate(10);

    return view('frontend.pages.product-detail', compact('product', 'relatedProducts', 'reviews'));
  }

  public function productsIndex(Request $request)
  {
    $query = Product::with(['category', 'productImagesGallery', 'variants', 'brand'])
      ->where(['status' => 1, 'is_approved' => 1]);

    if ($request->has('category')) {
      $category = Category::where('slug', $request->category)->firstOrFail();
      $query->where('category_id', $category->id);
    } elseif ($request->has('subcategory')) {
      $subCategory = SubCategory::where('slug', $request->subcategory)->firstOrFail();
      $query->where('sub_category_id', $subCategory->id);
    } elseif ($request->has('childcategory')) {
      $childCategory = ChildCategory::where('slug', $request->childcategory)->firstOrFail();
      $query->where('child_category_id', $childCategory->id);
    } elseif ($request->has('search')) {
      $query->where('name', 'like', '%' . $request->search . '%');
    }

    $products = $query
      ->when($request->has('brand'), function ($query) use ($request) {
        $brand = Brand::where('slug', $request->brand)->firstOrFail();
        return $query->where('brand_id', $brand->id);
      })
      ->when($request->filled('min_price') || $request->filled('max_price'), function ($query) use ($request) {
        $min = $request->min_price ? (int) str_replace('.', '', $request->min_price) : 0;
        $max = $request->max_price ? (int) str_replace('.', '', $request->max_price) : 100000000;
        return $query->whereRaw('COALESCE(offer_price, price) BETWEEN ? AND ?', [(int)$min, (int)$max]);
      })
      ->when($request->filled('price_range'), function ($query) use ($request) {
        [$min, $max] = explode('-', $request->price_range);
        return $query->whereRaw('COALESCE(offer_price, price) BETWEEN ? AND ?', [(int)$min, (int)$max]);
      })
      ->when($request->filled('sort_order'), function ($query) use ($request) {
        if ($request->sort_order == 'low-high') {
          return $query->orderByRaw('COALESCE(offer_price, price) asc');
        } elseif ($request->sort_order == 'high-low') {
          return $query->orderByRaw('COALESCE(offer_price, price) desc');
        }
      })
      ->paginate(15)
      ->appends(request()->query());

    $categories = Category::where('status', 1)->get();
    $brands = Brand::where('status', 1)->get();

    return view('frontend.pages.product', compact('products', 'categories', 'brands'));
  }

  public function productsListView(Request $request)
  {
    Session::put('list-view', $request->view);
  }
}
