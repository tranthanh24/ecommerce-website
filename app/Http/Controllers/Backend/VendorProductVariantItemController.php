<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorProductVariantItemDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;

class VendorProductVariantItemController extends Controller
{
  public function index(VendorProductVariantItemDataTable $dataTable, $productId, $variantId)
  {
    $product = Product::findOrFail($productId);
    if ($product->vendor_id !== auth()->user()->vendor->id) {
      toastr()->error('Bạn không có quyền xem sản phẩm này');
      abort(404);
    };

    $variant = ProductVariant::findOrFail($variantId);

    return $dataTable->render('vendor.product.variant-item.index', compact('product', 'variant'));
  }

  public function create(string $productId, string $variantId)
  {
    $product = Product::findOrFail($productId);
    $variant = ProductVariant::findOrFail($variantId);

    return view('vendor.product.variant-item.create', compact('variant', 'product'));
  }

  public function store(Request $request)
  {
    $variantItem = new ProductVariantItem();

    $request->validate([
      'variant_id' => ['required', 'integer'],
      'name' => ['required', 'string', 'max:200'],
      'price' => ['required', 'numeric'],
      'is_default' => ['required'],
      'status' => ['required'],
    ]);

    $variantItem->product_variant_id = $request->variant_id;
    $variantItem->name = $request->name;
    $variantItem->price = $request->price;
    $variantItem->is_default = $request->is_default;
    $variantItem->status = $request->status;
    $variantItem->save();

    toastr()->success('Mẫu sản phẩm đã được thêm mới thành công');

    return redirect()->route('vendor.products-variant-item.index', [
      'productId' => $request->product_id,
      'variantId' => $request->variant_id
    ]);
  }

  public function edit(string $variantItemId)
  {
    $variantItem = ProductVariantItem::findOrFail($variantItemId);
    if ($variantItem->productVariant->product->vendor_id !== auth()->user()->vendor->id) {
      toastr()->error('Bạn không có quyền xem mẫu sản phẩm này');
      abort(404);
    };

    $product = $variantItem->productVariant->product_id;
    $variant = $variantItem->productVariant->id;

    return view('vendor.product.variant-item.edit', compact('variantItem', 'product', 'variant'));
  }

  public function update(Request $request, string $variantItemId)
  {
    $variantItem = ProductVariantItem::findOrFail($variantItemId);
    if ($variantItem->productVariant->product->vendor_id !== auth()->user()->vendor->id) {
      toastr()->error('Bạn không có quyền xem mẫu sản phẩm này');
      abort(404);
    };

    $request->validate([
      'name' => ['required', 'string', 'max:200'],
      'price' => ['required', 'numeric'],
      'is_default' => ['required'],
      'status' => ['required'],
    ]);

    $variantItem->name = $request->name;
    $variantItem->price = $request->price;
    $variantItem->is_default = $request->is_default;
    $variantItem->status = $request->status;
    $variantItem->save();

    toastr()->success('Mẫu sản phẩm đã được cập nhật thành công');

    return redirect()->route('vendor.products-variant-item.index', [
      'productId' => $variantItem->productVariant->product_id,
      'variantId' => $variantItem->product_variant_id
    ]);
  }

  public function destroy(string $variantItemId)
  {
    $variantItem = ProductVariantItem::findOrFail($variantItemId);
    if ($variantItem->productVariant->product->vendor_id !== auth()->user()->vendor->id) {
      toastr()->error('Bạn không có quyền xóa mẫu sản phẩm này');
      abort(404);
    };

    $variantItem->delete();

    return response(['status' => 'success', 'message' => 'Mẫu sản phẩm đã được xóa thành công']);
  }

  public function changeStatus(Request $request)
  {
    $variantItem = ProductVariantItem::findOrFail($request->id);

    $variantItem->status = $request->isChecked === true ? 1 : 0;

    $variantItem->save();

    return response(['status' => 'success']);
  }
}
