@extends('frontend.layouts.master')

@section('title')
  Blog
@endsection

@section('content')
  <!-- Breadcrumb -->
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>Blog</h4>
            <ul>
              <li><a href="{{ url('/') }}">Trang chủ</a></li>
              <li><a href="javascript:void(0)">Blog</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="wsus__blogs">
    <div class="container">
      <div class="row">
        @if (request()->has('search'))
          <h5>Tìm kiếm: "{{ request()->search }}"</h5>
          <hr>
        @elseif (request()->has('category'))
          <h5>Tìm kiếm: "{{ request()->category }}"</h5>
          <hr>
        @endif

        @if (count($blogs) === 0)
          <div class="row m-0">
            <div class="col-12 p-0">
              <div class="card w-100 text-center">
                <div class="card-body">
                  <h3>Rất tiếc, không có blog nào phù hợp!</h3>
                </div>
              </div>
            </div>
          </div>
        @endif

        @foreach ($blogs as $blog)
          <div class="col-xl-4 col-sm-6 col-lg-4 col-xxl-3">
            <div class="wsus__single_blog wsus__single_blog_2">
              <a class="wsus__blog_img" href="{{ route('blog-detail', $blog->slug) }}">
                <img src="{{ asset($blog->image) }}" alt="blog" class="img-fluid w-100">
              </a>

              <div class="wsus__blog_text">
                <a class="blog_top blue" href="javascript:void(0)">{{ $blog->category->name }}</a>
                <div class="wsus__blog_text_center">
                  <a href="{{ route('blog-detail', $blog->slug) }}">{!! limitText($blog->title, 52) !!}</a>
                  <p class="date">{{ date('d-m-Y', strtotime($blog->created_at)) }}</p>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="col-xl-12">
        @if ($blogs->hasPages())
          <div class="wsus__pagination mt-4">
            {{ $blogs->withQueryString()->links() }}
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection
