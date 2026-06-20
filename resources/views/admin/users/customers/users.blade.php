@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Customer Users')}}</title>
@endsection 
@push('css')
<style type="text/css"></style>
@endpush 
@section('contents')

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Customer Users</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#AddUser" ><i class="bx bx-plus"></i> Add User </a>
                <a class="dropdown-item" href="{{route('admin.usersCustomer')}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>

    @include(adminTheme().'alerts')
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form action="{{route('admin.usersCustomer')}}">
                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <div class="input-group">
                                <input type="date" name="startDate" value="{{request()->startDate?:''}}" class="form-control {{$errors->has('startDate')?'error':''}}" />
                                <input type="date" value="{{request()->endDate?:''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}" />
                            </div>
                        </div>
                        <div class="col-md-6 mb-1">
                            <div class="input-group">
                                <input type="text" name="search" value="{{request()->search?:''}}" placeholder="User Name, Email, Mobile" class="form-control {{$errors->has('search')?'error':''}}" />
                                <button type="submit" class="btn btn-success rounded-0">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
            <form action="{{route('admin.usersCustomer')}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-1">
                            <select class="form-control form-control-sm rounded-0" name="action" required="">
                                <option value="">Select Action</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                                <option value="5">Delete</option>
                            </select>
                            <button class="btn btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <ul class="statuslist">
                            <li><a href="{{route('admin.usersCustomer')}}" class="{{ !request()->has('status') ? 'active' : '' }}">All ({{$totals->total}})</a></li>
                            <li><a href="{{route('admin.usersCustomer',['status'=>'active'])}}" class="{{ request()->get('status') === 'active' ? 'active' : '' }}">Active ({{$totals->active}})</a></li>
                            <li><a href="{{route('admin.usersCustomer',['status'=>'inactive'])}}" class="{{ request()->get('status') === 'inactive' ? 'active' : '' }}">Inactive ({{$totals->inactive}})</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive" style="min-height:300px;">
                    <table class="table mb-0  table-hover">
                        <thead  class="table-light">
                            <tr>
                                <th style="min-width: 60px; width: 60px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox"  id="checkall" >  <label class="custom-control-label" for="checkall">All <span class="checkCounter"></span> </label>
                                    </div>
                                </th>
                                <th style="min-width: 80px; width: 80px;text-align: center;">Image</th>
                                <th style="min-width: 200px; width: 200px;">Name</th>
                                <th style="min-width: 150px;">Email/Mobile</th>
                                <th style="min-width: 150px;">Address</th>
                                <th style="min-width: 80px;width: 80px;">Status</th>
                                <th style="min-width: 180px;width:180px;">Join Date</th>
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
                                    <img src="{{asset($user->image())}}" style="max-width: 50px; max-height: 50px;" />
                                </td>
                                <td><a href="{{route('admin.usersCustomerAction',['edit',$user->id])}}" class="invoice-action-view mr-1">{{$user->name}}</a></td>
                                <td>{{$user->email ?? $user->mobile}}</td>
                                <td>{{$user->fullAddress()}}</td>
                                <td>
                                    @if($user->status=='active')
                                    <span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">Active </span>
                                    @else
                                    <span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Inactive </span>
                                    @endif
                                </td>
                                <td>{{$user->created_at->format('d M Y h:i A')}}</td>
                                <td style="text-align:center;padding: 3px;">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary split-bg-primary" data-bs-toggle="dropdown">	
                                            <span class="bx bx-dots-vertical"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                            <a class="dropdown-item" href="{{route('admin.usersCustomerAction',['view',$user->id])}}"><i class="bx bxs-show"></i> View </a>
                                            <a class="dropdown-item" href="{{route('admin.usersCustomerAction',['edit',$user->id])}}"><i class="bx bxs-edit"></i> Edit </a>
                                            @if($user->id!=Auth::id()) 
                                            <a class="dropdown-item text-danger" href="{{route('admin.usersCustomerAction',['delete',$user->id])}}" onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="bx bxs-trash"></i> Delete
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align: center;">No Users Found</td>
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
 <div class="modal fade text-left" id="AddUser" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
	 <div class="modal-content">
	 	<form action="{{route('admin.usersCustomerAction','create')}}" method="post">
	   		@csrf
	   <div class="modal-header">
		 <h5 class="modal-title">Add User</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	   </div>
	   <div class="modal-body">
	   		<div class="form-group mb-3">
			    <label class="form-label">Name* </label>
                <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Name" required="">
				@if ($errors->has('name'))
				<div class="invalid-feedback">{{ $errors->first('name') }}</div>
				@endif
         	</div>
			 <div class="form-group mb-3">
				<label class="form-label">Email or Mobile* </label>
                <input type="text" class="form-control {{$errors->has('email_mobile')?'error':''}}" name="email_mobile" placeholder="Enter Email or Mobile" required="">
                @if ($errors->has('email_mobile'))
                <div class="invalid-feedback">{{ $errors->first('email_mobile') }}</div>
                @endif
         	</div>
	   </div>
	   <div class="modal-footer">
		 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close </button>
		 <button type="submit" class="btn btn-primary"> Add User</button>
	   </div>
	   </form>
	 </div>
   </div>
 </div>




@endsection 
@push('js') 
@endpush
