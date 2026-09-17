@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Mã giảm giá</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="{{ route('admin.coupons.index') }}" class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="card">
            <div class="card-header">
              <h4>Thêm mã giảm giá</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="">Tên mã giảm giá</label>
                  <input type="text" class="form-control" name="name" value="">
                </div>

                <div class="form-group">
                  <label for="">Mã giảm giá</label>
                  <input type="text" class="form-control" name="code" value="">
                </div>

                <div class="form-group">
                  <label for="">Số lượng</label>
                  <input type="text" class="form-control" name="quantity" value="">
                </div>

                <div class="form-group">
                  <label for="">Số lần sử dụng tối đa / người</label>
                  <input type="text" class="form-control" name="max_use" value="">
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Ngày bắt đầu</label>
                      <input type="text" class="form-control datepicker" name="start_date" value="">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Ngày kết thúc</label>
                      <input type="text" class="form-control datepicker" name="end_date" value="">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="inputState">Loại giảm giá</label>
                      <select id="inputState" class="form-control" name="discount_type">
                        <option value="percent">Phần trăm (%)</option>
                        <option value="amount">Số tiền ({{ $setting->currency_icon }})</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="">Giá trị giảm giá</label>
                      <input type="text" class="form-control" name="discount_value" value="">
                    </div>
                  </div>
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
