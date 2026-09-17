@php
  $sliderThree = json_decode(@$sliderThree->value, true);
@endphp

<div class="tab-pane fade" id="section-three" role="tabpanel" aria-labelledby="section-three-list">
  <div class="card border">
    <div class="card-body">
      <form action="{{ route('admin.product-slider-three') }}" method="POST">
        @csrf
        @method('PUT')

        <h5>Phần 1</h5>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="">Danh mục chính</label>
              <select name="category_one" class="form-control main-category">
                <option value="">Chọn</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}"
                    {{ $category->id == @$sliderThree[0]['category'] ? 'selected' : '' }}>
                    {{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              @php
                $subCategories = App\Models\SubCategory::where('category_id', @$sliderThree[0]['category'])->get();
              @endphp

              <label for="">Danh mục con</label>
              <select name="sub_category_one" class="form-control sub-category">
                <option value="">Chọn</option>
                @foreach ($subCategories as $subCategory)
                  <option value="{{ $subCategory->id }}"
                    {{ $subCategory->id == @$sliderThree[0]['sub_category'] ? 'selected' : '' }}>
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
                    @$sliderThree[0]['sub_category'],
                )->get();
              @endphp

              <label for="">Danh mục phụ</label>
              <select name="child_category_one" class="form-control child-category">
                <option value="">Chọn</option>
                @foreach ($childCategories as $childCategory)
                  <option value="{{ $childCategory->id }}"
                    {{ $childCategory->id == $sliderThree[0]['child_category'] ? 'selected' : '' }}>
                    {{ $childCategory->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <h5>Phần 2</h5>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="">Danh mục chính</label>
              <select name="category_two" class="form-control main-category">
                <option value="">Chọn</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}"
                    {{ $category->id == @$sliderThree[1]['category'] ? 'selected' : '' }}>
                    {{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              @php
                $subCategories = App\Models\SubCategory::where('category_id', @$sliderThree[1]['category'])->get();
              @endphp

              <label for="">Danh mục con</label>
              <select name="sub_category_two" class="form-control sub-category">
                <option value="">Chọn</option>
                @foreach ($subCategories as $subCategory)
                  <option value="{{ $subCategory->id }}"
                    {{ $subCategory->id == $sliderThree[1]['sub_category'] ? 'selected' : '' }}>
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
                    @$sliderThree[1]['sub_category'],
                )->get();
              @endphp

              <label for="">Danh mục phụ</label>
              <select name="child_category_two" class="form-control child-category">
                <option value="">Chọn</option>
                @foreach ($childCategories as $childCategory)
                  <option value="{{ $childCategory->id }}"
                    {{ $childCategory->id == @$sliderThree[1]['child_category'] ? 'selected' : '' }}>
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
          const id = e.target.value;
          const container = e.target.closest(".row");

          try {
            const res = await fetch(`{{ route('admin.product.get-subcategories') }}?id=${id}`)
            const data = await res.json()

            const subSelect = container.querySelector(".sub-category");
            subSelect.innerHTML = '<option value="">Chọn</option>';

            container.querySelector(".child-category").innerHTML = '<option value="">Chọn</option>';

            data.forEach(item => {
              const option = document.createElement('option');
              option.value = item.id;
              option.textContent = item.name;
              subSelect.appendChild(option);
            });
          } catch (err) {
            console.error(err)
          }
        }

        if (e.target.classList.contains("sub-category")) {
          const id = e.target.value;
          const container = e.target.closest(".row");

          try {
            const res = await fetch(`{{ route('admin.product.get-childcategories') }}?id=${id}`);
            const data = await res.json();

            const childSelect = container.querySelector(".child-category");

            childSelect.innerHTML = '<option value="">Chọn</option>';

            data.forEach(item => {
              const option = document.createElement('option');
              option.value = item.id;
              option.textContent = item.name;
              childSelect.appendChild(option);
            });
          } catch (err) {
            console.error(err);
          }
        }
      })
    })
  </script>
@endpush
