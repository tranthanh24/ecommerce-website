@php
  $popularCategory = json_decode(@$popularCategory->value, true);
@endphp

<div class="tab-pane fade show active" id="popular-category" role="tabpanel" aria-labelledby="popular-category-list">
  <div class="card border">
    <div class="card-body">
      <form action="{{ route('admin.popular-category-section') }}" method="POST">
        @csrf
        @method('PUT')

        @for ($i = 0; $i <= 3; $i++)
          <h5>Danh mục {{ $i + 1 }}</h5>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Danh mục chính</label>
                <select name="category[{{ $i }}]" class="form-control main-category">
                  <option value="">Chọn</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                      {{ isset($popularCategory[$i]) && $category->id == $popularCategory[$i]['category'] ? 'selected' : '' }}>
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                @php
                  $subCategories = isset($popularCategory[$i])
                      ? App\Models\SubCategory::where('category_id', $popularCategory[$i]['category'])->get()
                      : collect();
                @endphp

                <label for="">Danh mục con</label>
                <select name="sub_category[{{ $i }}]" class="form-control sub-category">
                  <option value="">Chọn</option>
                  @foreach ($subCategories as $subCategory)
                    <option value="{{ $subCategory->id }}"
                      {{ isset($popularCategory[$i]) && $subCategory->id == $popularCategory[$i]['sub_category'] ? 'selected' : '' }}>
                      {{ $subCategory->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                @php
                  $childCategories = isset($popularCategory[$i])
                      ? App\Models\ChildCategory::where('sub_category_id', $popularCategory[$i]['sub_category'])->get()
                      : collect();
                @endphp

                <label for="">Danh mục phụ</label>
                <select name="child_category[{{ $i }}]" class="form-control child-category">
                  <option value="">Chọn</option>
                  @foreach ($childCategories as $childCategory)
                    <option value="{{ $childCategory->id }}"
                      {{ isset($popularCategory[$i]) && $childCategory->id == $popularCategory[$i]['child_category'] ? 'selected' : '' }}>
                      {{ $childCategory->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        @endfor

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
