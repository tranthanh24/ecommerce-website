@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Sản phẩm</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="{{ route('admin.products.index') }}" class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="card">
            <div class="card-header">
              <h4>Cập nhật sản phẩm</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="">Xem trước</label> <br>
                  <img src={{ asset($product->thumb_image) }} width="200px" />
                </div>

                <div class="form-group">
                  <label for="">Hình ảnh</label>
                  <input type="file" class="form-control" name="image">
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="inputState">Danh mục</label>
                      <select id="inputState" class="form-control main-category" name="category">
                        <option value="">Chọn</option>
                        @foreach ($categories as $category)
                          <option {{ $category->id === $product->category_id ? 'selected' : '' }}
                            value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="inputState">Danh mục con</label>
                      <select id="inputState" class="form-control sub-category" name="sub_category">
                        <option value="">Chọn</option>
                        @foreach ($subCategories as $subCategory)
                          <option {{ $subCategory->id === $product->sub_category_id ? 'selected' : '' }}
                            value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="inputState">Danh mục phụ</label>
                      <select id="inputState" class="form-control child-category" name="child_category">
                        <option value="">Chọn</option>
                        @foreach ($childCategories as $childCategory)
                          <option {{ $childCategory->id === $product->child_category_id ? 'selected' : '' }}
                            value="{{ $childCategory->id }}">{{ $childCategory->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputState">Thương hiệu</label>
                  <select id="inputState" class="form-control" name="brand">
                    <option value="">Chọn</option>
                    @foreach ($brands as $brand)
                      <option {{ $brand->id === $product->brand_id ? 'selected' : '' }} value="{{ $brand->id }}">
                        {{ $brand->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Tên sản phẩm</label>
                  <input type="text" class="form-control" name="name" value="{{ $product->name }}">
                </div>

                <div class="form-group">
                  <label for="">Mã sản phẩm</label>
                  <input type="text" class="form-control" name="sku" value="{{ $product->sku }}">
                </div>

                <div class="form-group">
                  <label for="">Giá sản phẩm</label>
                  <input type="text" class="form-control" name="price" value="{{ $product->price }}">
                </div>

                <div class="form-group">
                  <label for="">Giá ưu đãi</label>
                  <input type="text" class="form-control" name="offer_price" value="{{ $product->offer_price }}">
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Ngày bắt đầu khuyến mãi</label>
                      <input type="text" class="form-control datepicker" name="offer_start_date"
                        value="{{ $product->offer_start_date }}">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Ngày kết thúc khuyến mãi</label>
                      <input type="text" class="form-control datepicker" name="offer_end_date"
                        value="{{ $product->offer_end_date }}">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">Số lượng sản phẩm còn trong kho</label>
                  <input type="number" min="0" class="form-control" name="qty" value="{{ $product->qty }}">
                </div>

                <div class="form-group">
                  <label for="">Video sản phẩm</label>
                  <input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}">
                </div>

                <div class="form-group">
                  <label for="">Mô tả ngắn</label>
                  <textarea class="form-control" name="short_description" value="{{ $product->short_description }}">{{ $product->short_description }}</textarea>
                </div>

                <div class="form-group">
                  <label for="">Mô tả chi tiết</label>
                  <textarea class="form-control summernote" name="long_description">{{ $product->long_description }}</textarea>
                </div>

                <div class="form-group">
                  <label for="inputState">Loại sản phẩm</label>
                  <select id="inputState" class="form-control" name="product_type">
                    <option value="">Chọn</option>
                    <option {{ $product->product_type === 'new_arrival' ? 'selected' : '' }} value="new_arrival">
                      Mới
                    </option>
                    <option {{ $product->product_type === 'top_product' ? 'selected' : '' }} value="top_product">
                      Nổi bật
                    </option>
                    <option {{ $product->product_type === 'featured_product' ? 'selected' : '' }}
                      value="featured_product">
                      Đề cử
                    </option>
                    <option {{ $product->product_type === 'best_product' ? 'selected' : '' }} value="best_product">
                      Bán chạy
                    </option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Tiêu đề SEO</label>
                  <input type="text" class="form-control" name="seo_title" value="{{ $product->seo_title }}">
                </div>

                <div class="form-group">
                  <label for="">Mô tả SEO</label>
                  <textarea class="form-control" name="seo_description" value="{{ $product->seo_description }}">{{ $product->seo_description }}</textarea>
                </div>

                <div class="form-group">
                  <label for="inputState">Trạng thái</label>
                  <select id="inputState" class="form-control" name="status">
                    <option {{ $product->status === 1 ? 'selected' : '' }} value="1">Hoạt động</option>
                    <option {{ $product->status === 0 ? 'selected' : '' }} value="0">Không hoạt động</option>
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
          document.querySelector('.child-category').innerHTML =
            '<option value="">Chọn</option>'
          let id = e.target.value

          try {
            const res = await fetch(
              `{{ route('admin.product.get-subcategories') }}?id=${id}`)
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
            const res = await fetch(
              `{{ route('admin.product.get-childcategories') }}?id=${id}`)
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
