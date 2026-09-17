<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BlogCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(BlogCategoryDataTable $dataTable)
  {
    return $dataTable->render('admin.blog.blog-category.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.blog.blog-category.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, BlogCategory $category)
  {
    $request->validate([
      'name' => ['required', 'max:200', 'unique:categories,name'],
      'status' => ['required'],
    ]);

    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $category->status = $request->status;

    $category->save();

    toastr()->success('Danh mục blog đã được thêm mới thành công');

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
    $category = BlogCategory::findOrFail($id);

    return view('admin.blog.blog-category.edit', compact('category'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $category = BlogCategory::findOrFail($id);

    $request->validate([
      'name' => ['required', 'max:200', 'unique:categories,name,'  . $category->id],
      'status' => ['required'],
    ]);

    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $category->status = $request->status;

    $category->save();

    toastr()->success('Danh mục blog đã được cập nhật thành công');

    return redirect()->route('admin.blog-category.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $category = BlogCategory::findOrFail($id);
    $blog = Blog::where('category_id', $category->id)->count();

    if ($blog > 0) {
      return response(['status' => 'error', 'message' => 'Bạn phải xóa blog trước']);
    }

    $category->delete();

    return response(['status' => 'success', 'message' => 'Danh mục blog đã được xóa thành công']);
  }

  public function changeStatus(Request $request)
  {
    $category = BlogCategory::findOrFail($request->id);

    $category->status = $request->isChecked === true ? 1 : 0;

    $category->save();

    return response(['status' => 'success']);
  }
}
