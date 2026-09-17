<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Review;
use App\Models\ReviewGallery;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\UserReviewDataTable;

class ReviewController extends Controller
{
  use ImageUpload;

  public function index(UserReviewDataTable $dataTable)
  {
    return $dataTable->render('frontend.dashboard.review.index');
  }

  public function create(Request $request)
  {
    $request->validate([
      'rating' => ['required'],
      'review' => ['required', 'string', 'max:255'],
      'image.*' => ['nullable', 'image']
    ]);

    $checkReviewExist = Review::where(['user_id' => auth()->user()->id, 'product_id' => $request->product_id])->first();
    if ($checkReviewExist) {
      toastr()->warning('Bạn đã thêm đánh giá cho sản phẩm này rồi');

      return redirect()->back();
    }

    $review = new Review();
    $review->product_id = $request->product_id;
    $review->vendor_id = $request->vendor_id;
    $review->user_id = auth()->user()->id;
    $review->rating = $request->rating;
    $review->review = $request->review;
    $review->save();

    $imagePaths = $this->uploadMultiImage($request, 'image', 'uploads/reviews');

    $reviewImage = new ReviewGallery();
    foreach ($imagePaths as $path) {
      $reviewImage->review_id = $review->id;
      $reviewImage->image = $path;
      $reviewImage->save();
    }

    toastr()->success('Đánh giá của bạn đã được gửi thành công');

    return redirect()->back();
  }
}
