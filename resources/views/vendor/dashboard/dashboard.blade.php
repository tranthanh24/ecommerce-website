@extends('vendor.layouts.master')

@section('title')
  Tổng quan
@endsection

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('vendor.layouts.sidebar')
      <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <h3 style="font-size:24px; font-weight:600; color:#2c3e50; margin-bottom:20px; margin-top:-20px;">
            Bảng điều khiển cửa hàng</h3>

          <div class="dashboard_content">
            <div class="wsus__dashboard">
              <div class="row mt-2">
                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item red" href="{{ route('vendor.orders.index') }}">
                    <i class="far fa-shopping-cart"></i>
                    <p>Hôm nay</p>
                    <h5 class="fw-bold">{{ $todayOrders }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item green" href="{{ route('vendor.orders.index') }}">
                    <i class="far fa-tasks"></i>
                    <p>Đang xử lý</p>
                    <h5 class="fw-bold">{{ $todayPendingOrders }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item sky" href="{{ route('vendor.orders.index') }}">
                    <i class="far fa-clipboard-list"></i>
                    <p>Tổng đơn</p>
                    <h5 class="fw-bold">{{ $orders }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item blue" href="{{ route('vendor.orders.index') }}">
                    <i class="far fa-hourglass-half"></i>
                    <p>Đơn chưa xong</p>
                    <h5 class="fw-bold">{{ $pendingOrders }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item orange" href="{{ route('vendor.orders.index') }}">
                    <i class="far fa-check-circle"></i>
                    <p>Đơn đã giao</p>
                    <h5 class="fw-bold">{{ $completedOrders }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item purple" href="{{ route('vendor.products.index') }}">
                    <i class="far fa-box-open"></i>
                    <p>Số sản phẩm</p>
                    <h5 class="fw-bold">{{ $products }}</h5>
                  </a>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item red" href="javascript:void(0);">
                    <i class="far fa-dollar-sign"></i>
                    <p>Doanh thu ngày</p>
                    <h5 class="fw-bold">{{ formatCurrency($todayEarning) }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item green" href="javascript:void(0);">
                    <i class="far fa-calendar-week"></i>
                    <p>Doanh thu tuần</p>
                    <h5 class="fw-bold">{{ formatCurrency($weekEarning) }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item sky" href="javascript:void(0);">
                    <i class="far fa-calendar-alt"></i>
                    <p>Doanh thu tháng</p>
                    <h5 class="fw-bold">{{ formatCurrency($monthEarning) }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item blue" href="javascript:void(0);">
                    <i class="far fa-calendar"></i>
                    <p>Doanh thu năm</p>
                    <h5 class="fw-bold">{{ formatCurrency($yearEarning) }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item orange" href="javascript:void(0);">
                    <i class="far fa-wallet"></i>
                    <p>Tổng doanh thu</p>
                    <h5 class="fw-bold">{{ formatCurrency($earning) }}</h5>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item purple" href="{{ route('vendor.messenger.index') }}">
                    <i class="far fa-sms"></i>
                    <p>Tin nhắn</p>
                    <h5 class="fw-bold">_</h5>
                  </a>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-12 col-md-6">
                  <div class="wsus__dashboard_chart">
                    <div class="wsus__dashboard_chart_header">
                      <div>
                        <h4>Phân bổ đơn hàng</h4>
                        <p>{{ $orders }} đơn hiện có</p>
                      </div>
                    </div>
                    <div class="wsus__dashboard_chart_body">
                      <canvas id="vendorOrdersStatusChart" height="240"></canvas>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="wsus__dashboard_chart wsus__dashboard_chart--line">
                    <div class="wsus__dashboard_chart_header">
                      <div>
                        <h4>Doanh thu theo tháng</h4>
                        <p>Năm {{ \Carbon\Carbon::now()->year }}</p>
                      </div>
                    </div>
                    <div class="wsus__dashboard_chart_body">
                      <canvas id="vendorRevenueChart" height="240"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  @php
    $vendorSegments = [
        ['Đang xử lý', $todayPendingOrders ?? 0, '#fb7185'],
        ['Đơn chưa xong', $pendingOrders ?? 0, '#60a5fa'],
        ['Đã giao', $completedOrders ?? 0, '#34d399'],
    ];
  @endphp

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('vendorOrdersStatusChart');
      if (!ctx || typeof Chart === 'undefined') return;

      const segments = @json($vendorSegments);

      const labels = segments.map(item => item[0]);
      const values = segments.map(item => Math.max(Number(item[1]) || 0, 0));
      const colors = segments.map(item => item[2]);
      const total = values.reduce((sum, value) => sum + value, 0);

      const chartContainer = ctx.parentElement;
      if (!total) {
        if (chartContainer) {
          chartContainer.innerHTML = '<div class="wsus__dashboard_chart_empty">Chưa có đơn hàng để hiển thị</div>';
        }
        return;
      }

      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels,
          datasets: [{
            data: values,
            backgroundColor: colors,
            borderWidth: 0,
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '72%',
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                usePointStyle: true,
                pointStyle: 'circle',
                padding: 16,
                boxWidth: 10,
              },
            },
            tooltip: {
              callbacks: {
                label(context) {
                  const formatter = new Intl.NumberFormat('vi-VN');
                  return `${context.label}: ${formatter.format(context.parsed)}`;
                },
              },
            },
          },
        },
      });

      const revenueCtx = document.getElementById('vendorRevenueChart');
      if (revenueCtx) {
        const revenueLabels = @json($monthlyLabels);
        const revenueValues = @json($monthlyRevenueData);

        new Chart(revenueCtx, {
          type: 'line',
          data: {
            labels: revenueLabels,
            datasets: [{
              label: 'Doanh thu',
              data: revenueValues,
              borderColor: '#2563eb',
              backgroundColor: 'rgba(37, 99, 235, 0.08)',
              fill: true,
              pointRadius: 4,
              pointBackgroundColor: '#fff',
              pointBorderWidth: 3,
              pointBorderColor: '#2563eb',
              tension: 0.35,
            }],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback(value) {
                    return new Intl.NumberFormat('vi-VN').format(value);
                  },
                },
              },
            },
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                callbacks: {
                  label(context) {
                    const formatter = new Intl.NumberFormat('vi-VN');
                    return `Doanh thu: ${formatter.format(context.parsed.y)} đ`;
                  },
                },
              },
            },
          },
        });
      }
    });
  </script>
@endpush
