@if($tags->count() > 0)
<div style="padding:5px;background:#ebe6e6;">
    @foreach($tags as $i=>$tag)
    <span class="tag-add" data-url="{{route('admin.productsAction',['add-tag',$product->id,'tag_id'=>$tag->id])}}">{{$tag->name}} <i class="fa fa-plus"></i></span>
    @endforeach
</div>
@endif