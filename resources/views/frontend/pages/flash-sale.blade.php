@extends('frontend.layouts.master')

@section('title')
  Flash sale
@endsection

@section('content')
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>Flash sale</h4>
            <ul>
              <li><a href="{{ url('/') }}">Trang chủ</a></li>
              <li><a href="#">Chi tiết ưu đãi</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="wsus__daily_deals">
    <div class="container">
      <div class="wsus__offer_details_area">
        <div class="row">
          <div class="col-xl-6 col-md-6">
            <div class="wsus__offer_details_banner">
              <a href="{{ $cart_banner['banner_one']['url'] ?? route('products.index', ['subcategory' => 'iphone']) }}">
                <img src="{{ asset(@$cart_banner['banner_one']['image']) }}" alt="banner" class="img-fluid w-100">
              </a>
            </div>
          </div>

          <div class="col-xl-6 col-md-6">
            <div class="wsus__offer_details_banner">
              <a href="{{ $cart_banner['banner_two']['url'] ?? route('products.index', ['subcategory' => 'iphone']) }}">
                <img src="{{ asset(@$cart_banner['banner_two']['image']) }}" alt="banner" class="img-fluid w-100">
              </a>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-12">
            <div class="wsus__section_header rounded-0">
              <h3>Flash sale</h3>
              <div class="wsus__offer_countdown">
                <span class="end_text">Kết thúc trong: </span>
                <div class="simply-countdown" id="simply-countdown-one"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          @foreach ($flashSaleItems as $item)
            @php
              $product = $item->product;
            @endphp

            <x-product-card :product="$product" />
          @endforeach
        </div>

        @foreach ($flashSaleItems as $item)
          @php
            $product = $item->product;
          @endphp

          <x-product-modal-card :product="$product" />
        @endforeach

        @if ($flashSaleItems->hasPages())
          <div class="row">
            <div class="col-12">
              <div class="wsus__pagination">
                {{ $flashSaleItems->links() }}
              </div>
            </div>
          </div>
        @endif
      </div>
  </section>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      simplyCountdown('#simply-countdown-one', {
        year: {{ date('Y', strtotime(@$flashSale->end_date)) }},
        month: {{ date('m', strtotime(@$flashSale->end_date)) }},
        day: {{ date('d', strtotime(@$flashSale->end_date)) }},
      });
    });
  </script>
@endpush
