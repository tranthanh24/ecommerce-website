@extends('frontend.dashboard.layouts.master')

@section('title')
  Yêu cầu
@endsection

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('frontend.dashboard.layouts.sidebar')

      <div class="row" style="margin-top:-20px;">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <div class="dashboard_content mt-2 mt-md-0">
            <h3><i class="far fa-store"></i> Mở cửa hàng ngay hôm nay</h3>

            <div class="wsus__dashboard_profile">
              <div class="wsus__dash_pro_area">
                <h4>Điều kiện mở cửa hàng</h4>

                <div class="vendor-condition-content">
                  {!! @$content->content !!}
                </div>
              </div>
            </div> <br>

            <div class="wsus__dashboard_profile">
              <div class="wsus__dash_pro_area">
                <form action="{{ route('user.vendor-request.create') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="wsus__dash_pro_single">
                    <i class="far fa-store" aria-hidden="true"></i>
                    <input type="file" name="shop_image" value="">
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="wsus__dash_pro_single">
                        <i class="far fa-store" aria-hidden="true"></i>
                        <input type="text" name="shop_name" value="" placeholder="Tên cửa hàng">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="wsus__dash_pro_single">
                        <i class="far fa-store" aria-hidden="true"></i>
                        <input type="text" name="shop_phone" value="" placeholder="Số điện thoại">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="wsus__dash_pro_single">
                        <i class="far fa-store" aria-hidden="true"></i>
                        <input type="text" name="shop_email" value="" placeholder="Email">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="wsus__dash_pro_single">
                        <i class="far fa-store" aria-hidden="true"></i>
                        <input type="text" name="shop_address" value="" placeholder="Địa chỉ">
                      </div>
                    </div>
                  </div>

                  <div class="wsus__dash_pro_single">
                    <textarea name="shop_about" value="" placeholder="Thông tin cửa hàng..."></textarea>
                  </div>

                  <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
