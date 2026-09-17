<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\DataTables\ChildCategoryDataTable;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ChildCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(ChildCategoryDataTable $datatable)
  {
    return $datatable->render('admin.child-category.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $categories = Category::all();
    return view('admin.child-category.create', compact('categories'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, ChildCategory $childCategory)
  {
    $request->validate([
      'category' => ['required'],
      'sub_category' => ['required'],
      'name' => ['required', 'max:200', 'unique:child_categories,name'],
      'status' => ['required'],
    ]);

    $childCategory->name = $request->name;
    $childCategory->slug = Str::slug($request->name);
    $childCategory->status = $request->status;
    $childCategory->category_id = $request->category;
    $childCategory->sub_category_id = $request->sub_category;

    $childCategory->save();

    toastr()->success('Danh mục phụ đã được thêm mới thành công');

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
    $categories = Category::all();
    $childCategory = ChildCategory::findOrFail($id);
    $subCategories = SubCategory::where('category_id', $childCategory->category_id)->get();
    return view('admin.child-category.edit', compact('categories', 'childCategory', 'subCategories'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $request->validate([
      'category' => ['required'],
      'sub_category' => ['required'],
      'name' => ['required', 'max:200', 'unique:child_categories,name,' . $id],
      'status' => ['required'],
    ]);

    $childCategory = ChildCategory::findOrFail($id);

    $childCategory->name = $request->name;
    $childCategory->slug = Str::slug($request->name);
    $childCategory->status = $request->status;
    $childCategory->category_id = $request->category;
    $childCategory->sub_category_id = $request->sub_category;

    $childCategory->save();

    toastr()->success('Danh mục phụ đã được cập nhật thành công');

    return redirect()->route('admin.child-category.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $childCategory = ChildCategory::findOrFail($id);

    if (Product::where('child_category_id', $childCategory->id)->count() > 0) {
      return response(['status' => 'error', 'message' => 'Bạn phải xóa sản phẩm trước']);
    }

    $childCategory->delete();

    return response(['status' => 'success', 'message' => 'Danh mục phụ đã được xóa thành công']);
  }

  public function getSubCategories(Request $request)
  {
    $subCategories = SubCategory::where('category_id', $request->id)->where('status', 1)->get();

    return $subCategories;
  }

  public function changeStatus(Request $request)
  {
    $childCategory = ChildCategory::findOrFail($request->id);

    $childCategory->status = $request->isChecked === true ? 1 : 0;

    $childCategory->save();

    return response(['status' => 'success']);
  }
}
