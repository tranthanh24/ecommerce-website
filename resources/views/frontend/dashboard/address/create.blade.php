@extends('frontend.dashboard.layouts.master')

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('frontend.dashboard.layouts.sidebar')

      <div class="row" style="margin-top:-20px;">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <div class="dashboard_content mt-2 mt-md-0">
            <a href="{{ route('user.address.index') }}" class="btn btn-warning mb-4">
              <i class="fas fa-long-arrow-alt-left"></i> Quay về</a>

            <h3><i class="far fa-map"></i>Thêm địa chỉ</h3>

            <div class="wsus__dashboard_add wsus__add_address">
              <form action="{{ route('user.address.store') }}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Tên <b>*</b></label>
                      <input type="text" name="name" placeholder="Tên">
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Email</label>
                      <input type="email" name="email" placeholder="Email">
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Số điện thoại <b>*</b></label>
                      <input type="text" name="phone" placeholder="Số điện thoại">
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Tỉnh / Thành phố <b>*</b></label>
                      <div class="wsus__topbar_select">
                        <select class="select_2" name="city">
                          <option value="">Chọn</option>
                          @foreach (config('settings.address') as $address)
                            <option value="{{ $address }}">{{ $address }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Địa chỉ cụ thể <b>*</b></label>
                      <input type="text" name="address" placeholder="Địa chỉ">
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Loại địa chỉ</label>
                      <div class="wsus__topbar_select">
                        <select class="select_2" name="address_type">
                          <option value="">Chọn</option>
                          <option value="home">Nhà</option>
                          <option value="office">Công ty</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-6">
                    <button type="submit" class="common_btn">Tạo mới</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
