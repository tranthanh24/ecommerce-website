@extends('frontend.layouts.master')

@section('title', 'Xác nhận mật khẩu')

@section('content')
  <section id="wsus__login_register">
    <div class="container py-5">
      <div class="mb-4 text-muted">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
      </div>

      <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
          <label for="password">{{ __('Password') }}</label>

          <input id="password" class="form-control mt-1" type="password" name="password" required
            autocomplete="current-password" />

          @error('password')
            <div class="text-danger mt-2" role="alert">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex justify-content-end mt-4">
          <button type="submit" class="common_btn mt-3">
            {{ __('Confirm') }}
          </button>
        </div>
      </form>
    </div>
  </section>
@endsection
