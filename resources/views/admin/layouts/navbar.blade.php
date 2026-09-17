<nav class="navbar navbar-expand-lg main-navbar">
  <form class="form-inline mr-auto">
  </form>

  <ul class="navbar-nav navbar-right">
    <li class="dropdown">
      <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <img alt="image" src="{{ asset(auth()->user()->image) }}" class="rounded-circle mr-1"
          style="width: 50px; height:50px; object-fit:cover">
        <div class="d-sm-none d-lg-inline-block">{{ auth()->user()->name }}</div>
      </a>

      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
          <i class="far fa-user"></i>Hồ sơ
        </a>
        <a href="{{ route('admin.features.activities') }}" class="dropdown-item has-icon">
          <i class="fas fa-bolt"></i>Hoạt động
        </a>
        <a href="{{ route('admin.features.settings') }}" class="dropdown-item has-icon">
          <i class="fas fa-cog"></i>Cài đặt
        </a>
        <div class="dropdown-divider"></div>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
            class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i>Đăng xuất</a>
        </form>
      </div>
    </li>
  </ul>
</nav>
