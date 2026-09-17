<div class="col-xl-2 col-6 col-sm-6 col-md-4 col-lg-3 category-{{ @$key }}">
  <a class="wsus__hot_deals__single" href="{{ route('product-detail', $product->slug) }}">
    <div class="wsus__hot_deals__single_img">
      <img src="{{ asset($product->thumb_image) }}" alt="{{ $product->name }}" class="img-fluid w-100">
    </div>

    <div class="wsus__hot_deals__single_text">
      <h5>{!! limitText($product->name) !!}</h5>

      <p class="wsus__rating">
        {!! rating($product->reviews, 'rating') !!}
        <span>({{ count($product->reviews) }} đánh giá)</span>
      </p>

      @if (checkDiscount($product))
        <p class="wsus__tk">
          {{ formatCurrency($product->offer_price) }} <del>{{ formatCurrency($product->price) }}</del>
        </p>
      @else
        <p class="wsus__tk">{{ formatCurrency($product->price) }}</p>
      @endif
    </div>
  </a>
</div>
