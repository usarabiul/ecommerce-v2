@if($coupon->couponProductPosts()->count() > 0)
<div class="row">
    <div class="col-md-4">
        <div class="input-group">
            <label style="background: #c9c6c6;padding: 5px 15px;margin: 0;margin-right: 10px;">
                <input type="checkbox" class="checkAll"> All
            </label>
            <button type="button" class="btn btn-sm btn-danger rounded-0 counponProductDelete" data-url="{{route('admin.ecommerceCouponsAction',['delete-product',$coupon->id])}}"><i class="fa fa-trash"></i> Delete</button>
        </div>
    </div>
</div>
<div class="row" style="margin:0 -5px;">
    @foreach($coupon->couponProductPosts as $productPost)
    <div class="col-md-2 col-6" style="padding:5px;">
        <div class="productGrid">
        @if($product =$productPost->product)
        <img src="{{asset($product->image())}}">
        <a href="{{route('productView',$product->slug?:'no-slug')}}" target="_blank" style="display: block;">
            {{$product->name}}
        </a>
        @else
        <h4>Not Found</h4>
        @endif
        <label>
                <input type="checkbox" value="{{$productPost->id}}" class="counponCheck">
        </label>
        
        </div>
    </div>
    @endforeach
</div>
@endif