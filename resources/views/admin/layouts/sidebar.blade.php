<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('admin.dashboard') }}">{{ @$setting->site_name }}</a>
    </div>

    <ul class="sidebar-menu">
      <li class="menu-header">Bảng điều khiển</li>

      <li class="dropdown active">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
          <i class="fas fa-fire"></i><span>Bảng điều khiển</span></a>
      </li>

      <li class="menu-header">Danh mục chính</li>

      <li class="dropdown {{ setActive(['admin.category.*', 'admin.sub-category.*', 'admin.child-category.*']) }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-folder"></i><span>Danh mục sản phẩm</span></a>

        <ul class="dropdown-menu">
          <li class="{{ setActive(['admin.category.*']) }}"><a class="nav-link"
              href="{{ route('admin.category.index') }}">Danh mục chính</a></li>
          <li class="{{ setActive(['admin.sub-category.*']) }}"><a class="nav-link"
              href="{{ route('admin.sub-category.index') }}">Danh mục con</a></li>
          <li class="{{ setActive(['admin.child-category.*']) }}"><a class="nav-link"
              href="{{ route('admin.child-category.index') }}">Danh mục phụ</a></li>
        </ul>
      </li>

      <li
        class="dropdown {{ setActive([
            'admin.brand.*',
            'admin.products.*',
            'admin.products-image-gallery.*',
            'admin.products-variant.*',
            'admin.products-variant-item.*',
            'admin.seller-products.*',
            'admin.seller-pending-products.*',
        ]) }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-box-open"></i><span>Sản phẩm</span></a>

        <ul class="dropdown-menu">
          <li class="{{ setActive(['admin.brand.*']) }}"><a class="nav-link"
              href="{{ route('admin.brand.index') }}">Thương hiệu</a></li>
          <li
            class="{{ setActive([
                'admin.products.*',
                'admin.products-image-gallery.*',
                'admin.products-variant.*',
                'admin.products-variant-item.*',
            ]) }}">
            <a class="nav-link" href="{{ route('admin.products.index') }}">Sản phẩm nội bộ</a>
          </li>
          <li class="{{ setActive(['admin.seller-products.*']) }}"><a class="nav-link"
              href="{{ route('admin.seller-products.index') }}">Sản phẩm đối tác</a></li>
          <li class="{{ setActive(['admin.seller-pending-products.*']) }}"><a class="nav-link"
              href="{{ route('admin.seller-pending-products.index') }}">Sản phẩm chờ duyệt</a></li>
        </ul>
      </li>

      <li class="dropdown {{ setActive(['admin.blog-category.*', 'admin.blog.*']) }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-newspaper"></i><span>Blog</span></a>

        <ul class="dropdown-menu">
          <li class="{{ setActive(['admin.blog.*']) }}"><a class="nav-link"
              href="{{ route('admin.blog.index') }}">Blog</a></li>
          <li class="{{ setActive(['admin.blog-category.*']) }}"><a class="nav-link"
              href="{{ route('admin.blog-category.index') }}">Danh mục</a></li>
        </ul>
      </li>

      <li
        class="dropdown {{ setActive([
            'admin.orders.*',
            'admin.pending-orders',
            'admin.confirmed-orders',
            'admin.processing-orders',
            'admin.shipped-orders',
            'admin.out-for-delivery-orders',
            'admin.completed-orders',
            'admin.cancelled-orders',
        ]) }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-shopping-basket"></i><span>Đơn hàng</span></a>

        <ul class="dropdown-menu">
          <li class="{{ setActive(['admin.orders.*']) }}"><a class="nav-link"
              href="{{ route('admin.orders.index') }}">Tất cả đơn hàng</a></li>
          <li class="{{ setActive(['admin.pending-orders']) }}"><a class="nav-link"
              href="{{ route('admin.pending-orders') }}">Chờ xác nhận</a></li>
          <li class="{{ setActive(['admin.confirmed-orders']) }}"><a class="nav-link"
              href="{{ route('admin.confirmed-orders') }}">Đã xác nhận</a></li>
          <li class="{{ setActive(['admin.processing-orders']) }}"><a class="nav-link"
              href="{{ route('admin.processing-orders') }}">Đang xử lý</a></li>
          <li class="{{ setActive(['admin.shipped-orders']) }}"><a class="nav-link"
              href="{{ route('admin.shipped-orders') }}">Đã gửi</a></li>
          <li class="{{ setActive(['admin.out-for-delivery-orders']) }}"><a class="nav-link"
              href="{{ route('admin.out-for-delivery-orders') }}">Đang giao</a></li>
          <li class="{{ setActive(['admin.completed-orders']) }}"><a class="nav-link"
              href="{{ route('admin.completed-orders') }}">Hoàn thành</a></li>
          <li class="{{ setActive(['admin.cancelled-orders']) }}"><a class="nav-link"
              href="{{ route('admin.cancelled-orders') }}">Đã hủy</a></li>
        </ul>
      </li>

      <li class="{{ setActive(['admin.transactions.index']) }}">
        <a class="nav-link" href="{{ route('admin.transactions.index') }}">
          <i class="fas fa-wallet"></i><span>Giao dịch</span></a>
      </li>

      <li class="{{ setActive(['admin.messenger.index']) }}">
        <a class="nav-link" href="{{ route('admin.messenger.index') }}">
          <i class="fab fa-rocketchat"></i><span>Tin nhắn</span></a>
      </li>

      <li class="menu-header">Cài đặt & Khác</li>

      <li
        class="dropdown {{ setActive([
            'admin.vendor-profile.*',
            'admin.flash-sale.*',
            'admin.coupons.*',
            'admin.shipping-rule.*',
            'admin.payment-setting.*',
        ]) }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-shopping-cart"></i><span>Bán hàng</span></a>

        <ul class="dropdown-menu">
          <li class="{{ setActive(['admin.flash-sale.*']) }}"><a class="nav-link"
              href="{{ route('admin.flash-sale.index') }}">Flash sale</a></li>
          <li class="{{ setActive(['admin.coupons.*']) }}"><a class="nav-link"
              href="{{ route('admin.coupons.index') }}">Mã giảm giá</a></li>
          <li class="{{ setActive(['admin.shipping-rule.*']) }}"><a class="nav-link"
              href="{{ route('admin.shipping-rule.index') }}">Vận chuyển</a></li>
          <li class="{{ setActive(['admin.vendor-profile.*']) }}"><a class="nav-link"
              href="{{ route('admin.vendor-profile.index') }}">Cửa hàng</a></li>
          <li class="{{ setActive(['admin.payment-setting.*']) }}"><a class="nav-link"
              href="{{ route('admin.payment-setting.index') }}">Thanh toán</a></li>
        </ul>
      </li>

      <li
        class="dropdown {{ setActive(['admin.slider.*', 'admin.homepage.settings.*', 'admin.vendor-condition.*']) }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-home"></i><span>Website</span></a>

        <ul class="dropdown-menu">
          <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link"
              href="{{ route('admin.slider.index') }}">Slider</a></li>
          <li class="{{ setActive(['admin.homepage.settings.*']) }}"><a class="nav-link"
              href="{{ route('admin.homepage.settings.index') }}">Trang chủ</a></li>
          <li class="{{ setActive(['admin.vendor-condition.*']) }}"><a class="nav-link"
              href="{{ route('admin.vendor-condition.index') }}">Điều kiện mở cửa hàng</a></li>
        </ul>
      </li>

      <li
        class="dropdown {{ setActive([
            'admin.vendors-request.*',
            'admin.customer.*',
            'admin.vendor.*',
            'admin.manage-user.*',
            'admin.admin-list.*',
        ]) }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-users"></i><span>Người dùng</span></a>

        <ul class="dropdown-menu">
          <li class="{{ setActive(['admin.customer.*']) }}"><a class="nav-link"
              href="{{ route('admin.customer.index') }}">Khách hàng</a></li>
          <li class="{{ setActive(['admin.vendor.*']) }}"><a class="nav-link"
              href="{{ route('admin.vendor.index') }}">Cửa hàng</a></li>
          <li class="{{ setActive(['admin.admin-list.*']) }}"><a class="nav-link"
              href="{{ route('admin.admin-list.index') }}">Quản trị viên</a></li>
          <li class="{{ setActive(['admin.vendors-request.*']) }}"><a class="nav-link"
              href="{{ route('admin.vendors-request.index') }}">Cửa hàng chờ duyệt</a></li>
          <li class="{{ setActive(['admin.manage-user.*']) }}"><a class="nav-link"
              href="{{ route('admin.manage-user.index') }}">Quản lý người dùng</a></li>
        </ul>
      </li>

      <li>
        <a class="nav-link {{ setActive(['admin.advertisement.*']) }}"
          href="{{ route('admin.advertisement.index') }}"><i class="fas fa-ad"></i><span>Quảng cáo</span></a>
      </li>

      <li>
        <a class="nav-link {{ setActive(['admin.settings.*']) }}" href="{{ route('admin.settings.index') }}">
          <i class="fas fa-user-cog"></i><span>Cài đặt chung</span></a>
      </li>
    </ul>
  </aside>
</div>
