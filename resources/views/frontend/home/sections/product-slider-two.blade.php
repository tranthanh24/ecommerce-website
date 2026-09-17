@php
  $productSectionTwo = json_decode(@$sliderTwo->value, true);

  $category = null;
  $products = collect();
  $query = null;

  if (!empty($productSectionTwo['child_category'])) {
      $category = \App\Models\ChildCategory::find($productSectionTwo['child_category']);
      $query = \App\Models\Product::where('child_category_id', $productSectionTwo['child_category']);
  } elseif (!empty($productSectionTwo['sub_category'])) {
      $category = \App\Models\SubCategory::find($productSectionTwo['sub_category']);
      $query = \App\Models\Product::where('sub_category_id', $productSectionTwo['sub_category']);
  } elseif (!empty($productSectionTwo['category'])) {
      $category = \App\Models\Category::find($productSectionTwo['category']);
      $query = \App\Models\Product::where('category_id', $productSectionTwo['category']);
  }

  if ($query) {
      $products = $query
          ->with(['variants', 'productImagesGallery', 'reviews'])
          ->inRandomOrder()
          ->take(10)
          ->get();
  }
@endphp


<section id="wsus__electronic">
  <div class="container">
    <div class="row">
      <div class="col-xl-12">
        <div class="wsus__section_header">
          <h3>{{ @$category->name }}</h3>

          @php
            $route = [];

            if (!empty($productSectionTwo['child_category'])) {
                $route['childcategory'] = $category->slug;
            } elseif (!empty($productSectionTwo['sub_category'])) {
                $route['subcategory'] = $category->slug;
            } elseif (!empty($productSectionTwo['category'])) {
                $route['category'] = $category->slug;
            }
          @endphp

          <a class="see_btn" href="{{ route('products.index', $route) }}">
            Xem thêm <i class="fas fa-caret-right"></i></a>
        </div>
      </div>
    </div>

    <div class="row flash_sell_slider">
      @foreach ($products as $product)
        <x-product-card :product="$product" />

        @push('modal')
          <x-product-modal-card :product="$product" />
        @endpush
      @endforeach
    </div>
  </div>
</section>
