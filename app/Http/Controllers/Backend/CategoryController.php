<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use App\DataTables\CategoryDataTable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(CategoryDataTable $datatable)
  {
    return $datatable->render('admin.category.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.category.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, Category $category)
  {
    $request->validate([
      'icon' => ['required', 'not_in:empty'],
      'name' => ['required', 'max:200', 'unique:categories,name'],
      'status' => ['required'],
    ]);

    $category->icon = $request->icon;
    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $category->status = $request->status;

    $category->save();

    toastr()->success('Danh mục đã được thêm mới thành công');

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
    $category = Category::findOrFail($id);

    return view('admin.category.edit', compact('category'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $category = Category::findOrFail($id);

    $request->validate([
      'icon' => ['required', 'not_in:empty'],
      'name' => ['required', 'max:200', 'unique:categories,name,'  . $category->id],
      'status' => ['required'],
    ]);

    $category->icon = $request->icon;
    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $category->status = $request->status;

    $category->save();

    toastr()->success('Danh mục đã được cập nhật thành công');

    return redirect()->route('admin.category.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $category = Category::findOrFail($id);
    $subCategory = SubCategory::where('category_id', $category->id)->count();

    if ($subCategory > 0) {
      return response(['status' => 'error', 'message' => 'Bạn phải xóa danh mục con trước']);
    }

    $category->delete();

    return response(['status' => 'success', 'message' => 'Danh mục đã được xóa thành công']);
  }

  public function changeStatus(Request $request)
  {
    $category = Category::findOrFail($request->id);

    $category->status = $request->isChecked === true ? 1 : 0;

    $category->save();

    return response(['status' => 'success']);
  }
}
