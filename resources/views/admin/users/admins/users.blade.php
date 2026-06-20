@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Admin Users')}}</title>
@endsection 
@push('css')
<style type="text/css"></style>
@endpush 

@section('contents')


<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Admin Users</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#AddUser" ><i class="bx bx-plus"></i> Add Admin </a>
                <a class="dropdown-item" href="{{route('admin.usersAdmin')}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>


@include(adminTheme().'alerts')

<div class="card">
    <div class="card-content">
        <div class="card-body">
        <form action="{{route('admin.usersAdmin')}}">
            <div class="row">
                <div class="col-md-4 mb-1">
                    <select name="role" class="form-control {{$errors->has('role')?'error':''}}">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                        <option value="{{$role->id}}" {{request()->role==$role->id?'selected':''}}>{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 mb-1">
                    <div class="input-group">
                        <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="User Name, Email, Mobile" class="form-control {{$errors->has('search')?'error':''}}" />
                        <button type="submit" class="btn btn-success rounded-0">Search</button>
                    </div>
                </div>
            </div>
        </form>
        <hr>
        <form action="{{route('admin.usersAdmin')}}">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group mb-1">
                        <select class="form-control form-control-sm rounded-0" name="action" required="">
                            <option value="">Select Action</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                            <option value="5">Remove</option>
                        </select>
                        <button class="btn btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                    </div>
                </div>
                <div class="col-md-4">
                    
                </div>
                <div class="col-md-4">
                    <ul class="statuslist">
                        <li><a href="{{route('admin.usersAdmin')}}" class="{{ !request()->has('status') ? 'active' : '' }}">All ({{$totals->total}})</a></li>
                        <li><a href="{{route('admin.usersAdmin',['status'=>'active'])}}" class="{{ request()->status=='active' ? 'active' : '' }}">Active ({{$totals->active}})</a></li>
                        <li><a href="{{route('admin.usersAdmin',['status'=>'inactive'])}}" class="{{ request()->status=='inactive' ? 'active' : '' }}">Inactive ({{$totals->inactive}})</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-responsive" style="min-height:300px;">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 100px; width: 100px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input checkbox"  id="checkall" >  <label class="custom-control-label" for="checkall">All <span class="checkCounter"></span> </label>
                                </div>
                            </th>
                            <th style="min-width: 80px;">Image</th>
                            <th style="min-width: 250px; width: 250px;">Name</th>
                            <th style="min-width: 150px;">Email</th>
                            <th style="min-width: 100px;">Role</th>
                            <th style="min-width: 80px;">Status</th>
                            <th style="min-width: 80px; width: 80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i=>$user)
                        <tr>
                            <td>
                                @if($user->id!=Auth::id()) 
                                 <div class="custom-control custom-control-inline custom-control-nolabel custom-checkbox">
                                    <input type="checkbox" class="custom-control-input checkbox" name="checkid[]" value="{{$user->id}}" id="ckb1">  <label class="custom-control-label" for="ckb1">{{ $users->firstItem() + $i }} </label>
                                </div>
                                @endif
                            </td>
                            <td style="padding: 0 3px; text-align: center;">
                                <span>
                                    <img src="{{asset($user->image())}}" style="max-width: 60px; max-height: 50px;" />
                                </span>
                            </td>
                            <td>
                                <a href="{{route('admin.usersAdminAction',['edit',$user->id])}}" class="invoice-action-view mr-1">{{$user->name}} </a>
                            </td>
                            <td>{{$user->email}}</td>
                            <td> 
                                @if($user->permission)
                                <span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">{{$user->permission->name}}</span>
                                @else
                                <span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Un-authorized</span>
                                @endif
                            </td>

                            <td>
                                @if($user->status='active')
                                <span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">Active </span>
                                @else
                                <span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Draft </span>
                                @endif
                            </td>
                            <td style="text-align:center;padding: 3px;">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary split-bg-primary" data-bs-toggle="dropdown">	
                                        <span class="bx bx-dots-vertical"></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                        <a class="dropdown-item" href="{{route('admin.usersAdminAction',['view',$user->id])}}"><i class="bx bxs-show"></i> View </a>
                                        <a class="dropdown-item" href="{{route('admin.usersAdminAction',['edit',$user->id])}}"><i class="bx bxs-edit"></i> Edit </a>
                                        @if($user->id!=Auth::id()) 
                                        <a class="dropdown-item text-danger" href="{{route('admin.usersAdminAction',['delete',$user->id])}}" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="bx bxs-trash"></i> Remove
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">No Admin Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{$users->links('pagination')}}
        </form>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade text-left" id="AddUser" tabindex="-1" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route('admin.usersAdminAction','create')}}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Add Admin User</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="controls">
                            <input type="text" class="form-control {{$errors->has('username')?'error':''}}" name="username" placeholder="Enter Email/Mobile" value="" required="" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection 
@push('js') 
<script type="text/javascript">

</script>
@endpush
