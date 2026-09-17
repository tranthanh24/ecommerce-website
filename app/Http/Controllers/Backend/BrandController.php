<?php

namespace App\Http\Controllers\Backend;

use App\Models\Brand;
use App\Models\Product;
use App\Traits\ImageUpload;
use App\DataTables\BrandDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
  use ImageUpload;
  /**
   * Display a listing of the resource.
   */
  public function index(BrandDataTable $datatable)
  {
    return $datatable->render('admin.brand.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.brand.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, Brand $brand)
  {
    $request->validate([
      'logo' => ['image', 'required', 'max:4096'],
      'name' => ['required', 'max:200'],
      'is_featured' => ['required'],
      'status' => ['required'],
    ]);

    $logoPath = $this->uploadImage($request, 'logo', 'uploads/brands');

    $brand->logo = $logoPath;
    $brand->name = $request->name;
    $brand->slug = Str::slug($request->name);
    $brand->is_featured = $request->is_featured;
    $brand->status = $request->status;
    $brand->save();
    Cache::forget('home.brands');

    toastr()->success('Thương hiệu đã được thêm mới thành công');

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
    return view('admin.brand.edit', [
      'brand' => Brand::findOrFail($id),
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $brand = Brand::findOrFail($id);

    $request->validate([
      'logo' => ['image', 'nullable', 'max:4096'],
      'name' => ['required', 'max:200'],
      'is_featured' => ['required'],
      'status' => ['required'],
    ]);

    $logoPath = $this->updateImage($request, 'logo', 'uploads/brands', $brand->logo);

    $brand->logo = $logoPath;
    $brand->name = $request->name;
    $brand->slug = Str::slug($request->name);
    $brand->is_featured = $request->is_featured;
    $brand->status = $request->status;
    $brand->save();
    Cache::forget('home.brands');

    toastr()->success('Thương hiệu đã được cập nhật thành công');

    return redirect()->route('admin.brand.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $brand = Brand::findOrFail($id);

    if (Product::where('brand_id', $brand->id)->count() > 0) {
      return response(['status' => 'error', 'message' => 'Bạn phải xóa sản phẩm trước']);
    }

    $this->deleteImage($brand->logo);

    $brand->delete();
    Cache::forget('home.brands');

    return response(['status' => 'success', 'message' => 'Thương hiệu đã được xóa thành công']);
  }

  public function changeStatus(Request $request)
  {
    $brand = Brand::findOrFail($request->id);

    $brand->status = $request->isChecked === true ? 1 : 0;

    $brand->save();
    Cache::forget('home.brands');

    return response(['status' => 'success']);
  }
}
