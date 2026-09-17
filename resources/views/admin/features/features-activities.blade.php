@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Hoạt động</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Bảng điều khiển</a></div>
        <div class="breadcrumb-item active">Hoạt động</div>
      </div>
    </div>

    <div class="section-body">
      <h2 class="section-title">Nhật ký hoạt động gần đây</h2>
      <p class="section-lead">Trang tổng quan nhỏ để xem nhanh một số hoạt động tiêu biểu.</p>

      <div class="row">
        <div class="col-12 col-lg-8">
          <div class="card">
            <div class="card-header">
              <h4>Hoạt động hệ thống</h4>
            </div>
            <div class="card-body">
              <div class="activities">
                <div class="activity">
                  <div class="activity-icon bg-primary text-white">
                    <i class="fas fa-user-check" style="font-size: 20px"></i>
                  </div>
                  <div class="activity-detail">
                    <h4>Đăng nhập quản trị</h4>
                    <p>Phiên làm việc hiện tại được bắt đầu từ menu tài khoản ở góc phải trên cùng.</p>
                  </div>
                </div>

                <div class="activity">
                  <div class="activity-icon bg-success text-white">
                    <i class="fas fa-shopping-cart" style="font-size: 20px"></i>
                  </div>
                  <div class="activity-detail">
                    <h4>Quản lý đơn hàng</h4>
                    <p>Kiểm tra, xử lý trạng thái đơn hàng trong mục <strong>Đơn hàng</strong> ở thanh menu bên trái.</p>
                  </div>
                </div>

                <div class="activity">
                  <div class="activity-icon bg-info text-white">
                    <i class="fas fa-bullhorn" style="font-size: 20px"></i>
                  </div>
                  <div class="activity-detail">
                    <h4>Chiến dịch khuyến mãi</h4>
                    <p>
                      Tạo flash sale, mã giảm giá và cấu hình vận chuyển trong nhóm menu
                      <strong>Bán hàng</strong>.
                    </p>
                  </div>
                </div>

                <div class="activity">
                  <div class="activity-icon bg-warning text-white">
                    <i class="fas fa-cog" style="font-size: 20px"></i>
                  </div>
                  <div class="activity-detail">
                    <h4>Cấu hình hệ thống</h4>
                    <p>
                      Các cài đặt chung, trang chủ, thanh toán… được quản lý trong mục <strong>Website</strong> và
                      <strong>Cài đặt chung</strong>.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="card">
            <div class="card-header">
              <h4>Ghi chú nhanh</h4>
            </div>
            <div class="card-body" style="text-align: justify">
              <p class="mb-3">
                Một vài lưu ý giúp theo dõi và vận hành hệ thống hiệu quả hơn:
              </p>
              <ul class="list-unstyled">
                <li class="mb-2">
                  <i class="fas fa-check text-success mr-2"></i>
                  Theo dõi mục <strong>Hoạt động hệ thống</strong> khi cần rà soát các thao tác quan trọng.
                </li>
                <li class="mb-2">
                  <i class="fas fa-check text-success mr-2"></i>
                  Kiểm tra trạng thái <strong>đơn hàng</strong> hàng ngày để xử lý kịp thời các vấn đề phát sinh.
                </li>
                <li class="mb-2">
                  <i class="fas fa-check text-success mr-2"></i>
                  Định kỳ xem lại <strong>phân quyền</strong> và tài khoản quản trị để đảm bảo an toàn hệ thống.
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
