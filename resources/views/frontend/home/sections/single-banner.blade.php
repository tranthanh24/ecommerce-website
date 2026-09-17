<section id="wsus__single_banner" class="wsus__single_banner_2">
  <div class="container">
    <div class="row">
      <div class="col-xl-6 col-lg-6">
        <div class="wsus__single_banner_content">
          <div class="wsus__single_banner_img">
            <a
              href="{{ $homepage_banner_two['banner_one']['url'] ?? route('product-detail', 'laptop-acer-asprie-5-a514-56p-55k5') }}">
              <img src="{{ asset(@$homepage_banner_two['banner_one']['image']) }}" alt="banner" class="img-fluid w-100">
            </a>
          </div>
        </div>
      </div>

      <div class="col-xl-6 col-lg-6">
        <div class="wsus__single_banner_content single_banner_2">
          <div class="wsus__single_banner_img">
            <a
              href="{{ $homepage_banner_two['banner_two']['url'] ?? route('product-detail', 'laptop-acer-asprie-5-a514-56p-55k5') }}">
              <img src="{{ asset(@$homepage_banner_two['banner_two']['image']) }}" alt="banner"
                class="img-fluid w-100">
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
