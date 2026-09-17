@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Hồ sơ</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Bảng điều khiển</a></div>
        <div class="breadcrumb-item">Hồ sơ</div>
      </div>
    </div>
    <div class="section-body">
      <div class="row mt-sm-4">
        <div class="col-12 col-md-12 col-lg-7">
          <div class="card">
            <form action="{{ route('admin.profile.update') }}" method="post" class="needs-validation" novalidate=""
              enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="card-header">
                <h4>Cập nhật hồ sơ</h4>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="form-group col-12">
                    <div class="mb-3">
                      <img src="{{ asset(auth()->user()->image) }}" width="100px" height="100px" alt="">
                    </div>
                    <label>Hình ảnh</label>
                    <input type="file" name="image" class="form-control">
                  </div>

                  <div class="form-group col-md-6 col-12">
                    <label>Tên</label>
                    <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}">
                  </div>

                  <div class="form-group col-md-6 col-12">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}">
                  </div>
                </div>
              </div>

              <div class="card-footer text-right">
                <button class="btn btn-primary">Lưu thay đổi</button>
              </div>
            </form>
          </div>
        </div>

        <div class="col-12 col-md-12 col-lg-7">
          <div class="card">
            <form action="{{ route('admin.profile.update.password') }}" method="post" class="needs-validation"
              novalidate="">
              @csrf
              <div class="card-header">
                <h4>Cập nhật mật khẩu</h4>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="form-group col-12">
                    <label>Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="form-control">
                  </div>

                  <div class="form-group col-12">
                    <label>Mật khẩu mới</label>
                    <input type="password" name="password" class="form-control">
                  </div>

                  <div class="form-group col-12">
                    <label>Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" class="form-control">
                  </div>
                </div>
              </div>

              <div class="card-footer text-right">
                <button class="btn btn-primary">Lưu thay đổi</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
