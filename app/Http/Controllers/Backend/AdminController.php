<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\News;
use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
  public function dashboard()
  {
    // Đơn hàng
    $todayOrders = Order::whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])->count();
    $todayPendingOrders = Order::whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])
      ->where('order_status', 'pending')->count();
    $orders = Order::count();
    $pendingOrders = Order::where('order_status', 'pending')->count();
    $cancelledOrders = Order::where('order_status', 'cancelled')->count();
    $completedOrders = Order::where('order_status', 'completed')->count();

    // All
    $products = Product::count();
    $brands = Brand::count();
    $blogs = Blog::count();
    $admins = User::where('role', 'admin')->count();
    $vendors = User::where('role', 'vendor')->count();
    $users = User::where('role', 'user')->count();
    $subscribers = News::count();

    // Earnings
    $completedQuery = Order::where('order_status', 'completed');

    $todayEarning = (clone $completedQuery)
      ->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])->sum('sub_total');
    $weekEarning = (clone $completedQuery)
      ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('sub_total');
    $monthEarning = (clone $completedQuery)->whereMonth('created_at', Carbon::now()->month)
      ->whereYear('created_at', Carbon::now()->year)->sum('sub_total');
    $yearEarning = (clone $completedQuery)->whereYear('created_at', Carbon::now()->year)->sum('sub_total');
    $earning = (clone $completedQuery)->sum('sub_total');

    // Monthly revenue for bar chart (12 months of current year)
    $monthlyRevenueRaw = (clone $completedQuery)
      ->selectRaw('MONTH(created_at) as month, SUM(sub_total) as total')
      ->whereYear('created_at', Carbon::now()->year)
      ->groupBy('month')
      ->orderBy('month')
      ->pluck('total', 'month')
      ->toArray();

    $monthlyRevenue = [];
    for ($month = 1; $month <= 12; $month++) {
      $monthlyRevenue[] = $monthlyRevenueRaw[$month] ?? 0;
    }

    return view('admin.dashboard', compact(
      'todayOrders',
      'todayPendingOrders',
      'orders',
      'pendingOrders',
      'cancelledOrders',
      'completedOrders',
      'products',
      'brands',
      'blogs',
      'admins',
      'vendors',
      'users',
      'subscribers',
      'todayEarning',
      'weekEarning',
      'monthEarning',
      'yearEarning',
      'earning',
      'monthlyRevenue'
    ));
  }

  public function login()
  {
    return view('admin.auth.login');
  }
}
