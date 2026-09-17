@php
  $sliderTwo = json_decode(@$sliderTwo->value, true);
@endphp

<div class="tab-pane fade" id="section-two" role="tabpanel" aria-labelledby="section-two-list">
  <div class="card border">
    <div class="card-body">
      <form action="{{ route('admin.product-slider-two') }}" method="POST">
        @csrf
        @method('PUT')

        <h5>Danh mục </h5>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="">Danh mục chính</label>
              <select name="category" class="form-control main-category">
                <option value="">Chọn</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" {{ $category->id == @$sliderTwo['category'] ? 'selected' : '' }}>
                    {{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              @php
                $subCategories = App\Models\SubCategory::where('category_id', @$sliderTwo['category'])->get();
              @endphp

              <label for="">Danh mục con</label>
              <select name="sub_category" class="form-control sub-category">
                <option value="">Chọn</option>
                @foreach ($subCategories as $subCategory)
                  <option value="{{ $subCategory->id }}"
                    {{ $subCategory->id == $sliderTwo['sub_category'] ? 'selected' : '' }}>
                    {{ $subCategory->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              @php
                $childCategories = App\Models\ChildCategory::where(
                    'sub_category_id',
                    @$sliderTwo['sub_category'],
                )->get();
              @endphp

              <label for="">Danh mục phụ</label>
              <select name="child_category" class="form-control child-category">
                <option value="">Chọn</option>
                @foreach ($childCategories as $childCategory)
                  <option value="{{ $childCategory->id }}"
                    {{ $childCategory->id == $sliderTwo['child_category'] ? 'selected' : '' }}>
                    {{ $childCategory->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.body.addEventListener("change", async (e) => {
        if (e.target.classList.contains("main-category")) {
          let id = e.target.value;

          try {
            const res = await fetch(`{{ route('admin.product.get-subcategories') }}?id=${id}`);

            const data = await res.json()

            document.querySelector('.sub-category').innerHTML =
              '<option value="">Chọn</option>' + data.map(item =>
                `<option value="${item.id}">${item.name}</option>`).join('')
          } catch (err) {
            console.error(err)
          }
        }
      })

      document.body.addEventListener("change", async (e) => {
        if (e.target.classList.contains("sub-category")) {
          let id = e.target.value

          try {
            const res = await fetch(`{{ route('admin.product.get-childcategories') }}?id=${id}`)

            const data = await res.json()
            document.querySelector('.child-category').innerHTML =
              '<option value="">Chọn</option>' + data.map(item =>
                `<option value="${item.id}">${item.name}</option>`).join('')
          } catch (err) {
            console.error(err)
          }
        }
      })
    })
  </script>
@endpush
