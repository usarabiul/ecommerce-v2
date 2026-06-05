<ul>
    @if($products->count() > 0)
    @foreach($products as $product)
    <li data-url="{{route('admin.ecommerceCouponsAction',['add-product',$coupon->id,'product_id'=>$product->id])}}">
        <span>{{$product->name}}</span>
    </li>
    @endforeach
    @else
    <li>
        <span>No Product Found</span>
    </li>
    @endif
</ul>