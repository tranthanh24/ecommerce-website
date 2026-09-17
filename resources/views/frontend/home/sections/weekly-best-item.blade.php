@php
  $productSectionThree = json_decode(@$sliderThree->value, true);
@endphp

<section id="wsus__weekly_best" class="home2_wsus__weekly_best_2 ">
  <div class="container">
    <div class="row">
      @if (!empty($productSectionThree))
        @foreach ($productSectionThree as $productGroup)
          @php
            $category = null;
            $items = collect();
            $query = null;

            foreach ($productGroup as $key => $value) {
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

                    if ($query) {
                        $items = $query->with('reviews')->inRandomOrder()->take(6)->get();
                    }
                }
            }
          @endphp

          <div class="col-xl-6 col-sm-6">
            <div class="wsus__section_header">
              <h3>{{ $category->name }}</h3>
            </div>

            <div class="row weekly_best2">
              @foreach ($items as $item)
                <x-mini-product-card :product="$item" />
              @endforeach
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</section>
