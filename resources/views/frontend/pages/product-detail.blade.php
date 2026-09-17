@extends('frontend.layouts.master')

@section('title')
  {{ $product->name }}
@endsection

@section('content')
  <!-- Breadcrumb -->
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>Sản phẩm</h4>
            <ul>
              <li><a href="{{ url('/') }}">Trang chủ</a></li>
              <li><a href="javascript:void(0)">Sản phẩm</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Product Detail -->
  <section id="wsus__product_details">
    <div class="container">
      <div class="wsus__details_bg">
        <div class="row">
          <div class="col-xl-4 col-md-5 col-lg-5" style="z-index: 10 !important;">
            <div id="sticky_pro_zoom">
              <div class="exzoom hidden" id="exzoom">
                <div class="exzoom_img_box">

                  @if ($product->video_link)
                    <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                      href="{{ $product->video_link }}"><i class="fas fa-play"></i></a>
                  @endif

                  <ul class='exzoom_img_ul'>
                    <li><img class="zoom ing-fluid w-100" src="{{ asset($product->thumb_image) }}"></li>

                    @foreach ($product->productImagesGallery as $image)
                      <li><img class="zoom ing-fluid w-100" src="{{ asset($image->image) }}"></li>
                    @endforeach
                  </ul>
                </div>

                <div class="exzoom_nav"></div>
                <p class="exzoom_btn">
                  <a href="javascript:void(0);" class="exzoom_prev_btn"><i class="far fa-chevron-left"></i></a>
                  <a href="javascript:void(0);" class="exzoom_next_btn"><i class="far fa-chevron-right"></i></a>
                </p>
              </div>
            </div>
          </div>

          <div class="col-xl-5 col-md-7 col-lg-7">
            <div class="wsus__pro_details_text">
              <a class="title" href="javascript:void(0);">{{ $product->name }}</a>

              @if ($product->qty > 0)
                <p class="wsus__stock_area">
                  <span class="in_stock">Còn hàng</span> ({{ $product->qty }} sản phẩm)
                </p>
              @elseif ($product->qty === 0)
                <p class="wsus__stock_area"><span class="in_stock">Hết hàng</span></p>
              @endif

              @if (checkDiscount($product))
                <h4>{{ formatCurrency($product->offer_price) }}
                  <del>{{ formatCurrency($product->price) }}</del>
                </h4>
              @else
                <h4>{{ formatCurrency($product->price) }}</h4>
              @endif

              <p class="review">
                {!! rating($reviews, 'rating') !!}
                <span>({{ count($reviews) }} đánh giá)</span>
              </p>

              <p class="description">{{ $product->short_description }}</p>

              <form action="" class="shopping-cart-form">
                <div class="wsus__selectbox">
                  <div class="row">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @foreach ($product->variants as $variant)
                      @if ($variant->status !== 0)
                        <div class="col-xl-6 col-sm-6">
                          <h5 class="mb-2">{{ $variant->name }}:</h5>
                          <select class="select_2" name="variants_items[]">
                            @foreach ($variant->productVariantItems as $variantItem)
                              @if ($variantItem->status !== 0)
                                <option {{ $variantItem->is_default === 1 ? 'selected' : '' }}
                                  value="{{ $variantItem->id }}">
                                  {{ $variantItem->name }} ({{ formatCurrency($variantItem->price) }})
                              @endif
                            @endforeach
                          </select>
                        </div>
                      @endif
                    @endforeach
                  </div>
                </div>

                <div class="wsus__quentity">
                  <h5 style="margin-right: 5px;">Số lượng:</h5>
                  <div class="select_number">
                    <input class="number_area" name="qty" value="1" min="1" max="100"
                      type="text" />
                  </div>
                </div>

                <ul class="wsus__button_area">
                  <li><button type="submit" class="add_cart">Thêm vào giỏ hàng</button></li>

                  <li>
                    <a href="#" class="add_to_wishlist" data-id="{{ $product->id }}">
                      <i class="{{ wishlistIcon($product->id) }} fa-heart"></i></a>
                    <a href="javascript:void(0)" type="button" data-bs-toggle="modal" data-bs-target="#messageModal"
                      z-index="100"><i class="far fa-comment-alt text-info"></i></a>
                  </li>
                </ul>
              </form>

              <p class="brand_model"><span>Thương hiệu: <i class="far fa-tags"></i></span>
                {{ $product->brand->name }}</p>
            </div>
          </div>

          <div class="col-xl-3 col-md-12 mt-md-5 mt-lg-0">
            <div class="wsus_pro_det_sidebar" id="sticky_sidebar">
              <ul>
                <li>
                  <span><i class="fal fa-truck"></i></span>
                  <div class="text">
                    <h4>Dễ dàng đổi trả</h4>
                  </div>
                </li>

                <li>
                  <span><i class="far fa-shield-check"></i></span>
                  <div class="text">
                    <h4>Thanh toán an toàn</h4>
                  </div>
                </li>

                <li>
                  <span><i class="fal fa-envelope-open-dollar"></i></span>
                  <div class="text">
                    <h4>Bảo hành minh bạch</h4>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-12">
          <div class="wsus__pro_det_description">
            <div class="wsus__details_bg">
              <ul class="nav nav-pills mb-3" id="pills-tab3" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-home-tab7" data-bs-toggle="pill"
                    data-bs-target="#pills-home22" type="button" role="tab" aria-controls="pills-home"
                    aria-selected="true">Thông số kỹ thuật</button>
                </li>

                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                    aria-selected="false">Thông tin cửa hàng</button>
                </li>

                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-contact-tab2" data-bs-toggle="pill"
                    data-bs-target="#pills-contact2" type="button" role="tab" aria-controls="pills-contact2"
                    aria-selected="false">Đánh giá</button>
                </li>
              </ul>

              <div class="tab-content" id="pills-tabContent4">
                <div class="tab-pane fade  show active " id="pills-home22" role="tabpanel"
                  aria-labelledby="pills-home-tab7">
                  <div class="row">
                    <div class="col-xl-12">
                      <div class="wsus__description_area">
                        {!! $product->long_description !!}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                  <div class="wsus__pro_det_vendor">
                    <div class="row">
                      <div class="col-xl-6 col-xxl-5 col-md-6">
                        <div class="wsus__vebdor_img">
                          <img src="{{ asset($product->vendor->banner) }}" class="img-fluid w-100">
                        </div>
                      </div>

                      <div class="col-xl-6 col-xxl-7 col-md-6 mt-4 mt-md-0">
                        <div class="wsus__pro_det_vendor_text">
                          <h4>{{ $product->vendor->user->name }}</h4>

                          <p class="rating">
                            <i class="fas fa-star"></i>
                            <span>({{ count($reviews) }} đánh giá)</span>
                          </p>

                          <p><span>Tên cửa hàng:</span> {{ $product->vendor->shop_name }}</p>
                          <p><span>Địa chỉ:</span> {{ $product->vendor->address }}</p>
                          <p><span>Điện thoại:</span> {{ $product->vendor->phone }}</p>
                          <p><span>Email:</span> {{ $product->vendor->email }}</p>
                          <a href="{{ route('vendors.product', $product->vendor->id) }}" class="see_btn">
                            Xem cửa hàng</a>
                        </div>
                      </div>

                      <div class="col-xl-12 mt-4">
                        <div class="wsus__vendor_details">
                          <p>{!! $product->vendor->description !!}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="pills-contact2" role="tabpanel" aria-labelledby="pills-contact-tab2">
                  <div class="wsus__pro_det_review">
                    <div class="wsus__pro_det_review_single">
                      <div class="row">
                        <div class="col-xl-8 col-lg-7">
                          <div class="wsus__comment_area">
                            <h4>Đánh giá <span>{{ count($reviews) }}</span></h4>

                            @foreach ($reviews as $review)
                              <div class="wsus__main_comment">
                                <div class="wsus__comment_img">
                                  <img src="{{ asset($review->user->image) }}" alt="user" class="img-fluid w-100">
                                </div>

                                <div class="wsus__comment_text reply">
                                  <h6>{{ $review->user->name }} <span>{{ $review->rating }}
                                      <i class="fas fa-star"></i></span></h6>

                                  <span>{{ date('d-m-Y', strtotime($review->created_at)) }}</span>

                                  <p>{{ $review->review }}</p>

                                  <ul>
                                    <li>
                                      @if (count($review->reviewGalleries) > 0)
                                        @foreach ($review->reviewGalleries as $image)
                                          <img src="{{ asset($image->image) }}" alt="product"
                                            class="img-fluid w-100">
                                        @endforeach
                                      @endif
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            @endforeach

                            <div class="col-xl-12">
                              @if ($reviews->hasPages())
                                <div class="wsus__pagination mt-4">
                                  {{ $reviews->withQueryString()->links() }}
                                </div>
                              @endif
                            </div>
                          </div>
                        </div>

                        @php
                          $isBuy = false;

                          if (auth()->check()) {
                              $orders = \App\Models\Order::where([
                                  'user_id' => auth()->id(),
                                  'order_status' => 'completed',
                              ])->get();

                              foreach ($orders as $order) {
                                  $item = $order->orderProducts()->where('product_id', $product->id)->exists();

                                  if ($item) {
                                      $isBuy = true;
                                      break;
                                  }
                              }
                          }
                        @endphp

                        @if ($isBuy)
                          <div class="col-xl-4 col-lg-5 mt-4 mt-lg-0">
                            <div class="wsus__post_comment rev_mar" id="sticky_sidebar3">
                              <h4>Viết đánh giá</h4>
                              <form action="{{ route('user.review.create') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <p class="rating">
                                  <span>Chọn số sao đánh giá: </span>

                                  <i class="far fa-star" data-value="1"></i>
                                  <i class="far fa-star" data-value="2"></i>
                                  <i class="far fa-star" data-value="3"></i>
                                  <i class="far fa-star" data-value="4"></i>
                                  <i class="far fa-star" data-value="5"></i>

                                  <input type="hidden" name="rating" id="rating-value" value="1">
                                </p>

                                <div class="col-xl-12">
                                  <div class="col-xl-12">
                                    <div class="wsus__single_com">
                                      <textarea cols="3" rows="3" name="review" placeholder="Viết đánh giá của bạn" autofocus>
                                    </textarea>
                                    </div>
                                  </div>
                                </div>

                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="vendor_id" value="{{ $product->vendor_id }}">

                                <div class="img_upload">
                                  <div class="gallery">
                                    <a class="cam" href="javascript:void(0)">
                                      <span><i class="fas fa-image"></i></span>
                                    </a>
                                  </div>
                                </div>

                                <button class="common_btn" type="submit">Gửi đánh giá</button>
                              </form>
                            </div>
                          </div>
                        @endif
                      </div>
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

  <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Gửi tin nhắn</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form action="" class="message_modal" method="POST">
            @csrf
            <div class="form-group">
              <label for=""></label>
              <textarea class="form-control" name="message" id="" rows="5" placeholder="Nhập tin nhắn..."></textarea>
              <input type="hidden" name="receiver_id" value="{{ $product->vendor->user_id }}">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-primary">Gửi</button>
            </div>
          </form>

          <div class="alert alert-success success-modal" style="display: none">
            Nhấp <a href="{{ route('user.messenger.index') }}" class="text-primary"> vào đây</a>
            để tiếp tục trò chuyện
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Related Product -->
  <section id="wsus__flash_sell">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
          <div class="wsus__section_header">
            <h3>Sản phẩm liên quan</h3>
            <a class="see_btn" href="{{ route('products.index', ['category' => $product->category->name]) }}">
              Xem thêm <i class="fas fa-caret-right"></i></a>
          </div>
        </div>
      </div>

      <div class="row flash_sell_slider">
        @foreach ($relatedProducts as $product)
          <x-product-card :product="$product" />
        @endforeach
      </div>

      @foreach ($relatedProducts as $product)
        <x-product-modal-card :product="$product" />
      @endforeach
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    // Review start
    const stars = document.querySelectorAll('.rating i');
    const ratingValue = document.getElementById('rating-value');

    stars.forEach(star => {
      star.addEventListener('click', function(e) {
        const value = +e.target.dataset.value;
        ratingValue.value = value;

        stars.forEach(s => {
          s.classList.remove('fas');
          s.classList.add('far');
        });

        for (let i = 1; i <= value; i++) {
          stars[i].classList.remove('far');
          stars[i].classList.add('fas');
        }
      });
    });

    // Send message
    document.querySelector('.message_modal').addEventListener('submit', async function(e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);

      const res = await fetch("{{ route('user.send-message') }}", {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: formData
      })

      const data = await res.json();
      if (data.status === 'success') {
        toastr.success(data.message);
        form.reset();

        document.querySelector('.success-modal').style.display = 'block';

        const modal = bootstrap.Modal.getInstance(document.getElementById('messageModal'))
        setTimeout(() => {
          modal.hide();
        }, 15000);
      } else {
        toastr.error(data.message);
      }
    });
  </script>
@endpush
