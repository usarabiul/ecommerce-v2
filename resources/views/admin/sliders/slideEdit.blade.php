@extends(adminTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Slide Edit')}}</title>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Slide Edit</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.slidersAction',['edit',$slide->parent_id])}}" type="button" class="btn btn-success mr-2"> Back</a>
            <a href="{{route('admin.slideAction',['edit',$slide->parent_id])}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>


@include(adminTheme().'alerts')
<form action="{{route('admin.slideAction',['update',$slide->id])}}" method="post" enctype="multipart/form-data">
	@csrf
	<div class="row">

		<div class="col-md-8">
			<div class="card">
				<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
					<h4 class="card-title">Slide Edit</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label">Slide Name</label>
							<input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Slider Name" value="{{$slide->name?:old('name')}}"  />
							@if ($errors->has('name'))
							<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('name') }}</p>
							@endif
								</div>
									
								<div class="mb-3">
									<label class="form-label">Description </label>
									<textarea name="description" rows="8" class="form-control {{$errors->has('description')?'error':''}}" placeholder="Enter Description">{!!$slide->description!!}</textarea>
									@if ($errors->has('description'))
									<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('description') }}</p>
									@endif
								</div>

								<div class="row">
									<div class="mb-3 col-md-6">
										<label class="form-label">Button Text </label>
										<input type="text" class="form-control {{$errors->has('buttonText')?'error':''}}" name="buttonText" placeholder="Enter Button Text" value="{{$slide->seo_title?:old('buttonText')}}" />
										@if ($errors->has('buttonText'))
										<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('buttonText') }}</p>
										@endif
									</div>
									<div class="mb-3 col-md-6">
										<label class="form-label">Button Link </label>
										<input type="text" class="form-control {{$errors->has('buttonLink')?'error':''}}" name="buttonLink" placeholder="Enter Button Link" value="{{$slide->seo_description?:old('buttonLink')}}"  />
										@if ($errors->has('buttonLink'))
										<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('buttonLink') }}</p>
										@endif
									</div>
								</div>
					</div>
					</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
					<h4 class="card-title">Slide Layer</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label">Slide Image</label>
							<input type="file" name="image" class="form-control {{$errors->has('image')?'error':''}}" >
							@if ($errors->has('image'))
							<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('image') }}</p>
							@endif
						</div>
						<div class="mb-3">
							<img src="{{asset($slide->image())}}" style="max-width: 100px;">
							@if($slide->imageFile)
							<a href="{{route('admin.mediesDelete',$slide->imageFile->id)}}" class="mediaDelete" style="color:red;"><i class="fa fa-trash"></i></a>
							@endif
						</div>
						<div class="mb-3">
							<label class="form-label">Mobile Slide Image</label>
							<input type="file" name="banner" class="form-control {{$errors->has('banner')?'error':''}}" >
							@if ($errors->has('banner'))
							<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('banner') }}</p>
							@endif
						</div>
						<div class="mb-3">
							@if($slide->bannerFile)
							<img src="{{asset($slide->banner())}}" style="max-width: 100px;">
							<a href="{{route('admin.mediesDelete',$slide->bannerFile->id)}}" class="mediaDelete" style="color:red;"><i class="fa fa-trash"></i></a>
							@endif
						</div>
						<div class="row">
							<div class="mb-3 col-6">
								<label class="form-label">Slide Status</label>
								<div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="status" {{$slide->status=='active'?'checked':''}} >Active
                                    </label>
                                </div>
							</div> 
						</div>
						<button type="submit" class="btn btn-primary">Save
							changes 
						</button>

					</div>
				</div>
			</div>
		</div>
	</div>
</form>




@endsection
@push('js')


@endpush