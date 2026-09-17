@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Phiên bản sản phẩm</h1>
    </div>

    <a href="{{ route('admin.products.index') }}" class="btn btn-warning mb-4">
      <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Sản phẩm: {{ $product->name }}</h4>
              <div class="card-header-action">
                <a href="{{ route('admin.products-variant.create', ['product' => $product->id]) }}"
                  class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
              </div>
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
            const res = await fetch("{{ route('admin.products-variant.change-status') }}", {
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
