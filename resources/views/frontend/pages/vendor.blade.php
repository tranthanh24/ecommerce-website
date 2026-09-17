@extends('frontend.layouts.master')

@section('title')
  Cửa hàng
@endsection

@section('content')
  <!-- Breadcrumb -->
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>Cửa hàng</h4>
            <ul>
              <li><a href="{{ url('/') }}">Trang chủ</a></li>
              <li><a href="javascript:void(0)">Cửa hàng</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Vendors -->
  <section id="wsus__product_page" class="wsus__vendors">
    <div class="container">
      <div class="row">
        <div class="col-xl-12 col-lg-8">
          <div class="row">
            @foreach ($vendors as $vendor)
              <div class="col-xl-6 col-md-6">
                <div class="wsus__vendor_single">
                  <img src="{{ asset($vendor->banner) }}" alt="vendor" class="img-fluid w-100">
                  <div class="wsus__vendor_text">
                    <div class="wsus__vendor_text_center">
                      <h4>{{ $vendor->shop_name }}</h4>
                      <a href="javascript:void(0)"><i class="far fa-phone-alt"></i>{{ $vendor->phone }}</a>
                      <a href="javascript:void(0)"><i class="fal fa-envelope"></i>{{ $vendor->email }}</a>
                      <a href="{{ route('vendors.product', $vendor->id) }}" class="common_btn">Xem cửa hàng</a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="col-xl-12">
          @if ($vendors->hasPages())
            <div class="wsus__pagination mt-4">
              {{ $vendors->links() }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
