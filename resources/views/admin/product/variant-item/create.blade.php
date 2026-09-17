@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Mẫu sản phẩm cụ thể</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="{{ route('admin.products-variant-item.index', ['productId' => $product->id, 'variantId' => $variant->id]) }}"
            class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="card">
            <div class="card-header">
              <h4>Thêm mẫu sản phẩm</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.products-variant-item.store') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="">Tên phiên bản</label>
                  <input type="text" class="form-control" name="variant_name" value="{{ $variant->name }}" readonly>
                </div>

                <div class="form-group">
                  <input type="hidden" class="form-control" name="variant_id" value="{{ $variant->id }}">
                </div>

                <div class="form-group">
                  <input type="hidden" class="form-control" name="product_id" value="{{ $product->id }}">
                </div>

                <div class="form-group">
                  <label for="">Mẫu sản phẩm</label>
                  <input type="text" class="form-control" name="name" value="">
                </div>

                <div class="form-group">
                  <label for="">Giá mẫu sản phẩm <code>Nhập giá (0 = miễn phí)</code> </label>
                  <input type="text" class="form-control" name="price" value="">
                </div>

                <div class="form-group">
                  <label for="inputState">Lựa chọn mặc định</label>
                  <select id="inputState" class="form-control" name="is_default">
                    <option value="">Chọn</option>
                    <option value="1">Có</option>
                    <option value="0">Không</option>
                  </select>
                </div>

                <div class="form-group">
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
  </section>
@endsection
