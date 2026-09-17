<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait ImageUpload
{
  public function uploadImage(Request $request, $inputName, $path)
  {
    if ($request->hasFile($inputName)) {

      $image = $request->{$inputName};
      $ext = $image->getClientOriginalExtension();
      $imageName = 'media_' . uniqid() . '.' . $ext;
      $image->move(public_path($path), $imageName);

      return $path . '/' . $imageName;
    }
  }

  public function uploadMultiImage(Request $request, $inputName, $path)
  {
    $imagePaths = [];
    if ($request->hasFile($inputName)) {

      $images = $request->{$inputName};

      foreach ($images as $image) {
        $ext = $image->getClientOriginalExtension();
        $imageName = 'media_' . uniqid() . '.' . $ext;
        $image->move(public_path($path), $imageName);

        $imagePaths[] = $path . '/' . $imageName;
      }
      return $imagePaths;
    }
    return [];
  }

  public function updateImage(Request $request, $inputName, $path, $oldPath = null)
  {
    if ($request->hasFile($inputName)) {
      if (File::exists(public_path($oldPath))) {
        File::delete(public_path($oldPath));
      }

      $image = $request->{$inputName};
      $ext = $image->getClientOriginalExtension();
      $imageName = 'media_' . uniqid() . '.' . $ext;
      $image->move(public_path($path), $imageName);

      return $path . '/' . $imageName;
    }
    return $oldPath;
  }

  public function deleteImage(?string $imagePath)
  {
    if (File::exists(public_path($imagePath))) {
      File::delete(public_path($imagePath));
    }
  }
}
