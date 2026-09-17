@extends('frontend.dashboard.layouts.master')

@section('title')
  Tổng quan
@endsection

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('frontend.dashboard.layouts.sidebar')
      <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <h3 style="font-size:24px; font-weight:600; color:#2c3e50; margin-bottom:20px; margin-top:-20px;">
            Bảng điều khiển khách hàng</h3>

          <div class="dashboard_content">
            <div class="wsus__dashboard">
              <div class="row mt-2">
                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item red" href="{{ route('user.orders.index') }}">
                    <i class="far fa-receipt"></i>
                    <p>Tổng đơn hàng</p>
                    <h5 class="fw-bold">{{ $orders ?? 0 }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item green" href="{{ route('user.orders.index') }}">
                    <i class="far fa-cart-plus"></i>
                    <p>Đơn đã nhận</p>
                    <h5 class="fw-bold">{{ $completedOrders ?? 0 }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item sky" href="{{ route('user.review.index') }}">
                    <i class="far fa-star"></i>
                    <p>Đánh giá</p>
                    <h5 class="fw-bold">{{ $reviews ?? 0 }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item blue" href="{{ route('user.wishlist.index') }}">
                    <i class="far fa-heart"></i>
                    <p>Yêu thích</p>
                    <h5 class="fw-bold">{{ $wishlists ?? 0 }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item orange" href="{{ route('user.messenger.index') }}">
                    <i class="far fa-sms"></i>
                    <p>Tin nhắn</p>
                    <h5 class="fw-bold">_</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item purple" href="{{ route('user.address.index') }}">
                    <i class="fal fa-map-marker-alt"></i>
                    <p>Địa chỉ</p>
                    <h5 class="fw-bold">_</h5>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
