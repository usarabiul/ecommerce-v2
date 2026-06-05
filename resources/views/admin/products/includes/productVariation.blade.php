@if($product->variation_status)
<table class="table table-bordered areaChargeTable">
	@if($product->productAttibutes->count() > 0)
	<tr>
		@if($pAttri = $product->productAttibutes->where('reff_id',68)->first())
		@if($pAttri->attribute)
		<td>
			<select class="form-control form-control-sm variationColor">
				<option value="">Select {{ucfirst($pAttri->attribute->name)}}</option>
				@foreach($product->productAttibutes->where('reff_id',$pAttri->reff_id) as $productAttri)
				<option value="{{$productAttri->parent_id}}">{{$productAttri->attributeItem?$productAttri->attributeItem->name:'Not Found'}}</option>
				@endforeach
			</select>
		</td>
		@endif
		@endif
		@if($pAttri = $product->productAttibutes->where('reff_id',73)->first())
		@if($pAttri->attribute)
		<td>
			<select class="form-control form-control-sm variationSize">
				<option value="">Select {{ucfirst($pAttri->attribute->name)}}</option>
				@foreach($product->productAttibutes->where('reff_id',$pAttri->reff_id) as $productAttri)
				<option value="{{$productAttri->parent_id}}">{{$productAttri->attributeItem?$productAttri->attributeItem->name:'Not Found'}}</option>
				@endforeach
			</select>
		</td>
		@endif
		@endif
		<td style="width:100px;min-width: 100px;">
		<span href="" class="btn btn-primary btn-sm variationItemsAdd"
		data-url="{{route('admin.productsUpdateAjax',['variationItemsAdd',$product->id])}}"
		 style="padding:8px 15px;"><i class="fa fa-plus"></i> Add</span>
		</td>
	</tr>
	
	@else
	<tr>
		<td style="text-align:center;color: red;">No Attribute. Please Add Attribute items</td>
	</tr>
	@endif
</table>

<h4>Variation SKU List</h4>
{!!$attriMessage!!}
<table class="table table-bordered areaChargeTable">
	<tr>
		{{--<th>Sku ID</th>--}}
		<th>Sku Name</th>
		<th style="width: 120px;">Price</th>
		<th style="width: 120px;">Qunatity</th>
		<th style="width: 100px;">Action</th>
	</tr>

	@foreach($product->productSkus as $sku)
	<tr>
		{{--<td>{{$product->id}}_ @foreach($sku->skuList as $i=>$list) {{$i==0?'':'_'}}{{$list->attributeItem?$list->attributeItem->name:'Not Found'}} @endforeach
		</td>--}}
		<th>
			{{$sku->attributeItem?$sku->attributeItem->name:''}}
			@if($sku->attributeItem && $sku->attribute)
			 - 
			@endif
			{{$sku->attribute?$sku->attribute->name:''}}
			<!-- <span>
			@foreach($sku->skuList as $i=>$list)
			 {{$i==0?'':'-'}} {{$list->attributeItem?$list->attributeItem->name:'Not Found'}}
			@endforeach
			</span> -->
		</th>
		<td>
			<input type="number" name="price" placeholder="Price" value="{{$sku->price > 0?$sku->price:'' }}"
			class="form-control form-control-sm variationItemsUpdate"
			data-id="{{$sku->id}}"
			data-url="{{route('admin.productsUpdateAjax',['variationItemsPrice',$product->id])}}"
			 style="width: 120px;">
		</td>
		<td><input type="number" name="quantity" placeholder="Quantity" value="{{$sku->duration > 0?$sku->duration:''}}"
			class="form-control form-control-sm variationItemsUpdate"
			data-id="{{$sku->id}}"
			data-url="{{route('admin.productsUpdateAjax',['variationItemsQuantity',$product->id])}}"
			 style="width: 120px;"></td>
		<td>
			<a href="javascript:void(0)" class="btn btn-sm btn-danger variationItemsDelete"
			data-id="{{$sku->sku_id}}"
			data-url="{{route('admin.productsUpdateAjax',['variationItemsDelete',$product->id])}}"
			><i class="fa fa-trash"></i></a>
		</td>
	</tr>
	@endforeach

</table>

@endif