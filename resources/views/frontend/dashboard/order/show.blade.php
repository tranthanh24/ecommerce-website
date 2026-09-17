@extends('frontend.dashboard.layouts.master')

@php
  $address = json_decode($order->order_address, true);
  $shipping = json_decode($order->shipping_method, true);
  $coupon = json_decode($order->coupon, true);
@endphp

@section('title')
  Đơn đặt hàng
@endsection

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('frontend.dashboard.layouts.sidebar')

      <div class="row" style="margin-top:-20px;">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <div class="dashboard_content mt-2 mt-md-0">
            <a href="{{ route('user.orders.index') }}" class="btn btn-warning mb-4">
              <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

            <h3><i class="far fa-receipt"></i> Chi tiết đơn đặt hàng</h3>

            <div class="wsus__invoice_area invoice-print">
              <div class="wsus__invoice_header">
                <div class="wsus__invoice_content">
                  <div class="row">
                    <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                      <div class="wsus__invoice_single">
                        <h5>Thông tin thanh toán</h5>
                        <h6><b>Tên: </b>{{ $address['name'] }}</h6>
                        <p><b>Email: </b>{{ $address['email'] }}</p>
                      </div>
                    </div>

                    <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                      <div class="wsus__invoice_single text-center">
                        <h5>Thông tin giao hàng</h5>
                        <h6><b>Tên: </b>{{ $address['name'] }}</h6>
                        <p><b>Email: </b>{{ $address['email'] }}</p>
                        <p><b>Số điện thoại: </b>{{ $address['phone'] }}</p>
                        <p><b>Địa chỉ: </b>{{ $address['address'] }}</p>
                      </div>
                    </div>

                    <div class="col-xl-4 col-md-4">
                      <div class="wsus__invoice_single text-md-end">
                        <h5>Phương thức thanh toán</h5>
                        <p> <b>Phương thức:</b> {{ $order->payment_method }}</p>
                        <p><b>Mã hóa đơn:</b> #{{ $order->invoice_id }}</p>
                        <p><b>Mã giao dịch:</b> {{ $order->transaction->transaction_id }}</p>
                        <p><b>Trạng thái đặt hàng: </b>
                          {{ config('order_status.order_status_admin')[$order->order_status]['status'] }}
                        </p>
                        <p><b>Trạng thái thanh toán: </b>
                          {{ $order->payment_status === 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                          </address>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="wsus__invoice_description">
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th class="name">
                          Sản phẩm
                        </th>
                        <th class="name">
                          Cấu hình
                        </th>
                        <th class="amount">
                          Đơn giá
                        </th>
                        <th class="quentity">
                          Số lượng
                        </th>
                        <th class="total">
                          Tổng cộng
                        </th>
                      </tr>

                      @foreach ($order->orderProducts as $product)
                        @php
                          $variants = json_decode($product->variants, true);
                        @endphp

                        <tr>
                          <td class="name">
                            <p>{{ $product->product_name }}</p>
                          </td>

                          <td class="name">
                            <p>
                              @foreach ($variants as $key => $variant)
                                <b>{{ $key }}: </b>
                                {{ $variant['name'] }} ({{ formatCurrency($variant['price']) }})<br />
                              @endforeach
                            </p>
                          </td>

                          <td class="amount">
                            {{ formatCurrency($product->unit_price) }}
                          </td>

                          <td class="quentity">
                            {{ $product->qty }}
                          </td>

                          <td class="total">
                            {{ formatCurrency($product->unit_price * $product->qty + $product->variants_total) }}
                          </td>
                        </tr>
                      @endforeach
                    </table>
                  </div>
                </div>
              </div>

              <div class="wsus__invoice_footer">
                <p><span>Tạm tính:</span> {{ formatCurrency($order->sub_total) }}</p>
                <p><span>Phí vận chuyển (+):</span> {{ formatCurrency($shipping['cost']) }}</p>
                <p><span>Mã giảm giá (-):</span>
                  {{ isset($coupon)
                      ? ($coupon['discount_type'] === 'percent'
                          ? $coupon['discount'] . ' %'
                          : formatCurrency($coupon['discount']))
                      : 0 }}
                </p>
                <p><span style="font-weight: bold;">Tổng cộng:</span> {{ formatCurrency($order->amount) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
