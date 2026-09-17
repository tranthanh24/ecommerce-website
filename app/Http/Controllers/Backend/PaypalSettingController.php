<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaypalSetting;
use Illuminate\Http\Request;

class PaypalSettingController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {}

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
  public function update(Request $request)
  {
    $request->validate([
      'status' => ['required'],
      'currency_rate' => ['required', 'numeric'],
      'client_id' => ['required'],
      'secret_key' => ['required']
    ]);

    PaypalSetting::updateOrCreate(
      ['id' => 1],
      [
        'status'        => $request->status,
        'currency_rate' => $request->currency_rate,
        'client_id'     => $request->client_id,
        'secret_key'    => $request->secret_key
      ]
    );

    toastr()->success('Cài đặt Paypal đã được cập nhật thành công');

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
