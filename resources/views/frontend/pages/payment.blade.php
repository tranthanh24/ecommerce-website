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
  <section id="wsus__cart_view">
    <div class="container">
      <div class="wsus__pay_info_area">
        <div class="row">
          <div class="col-xl-3 col-lg-3">
            <div class="wsus__payment_menu" id="sticky_sidebar">
              <div class="nav flex-column nav-pills" id="payment-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link common_btn active" id="paypal-tab" data-bs-toggle="pill"
                  data-bs-target="#paypal-pane" type="button" role="tab" aria-controls="paypal-pane"
                  aria-selected="true">
                  Thanh toán qua PayPal
                </button>

                <button class="nav-link common_btn" id="vnpay-tab" data-bs-toggle="pill" aria-selected="false"
                  data-bs-target="#vnpay-pane" type="button" role="tab" aria-controls="vnpay-pane">
                  Thanh toán qua VNPay
                </button>

                <button class="nav-link common_btn" id="cod-tab" data-bs-toggle="pill" data-bs-target="#cod-pane"
                  type="button" role="tab" aria-controls="cod-pane" aria-selected="false">
                  Thanh toán khi nhận hàng (COD)
                </button>
              </div>
            </div>
          </div>

          <div class="col-xl-5 col-lg-5">
            <div class="tab-content" id="payment-tabContent">
              <div class="tab-pane fade show active" id="paypal-pane" role="tabpanel" aria-labelledby="paypal-tab">
                <div class="wsus__payment_area">
                  <h5>Thanh toán qua PayPal</h5>
                  <p>Bạn sẽ được chuyển hướng tới cổng PayPal để thanh toán an toàn.</p>
                  <a class="nav_link common_btn text-center" style="width: 100%"
                    href="{{ route('user.paypal.payment') }}">Xác nhận thanh toán</a>
                </div>
              </div>

              <div class="tab-pane fade show" id="vnpay-pane" role="tabpanel" aria-labelledby="vnpay-tab">
                <div class="wsus__payment_area">
                  <h5>Thanh toán qua VNPay</h5>
                  <p>Bạn sẽ được chuyển hướng tới cổng VNPay để thanh toán an toàn.</p>
                  <a class="nav_link common_btn text-center" style="width: 100%"
                    href="{{ route('user.vnpay.payment') }}">Xác nhận thanh toán</a>
                </div>
              </div>

              <div class="tab-pane fade" id="cod-pane" role="tabpanel" aria-labelledby="cod-tab">
                <div class="wsus__payment_area">
                  <h5>Thanh toán khi nhận hàng (COD)</h5> <br>
                  <a class="nav_link common_btn text-center" style="width: 100%"
                    href="{{ route('user.cod.payment') }}">Xác nhận thanh toán</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-4">
            <div class="wsus__pay_booking_summary" id="sticky_sidebar2">
              <h5>Tóm tắt đơn hàng</h5>
              <p>Tạm tính: <span>{{ formatCurrency(getCartTotal()) }}</span></p>
              <p>Phí vận chuyển (+): <span>{{ formatCurrency(getShippingFee()) }}</span></p>
              <p>Mã giảm giá (-): <span>{{ cartDiscount() }}</span></p>
              <h6>Tổng cộng: <span>{{ formatCurrency(getFinalPayableAmount()) }}</span></h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
