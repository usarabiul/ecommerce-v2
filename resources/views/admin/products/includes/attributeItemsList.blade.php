{!!$attriMessage!!}
<table class="table table-bordered areaChargeTable" style="margin:0;">
	<tr>
		<th style="width: 200px;min-width: 200px;">Attribute</th>
		<th style="width: 200px;min-width: 250px;">Items</th>
		<th style="width:100px;min-width: 100px;">Action</th>
	</tr>
	<tbody class="sortable">
	@foreach($product->productAttibutes as $productAttri)
	<tr>
		<td style="cursor: all-scroll;">
			@if($productAttri->attribute)
			<span>{{$productAttri->attribute->name}}</span>
			@if($productAttri->attribute->view==2)
        	<small>(Color)</small>
        	@elseif($productAttri->attribute->view==3)
        	<small>(Image)</small>
        	@else
        	<small>(Text)</small>
        	@endif
        	@endif
        	<input type="hidden" name="attributeSerial[]" value="{{$productAttri->id}}">
		</td>
		<th>
			{{$productAttri->attributeItem?$productAttri->attributeItem->name:'Not Found'}}

			@if($productAttri->attribute && $productAttri->attributeItem)

			@if($productAttri->attribute->view==2)
        	<input type="color" class="attributesItemColor" 
        	data-id="{{$productAttri->id}}"
			data-url="{{route('admin.productsUpdateAjax',['attributesItemColor',$product->id])}}"
        	value="{{$productAttri->value_1?$productAttri->value_1:$productAttri->attributeItem->icon}}"
        	style="padding: 0;width: 40px;height: 18px;">
        	@elseif($productAttri->attribute->view==3)
        	<small>
        		<img src="{{asset($productAttri->imageFile?$productAttri->image():$productAttri->attributeItem->image())}}" style="max-width: 25px;">
        		
        	</small>
        	<input type="file" class="attributesItemImage" 
        	data-id="{{$productAttri->id}}"
			data-url="{{route('admin.productsUpdateAjax',['attributesItemImage',$product->id])}}"
        	style="padding: 0;width: 150px;">
        	@endif

			@endif
		</th>
		<td>
		<a href="javascript:void(0)" class="btn btn-sm btn-danger attributesItemDelete" 
		data-id="{{$productAttri->id}}"
		data-url="{{route('admin.productsUpdateAjax',['attributesItemDelete',$product->id])}}"
			><i class="fa fa-trash"></i></a>
		</td>
	</tr>
	@endforeach
	</tbody>
</table>

<script>
	$(function(){
		$( ".sortable" ).sortable();
        $( ".sortable" ).disableSelection();
	})
</script>