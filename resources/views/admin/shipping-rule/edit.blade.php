@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Quy tắc vận chuyển</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="{{ route('admin.shipping-rule.index') }}" class="btn btn-warning mb-4">
            <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

          <div class="card">
            <div class="card-header">
              <h4>Cập nhật quy tắc vận chuyển</h4>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.shipping-rule.update', $shippingRule->id) }}" method="POST"">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="">Tên quy tắc</label>
                  <input type="text" class="form-control" name="name" value="{{ $shippingRule->name }}">
                </div>

                <div class="form-group">
                  <label for="inputState">Loại phí vận chuyển</label>
                  <select id="inputState" class="form-control" name="type">
                    <option {{ $shippingRule->type === 'flat_cost' ? 'selected' : '' }} value="flat_cost">
                      Phí cố định
                    </option>
                    <option {{ $shippingRule->type === 'min_cost' ? 'selected' : '' }} value="min_cost">
                      Giá trị đơn hàng tối thiểu
                    </option>
                  </select>
                </div>

                <div class="form-group min_cost">
                  <label for="">Giá trị đơn hàng tối thiểu</label>
                  <input type="text" class="form-control" name="min_cost" value="{{ $shippingRule->min_cost }}">
                </div>

                <div class="form-group">
                  <label for="">Chi phí vận chuyển</label>
                  <input type="text" class="form-control" name="cost" value="{{ $shippingRule->cost }}">
                </div>

                <div class="form-group">
                  <label for="inputState">Trạng thái</label>
                  <select id="inputState" class="form-control" name="status">
                    <option {{ $shippingRule->status === 1 ? 'selected' : '' }} value="1">Hoạt động</option>
                    <option {{ $shippingRule->status === 0 ? 'selected' : '' }} value="0">Không hoạt động</option>
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

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const type = document.querySelector('select[name="type"]');
      const minCost = document.querySelector('.min_cost');

      function toggleMinCost() {
        if (type.value === 'min_cost') {
          minCost.style.display = 'block';
        } else {
          minCost.style.display = 'none';
        }
      }

      type.addEventListener('change', toggleMinCost);

      toggleMinCost();
    });
  </script>
@endpush
