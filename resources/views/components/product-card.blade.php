<div class="col-xl-3 col-sm-6 col-lg-4 {{ @$key }}">
  <div class="wsus__product_item">
    @if ($product->product_type)
      <span class="wsus__new">{{ checkProductType($product->product_type) }}</span>
    @endif

    @if (checkDiscount($product))
      <span class="wsus__minus">-{{ calculateDiscountPercentage($product->price, $product->offer_price) }}%</span>
    @endif

    <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
      <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />

      <img alt="product" class="img-fluid w-100 img_2"
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
          {{ formatCurrency($product->offer_price) }} <del>{{ formatCurrency($product->price) }}</del>
        </p>
      @else
        <p class="wsus__price">{{ formatCurrency($product->price) }}</p>
      @endif

      <form action="" class="shopping-cart-form">
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        @foreach ($product->variants as $variant)
          <select class="d-none" name="variants_items[]">
            @foreach ($variant->productVariantItems as $variantItem)
              <option {{ $variantItem->is_default === 1 ? 'selected' : '' }} value="{{ $variantItem->id }}">
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
