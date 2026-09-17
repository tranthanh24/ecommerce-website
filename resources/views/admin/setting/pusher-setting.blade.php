<div class="tab-pane fade" id="list-messenger" role="tabpanel" aria-labelledby="list-messenger-list">
  <div class="card border">
    <div class="card-body">
      <form action="{{ route('admin.pusher-settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="">App Id</label>
          <input type="text" class="form-control" name="app_id" value="{{ @$pusherSetting->app_id }}">
        </div>

        <div class="form-group">
          <label for="">Key</label>
          <input type="text" class="form-control" name="key" value="{{ @$pusherSetting->key }}">
        </div>

        <div class="form-group">
          <label for="">Secret</label>
          <input type="text" class="form-control" name="secret" value="{{ @$pusherSetting->secret }}">
        </div>

        <div class="form-group">
          <label for="">Cluster</label>
          <input type="text" class="form-control" name="cluster" value="{{ @$pusherSetting->cluster }}">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </form>
    </div>
  </div>
</div>
