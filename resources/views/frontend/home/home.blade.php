@extends('frontend.layouts.master')

@section('title')
  Trang chủ
@endsection

@section('content')
  <!-- Banner Slider -->
  @include('frontend.home.sections.banner-slider')

  <!-- Flash Sale -->
  @include('frontend.home.sections.flash-sale')

  <!-- Montly Top Product -->
  @include('frontend.home.sections.top-product')

  <!-- Brand Slider -->
  @include('frontend.home.sections.brand-silder')

  <!-- Single Banner -->
  @include('frontend.home.sections.single-banner')

  <!-- Hot Deals -->
  @include('frontend.home.sections.hot-deals')

  <!-- Product -->
  @include('frontend.home.sections.product-slider-one')

  <!-- Product -->
  @include('frontend.home.sections.product-slider-two')

  <!-- Large Banner -->
  @include('frontend.home.sections.large-banner')

  <!-- Weekly Best Item -->
  @include('frontend.home.sections.weekly-best-item')

  <!-- Services -->
  @include('frontend.home.sections.services')

  <!-- Blogs -->
  @include('frontend.home.sections.blogs')
@endsection
