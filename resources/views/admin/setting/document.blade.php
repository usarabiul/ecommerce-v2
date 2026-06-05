@extends(adminTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Apps Documents')}}</title>
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
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">{{ucfirst($type)}} Setting</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.setting',$type)}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>
 

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