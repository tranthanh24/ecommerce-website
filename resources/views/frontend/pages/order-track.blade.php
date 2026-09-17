@extends('frontend.layouts.master')

@section('title')
  Theo dõi đơn hàng
@endsection

@php
  $status = [
      'pending' => 'Chờ xác nhận',
      'confirmed' => 'Đã xác nhận',
      'processing' => 'Đang xử lý',
      'shipped' => 'Đã gửi hàng',
      'out_for_delivery' => 'Đang giao',
      'completed' => 'Hoàn thành',
      'cancelled' => 'Đã hủy',
  ];
@endphp

@section('content')
  <!-- Breadcrumb -->
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>Theo dõi đơn hàng</h4>
            <ul>
              <li><a href="{{ url('/') }}">Trang chủ</a></li>
              <li><a href="javascript:void(0)">Theo dõi đơn hàng</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="wsus__login_register">
    <div class="container">
      <div class="wsus__track_area">
        <div class="row">
          <div class="col-xl-5 col-md-10 col-lg-8 m-auto">
            <form class="tack_form" action="{{ route('order-track.index') }}" method="GET">
              <h4 class="text-center">Theo dõi đơn hàng</h4>
              <p class="text-center">Theo dõi trạng thái đơn hàng của bạn</p>

              <div class="wsus__track_input">
                <label class="d-block mb-2">Mã vận đơn*</label>
                <input type="text" name="tracker" placeholder="INV-990f0b44-055d-43e5-b87d-8f2d9fb17e90"
                  value="{{ @$order->invoice_id }}">
              </div>

              <button type="submit" class="common_btn">Theo dõi</button>
            </form>
          </div>
        </div>

        @if (isset($order))
          <div class="row">
            <div class="col-xl-12">
              <div class="wsus__track_header">
                <div class="wsus__track_header_text">
                  <div class="row">
                    <div class="col-xl-3 col-sm-6 col-lg-3">
                      <div class="wsus__track_header_single">
                        <h5>Ngày đặt hàng:</h5>
                        <p>{{ date('d-m-Y', strtotime($order->created_at)) }}</p>
                      </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-lg-3">
                      <div class="wsus__track_header_single">
                        <h5>Đặt hàng bởi:</h5>
                        <p>{{ $order->user->name }}</p>
                      </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-lg-3">
                      <div class="wsus__track_header_single">
                        <h5>Trạng thái:</h5>
                        <p>{{ $status[$order->order_status] }}</p>
                      </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-lg-3">
                      <div class="wsus__track_header_single border_none">
                        <h5>Mã vận đơn:</h5>
                        <p>{!! limitText(@$order->invoice_id, 30) !!}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-12">
              @php
                $currentStatus = $order->order_status;
                $steps = array_keys($status);

                $currentIndex = -1;
                if ($currentStatus) {
                    $currentIndex = array_search($currentStatus, $steps);
                }
              @endphp

              <ul class="progtrckr" data-progtrckr-steps="{{ count($status) }}">
                @foreach ($status as $key => $label)
                  @php
                    $stepIndex = array_search($key, $steps);
                    $class = '';

                    if ($currentStatus === 'cancelled') {
                        $class = 'red_mark';
                    } elseif ($currentStatus !== 'cancelled' && $stepIndex <= $currentIndex) {
                        $class = 'check_mark';
                    }
                  @endphp
                  <li class="progtrckr_done icon_{{ $loop->index + 1 }} {{ $class }}">{{ $label }}</li>
                @endforeach
              </ul>
            </div>

            <div class="col-xl-12">
              <a href="{{ url('/') }}" class="common_btn"><i class="fas fa-chevron-left"></i> Quay lại</a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection
