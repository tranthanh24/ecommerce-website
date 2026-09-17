<header style="position: sticky; top: 0; z-index: 1030;">
  <div class="container">
    <div class="row">
      <div class="col-2 col-md-1 d-lg-none">
        <div class="wsus__mobile_menu_area">
          <span class="wsus__mobile_menu_icon"><i class="fal fa-bars"></i></span>
        </div>
      </div>
      <div class="col-xl-2 col-7 col-md-8 col-lg-2">
        <div class="wsus_logo_area">
          <a class="wsus__header_logo" href="{{ url('/') }}">
            <img src="{{ asset(@$logo->logo) }}" alt="logo" class="img-fluid">
          </a>
        </div>
      </div>

      <div class="col-xl-5 col-md-6 col-lg-4 d-none d-lg-block">
        <div class="wsus__search">
          <form action="{{ route('products.index') }}" method="GET">
            <input type="text" placeholder="Tìm kiếm..." name="search" value="{{ request('search') }}">
            <button type="submit"><i class="far fa-search"></i></button>
          </form>
        </div>
      </div>

      <div class="col-xl-5 col-3 col-md-3 col-lg-6">
        <div class="wsus__call_icon_area">
          <div class="wsus__call_area">
            <div class="wsus__call">
              <i class="fas fa-user-headset"></i>
            </div>
            <div class="wsus__call_text">
              <p>{{ @$setting->contact_email }}</p>
              <p>{{ @$setting->contact_phone }}</p>
            </div>
          </div>

          <ul class="wsus__icon_area">
            <li><a href="{{ route('user.wishlist.index') }}">
                <i class="fal fa-heart" aria-hidden="true"></i>
                <span id="wishlist_count">
                  {{ auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0 }}
                </span></a>
            </li>

            <li><a class="wsus__cart_icon" href="#">
                <i class="fal fa-shopping-cart" aria-hidden="true"></i>
                <span id="cart-count">{{ Cart::content()->count() }}</span></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="wsus__mini_cart">
    <h4>Giỏ hàng <span class="wsus_close_mini_cart"><i class="far fa-times"></i></span></h4>

    <ul class="mini_cart_wrapper">
      @foreach (Cart::content() as $product)
        <li id="mini_cart_{{ $product->rowId }}">
          <div class="wsus__cart_img">
            <a href="#"><img src="{{ asset($product->options->image) }}" alt="product" class="img-fluid w-100">
            </a>
            <a class="wsis__del_icon remove_product" data-rowid="{{ $product->rowId }}" href="">
              <i class="fas fa-minus-circle"></i>
            </a>
          </div>

          <div class="wsus__cart_text">
            <a class="wsus__cart_title"
              href="{{ route('product-detail', $product->options->slug) }}">{{ $product->name }}
            </a>
            <p>{{ formatCurrency($product->price) }}</p>
            <small>Tổng cấu hình: {{ formatCurrency($product->options->variant_total) }}</small> <br>
            <small>Số lượng: {{ $product->qty }}</small>
          </div>
        </li>
      @endforeach

      @php $cartIsEmpty = Cart::content()->count() === 0; @endphp

      @if ($cartIsEmpty)
        <li class="text-center">Giỏ hàng của bạn đang trống</li>
      @endif
    </ul>

    <div class="mini_cart_action {{ $cartIsEmpty ? 'd-none' : '' }}">
      <h5>Tạm tính <span class="mini_cart_subtotal">{{ formatCurrency(getCartTotal()) }}</span></h5>
      <div class="wsus__minicart_btn_area">
        <a class="common_btn" href="{{ route('cart-detail') }}">Xem giỏ hàng</a>
        <a class="common_btn" href="{{ route('user.checkout') }}">Thanh toán</a>
      </div>
    </div>
  </div>
</header>
