<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Http\Controllers\Controller;
use App\DataTables\SubCategoryDataTable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(SubCategoryDataTable $datatable)
  {
    return $datatable->render('admin.sub-category.index');
  }


  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $categories = Category::all();
    return view('admin.sub-category.create', compact('categories'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, SubCategory $subCategory)
  {
    $request->validate([
      'category' => ['required'],
      'name' => ['required', 'max:200', 'unique:sub_categories,name'],
      'status' => ['required'],
    ]);

    $subCategory->category_id = $request->category;
    $subCategory->name = $request->name;
    $subCategory->slug = Str::slug($request->name);
    $subCategory->status = $request->status;

    $subCategory->save();

    toastr()->success('Danh mục con đã được thêm mới thành công');

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
    $subCategory = SubCategory::findOrFail($id);
    return view('admin.sub-category.edit', compact('subCategory', 'categories'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $subCategory = SubCategory::findOrFail($id);

    $request->validate([
      'category' => ['required', 'not_in:empty'],
      'name' => ['required', 'max:200', 'unique:sub_categories,name,' . $subCategory->id],
      'status' => ['required'],
    ]);

    $subCategory->category_id = $request->category;
    $subCategory->name = $request->name;
    $subCategory->slug = Str::slug($request->name);
    $subCategory->status = $request->status;

    $subCategory->save();

    toastr()->success('Danh mục con đã được cập nhật thành công');

    return redirect()->route('admin.sub-category.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $subCategory = SubCategory::findOrFail($id);
    $childCategory = ChildCategory::where('sub_category_id', $subCategory->id)->count();

    if ($childCategory > 0) {
      return response(['status' => 'error', 'message' => 'Bạn phải xóa danh mục phụ trước']);
    }

    $subCategory->delete();

    return response(['status' => 'success', 'message' => 'Danh mục con đã được xóa thành công']);
  }

  public function changeStatus(Request $request)
  {
    $subCategory = SubCategory::findOrFail($request->id);

    $subCategory->status = $request->isChecked === true ? 1 : 0;

    $subCategory->save();

    return response(['status' => 'success']);
  }
}
