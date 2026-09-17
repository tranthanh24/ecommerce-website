<div class="dashboard_sidebar">
  <span class="close_icon">
    <i class="far fa-bars dash_bar"></i>
    <i class="far fa-times dash_close"></i>
  </span>

  <a href="{{ url('/') }}" class="dash_logo"><img src="{{ asset($logo->logo) }}" alt="logo" class="img-fluid"></a>

  <ul class="dashboard_link" style="margin-top:10px">
    @if (auth()->user()->role === 'vendor')
      <li>
        <a class="active" href="{{ route('vendor.dashboard') }}"><i class="far fa-tachometer"></i>Cửa hàng</a>
      </li>
    @endif

    <li><a class="{{ setActive(['user.dashboard']) }}" href="{{ route('user.dashboard') }}">
        <i class="far fa-tachometer"></i>Tổng quan</a></li>
    <li><a href="{{ url('/') }}"><i class="far fa-home"></i>Đến trang chủ</a></li>
    <li><a class="{{ setActive(['user.messenger.*']) }}" href="{{ route('user.messenger.index') }}">
        <i class="far fa-sms"></i>Tin nhắn</a></li>
    <li><a class="{{ setActive(['user.orders.*']) }}" href="{{ route('user.orders.index') }}">
        <i class="far fa-receipt"></i>Đơn đặt hàng</a></li>
    <li><a class="{{ setActive(['user.review.index']) }}" href="{{ route('user.review.index') }}">
        <i class="far fa-star"></i>Đánh giá</a></li>
    <li><a class="{{ setActive(['user.profile']) }}" href="{{ route('user.profile') }}">
        <i class="far fa-user"></i>Hồ sơ của tôi</a></li>
    <li><a class="{{ setActive(['user.address.*']) }}" href="{{ route('user.address.index') }}">
        <i class="far fa-map"></i>Địa chỉ</a></li>

    @if (auth()->user()->role !== 'vendor')
      <li>
        <a class="{{ setActive(['user.vendor-request.index']) }}" href="{{ route('user.vendor-request.index') }}">
          <i class="far fa-store"></i>Yêu cầu mở cửa hàng</a>
      </li>
    @endif

    <li>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
          <i class="far fa-sign-out-alt"></i>Đăng xuất</a>
      </form>
    </li>
  </ul>
</div>
