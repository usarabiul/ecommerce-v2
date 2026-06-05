<table class="table table-bordered areaChargeTable" style="margin:0;">
	<tr>
		<td style="width: 200px;min-width: 200px;">Title</td>
		<td style="width: 200px;min-width: 200px;">Value</td>
		<td style="min-width: 100px;width: 100px;">Action</td>
	</tr>
	<tbody class="sortable">
	@foreach($product->extraAttribute as $extraAttri)
	<tr>
		<th style="cursor: all-scroll;">
		{{$extraAttri->name}}
		<input type="hidden" name="extraAttributeSerial[]" value="{{$extraAttri->id}}">
		</th>
		<td>{{$extraAttri->description}}</td>
		<td><a href="javascript:void(0)" 
			data-id="{{$extraAttri->id}}"
			data-url="{{route('admin.productsUpdateAjax',['extraAttributeDelete',$product->id])}}" 
			class="btn-sm btn btn-danger extraAttributeDelete"
			><i class="fa fa-trash"></i></a></td>
	</tr>
	@endforeach
	</tbody>
</table>