<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BlogDataTable;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Traits\ImageUpload;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BlogController extends Controller
{
  use ImageUpload;
  /**
   * Display a listing of the resource.
   */
  public function index(BlogDataTable $dataTable)
  {
    return $dataTable->render('admin.blog.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $categories = BlogCategory::where('status', 1)->get();

    return view('admin.blog.create', compact('categories'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, Blog $blog)
  {
    $request->validate([
      'image' => ['required', 'image', 'max:4096'],
      'title' => ['required', 'string', 'max:255'],
      'category' => ['required', 'integer'],
      'content' => ['required'],
      'seo_title' => ['nullable', 'max:200'],
      'seo_description' => ['nullable', 'max:255'],
      'status' => ['required', 'boolean'],
    ]);

    $imagePath = $this->uploadImage($request, 'image', 'uploads/blogs');

    $blog->image = $imagePath;
    $blog->title = $request->title;
    $blog->slug = Str::slug($request->title);
    $blog->user_id = auth()->user()->id;
    $blog->category_id = $request->category;
    $blog->content = $request->content;
    $blog->seo_title = $request->seo_title;
    $blog->seo_description = $request->seo_description;
    $blog->status = $request->status;
    $blog->save();

    toastr()->success('Blog đã được thêm mới thành công');

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
    $blog = Blog::findOrFail($id);
    $categories = BlogCategory::where('status', 1)->get();

    return view('admin.blog.edit', compact('categories', 'blog'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $request->validate([
      'image' => ['nullable', 'image', 'max:4096'],
      'title' => ['required', 'string', 'max:255'],
      'category' => ['required', 'integer'],
      'content' => ['required'],
      'seo_title' => ['nullable', 'max:200'],
      'seo_description' => ['nullable', 'max:255'],
      'status' => ['required', 'boolean'],
    ]);

    $blog = Blog::findOrFail($id);

    $imagePath = $this->updateImage($request, 'image', 'uploads/blogs', $blog->image);

    $blog->image = empty($imagePath) ? $blog->image : $imagePath;
    $blog->title = $request->title;
    $blog->slug = Str::slug($request->title);
    $blog->user_id = auth()->user()->id;
    $blog->category_id = $request->category;
    $blog->content = $request->content;
    $blog->seo_title = $request->seo_title;
    $blog->seo_description = $request->seo_description;
    $blog->status = $request->status;
    $blog->save();

    toastr()->success('Blog đã được cập nhật thành công');

    return redirect()->route('admin.blog.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $blog = Blog::findorFail($id);

    $this->deleteImage($blog->image);

    $blog->comments()->delete();

    $blog->delete();

    return response(['status' => 'success', 'message' => 'Blog đã được xóa thành công']);
  }

  public function changeStatus(Request $request)
  {
    $blog = Blog::findOrFail($request->id);

    $blog->status = $request->isChecked === true ? 1 : 0;

    $blog->save();

    return response(['status' => 'success']);
  }
}
