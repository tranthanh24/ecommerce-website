@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Bảng điều khiển</h1>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.orders.index') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Đơn hàng hôm nay</h4>
              </div>
              <div class="card-body">
                {{ $todayOrders }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.processing-orders') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="fas fa-tasks"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Đang xử lý hôm nay</h4>
              </div>
              <div class="card-body">
                {{ $todayPendingOrders }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.orders.index') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-info">
              <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Tất cả đơn hàng</h4>
              </div>
              <div class="card-body">
                {{ $orders }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.processing-orders') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-secondary">
              <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Đơn hàng đang xử lý</h4>
              </div>
              <div class="card-body">
                {{ $pendingOrders }}
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.cancelled-orders') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
              <i class="fas fa-times-circle"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Đơn hàng đã hủy</h4>
              </div>
              <div class="card-body">
                {{ $cancelledOrders }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.completed-orders') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-success">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Đơn hàng đã giao</h4>
              </div>
              <div class="card-body">
                {{ $completedOrders }}
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="javascript:void(0)">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Doanh thu ngày</h4>
              </div>
              <div class="card-body">
                {{ formatCurrency($todayEarning) }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="javascript:void(0)">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="fas fa-calendar"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Doanh thu tuần</h4>
              </div>
              <div class="card-body">
                {{ formatCurrency($weekEarning) }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="javascript:void(0)">
          <div class="card card-statistic-1">
            <div class="card-icon bg-info">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Doanh thu tháng</h4>
              </div>
              <div class="card-body">
                {{ formatCurrency($monthEarning) }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="javascript:void(0)">
          <div class="card card-statistic-1">
            <div class="card-icon bg-secondary">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Doanh thu năm</h4>
              </div>
              <div class="card-body">
                {{ formatCurrency($yearEarning) }}
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="javascript:void(0)">
          <div class="card card-statistic-1">
            <div class="card-icon bg-success">
              <i class="fas fa-wallet"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Tổng danh thu</h4>
              </div>
              <div class="card-body">
                {{ formatCurrency($earning) }}
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.admin-list.index') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-user-shield"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Quản trị viên</h4>
              </div>
              <div class="card-body">
                {{ $admins }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.vendor.index') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="fas fa-store"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Cửa hàng</h4>
              </div>
              <div class="card-body">
                {{ $vendors }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.customer.index') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-info">
              <i class="fas fa-users"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Người dùng</h4>
              </div>
              <div class="card-body">
                {{ $users }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="javascript:void(0)">
          <div class="card card-statistic-1">
            <div class="card-icon bg-secondary">
              <i class="fas fa-user-plus"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Người theo dõi</h4>
              </div>
              <div class="card-body">
                {{ $subscribers }}
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.products.index') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-box"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Sản phẩm</h4>
              </div>
              <div class="card-body">
                {{ $products }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.brand.index') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="fas fa-tags"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Thương hiệu</h4>
              </div>
              <div class="card-body">
                {{ $brands }}
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <a href="{{ route('admin.blog.index') }}">
          <div class="card card-statistic-1">
            <div class="card-icon bg-info">
              <i class="fas fa-newspaper"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Blog</h4>
              </div>
              <div class="card-body">
                {{ $blogs }}
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-lg-6 col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Biểu đồ đơn hàng</h4>
          </div>
          <div class="card-body">
            <canvas id="ordersPieChart" height="200"></canvas>
          </div>
        </div>
      </div>

      <div class="col-lg-6 col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Biểu đồ doanh thu</h4>
          </div>
          <div class="card-body">
            <canvas id="revenuePieChart" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Biểu đồ doanh thu theo tháng</h4>
          </div>
          <div class="card-body">
            <canvas id="revenueBarChart" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof Chart === 'undefined') {
        return;
      }

      function formatCurrencyVN(value) {
        let number = typeof value === 'number' ? value : Number(value) || 0;
        return number.toLocaleString('vi-VN') + ' ₫';
      }

      const ordersCtx = document.getElementById('ordersPieChart');
      if (ordersCtx) {
        const ordersChart = new Chart(ordersCtx, {
          type: 'pie',
          data: {
            labels: ['Đang xử lý', 'Đã giao', 'Đã hủy'],
            datasets: [{
              data: [
                {{ $pendingOrders }},
                {{ $completedOrders }},
                {{ $cancelledOrders }},
              ],
              backgroundColor: ['#facc15', '#22c55e', '#ef4444'],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            legend: {
              position: 'bottom'
            }
          }
        });
      }

      const revenueCtx = document.getElementById('revenuePieChart');
      if (revenueCtx) {
        const revenueChart = new Chart(revenueCtx, {
          type: 'pie',
          data: {
            labels: ['Hôm nay', 'Tuần này', 'Tháng này', 'Năm nay'],
            datasets: [{
              data: [
                {{ $todayEarning }},
                {{ $weekEarning }},
                {{ $monthEarning }},
                {{ $yearEarning }},
              ],
              backgroundColor: ['#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff'],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            legend: {
              position: 'bottom'
            }
          }
        });
      }

      const revenueBarCtx = document.getElementById('revenueBarChart');
      if (revenueBarCtx) {
        const revenueBarChart = new Chart(revenueBarCtx, {
          type: 'bar',
          data: {
            labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            datasets: [{
              label: 'Doanh thu',
              data: @json($monthlyRevenue),
              backgroundColor: '#4bc0c0',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            legend: {
              display: false
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  callback: function(value) {
                    return formatCurrencyVN(value);
                  }
                }
              }]
            },
            tooltips: {
              callbacks: {
                label: function(tooltipItem) {
                  return formatCurrencyVN(tooltipItem.yLabel);
                }
              }
            }
          }
        });
      }
    });
  </script>
@endpush
