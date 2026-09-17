<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorRequestDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorRequestController extends Controller
{
  public function index(VendorRequestDataTable $dataTable)
  {
    return $dataTable->render('admin.vendor-request.index');
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
