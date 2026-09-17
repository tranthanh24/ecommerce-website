<div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
  <div class="card border">
    <div class="card-body">
      <form action="{{ route('admin.advertisement.homepage-banner-two') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col-md-6">
            <h5>Banner 1</h5>
            <div class="form-group">
              <label for="">Xem trước</label> <br>
              @if (!empty($homepage_banner_two['banner_one']['image']))
                <img src="{{ asset($homepage_banner_two['banner_one']['image']) }}" width="200px" />
              @else
                <p>Chưa có ảnh</p>
              @endif
            </div>

            <div class="form-group">
              <label for="">Banner</label>
              <input type="file" class="form-control" name="image_1" value="">
            </div>

            <div class="form-group">
              <label for="">Url</label>
              <input type="text" class="form-control" name="url_1" value="">
            </div>
          </div>

          <div class="col-md-6">
            <h5>Banner 2</h5>
            <div class="form-group">
              <label for="">Xem trước</label> <br>
              @if (!empty($homepage_banner_two['banner_two']['image']))
                <img src="{{ asset($homepage_banner_two['banner_two']['image']) }}" width="200px" />
              @else
                <p>Chưa có ảnh</p>
              @endif
            </div>

            <div class="form-group">
              <label for="">Banner</label>
              <input type="file" class="form-control" name="image_2" value="">
            </div>

            <div class="form-group">
              <label for="">Url</label>
              <input type="text" class="form-control" name="url_2" value="">
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </form>
    </div>
  </div>
</div>
