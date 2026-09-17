 <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
   <div class="card border">
     <div class="card-body">
       <form action="{{ route('admin.general-settings.update') }}" method="POST">
         @csrf
         @method('PUT')
         <div class="form-group">
           <label for="">Tên website</label>
           <input type="text" class="form-control" name="site_name" value="{{ @$generalSetting->site_name }}">
         </div>

         <div class="form-group">
           <label for="">Email liên hệ</label>
           <input type="text" class="form-control" value="{{ @$generalSetting->contact_email }}"
             name="contact_email">
         </div>

         <div class="form-group">
           <label for="">Số điện thoại liên hệ</label>
           <input type="text" class="form-control" value="{{ @$generalSetting->contact_phone }}"
             name="contact_phone">
         </div>

         <div class="form-group">
           <label for="">Địa chỉ liên hệ</label>
           <input type="text" class="form-control" value="{{ @$generalSetting->contact_address }}"
             name="contact_address">
         </div>

         <div class="form-group">
           <label for="">Google map</label>
           <input type="text" class="form-control" value="{{ @$generalSetting->map }}" name="map">
         </div>
         <hr>

         <div class="form-group">
           <label for="">Ký hiệu tiền tệ</label>
           <input type="text" class="form-control" name="currency_icon"
             value="{{ @$generalSetting->currency_icon }}">
         </div>

         <div class="form-group">
           <label for="">Múi giờ</label>
           <select name="time_zone" id="" class="form-control">
             <option value="">Chọn múi giờ</option>
             @foreach (config('settings.time_zone') as $key => $timezone)
               <option {{ @$generalSetting->time_zone === $key ? 'selected' : '' }} value="{{ $key }}">
                 {{ $timezone }}</option>
             @endforeach
           </select>
         </div>

         <button type="submit" class="btn btn-primary">Cập nhật</button>
       </form>
     </div>
   </div>
 </div>
