@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Attribute Item Edit')}}</title>
@endsection
@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Attribute Edit</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Attribute Edit</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       	<a class="btn btn-outline-primary" href="{{route('admin.productsAttributesAction',['edit',$attribute->parent_id])}}">BACK</a>
       	<a class="btn btn-outline-success" href="{{route('admin.productsAttributesItemAction',['create',$attribute->parent_id])}}">ADD NEW</a>
       	<a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
	


@include(adminTheme().'alerts')

<form action="{{route('admin.productsAttributesItemAction',['update',$attribute->id])}}" method="post" enctype="multipart/form-data">
@csrf
	<div class="row">
	<div class="col-md-5">
			
		<div class="card">
			<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
				<h4 class="card-title">Attribute Edit</h4>
			</div>
				<div class="card-content">
					<div class="card-body">
					<div class="form-group">
						<label for="name">Attribute Name(*) </label>
						<input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Attribute Name" value="{{$attribute->name?:old('name')}}" required="" />
						@if ($errors->has('name'))
						<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('name') }}</p>
						@endif
					</div>
					<div class="form-group">
						<label for="description">Description </label>
						<textarea name="description" class="form-control {{$errors->has('description')?'error':''}}" placeholder="Enter Description">{!!$attribute->description!!}</textarea>
						@if ($errors->has('description'))
						<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('description') }}</p>
						@endif
					</div>
					<div class="form-group">
						@if($attribute->parent)

						@if($attribute->parent->view==2)
						<input type="color" name="color" value="{{$attribute->icon?:old('color')}}">

						@elseif($attribute->parent->view==3)
						
						<input type="file" name="image" class="form-control">
						<img src="{{asset($attribute->image())}}" style="max-width:50px;">
						@if($attribute->imageFile)
					<a href="{{route('admin.mediesDelete',$attribute->imageFile->id)}}" class="mediaDelete" style="color:red;"><i class="fa fa-trash"></i></a>
						@endif
						
						@endif

						@endif

					</div>
				<button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
									changes </button>

					</div>
				</div>
			</div>

		</div>

	</div>
</form>




@endsection
@push('js')


@endpush