@php
  $popularCategories = json_decode(@$popularCategory->value, true);

  $products = [];
  $categories = [];
@endphp

<section id="wsus__monthly_top" class="wsus__monthly_top_2">
  <div class="container">

    <div class="row mb-3">
      <div class="col-xl-12 col-lg-12">
        <a href="{{ $homepage_banner_one['banner_two']['url'] ?? route('products.index', ['category' => 'laptop']) }}">
          <img src="{{ asset(@$homepage_banner_one['banner_two']['image']) }}" alt="banner" class="img-fluid w-100">
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-12">
        <div class="wsus__section_header for_md">
          <h3>Danh mục nổi bật trong tháng</h3>
          <div class="monthly_top_filter">
            @if (!empty($popularCategories))
              @foreach ($popularCategories as $popularCategory)
                @php
                  $category = null;
                  $items = collect();
                  $query = null;

                  foreach ($popularCategory as $key => $value) {
                      if (!empty($value)) {
                          if ($key === 'category') {
                              $category = \App\Models\Category::find($value);
                              $query = \App\Models\Product::where('category_id', $value);
                          } elseif ($key === 'sub_category') {
                              $category = \App\Models\SubCategory::find($value);
                              $query = \App\Models\Product::where('sub_category_id', $value);
                          } elseif ($key === 'child_category') {
                              $category = \App\Models\ChildCategory::find($value);
                              $query = \App\Models\Product::where('child_category_id', $value);
                          }
                      }

                      if ($query) {
                          $items = $query->with('reviews')->inRandomOrder()->take(12)->get();
                      }
                  }

                  $categories[] = $category;
                  $products[] = $items;
                @endphp
              @endforeach
            @endif

            @foreach ($categories as $index => $category)
              <button class="{{ $index === 0 ? 'auto_click active' : '' }}" data-filter=".category-{{ $index }}">
                {{ $category->name }}
              </button>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-12 col-lg-12">
        <div class="row grid">

          @foreach ($products as $index => $productGroup)
            @foreach ($productGroup as $item)
              <x-mini-product-card :product="$item" :key="$index" />
            @endforeach
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
