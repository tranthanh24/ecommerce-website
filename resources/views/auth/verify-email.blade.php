@extends('frontend.layouts.master')

@section('title', 'Xác minh email')

@section('content')
  <section id="wsus__login_register">
    <div class="container py-5">
      <div class="mb-4 text-muted">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
      </div>

      @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-success" role="status">
          {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
      @endif

      <div class="mt-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <div>
            <button type="submit" class="common_btn">
              {{ __('Resend Verification Email') }}
            </button>
          </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-link">
            {{ __('Log Out') }}
          </button>
        </form>
      </div>
    </div>
  </section>
@endsection
