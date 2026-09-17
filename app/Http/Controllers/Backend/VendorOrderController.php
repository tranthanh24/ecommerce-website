<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
  public function index(VendorOrderDataTable $dataTable)
  {
    return $dataTable->render('vendor.order.index');
  }

  public function show(string $id)
  {
    $order = Order::with(['user'])->findOrFail($id);

    return view('vendor.order.show', compact('order'));
  }

  public function orderStatus(Request $request)
  {
    $order = Order::findOrFail($request->id);
    $order->order_status = $request->status;
    $order->save();

    return response(['status' => 'success', 'message' => 'Trạng thái đơn hàng đã được cập nhật thành công']);
  }
}
