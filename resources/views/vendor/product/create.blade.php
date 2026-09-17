@extends('vendor.layouts.master')

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('vendor.layouts.sidebar')

      <div class="row" style="margin-top:-20px;">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <a href="{{ route('vendor.products.index') }}" class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="dashboard_content mt-2 mt-md-0">
            <h3><i class="fas fa-shopping-bag"></i>Thêm sản phẩm</h3>
            <div class="wsus__dashboard_profile">
              <div class="wsus__dash_pro_area">
                <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group wsus__input">
                    <label for="">Hình ảnh</label>
                    <input type="file" class="form-control" name="image">
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group wsus__input">
                        <label for="inputState">Danh mục</label>
                        <select id="inputState" class="form-control main-category" name="category">
                          <option value="">Chọn</option>
                          @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group wsus__input">
                        <label for="inputState">Danh mục con</label>
                        <select id="inputState" class="form-control sub-category" name="sub_category">
                          <option value="">Chọn</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group wsus__input">
                        <label for="inputState">Danh mục phụ</label>
                        <select id="inputState" class="form-control child-category" name="child_category">
                          <option value="">Chọn</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group wsus__input">
                    <label for="inputState">Thương hiệu</label>
                    <select id="inputState" class="form-control" name="brand">
                      <option value="">Chọn</option>
                      @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Tên sản phẩm</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Mã sản phẩm</label>
                    <input type="text" class="form-control" name="sku" value="{{ old('sku') }}">
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Giá sản phẩm</label>
                    <input type="text" class="form-control" name="price" value="{{ old('price') }}">
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Giá ưu đãi</label>
                    <input type="text" class="form-control" name="offer_price" value="{{ old('offer_price') }}">
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group wsus__input">
                        <label for="">Ngày bắt đầu khuyến mãi</label>
                        <input type="text" class="form-control datepicker" name="offer_start_date"
                          value="{{ old('offer_start_date') }}">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group wsus__input">
                        <label for="">Ngày kết thúc khuyến mãi</label>
                        <input type="text" class="form-control datepicker" name="offer_end_date"
                          value="{{ old('offer_end_date') }}">
                      </div>
                    </div>
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Số lượng sản phẩm còn trong kho</label>
                    <input type="number" min="0" class="form-control" name="qty" value="{{ old('qty') }}">
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Video sản phẩm</label>
                    <input type="text" class="form-control" name="video_link" value="{{ old('video_link') }}">
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Mô tả ngắn</label>
                    <textarea class="form-control" name="short_description">{{ old('short_description') }}</textarea>
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Mô tả chi tiết</label>
                    <textarea class="form-control summernote" name="long_description">{{ old('long_description') }}</textarea>
                  </div>

                  <div class="form-group wsus__input">
                    <label for="inputState">Loại sản phẩm</label>
                    <select id="inputState" class="form-control" name="product_type">
                      <option value="">Chọn</option>
                      <option value="new_arrival">Mới</option>
                      <option value="top_product">Nổi bật</option>
                      <option value="featured_product">Đề cử</option>
                      <option value="best_product">Bán chạy</option>
                    </select>
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Tiêu đề SEO</label>
                    <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title"') }}">
                  </div>

                  <div class="form-group wsus__input">
                    <label for="">Mô tả SEO</label>
                    <textarea class="form-control" name="seo_description" value="{{ old('seo_description"') }}"></textarea>
                  </div>

                  <div class="form-group wsus__input">
                    <label for="inputState">Trạng thái</label>
                    <select id="inputState" class="form-control" name="status">
                      <option value="1">Hoạt động</option>
                      <option value="0">Không hoạt động</option>
                    </select>
                  </div>

                  <button type="submit" class="btn btn-primary">Tạo mới</button>
                </form>
              </div>
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
            const res = await fetch(
              `{{ route('vendor.product.get-subcategories') }}?id=${id}`)
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
              `{{ route('vendor.product.get-childcategories') }}?id=${id}`)
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
