 <div class="tab-pane fade show active" id="list-paypal" role="tabpanel" aria-labelledby="list-paypal-list">
   <div class="card border">
     <div class="card-body">
       <form action="{{ route('admin.paypal-setting.update', 1) }}" method="POST">
         @csrf
         @method('PUT')
         <div class="form-group">
           <label for="">Trạng thái</label>
           <select name="status" id="" class="form-control">
             <option {{ @$paypal->status === 1 ? 'selected' : 0 }} value="1">Kích hoạt</option>
             <option {{ @$paypal->status === 0 ? 'selected' : 0 }} value="0">Vô hiệu hóa</option>
           </select>
         </div>

         <div class="form-group">
           <label for="currency_rate">Tỷ giá (1 USD = ? VND)</label>
           <div class="d-flex">
             <input type="text" id="currency_rate" class="form-control" name="currency_rate"
               style="margin-right:10px" value="{{ @$paypal->currency_rate }}">
             <button type="button" id="fetchRateBtn" class="btn btn-info">
               🔄 Lấy tỷ giá tự động
             </button>
           </div>
           <small id="rate_status" class="text-muted"></small>
         </div>

         <div class="form-group">
           <label for="">PayPal Client ID</label>
           <input type="text" class="form-control" name="client_id" value="{{ @$paypal->client_id }}">
         </div>

         <div class="form-group">
           <label for="">PayPal Secret Key</label>
           <input type="text" class="form-control" name="secret_key" value="{{ @$paypal->secret_key }}">
         </div>

         <button type="submit" class="btn btn-primary">Cập nhật</button>
       </form>
     </div>
   </div>
 </div>

 <script>
   document.getElementById('fetchRateBtn').addEventListener('click', async function() {
     const btn = this;
     const rate = document.getElementById('currency_rate');
     const status = document.getElementById('rate_status');

     btn.disabled = true;
     btn.textContent = 'Đang lấy...';

     try {
       const res = await fetch('https://api.exchangerate-api.com/v4/latest/USD');

       const data = await res.json();
       const usdToVnd = data.rates?.VND;

       if (!usdToVnd) throw new Error("Không tìm thấy tỷ giá VND");

       rate.value = usdToVnd;
       status.textContent = `1 USD = ${usdToVnd.toLocaleString()} VND`;
     } catch (err) {
       status.textContent = 'Không lấy được tỷ giá!';
     } finally {
       btn.disabled = false;
       btn.textContent = '🔄 Lấy tỷ giá tự động';
     }
   });
 </script>
