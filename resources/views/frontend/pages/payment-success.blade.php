@extends('frontend.layouts.master')

@section('title')
  Thanh toán
@endsection

@section('content')
  <!-- Breadcrumb -->
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>Thanh toán</h4>
            <ul>
              <li><a href="{{ url('/') }}">Trang chủ</a></li>
              <li><a href="javascript:void(0)">Thanh toán</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Payment -->
  <section id="wsus__cart_view" class="py-5 bg-light">
    <div class="container">
      <div class="wsus__pay_info_area text-center p-5 bg-white shadow rounded">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="payment-success">
              <div class="icon mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
              </div>

              <h1 class="fw-bold mb-3 text-success">Thanh toán thành công!</h1>
              <p class="lead">Đơn hàng của bạn đang được xử lý và sẽ sớm được giao đến bạn.</p>
              <a href="{{ url('/') }}" class="btn btn-primary mt-2 px-4 py-2 rounded-pill">Tiếp tục mua sắm</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
