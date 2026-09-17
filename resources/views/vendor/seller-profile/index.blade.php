@extends('vendor.layouts.master')

@section('title')
  Hồ sơ bán hàng
@endsection

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('vendor.layouts.sidebar')

      <div class="row" style="margin-top:-20px;">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <div class="dashboard_content mt-2 mt-md-0">
            <h3><i class="fab fa-shopify"></i> Hồ sơ cửa hàng</h3>
            <form action="{{ route('vendor.shop-profile.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group wsus__input">
                <label for="">Xem trước</label> <br />
                <img src="{{ asset($profile->banner) }}" width="200px" alt="">
              </div>

              <div class="form-group wsus__input">
                <label for="">Hình ảnh</label>
                <input type="file" class="form-control" name="banner">
              </div>

              <div class="form-group wsus__input">
                <label for="">Tên cửa hàng</label>
                <input type="text" class="form-control" name="shop_name" value="{{ $profile->shop_name }}">
              </div>

              <div class="form-group wsus__input">
                <label for="">Điện thoại</label>
                <input type="text" class="form-control" name="phone" value="{{ $profile->phone }}">
              </div>

              <div class="form-group wsus__input">
                <label for="">Email</label>
                <input type="text" class="form-control" name="email" value="{{ $profile->email }}">
              </div>

              <div class="form-group wsus__input">
                <label for="">Địa chỉ</label>
                <input type="text" class="form-control" name="address" value="{{ $profile->address }}">
              </div>

              <div class="form-group wsus__input">
                <label for="">Mô tả</label>
                <textarea class="summernote" name="description">{{ $profile->description }}</textarea>
              </div>

              <div class="form-group wsus__input">
                <label for="">Facebook</label>
                <input type="text" class="form-control" name="fb_link" value="{{ $profile->fb_link }}">
              </div>

              <div class="form-group wsus__input">
                <label for="">Instagram</label>
                <input type="text" class="form-control" name="insta_link" value="{{ $profile->insta_link }}">
              </div>

              <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
