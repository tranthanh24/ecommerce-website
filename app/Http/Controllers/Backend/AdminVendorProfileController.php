<?php

namespace App\Http\Controllers\Backend;

use App\Models\Vendor;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminVendorProfileController extends Controller
{
  use ImageUpload;
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $profile = Vendor::where('user_id', auth()->user()->id)->first();
    return view('admin.vendor-profile.index', compact('profile'));
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
    $vendor = Vendor::where('user_id', auth()->user()->id)->firstOrFail();

    $validated = $request->validate([
      'banner' => ['nullable', 'image', 'max:4096'],
      'phone' => ['required', 'max:20'],
      'shop_name' => ['required', 'string', 'max:100'],
      'email' => ['required', 'email', 'max:100'],
      'address' => ['required', 'string', 'max:255'],
      'description' => ['required', 'string', 'max:1000'],
      'fb_link' => ['nullable', 'url'],
      'insta_link' => ['nullable', 'url'],
    ]);

    $oldPath = $vendor->banner ?? null;
    $validated['banner'] = $this->updateImage($request, 'banner', 'uploads/vendors', $oldPath);

    $vendor->update($validated);

    toastr()->success('Thông tin đã được cập nhật thành công');

    return redirect()->back();
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
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
