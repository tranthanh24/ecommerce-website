@extends('frontend.layouts.master')

@section('title')
  Chi tiết giỏ hàng
@endsection

@section('content')
  <!-- Breadcrumb -->
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>Giỏ hàng</h4>
            <ul>
              <li><a href="{{ url('/') }}">Trang chủ</a></li>
              <li><a href="javascript:void(0)">Giỏ hàng</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Cart View -->
  <section id="wsus__cart_view">
    <div class="container">
      <div class="row">
        <div class="col-xl-9">
          <div class="wsus__cart_list">
            <div class="table-responsive">
              <table>
                <tbody>
                  <tr class="d-flex">
                    <th class="wsus__pro_img">Sản phẩm</th>
                    <th class="wsus__pro_name">Chi tiết sản phẩm</th>
                    <th class="wsus__pro_tk">Đơn giá</th>
                    <th class="wsus__pro_tk">Tổng cộng</th>
                    <th class="wsus__pro_select">Số lượng</th>
                    <th class="wsus__pro_icon">
                      <a href="{{ route('clear-cart') }}" class="common_btn clear_cart">Xóa giỏ hàng</a>
                    </th>
                  </tr>

                  @foreach ($cartItems as $item)
                    <tr class="d-flex">
                      <td class="wsus__pro_img">
                        <img src="{{ asset($item->options->image) }}" alt="product" class="img-fluid w-100">
                      </td>

                      <td class="wsus__pro_name">
                        <a href="{{ route('product-detail', $item->options->slug) }}">{{ $item->name }}</a>

                        @foreach ($item->options->variants as $key => $variant)
                          <span>{{ $key }}: {{ $variant['name'] }}
                            ({{ formatCurrency($variant['price']) }})
                          </span>
                        @endforeach
                      </td>

                      <td class="wsus__pro_tk">
                        <h6>{{ formatCurrency($item->price) }}</h6>
                      </td>

                      <td class="wsus__pro_tk">
                        <h6 id="{{ $item->rowId }}">
                          {{ formatCurrency(($item->price + $item->options->variant_total) * $item->qty) }}
                        </h6>
                      </td>

                      <td class="wsus__pro_select">
                        <form class="product_qty_wrapper">
                          <button class="qty_btn minus" type="button">-</button>
                          <input class="product_qty" type="text" name="qty" value="{{ $item->qty }}"
                            data-rowid="{{ $item->rowId }}" min="1" max="100" readonly />
                          <button class="qty_btn plus" type="button">+</button>
                        </form>
                      </td>

                      <td class="wsus__pro_icon">
                        <a href="{{ route('cart.remove-product', $item->rowId) }}">
                          <i class="far fa-times-circle"></i></a>
                      </td>
                    </tr>
                  @endforeach

                  @if (count($cartItems) === 0)
                    <tr class="d-flex">
                      <td class="wsus__pro_icon" style="width:100%">
                        <i class="fas fa-cart-arrow-down mx-1"></i>Giỏ hàng của bạn đang trống!
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-xl-3">
          <div class="wsus__cart_list_footer_button" id="sticky_sidebar">
            <h6>Tổng tiền</h6>
            <p>Tạm tính: <span id="cart_subtotal">{{ formatCurrency(getCartTotal()) }}</span></p>
            <p>Mã giảm giá: <span id="discount">{{ cartDiscount() }}</span></p>
            <p class="total"><span>Tổng cộng:</span>
              <span id="cart_total">{{ formatCurrency(getPayableCartTotal()) }}</span>
            </p>

            <form id="coupon_form">
              <input type="text" placeholder="Mã giảm giá" name="coupon_code"
                value="{{ Session::get('coupon.coupon_code') }}">
              <button type="submit" class="common_btn">Dùng</button>
            </form>

            <a class="common_btn mt-4 w-100 text-center" href="{{ route('user.checkout') }}">Thanh toán</a>
            <a class="common_btn mt-1 w-100 text-center" href="{{ url('/') }}">
              <i class="fab fa-shopify"></i> Tiếp tục mua sắm</a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Update quantity
      document.querySelectorAll('.qty_btn').forEach(btn => {
        btn.addEventListener('click', async () => {
          const input = btn.parentElement.querySelector('.product_qty');
          let qty = +input.value;
          let rowId = input.dataset.rowid;

          qty = btn.classList.contains('plus') ? qty + 1 : Math.max(1, qty - 1);
          input.value = qty;

          try {
            const res = await fetch('{{ route('cart.update-qty') }}', {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                qty,
                rowId
              })
            });
            const data = await res.json();

            if (data.status === 'success') {
              // Change product price
              document.getElementById(rowId).innerHTML =
                `${formatNumber(data.product_total)} ${currency}`;

              // Change subtotal price
              getCartSubTotal((total) => {
                document.getElementById('cart_subtotal').innerText =
                  `${formatNumber(total)} ${currency}`;
              })

              calculateCouponDiscount();

              toastr.success(data.message);
            } else if (data.status === 'stock_out') {
              toastr.error(data.message);
            } else if (data.status === 'stock_not_available') {
              toastr.error(data.message);
            }
          } catch (err) {
            toastr.error("Đã có lỗi xảy ra:", err)
          }
        });
      });

      // Clear cart
      document.querySelector('.clear_cart').addEventListener('click', async (e) => {
        e.preventDefault();

        const deleteUrl = e.currentTarget.getAttribute('href');

        const result = await Swal.fire({
          title: "Bạn có chắc không?",
          text: "Hành động này sẽ xóa giỏ hàng của bạn!",
          icon: "warning",
          showCancelButton: true,
          cancelButtonColor: "#d33",
          cancelButtonText: "Hủy",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Vâng, xóa đi!"
        });

        if (result.isConfirmed) {
          try {
            const res = await fetch(deleteUrl, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
              }
            });
            const data = await res.json();

            if (res.ok) location.reload();
          } catch (error) {
            toastr.error("Đã có lỗi xảy ra:", err)
          }
        }
      });

      // Apply coupon on cart
      document.getElementById('coupon_form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const params = new URLSearchParams(formData).toString();

        try {
          const res = await fetch(`{{ route('apply-coupon') }}?${params}`, {
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            }
          });
          const data = await res.json();

          if (data.status === 'success') {
            calculateCouponDiscount();
            toastr.success(data.message);
          } else if (data.status === 'error') {
            toastr.error(data.message);
          }
        } catch (err) {
          toastr.error("Đã có lỗi xảy ra:", err)
        }
      });

      // Calculate discount
      async function calculateCouponDiscount() {
        try {
          const res = await fetch('{{ route('calculate-coupon') }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            }
          })
          const data = await res.json();

          if (data.status === 'success') {
            document.getElementById('cart_total').innerHTML =
              `${formatNumber(data.cart_total)} ${currency}`;
            if (data.discount_type === 'percent') {
              document.getElementById('discount').innerHTML =
                `${formatNumber(data.discount)}  %`;
            } else {
              document.getElementById('discount').innerHTML =
                `${formatNumber(data.discount)} ${currency}`;
            }
          } else if (data.status === 'no_coupon') {
            document.getElementById('cart_total').innerHTML =
              `${formatNumber(data.cart_total)} ${currency}`;
            document.getElementById('discount').innerHTML = 0;
          } else {
            toastr.error(data.message);
          }
        } catch (err) {
          toastr.error("Đã có lỗi xảy ra:", err)
        }
      }
    });
  </script>
@endpush
