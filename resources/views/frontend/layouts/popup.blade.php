<section id="wsus__pop_up" style="display: none;">
  <div class="wsus__pop_up_center">
    <div class="wsus__pop_up_text">
      <button type="button" onclick="closePopup()" class="close-popup" aria-label="Đóng khuyến mãi">
        <i class="fas fa-times"></i>
      </button>

      <span class="promo-eyebrow">🎉 Ưu đãi đặc biệt</span>
      <h3>Nhập mã để nhận ưu đãi siêu hấp dẫn ngay hôm nay</h3>
      <p class="promo-subtitle">Áp dụng cho đơn hàng hợp lệ đặt trong ngày.</p>

      <div class="discount-code">{{ @$coupon->code }}</div>
    </div>
  </div>
</section>

<button type="button" onclick="showPopup()" class="show-popup">
  Ưu đãi hôm nay
</button>

<script>
  const popup = document.getElementById('wsus__pop_up');

  function showPopup() {
    popup.style.display = 'block';
  }

  function closePopup() {
    popup.style.display = 'none';
    localStorage.setItem('popupClosed', 'true');
  }

  window.onclick = function(e) {
    if (e.target == popup) {
      closePopup();
    }
  }

  window.addEventListener('DOMContentLoaded', () => {
    const isClosed = localStorage.getItem('popupClosed');

    if (!isClosed) {
      showPopup();
    } else {
      popup.style.display = 'none';
    }
  });
</script>
