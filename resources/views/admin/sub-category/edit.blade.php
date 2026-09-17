@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Danh mục con</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="{{ route('admin.sub-category.index') }}" class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="card">
            <div class="card-header">
              <h4>Cập nhật danh mục con</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.sub-category.update', $subCategory->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="inputState">Danh mục</label>
                  <select id="inputState" class="form-control" name="category">
                    <option value="">Chọn</option>
                    @foreach ($categories as $category)
                      <option {{ $category->id === $subCategory->category_id ? 'selected' : '' }}
                        value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Tên danh mục</label>
                  <input type="text" class="form-control" name="name" value="{{ $subCategory->name }}">
                </div>

                <div class="form-group">
                  <label for="inputState">Trạng thái</label>
                  <select id="inputState" class="form-control" name="status">
                    <option value="1" {{ $subCategory->status === 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ $subCategory->status === 0 ? 'selected' : '' }}>Không hoạt động</option>
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
