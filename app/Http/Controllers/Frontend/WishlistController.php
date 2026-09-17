<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
  public function index()
  {
    $wishlists = Wishlist::with('product')->where('user_id', auth()->id())->orderBy('id', 'desc')->get();

    return view('frontend.pages.wishlist', compact('wishlists'));
  }

  public function addToWishList(Wishlist $wishlist, Request $request)
  {
    if (!auth()->check()) {
      return response(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào danh sách mong muốn']);
    }

    if (Wishlist::where(['product_id' => $request->id, 'user_id' => auth()->user()->id])->exists()) {
      return response(['status' => 'error', 'message' => 'Sản phẩm đã có trong danh sách mong muốn']);
    }

    $wishlist->product_id = $request->id;
    $wishlist->user_id = auth()->user()->id;
    $wishlist->save();

    $count = Wishlist::where('user_id', auth()->user()->id)->count();

    return response(['status' => 'success', 'message' => 'Sản phẩm đã được thêm vào danh sách mong muốn thành công', 'count' => $count]);
  }

  public function destroy(string $id)
  {
    $wishlist = Wishlist::where('id', $id)->first();

    if ($wishlist->user_id !== auth()->user()->id) {
      return response(['status' => 'error', 'message' => 'Bạn không có quyền xóa sản phẩm này']);
    }

    $wishlist->delete();

    $count = Wishlist::where('user_id', auth()->user()->id)->count();

    return response(['status' => 'success', 'message' => 'Sản phẩm đã được xóa khỏi danh sách mong muốn thành công', 'count' => $count]);
  }
}
