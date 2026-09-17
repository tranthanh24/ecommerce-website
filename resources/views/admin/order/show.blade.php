@extends('admin.layouts.master')

@php
  $address = json_decode($order->order_address, true);
  $shipping = json_decode($order->shipping_method, true);
  $coupon = json_decode($order->coupon, true);
@endphp

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Đơn đặt hàng</h1>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-warning mb-4">
      <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Chi tiết đơn đặt hàng</h4>
            </div>
            <div class="card-body">
              <div class="invoice">
                <div class="invoice-print">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="invoice-title">
                        <h2>Hóa đơn</h2>
                        <div class="invoice-number">Đơn hàng #{{ $order->invoice_id }}</div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-md-6">
                          <address>
                            <strong>Thông tin thanh toán:</strong><br>
                            <b>Tên:</b> {{ $address['name'] }}<br>
                            <b>Email:</b> {{ $address['email'] }}<br>
                          </address>
                        </div>

                        <div class="col-md-6 text-md-right">
                          <address>
                            <strong>Thông tin giao hàng:</strong><br>
                            <b>Tên:</b> {{ $address['name'] }}<br>
                            <b>Email:</b> {{ $address['email'] }}<br>
                            <b>Số điện thoại:</b> {{ $address['phone'] }}<br>
                            <b>Địa chỉ:</b> {{ $address['address'] }}<br>
                          </address>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <address>
                            <strong>Phương thức thanh toán:</strong><br>
                            <b>Phương thức:</b> {{ $order->payment_method }}<br>
                            <b>Mã giao dịch:</b> {{ $order->transaction->transaction_id }}
                            <br />
                            <b>Trạng thái: </b>
                            {{ $order->payment_status === 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                          </address>
                        </div>

                        <div class="col-md-6 text-md-right">
                          <address>
                            <strong>Ngày đặt hàng:</strong><br>
                            {{ \Carbon\Carbon::parse($order->created_at)->locale('vi')->isoFormat('D MMMM, YYYY') }}
                          </address>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col-md-12">
                      <div class="section-title">Tóm tắt đơn hàng</div>
                      <p class="section-lead">Tất cả sản phẩm dưới đây không thể xóa.</p>
                      <div class="table-responsive">
                        <table class="table table-striped table-hover table-md">
                          <tr>
                            <th data-width="40">#</th>
                            <th>Sản phẩm</th>
                            <th class="text-center">Cửa hàng</th>
                            <th class="text-center">Cấu hình</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-right">Tổng cộng</th>
                          </tr>

                          @foreach ($order->orderProducts as $product)
                            @php
                              $variants = json_decode($product->variants, true);
                            @endphp

                            <tr>
                              <td>{{ ++$loop->index }}</td>
                              <td>
                                <a href="{{ route('product-detail', $product->product->slug) }}" target="_blank">
                                  {{ $product->product_name }}</a>
                              </td>
                              <td class="text-center">{{ $product->vendor->shop_name }}</td>
                              <td class="text-center">
                                @foreach ($variants as $key => $variant)
                                  <b>{{ $key }}:</b>
                                  {{ $variant['name'] }} ({{ formatCurrency($variant['price']) }})
                                  <br />
                                @endforeach
                              </td>
                              <td class="text-center">
                                {{ formatCurrency($product->unit_price) }}</td>
                              <td class="text-center">{{ $product->qty }}</td>
                              <td class="text-right">
                                {{ formatCurrency($product->unit_price * $product->qty + $product->variants_total) }}
                              </td>
                            </tr>
                          @endforeach
                        </table>
                      </div>
                      <div class="row mt-4">
                        <div class="col-lg-8">
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="">Trạng thái thanh toán</label>
                              <select name="payment_status" id="payment_status" class="form-control">
                                <option {{ $order->payment_status === 0 ? 'selected' : '' }} value="0">
                                  Chưa thanh toán
                                </option>
                                <option {{ $order->payment_status === 1 ? 'selected' : '' }} value="1">
                                  Đã thanh toán
                                </option>
                              </select>
                            </div>

                            <div class="form-group">
                              <label for="">Trạng thái đơn hàng</label>
                              <select name="order_status" id="order_status" class="form-control">
                                @foreach (config('order_status.order_status_admin') as $key => $status)
                                  <option value="{{ $key }}"
                                    {{ $order->order_status === $key ? 'selected' : '' }}>
                                    {{ $status['status'] }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-4 text-right">
                          <div class="invoice-detail-item">
                            <div class="invoice-detail-name">Tạm tính</div>
                            <div class="invoice-detail-value">{{ formatCurrency($order->sub_total) }}</div>
                          </div>

                          <div class="invoice-detail-item">
                            <div class="invoice-detail-name">Phí vận chuyển (+)</div>
                            <div class="invoice-detail-value">{{ formatCurrency($shipping['cost']) }}</div>
                          </div>

                          <div class="invoice-detail-item">
                            <div class="invoice-detail-name">Mã giảm giá (-)</div>
                            <div class="invoice-detail-value">
                              {{ isset($coupon)
                                  ? ($coupon['discount_type'] === 'percent'
                                      ? $coupon['discount'] . ' %'
                                      : formatCurrency($coupon['discount']))
                                  : 0 }}
                            </div>
                          </div>

                          <hr class="mt-2 mb-2">

                          <div class="invoice-detail-item">
                            <div class="invoice-detail-name">Tổng cộng</div>
                            <div class="invoice-detail-value invoice-detail-value-lg">
                              {{ formatCurrency($order->amount) }}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="text-md-right">
                  <button class="btn btn-success btn-icon icon-left print-invoice"><i class="fas fa-print"></i>
                    In hóa đơn</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    document.getElementById('order_status').addEventListener('change', async function() {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      let status = this.value;
      let statusId = '{{ $order->id }}';

      try {
        const res = await fetch('{{ route('admin.orders.order-status') }}', {
          method: 'PUT',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            status,
            id: statusId
          })
        });

        const data = await res.json();

        if (data.status === 'success') toastr.success(data.message);
      } catch (err) {
        console.error(err);
        toastr.error("Đã có lỗi xảy ra!");
      }
    });

    document.getElementById('payment_status').addEventListener('change', async function() {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      let status = this.value;
      let statusId = '{{ $order->id }}';

      try {
        const res = await fetch('{{ route('admin.orders.payment-status') }}', {
          method: 'PUT',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            status,
            id: statusId
          })
        });

        const data = await res.json();

        if (data.status === 'success') toastr.success(data.message);
      } catch (err) {
        console.error(err);
        toastr.error("Đã có lỗi xảy ra!");
      }
    });

    document.querySelector('.print-invoice').addEventListener('click', function() {
      let printBody = document.querySelector('.invoice-print').innerHTML;
      let originalContents = document.body.innerHTML;

      document.body.innerHTML = printBody;
      window.print();
      document.body.innerHTML = originalContents;

      location.reload();
    });
  </script>
@endpush
