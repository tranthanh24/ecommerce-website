<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $addresses = UserAddress::where('user_id', auth()->user()->id)->get();
    return view('frontend.dashboard.address.index', compact('addresses'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('frontend.dashboard.address.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, UserAddress $address)
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

    return redirect()->route('user.address.index');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $address = UserAddress::findOrFail($id);
    return view('frontend.dashboard.address.edit', compact('address'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $address = UserAddress::findOrFail($id);

    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['nullable', 'string', 'email', 'max:255'],
      'phone' => ['required', 'string', 'max:15'],
      'city' => ['required', 'string', 'max:100'],
      'address' => ['required', 'string', 'max:255'],
      'address_type' => ['nullable', 'string', 'in:home,office'],
    ]);

    $address->user_id = auth()->user()->id;
    $address->name = $request->name;
    $address->email = $request->email;
    $address->phone = $request->phone;
    $address->city = $request->city;
    $address->address = $request->address;
    $address->address_type = $request->address_type;
    $address->save();

    toastr()->success('Địa chỉ đã được cập nhật thành công');

    return redirect()->route('user.address.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $address = UserAddress::findOrFail($id);
    $address->delete();

    return response(['status' => 'success', 'message' => 'Địa chỉ đã được xóa thành công']);
  }
}
