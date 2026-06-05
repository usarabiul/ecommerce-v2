@foreach($product->productTags()->whereHas('attribute')->get() as $posttag)
<span>{{$posttag->attribute->name}} <i class="remove fa fa-times ml-1 cursor-pointer" data-url="{{route('admin.productsAction',['remove-tag',$product->id,'tag_id'=>$posttag->id])}}"></i></span>
@endforeach