<select class="form-control form-control-sm attributesItemAddId">
	<option value="">Select Items</option>
	@foreach($attri->subAttributes as $item)
	<option value="{{$item->id}}">{{$item->name}}</option>
	@endforeach
</select>
<span class="btn btn-sm btn-primary rounded-0 attributesItemAdd"
data-url="{{route('admin.productsUpdateAjax',['attributesItemAdd',$product->id])}}"
 style="width: 50px;padding: 8px;"><i class="fa fa-plus"></i> </span>