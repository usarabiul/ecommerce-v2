<div class="row">
	<div class="form-group col-md-4">
		<label>Regular Price</label>
		<input type="number" name="regular_price" 
		value="{{$product->regular_price?$product->regular_price:old('regular_price')}}" 
		data-url="{{route('admin.productsUpdateAjax',['regular_price',$product->id])}}" 
		class="form-control productDataAjaxUpdate form-control-sm priceUpdate regular_price {{$errors->has('regular_price')?'error':''}}"
		 placeholder="Enter Price">
		@if ($errors->has('regular_price'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('regular_price') }}</p>
        @endif
	</div>
	<div class="form-group col-md-4">
		<label>Discount (optional)</label>
		<div class="input-group">
			<select class="form-control productDataAjaxUpdate form-control-sm discounttype priceUpdate {{$errors->has('discount_type')?'error':''}}"
			data-url="{{route('admin.productsUpdateAjax',['discount_type',$product->id])}}" 
			 name="discount_type">
				<option value="percent" {{$product->discount_type=='percent'?'selected':''}}>Percent (%)</option>
				<option value="flat" {{$product->discount_type=='flat'?'selected':''}}>Flat (Currency)</option>
			</select>
			<input type="number" name="discount" 
			value="{{$product->discount?$product->discount:old('discount')}}" 
			data-url="{{route('admin.productsUpdateAjax',['discount',$product->id])}}" 
			class="form-control productDataAjaxUpdate form-control-sm discount priceUpdate {{$errors->has('discount')?'error':''}}" placeholder="Dicount">
		</div>
		@if ($errors->has('discount_type'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('discount_type') }}</p>
        @endif
        @if ($errors->has('discount'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('discount') }}</p>
        @endif
	</div>
	<div class="form-group col-md-4">
		<label>Final Price</label>
		<input type="number" readonly="" 
		value="{{$product->final_price?$product->final_price:old('final_price')}}" 
		data-url="{{route('admin.productsUpdateAjax',['final_price',$product->id])}}" 
		class="form-control productDataAjaxUpdate form-control-sm final_price" placeholder="0.00">
	</div>
</div>

<div class="row">
	<div class="form-group col-md-4">
		<label>Stock Quantity</label>
		<input  type="number" name="quantity" 
		value="{{$product->quantity?$product->quantity:old('quantity')}}" 
		data-url="{{route('admin.productsUpdateAjax',['quantity',$product->id])}}" 
		class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('quantity')?'error':''}}" 
		placeholder="Default unlimited">
		@if ($errors->has('quantity'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('quantity') }}</p>
        @endif
	</div>
	<div class="form-group col-md-4">
		<label>Stock Out Limit</label>
		<input type="number" name="stock_out_limit" 
		value="{{$product->stock_out_limit?$product->stock_out_limit:old('stock_out_limit')}}"
		data-url="{{route('admin.productsUpdateAjax',['stock_out_limit',$product->id])}}"  
		class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('stock_out_limit')?'error':''}}" 
		placeholder="Stock Out 0">
		@if ($errors->has('stock_out_limit'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('stock_out_limit') }}</p>
        @endif
	</div>
	<div class="form-group col-md-4">
		<label>Stock Status</label>
		<select class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('stock_status')?'error':''}}"
		value="{{$product->stock_status?$product->stock_status:old('stock_status')}}" 
		data-url="{{route('admin.productsUpdateAjax',['stock_status',$product->id])}}"
		>
		    <option value="1" {{$product->stock_status==1?'selected':''}}>Stock In</option>
            <option value="0" {{$product->stock_status==0?'selected':''}}>Out Of Stock</option>
		</select>
		@if ($errors->has('stock_status'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('stock_status') }}</p>
        @endif
	</div>
</div>
<div class="row">
	
	<div class="form-group col-md-4">
		<label>Purchase Price</label>
		<input type="number" name="purchase_price" 
		value="{{$product->purchase_price?$product->purchase_price:old('purchase_price')}}" 
		data-url="{{route('admin.productsUpdateAjax',['purchase_price',$product->id])}}" 
		class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('purchase_price')?'error':''}}"
		placeholder="Purchase Price">
		@if ($errors->has('purchase_price'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('purchase_price') }}</p>
        @endif
	</div>
	<div class="form-group col-md-4">
		<label>Offer Schedule Start</label>
		<input type="date" name="offer_start_date" 
		value="{{$product->offer_start_date?Carbon\Carbon::parse($product->offer_start_date)->format('Y-m-d'):old('offer_start_date')}}" data-url="{{route('admin.productsUpdateAjax',['offer_start_date',$product->id])}}" 
		class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('offer_start_date')?'error':''}}" >
		@if ($errors->has('offer_start_date'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('offer_start_date') }}</p>
        @endif
	</div>
	<div class="form-group col-md-4">
		<label>Offer Schedule End</label>
		<input type="date" name="offer_end_date" value="{{$product->offer_end_date?Carbon\Carbon::parse($product->offer_end_date)->format('Y-m-d'):old('offer_end_date')}}" data-url="{{route('admin.productsUpdateAjax',['offer_end_date',$product->id])}}" class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('offer_end_date')?'error':''}}" >
		@if ($errors->has('offer_end_date'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('offer_end_date') }}</p>
        @endif
	</div>
</div>

<div class="row">
	<div class="form-group col-md-4">
		<label>Barcode (Optional)</label>
		<input type="text" name="bar_code" 
		value="{{$product->bar_code?:old('bar_code')}}" 
		data-url="{{route('admin.productsUpdateAjax',['bar_code',$product->id])}}" 
		class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('bar_code')?'error':''}}" 

		placeholder="Enter Barcode">
		@if ($errors->has('bar_code'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('bar_code') }}</p>
        @endif
	</div>
	<div class="form-group col-md-4">
		<label>SKU (Optional)</label>
		<input type="text" name="sku_code" 
		value="{{$product->sku_code?$product->sku_code:old('sku_code')}}" 
		data-url="{{route('admin.productsUpdateAjax',['sku_code',$product->id])}}" 
		class="form-control productDataAjaxUpdate form-control-sm {{$errors->has('sku_code')?'error':''}}" 

		placeholder="Enter SKU">
		@if ($errors->has('sku_code'))
        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('sku_code') }}</p>
        @endif
	</div>
	
</div>