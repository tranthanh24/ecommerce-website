@extends('frontend.layouts.master')

@section('title')
  Danh sách mong muốn
@endsection

@section('content')
  <!-- Breadcrumb -->
  <section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h4>Danh sách mong muốn</h4>
            <ul>
              <li><a href="{{ url('/') }}">Trang chủ</a></li>
              <li><a href="javascript:void(0)">Danh sách mong muốn</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Cart View -->
  <section id="wsus__cart_view">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="wsus__cart_list wishlist">
            <div class="table-responsive">
              <table>
                <tbody>
                  <tr class="d-flex">
                    <th class="wsus__pro_img">Sản phẩm</th>
                    <th class="wsus__pro_name" style="width:500px">Chi tiết sản phẩm</th>
                    <th class="wsus__pro_tk">Số lượng</th>
                    <th class="wsus__pro_tk" style="width:230px">Đơn giá</th>
                    <th class="wsus__pro_icon">Thao tác</th>
                  </tr>

                  @foreach ($wishlists as $item)
                    <tr class="d-flex">
                      <td class="wsus__pro_img">
                        <img src="{{ asset($item->product->thumb_image) }}" alt="product" class="img-fluid w-100">
                        <a data-url="{{ route('user.wishlist.destroy', $item->id) }}" class="remove_wishlist">
                          <i class="far fa-times"></i></a>
                      </td>

                      <td class="wsus__pro_name" style="width:500px">
                        <p>{{ $item->product->name }}</p>
                      </td>

                      <td class="wsus__pro_tk">
                        <p>{{ $item->product->qty }}</p>
                      </td>

                      <td class="wsus__pro_tk" style="width:230px">
                        <h6>{{ formatCurrency($item->product->price) }}</h6>
                      </td>

                      <td class="wsus__pro_icon">
                        <a href="{{ route('product-detail', $item->product->slug) }}"
                          class="common_btn wishlist_view_btn">Xem sản phẩm</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    document.addEventListener('click', async function(e) {
      const btn = e.target.closest('.remove_wishlist');

      if (!btn) return;

      const url = btn.dataset.url;

      if (!url) return;

      e.preventDefault();

      try {
        const res = await fetch(url, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
        });
        const data = await res.json();

        if (data.status === 'success') {
          toastr.success(data.message);
          btn.closest('tr')?.remove();
          document.getElementById('wishlist_count').innerText = data.count;
        } else if (data.status === 'error') {
          toastr.error(data.message);
        }
      } catch (err) {
        toastr.error("Đã có lỗi xảy ra:", err)
      }
    })
  </script>
@endpush
