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
        <div class="col-xl-3 col-lg-4">
          <div class="wsus__product_sidebar" id="sticky_sidebar">
            <div class="accordion" id="accordionExample">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">Khám phá tất cả hạng mục</button>
                </h2>

                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                  <div class="accordion-body">
                    <ul>
                      @foreach ($categories as $category)
                        <li><a href="{{ route('products.index', ['category' => $category->slug]) }}">
                            {{ $category->name }}</a></li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                    aria-expanded="false" aria-controls="collapseTwo">Giá cả</button>
                </h2>

                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo">
                  <div class="accordion-body">
                    <div class="price_ranger">
                      <form action="{{ url()->current() }}">
                        @foreach (request()->query() as $key => $value)
                          @if (!in_array($key, ['min_price', 'max_price', 'price_range', 'sort_order', 'page']))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                          @endif
                        @endforeach

                        <input type="hidden" name="page" value="1" />

                        <div class="wsus__check_single_form">
                          <input type="text" id="min_price" name="min_price" placeholder="0"
                            value="{{ request('min_price') }}">
                        </div>

                        <div class="wsus__check_single_form">
                          <input type="text" id="max_price" name="max_price" placeholder="100.000.000"
                            value="{{ request('max_price') }}">
                        </div>

                        <button type="submit" class="common_btn"><i class="fas fa-filter"></i> Lọc</button>
                      </form>

                      <form action="{{ url()->current() }}" class="mt-3">
                        @foreach (request()->query() as $key => $value)
                          @if (!in_array($key, ['min_price', 'max_price', 'price_range', 'sort_order', 'page']))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                          @endif
                        @endforeach

                        <input type="hidden" name="page" value="1" />

                        @php
                          $ranges = [
                              '0-10000000',
                              '10000000-20000000',
                              '20000000-30000000',
                              '30000000-40000000',
                              '40000000-50000000',
                              '50000000-100000000',
                          ];
                        @endphp

                        <div class="wsus__topbar_select mb-3">
                          <select name="price_range" class="select_2">
                            <option value="">Chọn mức giá</option>

                            @foreach ($ranges as $range)
                              @php
                                [$min, $max] = explode('-', $range);
                              @endphp

                              <option value="{{ $range }}"
                                {{ request('price_range') == $range ? 'selected' : '' }}>
                                {{ formatCurrency($min) }} - {{ formatCurrency($max) }}
                              </option>
                            @endforeach
                          </select>
                        </div>


                        <select name="sort_order" class="select_2">
                          <option value="">Sắp xếp</option>
                          <option value="low-high" {{ request('sort_order') == 'low-high' ? 'selected' : '' }}>
                            Giá thấp - cao</option>
                          <option value="high-low" {{ request('sort_order') == 'high-low' ? 'selected' : '' }}>
                            Giá cao - thấp</option>
                        </select>

                        <button type="submit" class="common_btn mt-3"><i class="fas fa-filter"></i> Lọc</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree3">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-controls="collapseThree"
                    data-bs-target="#collapseThree3" aria-expanded="false">Thương hiệu</button>
                </h2>

                <div id="collapseThree3" class="accordion-collapse collapse show" aria-labelledby="headingThree3">
                  <div class="accordion-body">
                    <ul>
                      @foreach ($brands as $brand)
                        <li><a href="{{ route('products.index', ['brand' => $brand->slug]) }}">
                            {{ $brand->name }}</a></li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-9 col-lg-8">
          <div class="row">
            <div class="col-xl-12 d-none d-md-block mt-md-4 mt-lg-0">
              <div class="wsus__product_topbar">
                <div class="wsus__product_topbar_left">
                  <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link list-view {{ session('list-view', 'grid') === 'grid' ? 'active' : '' }}"
                      id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button"
                      role="tab" aria-controls="v-pills-home" aria-selected="true" data-view="grid">
                      <i class="fas fa-th"></i></button>

                    <button class="nav-link list-view {{ session('list-view') === 'list' ? 'active' : '' }}"
                      id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button"
                      role="tab" aria-controls="v-pills-profile" aria-selected="false" data-view="list">
                      <i class="fas fa-list-ul"></i></button>
                  </div>
                </div>
              </div>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
              <div id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"
                class="tab-pane fade {{ session('list-view', 'grid') === 'grid' ? 'show active' : '' }}">
                <div class="row">

                  @foreach ($products as $product)
                    <div class="col-xl-4 col-sm-6">
                      <div class="wsus__product_item">
                        @if ($product->product_type)
                          <span class="wsus__new">{{ checkProductType($product->product_type) }}</span>
                        @endif

                        @if (checkDiscount($product))
                          <span class="wsus__minus">
                            -{{ calculateDiscountPercentage($product->price, $product->offer_price) }}%
                          </span>
                        @endif

                        <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
                          <img src="{{ asset($product->thumb_image) }}" alt="product"
                            class="img-fluid w-100 img_1" />

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
                    <div class="col-xl-12">
                      <div class="wsus__product_item wsus__list_view">
                        @if ($product->product_type)
                          <span class="wsus__new">{{ checkProductType($product->product_type) }}</span>
                        @endif

                        @if (checkDiscount($product))
                          <span class="wsus__minus">
                            -{{ calculateDiscountPercentage($product->price, $product->offer_price) }}%
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

                          <p class="list_description">{!! limitText($product->short_description, 300) !!}</p>

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
    <x-product-modal-card :product="$product" />
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

    // Format number realtime
    function formatNumberRealtime(input) {
      let cursorPos = input.selectionStart;

      let value = input.value.replace(/\D/g, '');

      if (value) {
        let formatted = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        input.value = formatted;

        let diff = input.value.length - value.length;
        input.setSelectionRange(cursorPos + diff, cursorPos + diff);
      } else {
        input.value = '';
      }
    }

    document.getElementById('min_price').addEventListener('input', function() {
      formatNumberRealtime(this);
    });

    document.getElementById('max_price').addEventListener('input', function() {
      formatNumberRealtime(this);
    });
  </script>
@endpush
