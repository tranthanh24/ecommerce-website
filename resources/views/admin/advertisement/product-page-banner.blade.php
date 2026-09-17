 <div class="tab-pane fade show active" id="list-products" role="tabpanel" aria-labelledby="list-products-list">
   <div class="card border">
     <div class="card-body">
       <form action="{{ route('admin.advertisement.product-banner') }}" method="POST" enctype="multipart/form-data">
         @csrf
         @method('PUT')
         <div class="form-group">
           <label for="">Xem trước</label> <br>
           @if (!empty($product_banner['banner_one']['image']))
             <img src="{{ asset($product_banner['banner_one']['image']) }}" width="200px" />
           @else
             <p>Chưa có ảnh</p>
           @endif
         </div>

         <div class="form-group">
           <label for="">Banner</label>
           <input type="file" class="form-control" name="image" value="">
         </div>

         <div class="form-group">
           <label for="">Url</label>
           <input type="text" class="form-control" name="url" value="">
         </div>

         <button type="submit" class="btn btn-primary">Cập nhật</button>
       </form>
     </div>
   </div>
 </div>
