@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Cài đặt trang chủ</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-3">
                  <div class="list-group" id="list-tab" role="tablist">
                    <a href="#popular-category" data-toggle="list" role="tab" id="popular-category-list"
                      class="active list-group-item list-group-item-action">Cài đặt phần danh mục phổ biến</a>
                    <a class="list-group-item list-group-item-action" id="section-one-list" data-toggle="list"
                      href="#section-one" role="tab">Slider danh mục sản phẩm 1</a>
                    <a class="list-group-item list-group-item-action" id="section-two-list" data-toggle="list"
                      href="#section-two" role="tab">Slider danh mục sản phẩm 2</a>
                    <a class="list-group-item list-group-item-action" id="section-three-list" data-toggle="list"
                      href="#section-three" role="tab">Slider danh mục sản phẩm 3</a>
                  </div>
                </div>

                <div class="col-9">
                  <div class="tab-content" id="nav-tabContent">
                    @include('admin.homepage-setting.sections.popular-category-section')

                    @include('admin.homepage-setting.sections.product-slider-section-one')

                    @include('admin.homepage-setting.sections.product-slider-section-two')

                    @include('admin.homepage-setting.sections.product-slider-section-three')
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
