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
                    <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list"
                      href="#list-home" role="tab">Cài đặt chung</a>
                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list"
                      href="#list-profile" role="tab">Cài đặt Email</a>
                    <a class="list-group-item list-group-item-action" id="list-messenger-list" data-toggle="list"
                      href="#list-messenger" role="tab">Cài đặt hộp thư</a>
                    <a class="list-group-item list-group-item-action" id="list-chatbot-list" data-toggle="list"
                      href="#list-chatbot" role="tab">Cài đặt Chatbot</a>
                    <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list"
                      href="#list-messages" role="tab">Logo và Footer</a>
                  </div>
                </div>

                <div class="col-9">
                  <div class="tab-content" id="nav-tabContent">

                    @include('admin.setting.general-setting')

                    @include('admin.setting.email-configuration')

                    @include('admin.setting.pusher-setting')

                    @include('admin.setting.chatbot-setting')

                    @include('admin.setting.logo')
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
