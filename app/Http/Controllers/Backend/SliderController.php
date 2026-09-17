<?php

namespace App\Http\Controllers\Backend;

use App\Models\Slider;
use App\Traits\ImageUpload;
use App\DataTables\SliderDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SliderController extends Controller
{
  use ImageUpload;
  /**
   * Display a listing of the resource.
   */
  public function index(SliderDataTable $datatable)
  {
    return $datatable->render('admin.slider.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.slider.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'banner' => ['image', 'max:4096'],
      'type' => ['required', 'string', 'max:200'],
      'title' => ['required', 'max:200'],
      'starting_price' => ['required', 'max:200'],
      'url' => ['required', 'url'],
      'serial' => ['required'],
      'status' => ['required'],
    ]);

    $slider = new Slider();

    $imagePath = $this->uploadImage($request, 'banner', 'uploads/sliders');

    $slider->banner = $imagePath;
    $slider->type = $request->type;
    $slider->title = $request->title;
    $slider->starting_price = $request->starting_price;
    $slider->url = $request->url;
    $slider->serial = $request->serial;
    $slider->status = $request->status;
    $slider->save();

    Cache::forget('sliders');

    toastr()->success('Slider đã được thêm mới thành công');

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
    $slider = Slider::findOrFail($id);

    return view('admin.slider.edit', compact('slider'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $request->validate([
      'banner' => ['nullable', 'image', 'max:4096'],
      'type' => ['required', 'string', 'max:200'],
      'title' => ['required', 'max:200'],
      'starting_price' => ['required', 'max:200'],
      'url' => ['required', 'url'],
      'serial' => ['required'],
      'status' => ['required'],
    ]);

    $slider = Slider::findOrFail($id);

    $imagePath = $this->updateImage($request, 'banner', 'uploads/sliders', $slider->banner);

    $slider->banner = $imagePath;
    $slider->type = $request->type;
    $slider->title = $request->title;
    $slider->starting_price = $request->starting_price;
    $slider->url = $request->url;
    $slider->serial = $request->serial;
    $slider->status = $request->status;
    $slider->save();

    Cache::forget('sliders');

    toastr()->success('Slider đã được cập nhật thành công');

    return redirect()->route('admin.slider.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $slider = Slider::findOrFail($id);

    $this->deleteImage($slider->banner);

    $slider->delete();

    return response(['status' => 'success', 'message' => 'Slider đã được xóa thành công']);
  }
}
