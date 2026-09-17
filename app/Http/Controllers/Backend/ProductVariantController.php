<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductVariantDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request, ProductVariantDataTable $dataTable)
  {
    $product = Product::findOrFail($request->product);
    return $dataTable->render('admin.product.variant.index', compact('product'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Request $request)
  {
    $product = Product::findOrFail($request->product);
    return view('admin.product.variant.create', compact('product'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, ProductVariant $variant)
  {
    $request->validate([
      'product_id' => ['required', 'integer'],
      'name' => ['required', 'max:200'],
      'status' => ['required', 'boolean'],
    ]);

    $variant->product_id = $request->product_id;
    $variant->name = $request->name;
    $variant->status = $request->status;
    $variant->save();

    toastr()->success('Phiên bản đã được thêm mới thành công');

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
    $variant = ProductVariant::findOrFail($id);
    return view('admin.product.variant.edit', compact('variant'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $variant = ProductVariant::findOrFail($id);

    $request->validate([
      'name' => ['required', 'max:200'],
      'status' => ['required', 'boolean'],
    ]);

    $variant->name = $request->name;
    $variant->status = $request->status;
    $variant->save();

    toastr()->success('Phiên bản đã được cập nhật thành công');

    return redirect()->route('admin.products-variant.index', ['product' => $variant->product_id]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $variant = ProductVariant::findOrFail($id);
    $variantIems = ProductVariantItem::where('product_variant_id', $variant->id)->count();

    if ($variantIems > 0) {
      return response(['status' => 'error', 'message' => 'Bạn phải xóa mẫu sản phẩm trước']);
    }

    $variant->delete();

    return response(['status' => 'success', 'message' => 'Phiên bản đã được xóa thành công']);
  }

  public function changeStatus(Request $request)
  {
    $variant = ProductVariant::findOrFail($request->id);

    $variant->status = $request->isChecked === true ? 1 : 0;

    $variant->save();

    return response(['status' => 'success']);
  }
}
