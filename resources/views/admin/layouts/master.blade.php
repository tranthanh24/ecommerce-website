<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $setting->site_name }}</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('backend/assets/modules/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- CSS Libraries -->
  <link rel="icon" type="image/png" href="{{ asset(@$logo->favicon) }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/bootstrap-iconpicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/modules/jqvmap/dist/jqvmap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/modules/summernote/summernote-bs4.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/modules/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/modules/weather-icon/css/weather-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/modules/weather-icon/css/weather-icons-wind.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/components.css') }}">
  <!-- Start GA -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>

  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-94034622-3');
  </script>
  <!-- /END GA -->

  <script>
    const USER = {
      id: "{{ auth()->user()->id }}",
      name: "{{ auth()->user()->name ? auth()->user()->name : 'No Name' }}",
      image: "{{ auth()->user()->image ? asset(auth()->user()->image) : asset('frontend/images/avatar.jpg') }}",
    }

    const PUSHER = {
      key: "{{ $pusher->key }}",
      cluster: "{{ $pusher->cluster }}",
      authEndpoint: "{{ url('/broadcasting/auth') }}"
    }
  </script>

  @vite(['resources/js/app.js', 'resources/js/backend.js'])
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      <!-- Navbar Content -->
      @include('admin.layouts.navbar')

      <!-- Sidebar Content -->
      @include('admin.layouts.sidebar')

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          {{ @$logo->footer }}
        </div>
        <div class="footer-right">

        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ asset('backend/assets/modules/jquery.min.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/popper.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/tooltip.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/moment.min.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/stisla.js') }}"></script>

  <!-- JS Libraies -->
  <script src="{{ asset('backend/assets/modules/chart.min.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/summernote/summernote-bs4.js') }}"></script>
  <script src="{{ asset('backend/assets/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/jqvmap/dist/jquery.vmap.min.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/simple-weather/jquery.simpleWeather.min.js') }}"></script>
  <script src="{{ asset('backend/assets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="//cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
  <script src="//cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Template JS File -->
  <script src="{{ asset('backend/assets/js/scripts.js') }}"></script>
  <script>
    @if ($errors->any())
      @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}")
      @endforeach
    @endif
  </script>

  <!-- Delete alert -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.body.addEventListener('click', async (e) => {
        const target = e.target.closest('.delete-item');
        if (!target) return;

        e.preventDefault();
        const deleteUrl = target.getAttribute('href');

        const result = await Swal.fire({
          title: "Bạn có chắc không?",
          text: "Bạn sẽ không thể hoàn tác điều này!",
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
                'X-CSRF-TOKEN': document.querySelector(
                  'meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
              }
            });

            const data = await res.json();

            if (data.status === 'success') {
              await Swal.fire("Đã xóa!", data.message, "success");
              location.reload();
            } else if (data.status === 'error') {
              await Swal.fire("Không thể xóa!", data.message, "error");
            }
          } catch (error) {
            console.error("Lỗi:", error);
          }
        }
      });
    });
  </script>

  <script>
    $(document).ready(function() {
      $('form').on('submit', function() {
        $('.summernote').each(function() {
          $(this).val($(this).summernote('code'));
        });
      });
    });
  </script>

  @stack('scripts')
  @stack('styles')
</body>

</html>
