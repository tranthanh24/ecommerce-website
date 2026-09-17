<div class="dashboard_sidebar">
  <span class="close_icon">
    <i class="far fa-bars dash_bar"></i>
    <i class="far fa-times dash_close"></i>
  </span>
  <a href="{{ route('vendor.dashboard') }}" class="dash_logo"><img src="{{ asset($logo->logo) }}" alt="logo"
      class="img-fluid"></a>

  <ul class="dashboard_link" style="margin-top:10px">
    <li><a class="{{ setActive(['vendor.dashboard']) }}" href="{{ route('vendor.dashboard') }}">
        <i class="far fa-tachometer"></i>Tổng quan</a></li>
    <li><a href="{{ url('/') }}"><i class="far fa-home"></i>Đến trang chủ</a></li>
    <li><a class="{{ setActive(['vendor.messenger.*']) }}" href="{{ route('vendor.messenger.index') }}">
        <i class="far fa-sms"></i>Tin nhắn</a></li>
    <li><a class="{{ setActive(['vendor.products.*']) }}" href="{{ route('vendor.products.index') }}">
        <i class="far fa-shopping-bag"></i>Sản phẩm</a></li>
    <li><a class="{{ setActive(['vendor.orders.*']) }}" href="{{ route('vendor.orders.index') }}">
        <i class="far fa-receipt"></i>Đơn đặt hàng</a></li>
    <li><a class="{{ setActive(['vendor.shop-profile.index']) }}" href="{{ route('vendor.shop-profile.index') }}">
        <i class="fab fa-shopify"></i>Hồ sơ cửa hàng</a></li>
    <li><a class="{{ setActive(['vendor.profile']) }}" href="{{ route('vendor.profile') }}">
        <i class="far fa-user"></i>Hồ sơ của tôi</a></li>
    <li>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
          <i class="far fa-sign-out-alt"></i>Đăng xuất</a>
      </form>
    </li>
  </ul>
</div>
