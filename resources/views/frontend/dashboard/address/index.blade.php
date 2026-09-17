@extends('frontend.dashboard.layouts.master')

@section('content')
  <section id="wsus__dashboard">
    <div class="container-fluid">
      @include('frontend.dashboard.layouts.sidebar')

      <div class="row" style="margin-top:-20px;">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <div class="dashboard_content">
            <h3><i class="far fa-map"></i> Địa chỉ</h3>
            <div class="wsus__dashboard_add">
              <div class="row">
                @foreach ($addresses as $address)
                  <div class="col-xl-6">
                    <div class="wsus__dash_add_single">
                      <h4>Địa chỉ thanh toán
                        <span>{{ $address->address_type === 'home' ? 'Nhà' : 'Công ty' }}</span>
                      </h4>
                      <ul>
                        <li><span>Tên:</span> {{ $address->name }}</li>
                        <li><span>Số điện thoại:</span> {{ $address->phone }}</li>
                        <li><span>Email:</span> {{ $address->email }}</li>
                        <li><span>Tỉnh / Thành phố:</span> {{ $address->city }}</li>
                        <li><span>Địa chỉ :</span> {{ $address->address }}</li>
                      </ul>

                      <div class="wsus__address_btn">
                        <a href="{{ route('user.address.edit', $address->id) }}" class="edit">
                          <i class="fal fa-edit"></i> Cập nhật</a>
                        <a href="{{ route('user.address.destroy', $address->id) }}" class="del delete-item"><i
                            class="fal fa-trash-alt"></i> Xóa</a>
                      </div>
                    </div>
                  </div>
                @endforeach

                <div class="col-12">
                  <a href="{{ route('user.address.create') }}" class="add_address_btn common_btn">
                    <i class="far fa-plus"></i> Thêm địa chỉ mới</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
