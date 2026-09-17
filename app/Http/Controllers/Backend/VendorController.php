<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;

class VendorController extends Controller
{
  public function dashboard()
  {
    $vendorId = auth()->user()->vendor->id;

    // Đơn hàng
    $ordersQuery = Order::whereHas('orderProducts', fn($query) => $query->where('vendor_id', $vendorId));

    $todayOrders = (clone $ordersQuery)->whereDate('created_at', Carbon::today())->count();
    $todayPendingOrders = (clone $ordersQuery)
      ->whereDate('created_at', Carbon::today())->where('order_status', 'pending')->count();
    $orders = (clone $ordersQuery)->count();
    $pendingOrders = (clone $ordersQuery)->where('order_status', 'pending')->count();
    $completedOrders = (clone $ordersQuery)->where('order_status', 'completed')->count();

    // Sản phẩm
    $products = Product::where('vendor_id', $vendorId)->count();

    // Earnings
    $completedQuery = (clone $ordersQuery)->where('order_status', 'completed');

    $todayEarning = (clone $completedQuery)->whereDate('created_at', Carbon::today())->sum('sub_total');
    $weekEarning = (clone $completedQuery)
      ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('sub_total');
    $monthEarning = (clone $completedQuery)->whereMonth('created_at', Carbon::now()->month)
      ->whereYear('created_at', Carbon::now()->year)->sum('sub_total');
    $yearEarning = (clone $completedQuery)->whereYear('created_at', Carbon::now()->year)->sum('sub_total');
    $earning = (clone $completedQuery)->sum('sub_total');

    $revenueByMonth = (clone $completedQuery)
      ->whereYear('created_at', Carbon::now()->year)
      ->selectRaw('MONTH(created_at) as month, SUM(sub_total) as revenue')
      ->groupBy('month')
      ->pluck('revenue', 'month')
      ->toArray();

    $monthlyLabels = [];
    $monthlyRevenueData = [];
    foreach (range(1, 12) as $month) {
      $monthlyLabels[] = Carbon::createFromDate(null, $month, 1)->format('M');
      $monthlyRevenueData[] = $revenueByMonth[$month] ?? 0;
    }

    return view('vendor.dashboard.dashboard', compact(
      'todayOrders',
      'todayPendingOrders',
      'orders',
      'pendingOrders',
      'completedOrders',
      'products',
      'todayEarning',
      'weekEarning',
      'monthEarning',
      'yearEarning',
      'earning',
      'monthlyLabels',
      'monthlyRevenueData'
    ));
  }
}
