<section id="wsus__banner">
  <div class="container">
    <div class="row">
      <div class="col-xl-12">
        <div class="wsus__banner_content">
          <div class="row banner_slider">
            @foreach ($sliders as $slider)
              <div class="col-xl-12">
                <div class="wsus__single_slider" style="background: url({{ $slider->banner }});">
                  <div class="wsus__single_slider_text">
                    <h4>{!! $slider->type !!}</h4>
                    <h2 class="h2_slider">{!! $slider->title !!}</h2>
                    <h6>Giá chỉ từ {{ formatCurrency($slider->starting_price) }}</h6>
                    <a class="common_btn_slider" href="{{ $slider->url }}">Khám phá ngay</a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
