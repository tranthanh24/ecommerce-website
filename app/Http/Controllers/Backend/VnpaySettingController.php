<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\VnpaySetting;
use Illuminate\Http\Request;

class VnpaySettingController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
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
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $request->validate([
      'status' => ['required'],
      'tmn_code' => ['required'],
      'hash_secret' => ['required'],
      'return_url' => ['required']
    ]);

    VnpaySetting::updateOrCreate(
      ['id' => 1],
      [
        'status'        => $request->status,
        'tmn_code' => $request->tmn_code,
        'hash_secret'     => $request->hash_secret,
        'return_url'    => $request->return_url
      ]
    );

    toastr()->success('Cài đặt VNPay đã được cập nhật thành công');

    return redirect()->back();
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
