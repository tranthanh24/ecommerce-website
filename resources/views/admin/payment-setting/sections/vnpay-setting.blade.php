<div class="tab-pane fade" id="list-vnpay" role="tabpanel" aria-labelledby="list-vnpay-list">
  <div class="card border">
    <div class="card-body">
      <form action="{{ route('admin.vnpay-setting.update', 1) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="">Trạng thái</label>
          <select name="status" class="form-control">
            <option {{ @$vnpay->status === 1 ? 'selected' : '' }} value="1">Kích hoạt</option>
            <option {{ @$vnpay->status === 0 ? 'selected' : '' }} value="0">Vô hiệu hóa</option>
          </select>
        </div>

        <div class="form-group">
          <label for="">VNPay Terminal Code (TmnCode)</label>
          <input type="text" class="form-control" name="tmn_code" value="{{ @$vnpay->tmn_code }}">
        </div>

        <div class="form-group">
          <label for="">VNPay Hash Secret</label>
          <input type="text" class="form-control" name="hash_secret" value="{{ @$vnpay->hash_secret }}">
        </div>

        <div class="form-group">
          <label for="">VNPay Return URL</label>
          <input type="text" class="form-control" name="return_url" value="{{ @$vnpay->return_url }}">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </form>
    </div>
  </div>
</div>
