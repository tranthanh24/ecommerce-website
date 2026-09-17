@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Sản phẩm Flash sale</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Ngày kết thúc Flash sale</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.flash-sale.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Ngày kết thúc khuyến mãi</label>
                      <input type="text" class="form-control datepicker" name="end_date"
                        value="{{ optional($flashSale)->end_date }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Thêm sản phẩm Flash sale</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.flash-sale.add-product') }}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Thêm sản phẩm</label>
                      <select name="product_id" id="" class="form-control select2">
                        <option value="">Chọn sản phẩm</option>
                        @foreach ($products as $product)
                          <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="">Gắn lên trang chủ</label>
                      <select name="show_at_home" id="" class="form-control select2">
                        <option value="">Chọn </option>
                        <option value="1">Có</option>
                        <option value="0">Không</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="">Trạng thái</label>
                      <select name="status" id="" class="form-control select2">
                        <option value="">Chọn</option>
                        <option value="1">Hoạt động</option>
                        <option value="0">Không hoạt động</option>
                      </select>
                    </div>
                  </div>
                </div>

                <button class="btn btn-primary">Cập nhật</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Danh sách sản phẩm Flash sale</h4>
            </div>

            <div class="card-body">
              {{ $dataTable->table() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.body.addEventListener("click", async (e) => {
        if (e.target.classList.contains("change-status")) {
          const id = e.target.dataset.id;
          const isChecked = e.target.checked;

          try {
            const res = await fetch(
              "{{ route('admin.flash-sale.change-status') }}", {
                method: 'PUT',
                headers: {
                  'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]').getAttribute('content'),
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                  id,
                  isChecked,
                })
              });

            const data = await res.json();

            if (data.status === "success") {
              toastr.success('Trạng thái đã được cập nhật thành công');
            } else {
              toast.error('Trạng thái cập nhật thất bại');
            }
          } catch (err) {
            console.error(err);
          }
        }
      });

      document.body.addEventListener("click", async (e) => {
        if (e.target.classList.contains("change-show-at-home")) {
          const id = e.target.dataset.id;
          const isChecked = e.target.checked;

          try {
            const res = await fetch(
              "{{ route('admin.flash-sale.change-show-at-home') }}", {
                method: 'PUT',
                headers: {
                  'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]').getAttribute('content'),
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                  id,
                  isChecked,
                })
              });

            const data = await res.json();

            if (data.status === "success") {
              toastr.success('Trạng thái hiển thị đã được cập nhật thành công');
            } else {
              toast.error('Trạng thái hiển thị cập nhật thất bại');
            }
          } catch (err) {
            console.error(err);
          }
        }
      });
    });
  </script>
@endpush

@push('styles')
  <style>
    table.dataTable thead th {
      text-align: center;
      vertical-align: middle;
    }
  </style>
@endpush
