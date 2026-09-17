<section id="wsus__flash_sell" class="wsus__flash_sell_2">
  <div class=" container">
    <div class="row">
      <div class="col-xl-12">
        <div class="offer_time" style="background: url({{ asset(@$homepage_banner_one['banner_one']['image']) }})">
          <div class="wsus__flash_coundown">
            <span class=" end_text">Flash sale</span>
            <div class="simply-countdown" id="simply-countdown-one"></div>
            <a class="common_btn" href="{{ route('flash-sale') }}">Xem thêm<i class="fas fa-caret-right"></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="row flash_sell_slider">
      @foreach ($flashSaleItems as $item)
        @php
          $product = $item->product;
        @endphp

        <x-product-card :product="$product" />

        @push('modal')
          <x-product-modal-card :product="$product" />
        @endpush
      @endforeach
    </div>
  </div>
</section>

@push('scripts')
  <script>
    $(document).ready(function() {
      simplyCountdown('#simply-countdown-one', {
        year: {{ date('Y', strtotime(@$flashSale->end_date)) }},
        month: {{ date('m', strtotime(@$flashSale->end_date)) }},
        day: {{ date('d', strtotime(@$flashSale->end_date)) }},
      });
    });
  </script>
@endpush
