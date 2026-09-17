<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderDataTable;
use App\DataTables\OrderPendingDataTable;
use App\DataTables\OrderShippedDataTable;
use App\DataTables\OrderCancelledDataTable;
use App\DataTables\OrderCompletedDataTable;
use App\DataTables\OrderConfirmedDataTable;
use App\DataTables\OrderProcessingDataTable;
use App\DataTables\OrderOutForDeliveryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(OrderDataTable $dataTable)
  {
    // Return the data table view for orders
    return $dataTable->render('admin.order.index');
  }

  /**
   * Display a listing of porders.
   */
  public function pendingOrders(OrderPendingDataTable $dataTable)
  {
    return $dataTable->render('admin.order.pending-order');
  }

  public function confirmedOrders(OrderConfirmedDataTable $dataTable)
  {
    return $dataTable->render('admin.order.confirmed-order');
  }

  public function processingOrders(OrderProcessingDataTable $dataTable)
  {
    return $dataTable->render('admin.order.processing-order');
  }

  public function shippedOrders(OrderShippedDataTable $dataTable)
  {
    return $dataTable->render('admin.order.shipped-order');
  }

  public function outForDeliveryOrders(OrderOutForDeliveryDataTable $dataTable)
  {
    return $dataTable->render('admin.order.out-for-delivery-order');
  }

  public function completedOrders(OrderCompletedDataTable $dataTable)
  {
    return $dataTable->render('admin.order.completed-order');
  }

  public function cancelledOrders(OrderCancelledDataTable $dataTable)
  {
    return $dataTable->render('admin.order.cancelled-order');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $order = Order::findOrFail($id);

    return view('admin.order.show', compact('order'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $order = Order::findOrFail($id);
    $order->orderProducts()->delete();
    $order->transaction()->delete();
    $order->delete();

    return response(['status' => 'success', 'message' => 'Đơn hàng đã được xóa thành công']);
  }

  public function changeOrderStatus(Request $request)
  {
    $order = Order::findOrFail($request->id);
    $order->order_status = $request->status;
    $order->save();

    return response(['status' => 'success', 'message' => 'Trạng thái đơn hàng đã được cập nhật thành công']);
  }

  public function changePaymentStatus(Request $request)
  {
    $payment = Order::findOrFail($request->id);
    $payment->payment_status = $request->status;
    $payment->save();

    return response(['status' => 'success', 'message' => 'Trạng thái thanh toán đã được cập nhật thành công']);
  }
}
