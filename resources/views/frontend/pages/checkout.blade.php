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
              <li><a href="javascript:void(0)">Giỏ hàng</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Check out -->
  <section id="wsus__cart_view">
    <div class="container">
      <div class="row">
        <div class="col-xl-8 col-lg-7">
          <div class="wsus__check_form">
            <h5 style="display: flex; justify-content: space-between; align-items: center; margin: 0;">
              Thông tin thanh toán
              <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal"
                style="color: #fff; background: #0d6efd; padding: 6px 12px; border-radius: 30px; font-size: 1rem">
                Thêm địa chỉ mới</a>
            </h5>

            <div class="row">
              @foreach ($addresses as $address)
                <div class="col-xl-6">
                  <div class="wsus__checkout_single_address">
                    <div class="form-check">
                      <input class="form-check-input shipping_address" type="radio" name="shipping_address"
                        id="address-{{ $address->id }}" data-id="{{ $address->id }}" value="{{ $address->id }}">
                      <label class="form-check-label" for="address-{{ $address->id }}">Chọn địa chỉ</label>
                    </div>

                    <ul>
                      <li><span>Tên:</span>{{ $address->name }}</li>
                      <li><span>Email:</span>{{ $address->email }}</li>
                      <li><span>Điện thoại:</span>{{ $address->phone }}</li>
                      <li><span>Tỉnh / Thành phố:</span>{{ $address->city }}</li>
                      <li><span>Địa chỉ:</span>{{ $address->address }}</li>
                      <li><span>Loại địa chỉ:</span>
                        {{ $address->address_type === 'office' ? 'Công ty' : 'Nhà' }}
                      </li>
                    </ul>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        @php
          $cartTotal = getCartTotal();
        @endphp

        <div class="col-xl-4 col-lg-5">
          <div class="wsus__order_details" id="sticky_sidebar">
            <p class="wsus__product">Phương thức giao hàng</p>

            @foreach ($shippingMethods as $method)
              <div class="form-check">
                <input class="form-check-input shipping_method" type="radio" name="shipping_method"
                  id="shipping_{{ $method->id }}" data-id="{{ $method->cost }}" value="{{ $method->id }}">

                <label class="form-check-label" for="shipping_{{ $method->id }}">
                  @if ($method->name === 'Giao hàng nhanh')
                    Giao hàng nhanh <span>(3 - 5 ngày)</span>
                  @elseif ($method->name === 'Hỏa tốc')
                    Hỏa tốc <span>(Trong ngày)</span>
                  @else
                    Giao hàng miễn phí <span>(5 - 7 ngày)</span>
                  @endif

                  <div class="small text-muted">
                    @if ($method->type === 'flat_cost')
                      {{ formatCurrency($method->cost) }}
                    @elseif ($method->type === 'min_cost')
                      @if ($cartTotal >= $method->min_cost)
                        Miễn phí (đã đủ điều kiện)
                      @else
                        Miễn phí với đơn từ {{ formatCurrency($method->min_cost) }}<br>
                        (Bạn còn thiếu {{ formatCurrency($method->min_cost - $cartTotal) }})
                      @endif
                    @endif
                  </div>
                </label>
              </div>
            @endforeach

            <div class="wsus__order_details_summery">
              <p>Tạm tính: <span>{{ formatCurrency($cartTotal) }}</span></p>
              <p>Phí vận chuyển (+): <span id="shipping-fee">{{ formatCurrency(0) }}</span></p>
              <p>Mã giảm giá (-): <span id="discount">{{ cartDiscount() }}</span></p>
              <p><b>Tổng cộng:</b>
                <span id="total_amount" data-id="{{ getPayableCartTotal() }}">
                  <b>{{ formatCurrency(getPayableCartTotal()) }}</b>
                </span>
              </p>
            </div>

            <div class="terms_area">
              <div class="form-check">
                <input class="form-check-input agree_term" type="checkbox" value="" id="flexCheckChecked3" checked>
                <label class="form-check-label" for="flexCheckChecked3">
                  Tôi xác nhận đã đọc và đồng ý với <a href="javascript:void(0)">các điều khoản và điều kiện </a>
                  của trang web
                </label>
              </div>
            </div>

            <form action="" id="checkoutForm">
              <input type="hidden" name="shipping_method_id" id="shipping_method_id">
              <input type="hidden" name="shipping_address_id" id="shipping_address_id">
            </form>

            <a href="" class="common_btn" id="submitCheckoutForm">Đặt hàng</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="wsus__popup_address">
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Thêm địa chỉ mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body p-0">
            <div class="wsus__check_form p-3">
              <form action="{{ route('user.checkout.address.create') }}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="wsus__check_single_form">
                      <input type="text" name='name' placeholder="Tên *" value="{{ old('name') }}">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="wsus__check_single_form">
                      <input type="text" name='email' placeholder="Email" value="{{ old('email') }}">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="wsus__check_single_form">
                      <input type="text" name='phone' placeholder="Số điện thoại *" value="{{ old('phone') }}">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="wsus__check_single_form">
                      <select class="select_2" name="city">
                        <option value="">Tỉnh / Thành phố *</option>
                        <option value="">Chọn</option>
                        @foreach (config('settings.address') as $address)
                          <option {{ $address === old('city') ? 'selected' : '' }} value="{{ $address }}">
                            {{ $address }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="wsus__check_single_form">
                      <input type="text" name="address" placeholder="Địa chỉ *" value="{{ old('address') }}">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="wsus__check_single_form">
                      <select class="select_2" name="address_type">
                        <option value="">Loại địa chỉ</option>
                        <option value="">Chọn</option>
                        <option {{ old('address_type') === 'home' ? 'selected' : '' }} value="home">Nhà</option>
                        <option {{ old('address_type') === 'office' ? 'selected' : '' }} value="office">Công ty
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="col-xl-12">
                    <div class="wsus__check_single_form">
                      <button type="submit" class="btn btn-primary">Tạo mới</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    const shippingMethodId = document.getElementById('shipping_method_id');
    const shippingAddressId = document.getElementById('shipping_address_id');
    const btn = document.getElementById('submitCheckoutForm');
    const term = document.querySelector('.agree_term');

    document.querySelectorAll('.shipping_method').forEach((radio) => {
      radio.addEventListener('change', function() {
        shippingMethodId.value = this.value;

        const fee = +this.dataset.id;
        document.getElementById('shipping-fee').innerText = `${formatNumber(fee)} ${currency}`;

        const cartTotal = +document.getElementById('total_amount').dataset.id;

        const total = cartTotal + fee;

        document.getElementById('total_amount').innerHTML =
          `<b> ${formatNumber(total)} ${currency} </b>`;
      });
    });

    document.querySelectorAll('.shipping_address').forEach((radio) => {
      radio.addEventListener('change', function() {
        shippingAddressId.value = this.value;
      });
    });

    // Submit form
    document.getElementById('submitCheckoutForm').addEventListener('click', async (e) => {
      e.preventDefault();

      if (shippingMethodId.value === "" || shippingAddressId.value === "") {
        return toastr.error('Vui lòng chọn đầy đủ địa chỉ và phương thức giao hàng');
      }
      if (!term.checked) {
        return toastr.warning('Bạn phải đồng ý với các điều khoản và điều kiện của trang web');
      }

      const formElement = document.getElementById('checkoutForm');
      const formData = new FormData(formElement);
      const formBody = new URLSearchParams(formData).toString();

      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin fa-1x"></i> Đang xử lý...';

      try {
        const res = await fetch('{{ route('user.checkout.form-submit') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          },
          body: formBody
        });
        const data = await res.json();

        if (data.status === 'success') {
          document.getElementById('submitCheckoutForm').innerText = 'Đặt hàng';
          window.location.href = data.redirect_url;
        }
      } catch (err) {
        toastr.error("Đã có lỗi xảy ra:", err)
      }
    })
  </script>
@endpush
