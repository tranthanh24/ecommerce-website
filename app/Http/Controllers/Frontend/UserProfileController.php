<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserProfileController extends Controller
{
  public function index()
  {
    return view('frontend.dashboard.profile');
  }

  public function updateProfile(Request $request)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:50'],
      'email' => ['required', 'email', 'unique:users,email,' . auth()->id()],
      'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
    ]);

    $user = Auth::user();

    if ($request->hasFile('image')) {
      if (File::exists(public_path($user->image))) {
        File::delete(public_path($user->image));
      }

      $image = $request->image;
      $imageName = time() . '_' . $image->getClientOriginalName();
      $image->move(public_path('uploads'), $imageName);

      $path = '/uploads/' . $imageName;
      $user->image = $path;
    }

    $request->user()->update([
      'name' => $request->name,
      'email' => $request->email,
    ]);

    toastr()->success('Hồ sơ đã được cập nhật thành công');
    return redirect()->back();
  }

  public function updatePassword(Request $request)
  {
    $request->validate([
      'current_password' => ['required', 'current_password'],
      'password' => ['required', 'min:3', 'confirmed'],
    ]);

    $request->user()->update([
      'password' => bcrypt($request->password),
    ]);

    toastr()->success('Mật khẩu đã được cập nhật thành công');
    return redirect()->back();
  }
}
