@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Phiên bản sản phẩm</h1>
    </div>

    <a href="{{ route('admin.products-variant.index', ['product' => $variant->product->id]) }}"
      class="btn btn-warning mb-4">
      <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Cập nhật phiên bản sản phẩm</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.products-variant.update', $variant->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="">Tên phiên bản</label>
                  <input type="text" class="form-control" name="name" value="{{ $variant->name }}" required>
                </div>

                <div class="form-group">
                  <label for="inputState">Trạng thái</label>
                  <select id="inputState" class="form-control" name="status">
                    <option {{ $variant->status === 1 ? 'selected' : '' }} value="1">Hoạt động</option>
                    <option {{ $variant->status === 0 ? 'selected' : '' }} value="0">Không hoạt động</option>
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
