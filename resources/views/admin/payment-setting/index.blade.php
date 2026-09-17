@extends('admin.layouts.master')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Cài đặt</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-3">
                  <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" data-toggle="list" href="#list-paypal"
                      role="tab" id="list-paypal-list">PayPal</a>

                    <a class="list-group-item list-group-item-action" id="list-vnpay-list" data-toggle="list"
                      href="#list-vnpay" role="tab">VNPay</a>
                  </div>
                </div>

                <div class="col-9">
                  <div class="tab-content" id="nav-tabContent">

                    @include('admin.payment-setting.sections.paypal-setting')

                    @include('admin.payment-setting.sections.vnpay-setting')
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
