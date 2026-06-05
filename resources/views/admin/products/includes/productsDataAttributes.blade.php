<div class="row">
	<div class="form-group col-md-6">
		<select class="form-control form-control-sm attributesItemFilter" 
		data-url="{{route('admin.productsUpdateAjax',['attributesItemFilter',$product->id])}}"
		>
			<option value="">Select Attribute</option>
			@foreach($attributes as $attri)
			<option value="{{$attri->id}}">{{$attri->name}} - 
			@if($attri->view==2)
        	(Color)
        	@elseif($attri->view==3)
        	(Image)
        	@else
        	(Text)
        	@endif
			</option>
			@endforeach
		</select>
	</div>
	<div class="form-group col-md-6">
		<div class="input-group AttributeItems">
			
		</div>
	</div>
	<div class="form-group col-md-12">
		<label>Attributes</label>
		
		<div class="AttributeItemsList table-responsive">
			@include('admin.products.includes.attributeItemsList')
		</div>

	</div>
	<div class="col-md-4">
		<label style="cursor:pointer;"
		><input type="checkbox" name="" class="priceVariationStatus"
		data-url="{{route('admin.productsUpdateAjax',['priceVariationStatus',$product->id])}}"
		 {{$product->variation_status?'checked':''}}>
		 Variation Products</label>
	</div>
	<div class="col-md-12 priceVariationDiv">
		@include('admin.products.includes.productVariation')
	</div>
</div>