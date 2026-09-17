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
            <h3><i class="far fa-image"></i> Sản phẩm: {{ $product->name }}</h3>

            <div class="wsus__dashboard_profile">
              <div class="wsus__dash_pro_area">
                <form action="{{ route('vendor.products-image-gallery.store') }}" method="POST"
                  enctype="multipart/form-data">
                  @csrf
                  <div class="form-group wsus__input">
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

      <div class="row mt-5">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <div class="dashboard_content mt-2 mt-md-0">
            <h3><i class="fas fa-images"></i> Thư viện ảnh sản phẩm</h3>

            <div class="wsus__dashboard_profile">
              <div class="wsus__dash_pro_area">
                {{ $dataTable->table() }}
              </div>
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
