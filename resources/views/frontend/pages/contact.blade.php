@extends('frontend.layouts.master')

@section('title')
  Liên hệ
@endsection

@section('content')
  <!-- Breadcrumb -->
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>Liên hệ</h4>
            <ul>
              <li><a href="{{ url('/') }}">Trang chủ</a></li>
              <li><a href="javascript:void(0)">Liên hệ</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="wsus__contact">
    <div class="container">
      <div class="wsus__contact_area">
        <div class="row">
          <div class="col-xl-4">
            <div class="row">
              <div class="col-xl-12">
                <div class="wsus__contact_single">
                  <i class="fal fa-envelope"></i>
                  <h5>Email</h5>
                  <a href="mailto:{{ $setting->contact_email }}">{{ $setting->contact_email }}</a>
                  <span><i class="fal fa-envelope"></i></span>
                </div>
              </div>

              <div class="col-xl-12">
                <div class="wsus__contact_single">
                  <i class="far fa-phone-alt"></i>
                  <h5>Số điện thoại</h5>
                  <a href="tel:+{{ $setting->contact_phone }}">{{ $setting->contact_phone }}</a>
                  <span><i class="far fa-phone-alt"></i></span>
                </div>
              </div>

              <div class="col-xl-12">
                <div class="wsus__contact_single">
                  <i class="fal fa-map-marker-alt"></i>
                  <h5>Địa chỉ</h5>
                  <a href="">{{ $setting->contact_address }}</a>
                  <span><i class="fal fa-map-marker-alt"></i></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-8">
            <div class="wsus__contact_question">
              <h5>Gửi tin nhắn cho chúng tôi</h5>
              <form id="contact-form">
                <div class="row">
                  <div class="col-xl-12">
                    <div class="wsus__con_form_single">
                      <input type="text" placeholder="Tên của bạn" name="name">
                    </div>
                  </div>

                  <div class="col-xl-12">
                    <div class="wsus__con_form_single">
                      <input type="email" placeholder="Email" name="email">
                    </div>
                  </div>

                  <div class="col-xl-12">
                    <div class="wsus__con_form_single">
                      <input type="text" placeholder="Tiêu đề" name="subject">
                    </div>
                  </div>

                  <div class="col-xl-12">
                    <div class="wsus__con_form_single">
                      <textarea cols="3" rows="5" placeholder="Nội dung" name="message"></textarea>
                    </div>

                    <button type="submit" class="common_btn contact_btn">Gửi ngay</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="col-xl-12">
            <div class="wsus__con_map">
              <iframe src="{{ $setting->map }}" width="100%" height="450" style="border:0;" allowfullscreen
                loading="lazy"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    document.getElementById('contact-form').addEventListener('submit', async function(e) {
      e.preventDefault();

      const btn = document.querySelector('.contact_btn');

      const formData = new FormData(this);
      const formBody = new URLSearchParams(formData).toString();

      btn.innerText = 'Đang gửi...';
      btn.disabled = true;

      try {
        const res = await fetch('{{ route('contact.submit-form') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          },
          body: formBody
        });
        const data = await res.json();

        if (data.status === 'success') {
          toastr.success(data.message);
          this.reset();
        } else {
          toastr.error(data.message);
        }
      } catch (err) {
        toastr.error("Đã có lỗi xảy ra:" + err.message)
      } finally {
        btn.innerText = 'Gửi ngay'
        btn.disabled = false;
      }
    })
  </script>
@endpush
