<div class="row">
	<div class="form-group col-md-12">
		<div class="input-group">
			<input type="text" class="form-control extraAttributeTitle form-control-sm" placeholder="Title">
			<input type="text" class="form-control extraAttributeValue form-control-sm" placeholder="Value">
			<span class="btn btn-sm btn-primary extraAttributeAdd rounded-0"
			data-url="{{route('admin.productsUpdateAjax',['extraAttributeAdd',$product->id])}}"
			 style="min-width:50px;text-align:center;cursor:pointer;padding: 8px;"
			 ><i class="fa fa-plus"></i> </span>
		</div>
	</div>
	<div class="form-group col-md-12">
		<label>Additional Info</label>
		<div class="extraAttributeList">
			@include('admin.products.includes.extraAttributeList')
		</div>
	</div>
</div>