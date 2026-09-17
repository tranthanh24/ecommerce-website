<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorListDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorListController extends Controller
{
  public function index(VendorListDataTable $dataTable)
  {
    return $dataTable->render('admin.vendor-list.index');
  }

  public function changeStatus(Request $request)
  {
    $vendor = Vendor::findOrFail($request->id);
    $vendor->status = $request->isChecked ? 1 : 0;
    $vendor->save();

    $user = User::findOrFail($vendor->user_id);
    $user->role = $vendor->status === 1 ? 'vendor' : 'user';
    $user->save();

    return response(['status' => 'success', 'message' => 'Trạng thái cửa hàng đã được cập nhật thành công']);
  }
}
