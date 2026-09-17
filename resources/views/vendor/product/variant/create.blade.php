@extends('vendor.layouts.master')

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('vendor.layouts.sidebar')

      <div class="row" style="margin-top:-20px;">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <a href="{{ route('vendor.products-variant.index', ['product' => $product->id]) }}"
            class="btn btn-warning mb-4"><i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="dashboard_content mt-2 mt-md-0">
            <h3><i class="far fa-copy"></i>Thêm phiên bản sản phẩm</h3>
            <div class="wsus__dashboard_profile">
              <div class="wsus__dash_pro_area">
                <form action="{{ route('vendor.products-variant.store') }}" method="POST">
                  @csrf
                  <div class="form-group wsus__input">
                    <label for="">Tên phiên bản</label>
                    <input type="text" class="form-control" name="name" value="">
                  </div>

                  <div class="form-group wsus__input">
                    <input type="hidden" class="form-control" name="product_id" value="{{ $product->id }}">
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
