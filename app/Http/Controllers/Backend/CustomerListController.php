<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CustomerListDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerListController extends Controller
{
  public function index(CustomerListDataTable $dataTable)
  {
    return $dataTable->render('admin.customer.index');
  }

  public function changeStatus(Request $request)
  {
    $user = User::findOrFail($request->id);
    $user->status = $request->isChecked ? 'active' : 'inactive';
    $user->save();

    return response(['status' => 'success', 'message' => 'Trạng thái khách hàng đã được cập nhật thành công']);
  }
}
