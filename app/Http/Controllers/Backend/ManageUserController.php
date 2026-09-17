<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\AccountCreatedMail;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ManageUserController extends Controller
{
  public function index()
  {
    return view('admin.manage-user.index');
  }

  public function create(Request $request)
  {
    $request->validate([
      'name' => ['required', 'max:200'],
      'email' => ['required', 'email', 'unique:users,email'],
      'password' => ['required', 'min:8', 'confirmed'],
      'role' => ['required', 'in:user,vendor,admin'],
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->role = $request->role;
    $user->status = 'active';
    $user->save();

    if (in_array($request->role, ['vendor', 'admin'])) {
      $vendor = new Vendor();
      $vendor->banner = 'https://byvn.net/HnfM';
      $vendor->shop_name = $request->name . 'Shop';
      $vendor->phone = '0123 456 789';
      $vendor->address = 'TP. Hồ Chí Minh';
      $vendor->description = 'Test description.';
      $vendor->email = $request->role === 'vendor' ? 'vendor@gmail.com' : 'admin@gmail.com';
      $vendor->user_id = $user->id;
      $vendor->status = 1;
      $vendor->save();
    }

    Mail::to($request->email)->send(new AccountCreatedMail($request->name, $request->email, $request->password));

    toastr()->success('Người dùng đã được thêm mới thành công');

    return redirect()->back();
  }
}
