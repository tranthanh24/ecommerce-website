<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SellerProductDataTable;
use App\DataTables\SellerPendingProductDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SellerProductController extends Controller
{
  public function index(SellerProductDataTable $dataTable)
  {
    return $dataTable->render('admin.product.seller.index');
  }

  public function pendingProducts(SellerPendingProductDataTable $dataTable)
  {
    return $dataTable->render('admin.product.seller-pending.index');
  }

  public function changeApproveStatus(Request $request)
  {
    $product = Product::findOrFail($request->id);

    $product->is_approved = $request->isApproved;

    $product->save();

    return response(['status' => 'success']);
  }
}
