@php
  $categories = \App\Models\Category::where('status', 1)
      ->with([
          'subCategories' => function ($query) {
              $query->where('status', 1)->with([
                  'childCategories' => function ($query) {
                      $query->where('status', 1);
                  },
              ]);
          },
      ])
      ->get();
@endphp

<nav class="wsus__main_menu d-none d-lg-block" style="top:90px; z-index:1020;">
  <div class="container">
    <div class="row">
      <div class="col-xl-12">
        <div class="relative_contect d-flex align-items-center">
          <div class="wsus_menu_category_bar">
            <i class="far fa-bars"></i>
          </div>

          <ul class="wsus_menu_cat_item show_home toggle_menu">
            @foreach ($categories as $category)
              @php $hasSub = count($category->subCategories) > 0; @endphp

              <li>
                <a class="{{ $hasSub ? 'wsus__droap_arrow' : '' }}"
                  href="{{ route('products.index', ['category' => $category->slug]) }}">
                  <i class="{{ $category->icon }}"></i>{{ $category->name }}</a>

                @if ($hasSub)
                  <ul class="wsus_menu_cat_droapdown">
                    @foreach ($category->subCategories as $subCategory)
                      @php $hasChild = count($subCategory->childCategories) > 0; @endphp

                      <li>
                        <a href="{{ route('products.index', ['subcategory' => $subCategory->slug]) }}">
                          {{ $subCategory->name }}<i class="{{ $hasChild ? 'fas fa-angle-right' : '' }}"></i></a>

                        @if ($hasChild)
                          <ul class="wsus__sub_category">
                            @foreach ($subCategory->childCategories as $childCategory)
                              <li><a href="{{ route('products.index', ['childcategory' => $childCategory->slug]) }}">
                                  {{ $childCategory->name }}</a></li>
                            @endforeach
                          </ul>
                        @endif
                      </li>
                    @endforeach
                  </ul>
                @endif
              </li>
            @endforeach

            <li><a href="{{ route('products.index') }}"><i class="fal fa-gem"></i> Tất cả sản phẩm</a></li>
          </ul>

          <ul class="wsus__menu_item">
            <li><a class="{{ setActive(['home']) }}" href="{{ url('/') }}">Trang chủ</a></li>
            <li><a class="{{ setActive(['vendors.index']) }}" href="{{ route('vendors.index') }}">Cửa hàng</a></li>
            <li><a class="{{ setActive(['blog']) }}" href="{{ route('blog') }}">Blog</a></li>
            <li><a class="{{ setActive(['flash-sale']) }}" href="{{ route('flash-sale') }}">Flash sale</a></li>
            <li><a class="{{ setActive(['contact.index']) }}" href="{{ route('contact.index') }}">Liên hệ</a></li>
          </ul>

          <ul class="wsus__menu_item wsus__menu_item_right">
            <li><a class="{{ setActive(['order-track.index']) }}" href="{{ route('order-track.index') }}">
                Theo dõi đơn hàng</a></li>

            @if (auth()->check())
              @if (auth()->user()->role === 'user')
                <li><a class="{{ setActive(['user.dashboard']) }}" href="{{ route('user.dashboard') }}">
                    Tài khoản của tôi</a></li>
              @elseif (auth()->user()->role === 'vendor')
                <li><a class="{{ setActive(['vendor.dashboard']) }}" href="{{ route('vendor.dashboard') }}">
                    Tài khoản của tôi</a></li>
              @else
                <li><a class="{{ setActive(['admin.dashboard']) }}" href="{{ route('admin.dashboard') }}">
                    Tài khoản của tôi</a></li>
              @endif
            @else
              <li><a class="{{ setActive(['login']) }}" href="{{ route('login') }}">Đăng nhập</a></li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- Mobile Menu -->
<section id="wsus__mobile_menu">
  <span class="wsus__mobile_menu_close"><i class="fal fa-times"></i></span>
  <ul class="wsus__mobile_menu_header_icon d-inline-flex">
    <li><a href="{{ route('user.wishlist.index') }}"><i class="far fa-heart"></i>
        <span> {{ \App\Models\Wishlist::where('user_id', auth()->id())->count() }}</span></a></li>
  </ul>

  <form action="{{ route('products.index') }}" method="GET">
    <input type="text" placeholder="Tìm kiếm..." name="search" value="{{ request('search') }}">
    <button type="submit"><i class="far fa-search"></i></button>
  </form>

  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
        role="tab" aria-controls="pills-home" aria-selected="true">Danh mục</button>
    </li>

    <li class="nav-item" role="presentation">
      <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
        role="tab" aria-controls="pills-profile" aria-selected="false">Menu chính</button>
    </li>
  </ul>

  <div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
      <div class="wsus__mobile_menu_main_menu">
        <div class="accordion accordion-flush" id="accordionFlushExample">
          <ul class="wsus_mobile_menu_category">
            @foreach ($categories as $category)
              @php $hasSub = count($category->subCategories) > 0; @endphp

              <li>
                <a href="#" class="{{ $hasSub ? 'accordion-button' : '' }} collapsed" data-bs-toggle="collapse"
                  data-bs-target="#flush-collapseThreew-{{ $loop->index }}" aria-expanded="false"
                  aria-controls="flush-collapseThreew-{{ $loop->index }}">
                  <i class="{{ $category->icon }}"></i>{{ $category->name }}</a>

                @if ($hasSub)
                  <div id="flush-collapseThreew-{{ $loop->index }}" class="accordion-collapse collapse"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                      <ul>
                        @foreach ($category->subCategories as $subCategory)
                          <li><a href="{{ route('products.index', ['subcategory' => $subCategory->slug]) }}">
                              {{ $subCategory->name }}</a>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                @endif
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
      <div class="wsus__mobile_menu_main_menu">
        <div class="accordion accordion-flush" id="accordionFlushExample2">
          <ul>
            <li><a class="{{ setActive(['home']) }}" href="{{ url('/') }}">Trang chủ</a></li>
            <li><a class="{{ setActive(['vendors.index']) }}" href="{{ route('vendors.index') }}">Cửa hàng</a></li>
            <li><a class="{{ setActive(['blog']) }}" href="{{ route('blog') }}">Blog</a></li>
            <li><a class="{{ setActive(['flash-sale']) }}" href="{{ route('flash-sale') }}">Flash sale</a></li>
            <li><a class="{{ setActive(['contact.index']) }}" href="{{ route('contact.index') }}">Liên hệ</a></li>
            <li><a class="{{ setActive(['order-track.index']) }}" href="{{ route('order-track.index') }}">
                Theo dõi đơn hàng</a></li>

            @if (auth()->check())
              @if (auth()->user()->role === 'user')
                <li><a class="{{ setActive(['user.dashboard']) }}" href="{{ route('user.dashboard') }}">
                    Tài khoản của tôi</a></li>
              @elseif (auth()->user()->role === 'vendor')
                <li><a class="{{ setActive(['vendor.dashboard']) }}" href="{{ route('vendor.dashboard') }}">
                    Tài khoản của tôi</a></li>
              @else
                <li><a class="{{ setActive(['admin.dashboard']) }}" href="{{ route('admin.dashboard') }}">
                    Tài khoản của tôi</a></li>
              @endif
            @else
              <li><a class="{{ setActive(['login']) }}" href="{{ route('login') }}">Đăng nhập</a></li>
            @endif

            @if (auth()->check())
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <a style="padding: 0 10px !important;" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Đăng xuất</a>
                </form>
              </li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
