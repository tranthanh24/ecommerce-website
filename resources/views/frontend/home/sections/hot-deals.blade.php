<section id="wsus__hot_deals" class="wsus__hot_deals_2">
  <div class="container">
    <div class="wsus__hot_large_item">
      <div class="row">
        <div class="col-xl-12">
          <div class="wsus__section_header justify-content-start">
            <div class="monthly_top_filter2 mb-1">
              <button class="active auto_click" data-filter=".new_arrival">Mới</button>
              <button data-filter=".top_product">Nổi bật</button>
              <button data-filter=".featured_product">Đề cử</button>
              <button data-filter=".best_product">Bán chạy</button>
            </div>
          </div>
        </div>
      </div>

      <div class="row grid2">
        @foreach ($typeProduct as $key => $products)
          @foreach ($products as $product)
            <x-product-card :product="$product" :key="$key" />

            @push('modal')
              <x-product-modal-card :product="$product" />
            @endpush
          @endforeach
        @endforeach
      </div>
    </div>

    <section id="wsus__single_banner" class="home_2_single_banner">
      <div class="container">
        <div class="row">
          <div class="col-xl-6 col-lg-6">
            <div class="wsus__single_banner_content banner_1">
              <div class="wsus__single_banner_img">
                <a
                  href="{{ $homepage_banner_three['banner_one']['url'] ?? route('product-detail', 'samsung-galaxy-z-fold7-12gb-256gb') }}">
                  <img src="{{ asset(@$homepage_banner_three['banner_one']['image']) }}" alt="banner"
                    class="img-fluid w-100">
                </a>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="row">
              <div class="col-12">
                <div class="wsus__single_banner_content single_banner_2">
                  <div class="wsus__single_banner_img">
                    <a
                      href="{{ $homepage_banner_three['banner_two']['url'] ?? route('product-detail', 'samsung-galaxy-z-fold7-12gb-256gb') }}">
                      <img src="{{ asset(@$homepage_banner_three['banner_two']['image']) }}" alt="banner"
                        class="img-fluid w-100">
                    </a>
                  </div>
                </div>
              </div>

              <div class="col-12 mt-lg-4">
                <div class="wsus__single_banner_content">
                  <div class="wsus__single_banner_img">
                    <a
                      href="{{ $homepage_banner_three['banner_three']['url'] ??
                          route('product-detail', 'samsung-galaxy-z-flip7-12gb-256gb') }}">
                      <img src="{{ asset(@$homepage_banner_three['banner_three']['image']) }}" alt="banner"
                        class="img-fluid w-100">
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</section>
