@extends('frontend.layouts.master')

@section('title')
  {{ $blog->title }}
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

  <section id="wsus__blog_details">
    <div class="container">
      <div class="row">
        <div class="col-xxl-9 col-xl-8 col-lg-8">
          <div class="wsus__main_blog">
            <div class="wsus__main_blog_img">
              <img src="{{ asset($blog->image) }}" alt="blog" class="img-fluid w-100">
            </div>

            <p class="wsus__main_blog_header">
              <span><i class="fas fa-user-tie"></i> {{ $blog->user->name }}</span>
              <span><i class="fal fa-calendar-alt"></i> {{ date('d-m-Y', strtotime($blog->created_at)) }}</span>
            </p>

            <div class="wsus__description_area">
              <h1>{{ $blog->title }}</h1>
              <p>{!! $blog->content !!}</p>
            </div>

            <div class="wsus__share_blog">
              <p>Chia sẻ:</p>
              <ul>
                <li><a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}">
                    <i class="fab fa-facebook-f"></i></a></li>
              </ul>
            </div>

            @if (count($recentBlogs) !== 0)
              <div class="wsus__related_post">
                <div class="row">
                  <div class="col-xl-12">
                    <h5>Blog liên quan</h5>
                  </div>
                </div>

                <div class="row blog_det_slider">
                  @foreach ($recentBlogs as $blog)
                    <div class="col-xl-3">
                      <div class="wsus__single_blog wsus__single_blog_2">
                        <a class="wsus__blog_img" href="{{ route('blog-detail', $blog->slug) }}">
                          <img src="{{ asset($blog->image) }}" alt="blog" class="img-fluid w-100">
                        </a>

                        <div class="wsus__blog_text">
                          <a class="blog_top blue" href="javascript:void(0)">{{ $blog->category->name }}</a>
                          <div class="wsus__blog_text_center">
                            <a href="{{ route('blog-detail', $blog->slug) }}">{!! limitText($blog->title, 50) !!}</a>
                            <p class="date">{{ date('d-m-Y', strtotime($blog->created_at)) }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <div class="wsus__comment_area">
              <h4>Bình luận <span>{{ count($comments) }}</span></h4>

              @foreach ($comments as $comment)
                <div class="wsus__main_comment">
                  <div class="wsus__comment_img">
                    <img src="{{ asset($comment->user->image) }}" class="img-fluid w-100">
                  </div>

                  <div class="wsus__comment_text reply">
                    <h6>{{ $comment->user->name }}</h6>
                    <span>{{ date('d-m-Y', strtotime($comment->created_at)) }}</span>
                    <p>{{ $comment->comment }}</p>
                  </div>
                </div>
              @endforeach

              @if (count($comments) === 0)
                <p>“Hãy là người mở đầu cuộc trò chuyện!”</p>
              @endif

              <div class="col-xl-12">
                @if ($comments->hasPages())
                  <div class="wsus__pagination mt-4">
                    {{ $comments->withQueryString()->links() }}
                  </div>
                @endif
              </div>
            </div>

            <div class="wsus__post_comment">
              @if (auth()->check())
                <h4>Để lại bình luận</h4>

                <form action="{{ route('user.comment') }}" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-xl-12">
                      <div class="wsus__single_com">
                        <textarea rows="5" placeholder="Bình luận của bạn" name="comment"></textarea>
                        <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                      </div>
                    </div>
                  </div>

                  <button class="common_btn" type="submit">Đăng bình luận</button>
                </form>
              @else
                <h4>Đăng nhập để bình luận</h4>
              @endif
            </div>
          </div>
        </div>

        <div class="col-xxl-3 col-xl-4 col-lg-4">
          <div class="wsus__blog_sidebar" id="sticky_sidebar">
            <div class="wsus__blog_search">
              <h4>Tìm kiếm</h4>
              <form action="{{ route('blog') }}" method="GET">
                <input type="text" placeholder="Tìm kiếm" name="search">
                <button type="submit" class="common_btn"><i class="far fa-search"></i></button>
              </form>
            </div>

            <div class="wsus__blog_category">
              <h4>Danh mục</h4>
              <ul>
                @foreach ($categories as $category)
                  <li><a href="{{ route('blog', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                @endforeach
              </ul>
            </div>

            <div class="wsus__blog_post">
              <h4>Blog phổ biến</h4>

              @foreach ($moreBlogs as $blog)
                <div class="wsus__blog_post_single">
                  <a href="{{ route('blog-detail', $blog->slug) }}" class="wsus__blog_post_img">
                    <img src="{{ asset($blog->image) }}" alt="blog" class="imgofluid w-100">
                  </a>

                  <div class="wsus__blog_post_text">
                    <a href="{{ route('blog-detail', $blog->slug) }}">{!! limitText($blog->title, 40) !!}</a>

                    <p><span>{{ date('d-m-Y', strtotime($blog->created_at)) }}</span>
                      {{ count($blog->comments) }} bình luận</p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
