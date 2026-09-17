<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Wishlist;

class UserDashboardController extends Controller
{
  public function index()
  {
    $orders = Order::where('user_id', auth()->user()->id)->count();
    $completedOrders = Order::where('user_id', auth()->user()->id)->where('order_status', 'completed')->count();
    $reviews = Review::where('user_id', auth()->user()->id)->count();
    $wishlists = Wishlist::where('user_id', auth()->user()->id)->count();

    return view('frontend.dashboard.dashboard', compact('orders', 'completedOrders', 'reviews', 'wishlists'));
  }
}
