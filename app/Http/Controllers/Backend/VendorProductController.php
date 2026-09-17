<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorProductDataTable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\ProductVariant;
use App\Models\ProductImageGallery;
use App\Traits\ImageUpload;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VendorProductController extends Controller
{
  use ImageUpload;
  /**
   * Display a listing of the resource.
   */
  public function index(VendorProductDataTable $dataTable)
  {
    return $dataTable->render('vendor.product.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $categories = Category::where('status', 1)->get();
    $brands = Brand::where('status', 1)->get();
    return view('vendor.product.create', compact('categories', 'brands'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, Product $product)
  {
    $request->validate([
      'image' => ['required', 'image', 'max:4096'],
      'name' => ['required', 'string', 'max:255'],
      'category' => ['required', 'integer'],
      'brand' => ['required', 'integer'],
      'price' => ['required', 'numeric'],
      'qty' => ['required', 'integer'],
      'short_description' => ['nullable', 'max:500'],
      'long_description' => ['nullable', 'max:20000'],
      'video_link' => ['nullable', 'url'],
      'sku' => ['nullable', 'string', 'max:100'],
      'product_type' => ['nullable', 'string'],
      'seo_title' => ['nullable', 'max:200'],
      'seo_description' => ['nullable', 'max:255'],
      'status' => ['required', 'boolean'],
    ]);

    $imagePath = $this->uploadImage($request, 'image', 'uploads/products');

    $product->thumb_image = $imagePath;
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->vendor_id = auth()->user()->vendor->id;
    $product->category_id = $request->category;
    $product->sub_category_id = $request->sub_category;
    $product->child_category_id = $request->child_category;
    $product->brand_id = $request->brand;
    $product->qty = $request->qty;
    $product->short_description = $request->short_description;
    $product->long_description = $request->long_description;
    $product->video_link = $request->video_link;
    $product->sku = $request->sku;
    $product->price = $request->price;
    $product->offer_price = $request->offer_price;
    $product->offer_start_date = $request->offer_start_date;
    $product->offer_end_date = $request->offer_end_date;
    $product->product_type = $request->product_type;
    $product->is_approved = 0;
    $product->status = $request->status;
    $product->seo_title = $request->seo_title;
    $product->seo_description = $request->seo_description;
    $product->save();

    toastr()->success('Sản phẩm đã được thêm mới thành công');

    return redirect()->back();
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $product = Product::findOrFail($id);
    if ($product->vendor_id !== auth()->user()->vendor->id) {
      toastr()->error('Bạn không có quyền sửa sản phẩm này');
      abort(404);
    };

    // Get brands, categories, subcategories, and child categories
    $brands = Brand::where('status', 1)->get();
    $categories = Category::where('status', 1)->get();
    $subCategories = SubCategory::where('category_id', $product->category_id)->where('status', 1)->get();
    $childCategories = ChildCategory::where('sub_category_id', $product->sub_category_id)->where('status', 1)->get();
    return view('vendor.product.edit', compact('product', 'categories', 'subCategories', 'childCategories', 'brands'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $product = Product::findOrFail($id);
    if ($product->vendor_id !== auth()->user()->vendor->id) {
      toastr()->error('Bạn không có quyền sửa sản phẩm này');
      abort(404);
    };

    $request->validate([
      'image' => ['nullable', 'image', 'max:4096'],
      'name' => ['required', 'string', 'max:255'],
      'category' => ['required', 'integer'],
      'brand' => ['required', 'integer'],
      'price' => ['required', 'numeric'],
      'qty' => ['required', 'integer'],
      'short_description' => ['nullable', 'max:500'],
      'long_description' => ['nullable', 'max:20000'],
      'video_link' => ['nullable', 'url'],
      'sku' => ['nullable', 'string', 'max:100'],
      'product_type' => ['nullable', 'string'],
      'seo_title' => ['nullable', 'max:200'],
      'seo_description' => ['nullable', 'max:255'],
      'status' => ['required', 'boolean'],
    ]);

    $imagePath = $this->updateImage($request, 'image', 'uploads/products', $product->thumb_image);

    $product->thumb_image = empty($imagePath) ? $product->thumb_image : $imagePath;
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->vendor_id = auth()->user()->vendor->id;
    $product->category_id = $request->category;
    $product->sub_category_id = $request->sub_category;
    $product->child_category_id = $request->child_category;
    $product->brand_id = $request->brand;
    $product->qty = $request->qty;
    $product->short_description = $request->short_description;
    $product->long_description = $request->long_description;
    $product->video_link = $request->video_link;
    $product->sku = $request->sku;
    $product->price = $request->price;
    $product->offer_price = $request->offer_price;
    $product->offer_start_date = $request->offer_start_date;
    $product->offer_end_date = $request->offer_end_date;
    $product->product_type = $request->product_type;
    $product->is_approved = $product->is_approved;
    $product->status = $request->status;
    $product->seo_title = $request->seo_title;
    $product->seo_description = $request->seo_description;
    $product->save();

    toastr()->success('Sản phẩm đã được cập nhật thành công');

    return redirect()->route('vendor.products.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $product = Product::findorFail($id);
    if ($product->vendor_id !== auth()->user()->vendor->id) {
      toastr()->error('Bạn không có quyền xóa sản phẩm này');
      abort(404);
    };

    $this->deleteImage($product->thumb_image);

    $gelleryImages = ProductImageGallery::where('product_id', $product->id)->get();
    if ($gelleryImages) {
      foreach ($gelleryImages as $image) {
        $this->deleteImage($image->image);
        $image->delete();
      }
    }

    $variants = ProductVariant::where('product_id', $product->id)->get();
    if ($variants) {
      foreach ($variants as $variant) {
        $variant->productVariantItems()->delete();
        $variant->delete();
      }
    }

    $product->delete();

    return response(['status' => 'success', 'message' => 'Sản phẩm đã được xóa thành công']);
  }

  public function getSubCategories(Request $request)
  {
    $subCategories = SubCategory::where('category_id', $request->id)->where('status', 1)->get();
    return $subCategories;
  }

  public function getChildCategories(Request $request)
  {
    $childCategories = ChildCategory::where('sub_category_id', $request->id)->where('status', 1)->get();
    return $childCategories;
  }

  public function changeStatus(Request $request)
  {
    $product = Product::findOrFail($request->id);

    $product->status = $request->isChecked === true ? 1 : 0;

    $product->save();

    return response(['status' => 'success']);
  }
}
