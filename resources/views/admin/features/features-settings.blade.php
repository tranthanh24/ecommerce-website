@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Cài đặt nhanh</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Bảng điều khiển</a></div>
        <div class="breadcrumb-item active">Cài đặt</div>
      </div>
    </div>

    <div class="section-body">
      <h2 class="section-title">Khu vực cấu hình quan trọng</h2>
      <p class="section-lead">
        Các liên kết tới những trang cài đặt được dùng nhiều trong hệ thống.
      </p>

      <div class="row" style="text-align: justify">
        <div class="col-md-6 col-lg-4 mb-4">
          <a href="{{ route('admin.settings.index') }}" class="feature-settings-link">
            <div class="card feature-settings-card h-100">
              <div class="card-body d-flex align-items-center">
                <div
                  class="feature-settings-icon mr-3 d-flex align-items-center justify-content-center bg-primary text-white rounded-circle">
                  <i class="fas fa-sliders-h" style="margin:0"></i>
                </div>
                <div>
                  <h5 class="mb-1">Cài đặt chung</h5>
                  <p class="mb-0 text-muted">Tên website, tiền tệ, logo, footer, email, pusher...</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <a href="{{ route('admin.homepage.settings.index') }}" class="feature-settings-link">
            <div class="card feature-settings-card h-100">
              <div class="card-body d-flex align-items-center">
                <div
                  class="feature-settings-icon mr-3 d-flex align-items-center justify-content-center bg-info text-white rounded-circle">
                  <i class="fas fa-home" style="margin:0"></i>
                </div>
                <div>
                  <h5 class="mb-1">Trang chủ</h5>
                  <p class="mb-0 text-muted">Banner, danh mục nổi bật, slider sản phẩm.</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <a href="{{ route('admin.payment-setting.index') }}" class="feature-settings-link">
            <div class="card feature-settings-card h-100">
              <div class="card-body d-flex align-items-center">
                <div
                  class="feature-settings-icon mr-3 d-flex align-items-center justify-content-center bg-success text-white rounded-circle">
                  <i class="fas fa-credit-card" style="margin:0"></i>
                </div>
                <div>
                  <h5 class="mb-1">Thanh toán</h5>
                  <p class="mb-0 text-muted">Cấu hình VNPay, PayPal và phương thức thanh toán.</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <a href="{{ route('admin.advertisement.index') }}" class="feature-settings-link">
            <div class="card feature-settings-card h-100">
              <div class="card-body d-flex align-items-center">
                <div
                  class="feature-settings-icon mr-3 d-flex align-items-center justify-content-center bg-warning text-white rounded-circle">
                  <i class="fas fa-bullhorn" style="margin:0"></i>
                </div>
                <div>
                  <h5 class="mb-1">Quảng cáo & Banner</h5>
                  <p class="mb-0 text-muted">Banner trang chủ, trang sản phẩm, giỏ hàng...</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <a href="{{ route('admin.vendor-condition.index') }}" class="feature-settings-link">
            <div class="card feature-settings-card h-100">
              <div class="card-body d-flex align-items-center">
                <div
                  class="feature-settings-icon mr-3 d-flex align-items-center justify-content-center bg-secondary text-white rounded-circle">
                  <i class="fas fa-store"></i>
                </div>
                <div>
                  <h5 class="mb-1">Điều kiện mở cửa hàng</h5>
                  <p class="mb-0 text-muted">Quy định và điều kiện dành cho nhà bán hàng.</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <a href="{{ route('admin.manage-user.index') }}" class="feature-settings-link">
            <div class="card feature-settings-card h-100">
              <div class="card-body d-flex align-items-center">
                <div
                  class="feature-settings-icon mr-3 d-flex align-items-center justify-content-center bg-dark text-white rounded-circle">
                  <i class="fas fa-users-cog"></i>
                </div>
                <div>
                  <h5 class="mb-1">Quản lý người dùng</h5>
                  <p class="mb-0 text-muted">Phân quyền, quản lý admin và tài khoản hệ thống.</p>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('styles')
  <style>
    .feature-settings-link {
      display: block;
      text-decoration: none;
    }

    .feature-settings-link:hover,
    .feature-settings-link:focus,
    .feature-settings-link:active {
      text-decoration: none;
    }

    .feature-settings-card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      height: 140px;
      display: flex;
      align-items: center;
    }

    .feature-settings-card .card-body {
      width: 100%;
    }

    .feature-settings-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 18px 40px -30px rgba(15, 23, 42, 0.35);
    }

    .feature-settings-icon {
      width: 44px;
      height: 44px;
      flex: 0 0 44px;
      border-radius: 999px;
    }

    .feature-settings-icon i {
      font-size: 20px;
      line-height: 1;
    }
  </style>
@endpush
