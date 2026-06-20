@extends(adminTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('User Profile')}}</title>
@endsection

@push('css')
<style type="text/css">
    .showPassword {
    right: 0 !important;
    cursor: pointer;
    }
    .ProfileImage{
        max-width: 64px;
        max-height: 64px;
    }
</style>
@endpush
@section('contents')


<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">View Profile</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('admin.usersAdmin')}}" type="button" class="btn btn-primary">Back</a>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.usersAdminAction',['edit',$user->id])}}"><i class="bx bx-edit"></i> Edit </a>
                <a class="dropdown-item" href="{{route('admin.usersAdminAction',['view',$user->id])}}"><i class="bx bx-refresh"></i> reload</a>
            </div>
        </div>
    </div>
</div>
	
@include(adminTheme().'alerts')
<div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">View Profile</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="{{ asset($user->image()) }}" alt="Admin" class="rounded-circle p-1 bg-primary" style="width: 150px; height: 150px;">
                            <div class="mt-3">
                                <h4>{{ $user->name }}</h4>
                                <p class="text-secondary mb-1">{{$user->permission?$user->permission->name:'Unauthorized' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Profile Information</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                       <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Mobile </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $user->mobile ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $user->email ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Address </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $user->fullAddress() ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Joined At </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $user->created_at->format('d M, Y') }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Status </h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                @if($user->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('js')



@endpush