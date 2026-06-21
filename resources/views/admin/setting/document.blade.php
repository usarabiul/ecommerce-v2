@extends(adminTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Apps Documents')}}</title>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')
 
<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">{{ucfirst($type)}} Setting</div>
    <div class="ms-auto">
        <a href="{{route('admin.setting',$type)}}" class="btn btn-primary"><i class="bx bx-refresh"></i> Reload </a>
    </div>
</div>

	<div class="card">
		<div class="card-content">
			<div class="card-body">
			<h1>Apps Documents white...</h1>
			</div>
		</div>
	</div>





@endsection
@push('js')

@endpush