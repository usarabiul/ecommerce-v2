<div class="row">
	<div class="form-group col-md-6">
		<label>Minimum Order Quantity (MOQ)</label>
		<input type="number" name="min_order_quantity" 
		value="{{$product->min_order_quantity?$product->min_order_quantity:old('min_order_quantity')}}" 
		data-url="{{route('admin.productsUpdateAjax',['min_order_quantity',$product->id])}}" 
		class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('min_order_quantity')?'error':''}}" 
		placeholder="Default 1">
		@if ($errors->has('min_order_quantity'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('min_order_quantity') }}</p>
        @endif
	</div>
	<div class="form-group col-md-6">
		<label>Maximum Order Quantity (MaxOQ)</label>
		<input type="number" name="max_order_quantity" 
		value="{{$product->max_order_quantity?$product->max_order_quantity:old('max_order_quantity')}}" 
		data-url="{{route('admin.productsUpdateAjax',['max_order_quantity',$product->id])}}" 
		class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('max_order_quantity')?'error':''}}" 
		placeholder="Maximum Stock Limit">
	</div>
</div>
<div class="row">
	<div class="form-group col-md-4">
		<label>Weight</label>
		<div class="input-group">
			
			<input type="text" name="weight_unit" 
			value="{{$product->weight_unit?$product->weight_unit:old('weight_unit')}}" 
			data-url="{{route('admin.productsUpdateAjax',['weight_unit',$product->id])}}" 
		    class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('weight_unit')?'error':''}}"
			placeholder="Unit (kg)">
			
			{{--<select 
			name="weight_unit"
			class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('weight_unit')?'error':''}}"
			data-url="{{route('admin.productsUpdateAjax',['weight_unit',$product->id])}}"
			>
			    <option value="">Select Unit</option>
			    <option value="gram" {{$product->weight_unit=='gram'?'selected':''}}>Gram</option>
			    <option value="ml" {{$product->weight_unit=='ml'?'selected':''}}>ML</option>
			</select>
			--}}
			
			<input type="number" name="weight_amount" 
			value="{{$product->weight_amount?$product->weight_amount:old('weight_amount')}}" 
			data-url="{{route('admin.productsUpdateAjax',['weight_amount',$product->id])}}" 
		    class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('weight_amount')?'error':''}}"
			placeholder="Weight">
		</div>
	</div>
	<div class="form-group col-md-8">
		<label>Dimensions (cm)</label>
		<div class="input-group">
			<input type="text" name="dimensions_unit" 
			value="{{$product->dimensions_unit?$product->dimensions_unit:old('dimensions_unit')}}" 
			data-url="{{route('admin.productsUpdateAjax',['dimensions_unit',$product->id])}}" 
		    class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('dimensions_unit')?'error':''}}"
			 placeholder="Unit (cm)">
			<input type="number" name="dimensions_length" 
			value="{{$product->dimensions_length?$product->dimensions_length:old('dimensions_length')}}" 
			data-url="{{route('admin.productsUpdateAjax',['dimensions_length',$product->id])}}" 
		    class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('dimensions_length')?'error':''}}"
			placeholder="Length">
			<input type="number" name="dimensions_width" 
			value="{{$product->dimensions_width?$product->dimensions_width:old('dimensions_width')}}" 
			data-url="{{route('admin.productsUpdateAjax',['dimensions_width',$product->id])}}" 
		    class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('dimensions_width')?'error':''}}"
			placeholder="Width">
			<input type="number" name="dimensions_height" 
			value="{{$product->dimensions_height?$product->dimensions_height:old('dimensions_height')}}" 
			data-url="{{route('admin.productsUpdateAjax',['dimensions_height',$product->id])}}" 
		    class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('dimensions_height')?'error':''}}"
			placeholder="Height">
		</div>
	</div>
</div>

{{--
<div class="row">
	<div class="form-group col-md-12">
		<label>Delivery charge of area </label>
		
		<table class="table table-bordered areaChargeTable" style="margin:0;">
			<tr>
				<td>Area</td>
				<td>Charge</td>
			</tr>
			<tr>
				<td>Dhaka Metro Area</td>
				<td style="padding:0;">
				<input type="number" name="charge" class="form-control form-control-sm" placeholder="Charge">
				</td>
			</tr>
			<tr>
				<td>Out of Area</td>
				<td style="padding:0;">
				<input type="number" name="charge" class="form-control form-control-sm" placeholder="Charge">
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;">No Area Found</td>
			</tr>
		</table>
	</div>
	<div class="form-group col-md-4">
		<label><input type="checkbox" name="">
		 Delivery Free</label>
	</div>
</div>
--}}