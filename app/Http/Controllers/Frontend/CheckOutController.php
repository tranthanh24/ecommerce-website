<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Models\ShippingRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckOutController extends Controller
{
  public function index()
  {
    $addresses = UserAddress::where('user_id', auth()->user()->id)->get();
    $shippingMethods = ShippingRule::where('status', 1)->get();

    return view('frontend.pages.checkout', compact('addresses', 'shippingMethods'));
  }

  public function createAddress(Request $request, UserAddress $address)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['nullable', 'string', 'email', 'max:255'],
      'phone' => ['required', 'string', 'max:15'],
      'city' => ['required', 'string', 'max:100'],
      'address' => ['required', 'string', 'max:255'],
      'address_type' => ['nullable', 'string'],
    ]);

    $address->user_id = auth()->user()->id;
    $address->name = $request->name;
    $address->email = $request->email;
    $address->phone = $request->phone;
    $address->city = $request->city;
    $address->address = $request->address;
    $address->address_type = $request->address_type;
    $address->save();

    toastr()->success('Địa chỉ đã được thêm mới thành công');

    return redirect()->back();
  }

  public function checkoutFormSubmit(Request $request)
  {
    $request->validate([
      'shipping_method_id' => ['required', 'integer'],
      'shipping_address_id' => ['required', 'integer']
    ]);

    $shippingMethod = ShippingRule::findOrFail($request->shipping_method_id);
    if ($shippingMethod) {
      Session::put('shipping_method', [
        'id' => $shippingMethod->id,
        'name' => $shippingMethod->name,
        'type' => $shippingMethod->type,
        'cost' => $shippingMethod->cost
      ]);
    }

    $address = UserAddress::findOrFail($request->shipping_address_id)->toArray();
    if ($address) {
      Session::put('shipping_address', $address);
    }

    return response(['status' => 'success', 'redirect_url' => route('user.payment')]);
  }
}
