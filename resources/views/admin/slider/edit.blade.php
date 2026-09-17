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
              <h4>Cập nhật slider</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="">Xem trước</label> <br>
                  <img src={{ asset($slider->banner) }} width="200px" />
                </div>

                <div class="form-group">
                  <label for="">Hình ảnh</label>
                  <input type="file" class="form-control" name="banner">
                </div>

                <div class="form-group">
                  <label for="">Loại</label>
                  <input type="text" class="form-control" name="type" value="{{ $slider->type }}">
                </div>

                <div class="form-group">
                  <label for="">Tiêu đề</label>
                  <input type="text" class="form-control" name="title" value="{{ $slider->title }}">
                </div>

                <div class="form-group">
                  <label for="">Giá từ</label>
                  <input type="text" class="form-control" name="starting_price" value="{{ $slider->starting_price }}">
                </div>

                <div class="form-group">
                  <label for="">Đường dẫn</label>
                  <input type="text" class="form-control" name="url" value="{{ $slider->url }}">
                </div>

                <div class="form-group">
                  <label for="">Thứ tự</label>
                  <input type="text" class="form-control" name="serial" value="{{ $slider->serial }}">
                </div>

                <div class="form-group">
                  <label for="inputState">Trạng thái</label>
                  <select id="inputState" class="form-control" name="status">
                    <option {{ $slider->status === 1 ? 'selected' : '' }} value="1">Hoạt động</option>
                    <option {{ $slider->status === 0 ? 'selected' : '' }} value="0">Không hoạt động</option>
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
