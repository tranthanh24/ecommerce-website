<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <title>
    @yield('title')
  </title>

  <link rel="icon" type="image/png" href="{{ asset($logo->favicon) }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/mobile_menu.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/venobox.min.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/ranger_style.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/jquery.exzoom.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/add_row_custon.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/jquery.calendar.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/multiple-image-video.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/jquery.nice-number.min.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/jquery.classycountdown.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">

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

  @vite(['resources/js/app.js', 'resources/js/frontend.js'])
</head>

<body>
  <!-- Menu -->
  <div class="wsus__dashboard_menu">
    <div class="wsusd__dashboard_user">
      <img src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('frontend/images/avatar.jpg') }}"
        alt="img" class="img-fluid">
      <p>{{ auth()->user()->name }}</p>
    </div>
  </div>

  <!-- Dashboard -->
  @yield('content')

  <!-- Scroll Button -->
  <div class="wsus__scroll_btn">
    <i class="fas fa-chevron-up"></i>
  </div>

  @include('frontend.layouts.chatbot')

  <!--jquery library js-->
  <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
  <!--jquery ui (for ranger slider)-->
  <script src="{{ asset('frontend/js/ranger_jquery-ui.min.js') }}"></script>
  <!--bootstrap js-->
  <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
  <!--slick slider js-->
  <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
  <!--venobox js-->
  <script src="{{ asset('frontend/js/venobox.min.js') }}"></script>
  <!--font-awesome js-->
  <script src="{{ asset('frontend/js/Font-Awesome.js') }}"></script>
  <!--product zoomer js-->
  <script src="{{ asset('frontend/js/jquery.exzoom.js') }}"></script>
  <!--add row js-->
  <script src="{{ asset('frontend/js/add_row_custon.js') }}"></script>
  <!--sticky sidebar js-->
  <script src="{{ asset('frontend/js/sticky_sidebar.js') }}"></script>
  <!--simplyCountdown js-->
  <script src="{{ asset('frontend/js/simplyCountdown.js') }}"></script>
  <!--isotope js-->
  <script src="{{ asset('frontend/js/isotope.pkgd.min.js') }}"></script>
  <!--counter js-->
  <script src="{{ asset('frontend/js/jquery.countup.min.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.waypoints.min.js') }}"></script>
  <!--multiple-image-video js-->
  <script src="{{ asset('frontend/js/multiple-image-video.js') }}"></script>
  <!--price ranger js-->
  <script src="{{ asset('frontend/js/ranger_slider.js') }}"></script>
  <!--nice-number js-->
  <script src="{{ asset('frontend/js/jquery.nice-number.min.js') }}"></script>
  <!--classycountdown js-->
  <script src="{{ asset('frontend/js/jquery.classycountdown.js') }}"></script>
  <!--select2 js-->
  <script src="{{ asset('frontend/js/select2.min.js') }}"></script>
  <!--main/custom js-->
  <script src="{{ asset('frontend/js/main.js') }}"></script>
  <!--sweetalert js-->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!--data table js-->
  <script src="//cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
  <script src="//cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
  <!--toast js-->
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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

  @stack('scripts')
  @stack('styles')
</body>

</html>
