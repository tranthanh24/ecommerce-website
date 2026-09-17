@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Phiên bản sản phẩm</h1>
    </div>

    <a href="{{ route('admin.products-variant.index', ['product' => $product->id]) }}" class="btn btn-warning mb-4">
      <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Thêm phiên bản sản phẩm</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.products-variant.store') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="">Tên phiên bản</label>
                  <input type="text" class="form-control" name="name" value="">
                </div>

                <div class="form-group">
                  <input type="hidden" class="form-control" name="product_id" value="{{ $product->id }}">
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
