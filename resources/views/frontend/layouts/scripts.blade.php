<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const currency = @json(@$setting->currency_icon);

  // Format number
  const formatNumber = (num) => {
    return new Intl.NumberFormat('vi-VN').format(num);
  };

  // Get subtotal price
  async function getCartSubTotal(callback) {
    try {
      const res = await fetch('{{ route('cart.sidebar-product-total') }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      });
      const data = await res.json();

      if (res.ok) {
        if (typeof callback === 'function') callback(data.total);
        return data.total;
      }
    } catch (err) {
      toastr.error("Đã có lỗi xảy ra:", err)
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Get cart item count
    async function getCartCount() {
      try {
        const res = await fetch('{{ route('cart-count') }}', {
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        });
        const data = await res.json();

        if (res.ok) document.getElementById('cart-count').innerText = data.count;
      } catch (err) {
        toastr.error("Đã có lỗi xảy ra:", err)
      }
    }

    // Fetch list of products
    async function fetchCartProducts() {
      try {
        const res = await fetch('{{ route('cart-products') }}', {
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        });
        const data = await res.json();

        if (res.ok) {
          let html = '';
          for (let rowId in data.products) {
            const item = data.products[rowId];
            html +=
              `<li id="mini_cart_${item.rowId}">
                <div class="wsus__cart_img">
                  <a href="#"><img src="{{ asset('/${item.options.image}') }}" alt="product" class="img-fluid w-100"></a>
                  <a class="wsis__del_icon remove_product" data-rowid="${item.rowId}" href=""><i class="fas fa-minus-circle"></i></a>
                </div>
                <div class="wsus__cart_text">
                  <a class="wsus__cart_title" href="/product-detail/${item.options.slug}">${item.name}</a>
                  <p>${formatNumber(item.price)}${currency}</p>
                  <small>Tổng cấu hình: ${formatNumber(item.options.variant_total)} ${currency}</small> <br>
                  <small>Số lượng: ${item.qty}</small>
                </div>
              </li>`
          }
          document.querySelector('.mini_cart_wrapper').innerHTML = html

          getCartSubTotal((total) => {
            document.querySelector('.mini_cart_subtotal').innerText =
              `${formatNumber(total)} ${currency}`;
          });
        }
      } catch (err) {
        toastr.error("Đã có lỗi xảy ra:", err)
      }
    }

    // Add product to cart
    document.querySelectorAll('.shopping-cart-form').forEach(form => {
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const formBody = new URLSearchParams(formData).toString();

        try {
          const res = await fetch('{{ route('add-to-cart') }}', {
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
            await Promise.all([getCartCount(), fetchCartProducts()]);
            toastr.success(data.message);
            document.querySelector('.mini_cart_action')?.classList.remove(
              'd-none');
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

    // Delete product (event delegation)
    document.querySelector('.mini_cart_wrapper')?.addEventListener('click', async function(e) {
      const btn = e.target.closest('.remove_product');
      if (!btn) return;

      e.preventDefault();

      let rowId = btn.dataset.rowid;

      try {
        const res = await fetch('{{ route('cart.remove-sidebar-product') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            rowId
          })
        });
        const data = await res.json();

        if (data.status === 'success') {
          document.getElementById('mini_cart_' + rowId)?.remove();
          toastr.success(data.message);
          getCartSubTotal();

          const cartWrapper = document.querySelector('.mini_cart_wrapper');
          if (cartWrapper && cartWrapper.querySelectorAll('li').length === 0) {
            document.querySelector('.mini_cart_action').classList.add('d-none');
            cartWrapper.innerHTML =
              '<li class="text-center">Giỏ hàng của bạn đang trống</li>';
          }
        }
      } catch (err) {
        toastr.error("Đã có lỗi xảy ra:", err)
      }
    })

    // Add product to wishlist
    document.addEventListener('click', async function(e) {
      const btn = e.target.closest('.add_to_wishlist');
      if (!btn) return;

      e.preventDefault();

      const id = btn.dataset.id;

      try {
        const res = await fetch('{{ route('user.wishlist.add') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            id
          })
        });

        if (!res.ok) {
          if (res.status === 401) {
            toastr.error("Bạn cần đăng nhập để thêm sản phẩm vào danh sách mong muốn");
          } else {
            toastr.error(`Lỗi ${res.status}: Không thể thêm sản phẩm`);
          }
          return;
        }

        const data = await res.json();

        if (data.status === 'success') {
          toastr.success(data.message);
          document.getElementById('wishlist_count').innerText = data.count;
        } else if (data.status === 'error') {
          toastr.error(data.message);
        }
      } catch (err) {
        toastr.error("Đã có lỗi xảy ra:", err)
      }
    });

    // Newsletter subscription
    document.getElementById('news').addEventListener('submit', async function(e) {
      e.preventDefault();

      const btn = document.querySelector('.subcribe_btn');

      const formData = new FormData(this);
      const formBody = new URLSearchParams(formData).toString();

      btn.innerText = 'Đang gửi...'
      btn.disabled = true;

      try {
        const res = await fetch('{{ route('news.subscribe') }}', {
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
          toastr.success(data.message);
        } else {
          toastr.error(data.message);
        }
      } catch (err) {
        toastr.error("Đã có lỗi xảy ra:" + err.message)
      } finally {
        btn.innerText = 'Đăng ký'
        btn.disabled = false;
      }
    })
  });
</script>
