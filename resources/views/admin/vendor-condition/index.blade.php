@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Điều kiện mở cửa hàng</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Điều kiện mở cửa hàng</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.vendor-condition.update') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="">Nội dung</label>
                  <textarea class="summernote" name="content">{{ @$content->content }}</textarea>
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
