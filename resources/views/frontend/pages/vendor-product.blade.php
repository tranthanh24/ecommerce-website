@extends('frontend.layouts.master')

@section('title')
  Tất cả sản phẩm
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

  <!-- Product page -->
  <section id="wsus__product_page">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
          <div class="wsus__pro_page_bammer vendor_det_banner">
            <img src="{{ asset($vendor->banner) }}" alt="banner" class="img-fluid w-100" style="height: 250px">
            <div class="wsus__pro_page_bammer_text wsus__vendor_det_banner_text">
              <div class="wsus__vendor_text_center">
                <h4>{{ $vendor->shop_name }}</h4>
                <a href="javascript:void(0)"><i class="far fa-phone-alt"></i>{{ $vendor->phone }}</a>
                <a href="javascript:void(0)"><i class="far fa-envelope"></i>{{ $vendor->email }}</a>
                <p class="wsus__vendor_location"><i class="fal fa-map-marker-alt"></i>{{ $vendor->address }}</p>
                <p class="wsus__open_store"><i class="fab fa-shopify"></i>Đang mở</p>
                <ul class="d-flex">
                  <li><a class="facebook" href="{{ $vendor->fb_link }}"><i class="fab fa-facebook-f"></i></a></li>
                  <li><a class="instagram" href="{{ $vendor->ins_link }}"><i class="fab fa-instagram"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-12 col-lg-8">
          <div class="row">
            <div class="col-xl-12 d-none d-md-block mt-md-4 mt-lg-0">
              <div class="wsus__product_topbar">
                <div class="wsus__product_topbar_left">
                  <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link list-view {{ session('list-view', 'grid') === 'grid' ? 'active' : '' }}"
                      id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button"
                      role="tab" aria-controls="v-pills-home" aria-selected="true" data-view="grid">
                      <i class="fas fa-th"></i>
                    </button>

                    <button class="nav-link list-view {{ session('list-view') === 'list' ? 'active' : '' }}"
                      id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button"
                      role="tab" aria-controls="v-pills-profile" aria-selected="false" data-view="list">
                      <i class="fas fa-list-ul"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
              <div id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"
                class="tab-pane fade {{ session('list-view', 'grid') === 'grid' ? 'show active' : '' }}">
                <div class="row">

                  @foreach ($products as $product)
                    <div class="col-xl-3 col-sm-6">
                      <div class="wsus__product_item">
                        @if ($product->product_type)
                          <span class="wsus__new">{{ checkProductType($product->product_type) }}</span>
                        @endif

                        @if (checkDiscount($product))
                          <span
                            class="wsus__minus">-{{ calculateDiscountPercentage($product->price, $product->offer_price) }}%
                          </span>
                        @endif

                        <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
                          <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />

                          <img class="img-fluid w-100 img_2"
                            src="{{ asset($product->productImagesGallery->count() > 0 ? $product->productImagesGallery[0]->image : $product->thumb_image) }}" />
                        </a>

                        <ul class="wsus__single_pro_icon">
                          <li><a href="#" data-bs-toggle="modal" data-bs-target="#product-{{ $product->id }}">
                              <i class="far fa-eye"></i></a></li>
                          <li><a href="#" class="add_to_wishlist" data-id="{{ $product->id }}">
                              <i class="{{ wishlistIcon($product->id) }} fa-heart"></i></a></li>
                        </ul>

                        <div class="wsus__product_details">
                          <a class="wsus__category" href="#">{{ $product->category->name }}</a>

                          <p class="wsus__pro_rating">
                            {!! rating($product->reviews, 'rating') !!}
                            <span>({{ count($product->reviews) }} đánh giá)</span>
                          </p>

                          <a class="wsus__pro_name" href="{{ route('product-detail', $product->slug) }}">
                            {!! limitText($product->name, 50) !!}</a>

                          @if (checkDiscount($product))
                            <p class="wsus__price">
                              {{ formatCurrency($product->offer_price) }}
                              <del>{{ formatCurrency($product->price) }}</del>
                            </p>
                          @else
                            <p class="wsus__price">{{ formatCurrency($product->price) }}</p>
                          @endif

                          <form action="" class="shopping-cart-form">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            @foreach ($product->variants as $variant)
                              <select class="d-none" name="variants_items[]">
                                @foreach ($variant->productVariantItems as $variantItem)
                                  <option {{ $variantItem->is_default === 1 ? 'selected' : '' }}
                                    value="{{ $variantItem->id }}">
                                    {{ $variantItem->name }} ({{ formatCurrency($variantItem->price) }})
                                  </option>
                                @endforeach
                              </select>
                            @endforeach

                            <input name="qty" type="hidden" value="1" />

                            <button class="add_cart" type="submit">Thêm vào giỏ hàng</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <div id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab"
                class="tab-pane fade {{ session('list-view') === 'list' ? 'show active' : '' }}">
                <div class="row">
                  @foreach ($products as $product)
                    <div class="col-xl-10 d-flex justify-content-center align-items-center m-auto">
                      <div class="wsus__product_item wsus__list_view">
                        @if ($product->product_type)
                          <span class="wsus__new">{{ checkProductType($product->product_type) }}</span>
                        @endif

                        @if (checkDiscount($product))
                          <span
                            class="wsus__minus">-{{ calculateDiscountPercentage($product->price, $product->offer_price) }}%
                          </span>
                        @endif

                        <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
                          <img src="{{ asset($product->thumb_image) }}" alt="product"
                            class="img-fluid w-100 img_1" />

                          <img class="img-fluid w-100 img_2"
                            src="{{ asset($product->productImagesGallery->count() > 0 ? $product->productImagesGallery[0]->image : $product->thumb_image) }}" />
                        </a>

                        <div class="wsus__product_details">
                          <a class="wsus__category" href="#">{{ $product->category->name }}</a>

                          <p class="wsus__pro_rating">
                            {!! rating($product->reviews, 'rating') !!}
                            <span>({{ count($product->reviews) }} đánh giá)</span>
                          </p>

                          <a class="wsus__pro_name" href="{{ route('product-detail', $product->slug) }}">
                            {{ $product->name }}</a>

                          @if (checkDiscount($product))
                            <p class="wsus__price">
                              {{ formatCurrency($product->offer_price) }}
                              <del>{{ formatCurrency($product->price) }}</del>
                            </p>
                          @else
                            <p class="wsus__price">{{ formatCurrency($product->price) }}</p>
                          @endif

                          <p class="list_description">{{ $product->short_description }}</p>

                          <ul class="wsus__single_pro_icon">
                            <li>
                              <form action="" class="shopping-cart-form">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                @foreach ($product->variants as $variant)
                                  <select class="d-none" name="variants_items[]">
                                    @foreach ($variant->productVariantItems as $variantItem)
                                      <option {{ $variantItem->is_default === 1 ? 'selected' : '' }}
                                        value="{{ $variantItem->id }}">
                                        {{ $variantItem->name }} ({{ formatCurrency($variantItem->price) }})
                                      </option>
                                    @endforeach
                                  </select>
                                @endforeach

                                <input name="qty" type="hidden" value="1" />

                                <button class="add_cart" style="border-radius:3px;margin-right:10px"
                                  type="submit">Thêm vào giỏ hàng</button>
                              </form>
                            </li>
                            <li><a href="#" class="add_to_wishlist" data-id="{{ $product->id }}">
                                <i class="{{ wishlistIcon($product->id) }} fa-heart"></i></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          @if (count($products) === 0)
            <div class="text-center mt-5">
              <h2>Sản phẩm đang được cập nhật, vui lòng quay lại sau!!!</h2>
            </div>
          @endif
        </div>

        <div class="col-xl-12">
          @if ($products->hasPages())
            <div class="wsus__pagination mt-4">
              {{ $products->links() }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  @foreach ($products as $product)
    <section class="product_popup_modal">
      <div class="modal fade" id="product-{{ $product->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <i class="far fa-times"></i></button>

              <div class="row">
                <div class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                  <div class="wsus__quick_view_img">

                    @if ($product->video_link)
                      <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                        href="{{ $product->video_link }}"><i class="fas fa-play"></i></a>
                    @endif

                    <div class="row modal_slider">
                      <div class="col-xl-12">
                        <div class="modal_slider_img">
                          <img src="{{ asset($product->thumb_image) }}" class="img-fluid w-100">
                        </div>
                      </div>

                      @foreach ($product->productImagesGallery as $image)
                        <div class="col-xl-12">
                          <div class="modal_slider_img">
                            <img src="{{ asset($image->image) }}" class="img-fluid w-100">
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>

                <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                  <div class="wsus__pro_details_text">
                    <a class="title" href="javascript:void(0)">{{ $product->name }}</a>

                    <p class="wsus__stock_area">
                      <span class="in_stock">in stock</span> (167 item)
                    </p>

                    @if (checkDiscount($product))
                      <h4>{{ formatCurrency($product->offer_price) }}
                        <del>{{ formatCurrency($product->price) }}</del>
                      </h4>
                    @else
                      <h4>{{ formatCurrency($product->price) }}</h4>
                    @endif

                    <p class="review">
                      {!! rating($product->reviews, 'rating') !!}
                      <span>({{ count($product->reviews) }} đánh giá)</span>
                    </p>

                    <p class="description">{{ $product->short_description }}</p>

                    <div class="product-card">
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
                                          {{ $variantItem->name }}
                                          ({{ formatCurrency($variantItem->price) }})
                                        </option>
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
                            <input class="number_area" name="qty" type="text" min="1" max="100"
                              value="1" />
                          </div>
                        </div>

                        <ul class="wsus__button_area">
                          <li><button type="submit" class="add_cart">Thêm vào giỏ hàng</button></li>
                          <li><a href="#" class="add_to_wishlist" data-id="{{ $product->id }}">
                              <i class="{{ wishlistIcon($product->id) }} fa-heart"></i></a></li>
                        </ul>
                      </form>
                    </div>

                    <p class="brand_model"><span>Thương hiệu:</span> {{ $product->brand->name }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endforeach
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      document.querySelectorAll('.list-view').forEach(btn => {
        btn.addEventListener('click', async function(e) {
          const view = e.currentTarget.dataset.view;

          try {
            const res = await fetch('{{ route('products-list-view') }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
              },
              credentials: 'same-origin',
              body: JSON.stringify({
                view
              })
            });
          } catch (err) {
            console.error(err);
          }
        });
      });
    });
  </script>
@endpush
