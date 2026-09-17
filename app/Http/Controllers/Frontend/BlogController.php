<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
  public function blog(Request $request)
  {
    $query = Blog::where('status', 1)->orderBy('id', 'desc');

    if ($request->has('search') && $request->search != '') {
      $query->where('title', 'like', '%' . $request->search . '%');
    } elseif ($request->has('category')) {
      $category = BlogCategory::where(['status' => 1, 'slug' => $request->category])->firstOrFail();
      $query->where('category_id', $category->id);
    }

    $blogs = $query->paginate(12)->withQueryString();

    return view('frontend.pages.blogs', compact('blogs'));
  }

  public function blogDetail(string $slug)
  {
    $blog = Blog::with('comments')->where('slug', $slug)->where('status', 1)->firstOrFail();
    $moreBlogs = Blog::where('status', 1)->where('id', '!=', $blog->id)->inRandomOrder()->take(5)->get();
    $recentBlogs = Blog::where(['status' => 1, 'category_id' => $blog->category_id])->where('id', '!=', $blog->id)->inRandomOrder()->take(9)->get();
    $categories = BlogCategory::where('status', 1)->get();
    $comments = $blog->comments()->paginate(5);

    return view('frontend.pages.blog-detail', compact('blog', 'moreBlogs', 'recentBlogs', 'comments', 'categories'));
  }

  public function comment(Request $request)
  {
    $request->validate([
      'comment' => ['required', 'max:1000']
    ]);

    $comment = new BlogComment();
    $comment->user_id = auth()->user()->id;
    $comment->blog_id = $request->blog_id;
    $comment->comment = $request->comment;
    $comment->save();

    toastr()->success('Bạn đã thêm bình luận thành công');

    return redirect()->back();
  }
}
