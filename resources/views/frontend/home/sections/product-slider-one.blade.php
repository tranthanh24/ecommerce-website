@php
  $productSectionOne = json_decode(@$sliderOne->value, true);

  $category = null;
  $products = collect();
  $query = null;

  if (!empty($productSectionOne['child_category'])) {
      $category = \App\Models\ChildCategory::find($productSectionOne['child_category']);
      $query = \App\Models\Product::where('child_category_id', $productSectionOne['child_category']);
  } elseif (!empty($productSectionOne['sub_category'])) {
      $category = \App\Models\SubCategory::find($productSectionOne['sub_category']);
      $query = \App\Models\Product::where('sub_category_id', $productSectionOne['sub_category']);
  } elseif (!empty($productSectionOne['category'])) {
      $category = \App\Models\Category::find($productSectionOne['category']);
      $query = \App\Models\Product::where('category_id', $productSectionOne['category']);
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

            if (!empty($productSectionOne['child_category'])) {
                $route['childcategory'] = $category->slug;
            } elseif (!empty($productSectionOne['sub_category'])) {
                $route['subcategory'] = $category->slug;
            } elseif (!empty($productSectionOne['category'])) {
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
