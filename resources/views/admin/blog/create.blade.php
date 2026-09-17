@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Blogs</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="{{ route('admin.blog.index') }}" class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="card">
            <div class="card-header">
              <h4>Thêm blog</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">Hình ảnh</label>
                  <input type="file" class="form-control" name="image">
                </div>

                <div class="form-group">
                  <label for="">Tiêu đề</label>
                  <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                </div>

                <div class="form-group">
                  <label for="inputState">Danh mục</label>
                  <select id="inputState" class="form-control" name="category">
                    <option value="">Chọn</option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Nội dung</label>
                  <textarea class="form-control summernote" name="content">{{ old('content') }}</textarea>
                </div>

                <div class="form-group">
                  <label for="">Tiêu đề SEO</label>
                  <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title"') }}">
                </div>

                <div class="form-group">
                  <label for="">Mô tả SEO</label>
                  <textarea class="form-control" name="seo_description" value="{{ old('seo_description"') }}"></textarea>
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
