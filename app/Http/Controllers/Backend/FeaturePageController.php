<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class FeaturePageController extends Controller
{
  public function activities()
  {
    return view('admin.features.features-activities');
  }

  public function settings()
  {
    return view('admin.features.features-settings');
  }
}
