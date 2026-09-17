@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Slider</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="{{ route('admin.slider.index') }}" class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="card">
            <div class="card-header">
              <h4>Thêm slider</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.slider.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">Hình ảnh</label>
                  <input type="file" class="form-control" name="banner">
                </div>

                <div class="form-group">
                  <label for="">Loại</label>
                  <input type="text" class="form-control" name="type" value="{{ old('type') }}">
                </div>

                <div class="form-group">
                  <label for="">Tiêu đề</label>
                  <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                </div>

                <div class="form-group">
                  <label for="">Giá từ</label>
                  <input type="text" class="form-control" name="starting_price" value="{{ old('starting_price') }}">
                </div>

                <div class="form-group">
                  <label for="">Đường dẫn</label>
                  <input type="text" class="form-control" name="url" value="{{ old('url') }}">
                </div>

                <div class="form-group">
                  <label for="">Thứ tự</label>
                  <input type="text" class="form-control" name="serial" value="{{ old('serial') }}">
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
