<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\FlashSaleItemDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FlashSaleController extends Controller
{
  public function index(FlashSaleItemDataTable $dataTable)
  {
    $flashSale = FlashSale::first();
    $products = Product::where('status', 1)->where('is_approved', 1)->orderBy('id', 'DESC')->get();
    return $dataTable->render('admin.flash-sale.index', compact('flashSale', 'products'));
  }

  public function update(Request $request)
  {
    $request->validate([
      'end_date' => ['required', 'after_or_equal:today'],
    ]);

    FlashSale::updateOrCreate(
      ['id' => 1],
      ['end_date' => $request->end_date]
    );
    Cache::forget('home.flash_sale');
    Cache::forget('home.flash_sale_items');

    toastr()->success('Thời gian khuyến mãi đã được cập nhật thành công');

    return redirect()->back();
  }

  public function addProduct(Request $request, FlashSaleItem $flashSaleItem)
  {
    $request->validate([
      'product_id' => ['required', 'exists:products,id', 'unique:flash_sale_items,product_id'],
      'show_at_home' => ['required', 'boolean'],
      'status' => ['required', 'boolean'],
    ], [
      'product_id.unique' => 'Sản phẩm đã được thêm vào chương trình khuyến mãi',
    ]);

    $flashSale = FlashSale::first();
    if (!$flashSale) {
      toastr()->error('Không tìm thấy chương trình khuyến mãi');
      return redirect()->back();
    }

    $flashSaleItem->product_id = $request->product_id;
    $flashSaleItem->flash_sale_id = $flashSale->id;
    $flashSaleItem->show_at_home = $request->show_at_home;
    $flashSaleItem->status = $request->status;
    $flashSaleItem->save();
    Cache::forget('home.flash_sale');
    Cache::forget('home.flash_sale_items');

    toastr()->success('Sản phẩm đã được thêm vào chương trình khuyến mãi thành công');

    return redirect()->back();
  }

  public function changeStatus(Request $request)
  {
    $flashSaleItem = FlashSaleItem::findOrFail($request->id);

    $flashSaleItem->status = $request->isChecked === true ? 1 : 0;

    $flashSaleItem->save();
    Cache::forget('home.flash_sale');
    Cache::forget('home.flash_sale_items');

    return response(['status' => 'success']);
  }

  public function changeShowAtHome(Request $request)
  {
    $flashSaleItem = FlashSaleItem::findOrFail($request->id);

    $flashSaleItem->show_at_home = $request->isChecked === true ? 1 : 0;

    $flashSaleItem->save();
    Cache::forget('home.flash_sale');
    Cache::forget('home.flash_sale_items');

    return response(['status' => 'success']);
  }

  public function destroy(string $id)
  {
    $flashSaleItem = FlashSaleItem::findOrFail($id);
    $flashSaleItem->delete();
    Cache::forget('home.flash_sale');
    Cache::forget('home.flash_sale_items');

    return response(['status' => 'success', 'message' => 'Sản phẩm đã được xóa thành công']);
  }
}
