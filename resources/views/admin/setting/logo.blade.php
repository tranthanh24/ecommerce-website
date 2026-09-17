<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
  <form action="{{ route('admin.logo.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="">Xem trước logo</label> <br>
      <img src="{{ asset(@$logo->logo) }}" width="200px" alt="">
    </div>

    <div class="form-group">
      <label for="">Logo</label>
      <input type="file" class="form-control" name="logo" value="">
    </div>

    <div class="form-group">
      <label for="">Xem trước Favicon</label> <br>
      <img src="{{ asset(@$logo->favicon) }}" width="200px" alt="">
    </div>

    <div class="form-group">
      <label for="">Favicon</label>
      <input type="file" class="form-control" name="favicon" value="">
    </div>

    <div class="form-group">
      <label for="">Footer</label>
      <input type="text" class="form-control" value="{{ @$logo->footer }}" name="footer">
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
  </form>
</div>
