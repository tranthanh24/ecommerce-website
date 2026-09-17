<div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
  <div class="card border">
    <div class="card-body">
      <form action="{{ route('admin.email-settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="">Email</label>
          <input type="text" class="form-control" name="email" value="{{ @$emailSetting->email }}">
        </div>

        <div class="form-group">
          <label for="">Mail Host</label>
          <input type="text" class="form-control" name="host" value="{{ @$emailSetting->host }}">
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Smtp Username</label>
              <input type="text" class="form-control" name="username" value="{{ @$emailSetting->username }}">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="">Smtp Password</label>
              <input type="text" class="form-control" name="password" value="{{ @$emailSetting->password }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Mail Port</label>
              <input type="text" class="form-control" name="port" value="{{ @$emailSetting->port }}">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="">Mail Encryption</label>
              <select class="form-control" name="encryption" value="">
                <option {{ @$emailSetting->encryption === 'tls' ? 'selected' : '' }} value="tls">TLS</option>
                <option {{ @$emailSetting->encryption === 'ssl' ? 'selected' : '' }} value="ssl">SSL</option>
              </select>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </form>
    </div>
  </div>
</div>
