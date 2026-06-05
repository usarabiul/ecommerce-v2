@extends(adminTheme().'layouts.app') @section('title')
<title> {{websiteTitle('User Roles')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">User Roles</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">User Roles</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            @isset(json_decode(Auth::user()->permission->permission, true)['adminRoles']['add'])
            <a class="btn btn-outline-primary" href="{{route('admin.userRoleAction','create')}}">
                Add Role
            </a>
            @endisset

            <a class="btn btn-outline-primary" href="{{route('admin.userRoles')}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
                @include(adminTheme().'alerts')

                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div id="accordion">
                                <div
                                    class="card-header collapsed"
                                    data-toggle="collapse"
                                    data-target="#collapseTwo"
                                    aria-expanded="false"
                                    aria-controls="collapseTwo"
                                    id="headingTwo"
                                    style="background: #f5f7fa; padding: 10px; cursor: pointer; border: 1px solid #00b5b8;"
                                >
                                    Search click Here..
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" style="border: 1px solid #00b5b8; border-top: 0;">
                                    <div class="card-body">
                                        <form action="{{route('admin.userRoles')}}">
                                            <div class="row">
                                                <div class="col-md-12 mb-1">
                                                    <div class="input-group">
                                                        <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Search Role Name.." class="form-control {{$errors->has('search')?'error':''}}" />
                                                        <button type="submit" class="btn btn-success btn-sm rounded-0">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Roles All List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 100px; width: 100px;">SL</th>
                                            <th style="min-width: 250px; width: 250px;">Name</th>
                                            <th style="min-width: 250px;">Users</th>
                                            <th style="min-width: 120px; width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($roles as $i=>$role)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$role->name}}</td>
                                            <td>Users ({{$role->users->count()}})</td>
                                            <td>
                                                @if($role->id==1)
                                                <a href="{{route('admin.userRoleAction',['edit',$role->id])}}" class="invoice-action-view mr-1">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @else
                                                <a href="{{route('admin.userRoleAction',['edit',$role->id])}}" class="invoice-action-view mr-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @isset(json_decode(Auth::user()->permission->permission, true)['adminRoles']['delete'])
                                                <a href="{{route('admin.userRoleAction',['delete',$role->id])}}" onclick="return confirm('Are You Want To Delete')" class="invoice-action-edit cursor-pointer danger">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endisset @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{$roles->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js') @endpush
