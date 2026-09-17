<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\AdminListDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminListController extends Controller
{
  public function index(AdminListDataTable $dataTable)
  {
    return $dataTable->render('admin.admin-list.index');
  }

  public function changeStatus(Request $request)
  {
    $admin = User::findOrFail($request->id);
    $admin->status = $request->isChecked ? 'active' : 'inactive';
    $admin->save();

    $vendor = Vendor::where('user_id', $admin->id)->first();
    if ($vendor) {
      $vendor->status = $request->isChecked ? 1 : 0;
      $vendor->save();
    }

    return response(['status' => 'success', 'message' => 'Trạng thái quản trị viên đã được cập nhật thành công']);
  }

  public function destroy(string $id)
  {
    $admin = User::findOrFail($id);
    $vendor = Vendor::where('user_id', $admin->id)->first();
    $products = Product::where('vendor_id', $admin->vendor->id)->get();

    if (count($products) > 0) {
      $admin->status = 'inactive';
      $admin->save();

      $vendor->status = 0;
      $vendor->save();

      return response(['status' => 'error', 'message' => 'Tài khoản quản trị viên này không thể bị xóa']);
    }

    $vendor->delete();
    $admin->delete();

    return response(['status' => 'success', 'message' => 'Tài khoản quản trị viên đã được xóa thành công']);
  }
}
