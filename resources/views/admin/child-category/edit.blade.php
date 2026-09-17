@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Danh mục phụ</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="{{ route('admin.child-category.index') }}" class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="card">
            <div class="card-header">
              <h4>Cập nhật danh mục phụ</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.child-category.update', $childCategory->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="inputState">Danh mục</label>
                  <select id="inputState" class="form-control main-category" name="category">
                    <option value="">Chọn</option>
                    @foreach ($categories as $category)
                      <option {{ $category->id === $childCategory->category_id ? 'selected' : '' }}
                        value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label for="inputState">Danh mục con</label>
                  <select id="inputState" class="form-control sub-category" name="sub_category">
                    <option value="">Chọn</option>
                    @foreach ($subCategories as $subCategory)
                      <option {{ $subCategory->id === $childCategory->sub_category_id ? 'selected' : '' }}
                        value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Tên danh mục</label>
                  <input type="text" class="form-control" name="name" value="{{ $childCategory->name }}">
                </div>

                <div class="form-group">
                  <label for="inputState">Trạng thái</label>
                  <select id="inputState" class="form-control" name="status">
                    <option value="1" {{ $childCategory->status === 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ $childCategory->status === 0 ? 'selected' : '' }}>Không hoạt động</option>
                  </select>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.body.addEventListener("change", async (e) => {
        if (e.target.classList.contains("main-category")) {
          let id = e.target.value

          try {
            const res = await fetch(`{{ route('admin.get-subcategories') }}?id=${id}`)
            const data = await res.json()
            document.querySelector('.sub-category').innerHTML =
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
