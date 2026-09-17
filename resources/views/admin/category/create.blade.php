@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Danh mục</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="{{ route('admin.category.index') }}" class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="card">
            <div class="card-header">
              <h4>Thêm danh mục</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.category.store') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="">Biểu tượng</label> <br />
                  <button class="btn btn-primary" data-selected-class="btn-danger" data-unselected-class="btn-info"
                    role="iconpicker" name="icon"></button>
                </div>

                <div class="form-group">
                  <label for="">Tên danh mục</label>
                  <input type="text" class="form-control" name="name" value="">
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
