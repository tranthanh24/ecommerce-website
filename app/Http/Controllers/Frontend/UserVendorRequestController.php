<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorCondition;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;

class UserVendorRequestController extends Controller
{
  use ImageUpload;

  public function index()
  {
    $content = VendorCondition::first();

    return view('frontend.dashboard.vendor-request.index', compact('content'));
  }

  public function create(Request $request)
  {
    $request->validate([
      'shop_image' => ['required', 'image', 'max:4096'],
      'shop_name' => ['required', 'string', 'max:100'],
      'shop_phone' => ['required', 'string', 'max:15'],
      'shop_email' => ['required', 'string', 'max:100'],
      'shop_address' => ['required', 'string', 'max:255'],
      'shop_about' => ['nullable', 'string', 'max:255'],
    ]);

    if (auth()->user()->role === 'vendor') {
      return redirect()->back();
    }

    $vendor = new Vendor();

    $imagePath = $this->uploadImage($request, 'shop_image', 'uploads/vendors');

    $vendor->banner = $imagePath;
    $vendor->user_id = auth()->user()->id;
    $vendor->shop_name = $request->shop_name;
    $vendor->phone = $request->shop_phone;
    $vendor->email = $request->shop_email;
    $vendor->address = $request->shop_address;
    $vendor->description = $request->shop_about;
    $vendor->status = 0;
    $vendor->save();

    toastr()->success('Yêu cầu mở cửa hàng đã được gửi thành công');

    return redirect()->back();
  }
}
