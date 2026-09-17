@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Thư viện ảnh sản phẩm</h1>
    </div>

    <a href="{{ route('admin.products.index') }}" class="btn btn-warning mb-4">
      <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Sản phẩm: {{ $product->name }}</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.products-image-gallery.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">Hình ảnh <code>(Cho phép tải lên nhiều ảnh)</code> </label>
                  <input type="file" class="form-control" name="image[]" multiple>
                  <input type="hidden" name="product_id" value="{{ $product->id }}">
                </div>

                <button type="submit" class="btn btn-primary">Tải lên</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Tất cả hình ảnh</h4>
            </div>
            
            <div class="card-body">
              {{ $dataTable->table() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush

@push('styles')
  <style>
    table.dataTable thead th {
      text-align: center;
      vertical-align: middle;
    }
  </style>
@endpush
