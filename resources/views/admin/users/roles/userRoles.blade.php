@extends(adminTheme().'layouts.app') 
@section('title')
<title> {{websiteTitle('User Roles')}}</title>
@endsection 
@push('css')
<style type="text/css"></style>
@endpush 
@section('contents')

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">User Roles</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.userRoleAction','create')}}" ><i class="bx bx-plus"></i> Add Role </a>
                <a class="dropdown-item" href="{{route('admin.userRoles')}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>



@include(adminTheme().'alerts')

<div class="card">
    <div class="card-content">
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
            <hr>
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
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
                                <div class="d-flex order-actions">
                                    @if($role->id==1)
                                    <a href="{{route('admin.userRoleAction',['edit',$role->id])}}" ><i class="bx bx-show"></i></a>
                                    @else
                                    <a href="{{route('admin.userRoleAction',['edit',$role->id])}}" ><i class="bx bxs-edit"></i></a>
                                    <a href="{{route('admin.userRoleAction',['delete',$role->id])}}" class="ms-3"><i class="bx bxs-trash"></i></a>
                                    @endif
                                </div>
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


@endsection @push('js') @endpush
