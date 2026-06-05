@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Customer Users')}}</title>
@endsection 
@push('css')
<style type="text/css"></style>
@endpush 
@section('contents')


<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Customer Users</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <button type="button" data-toggle="modal" data-target="#AddUser" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add User</button>
            <a href="{{route('admin.usersCustomer')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

    @include(adminTheme().'alerts')
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">Customer users List</h4>
        </div>
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
                    <div class="col-md-4">
                        
                    </div>
                    <div class="col-md-4">
                        <ul class="statuslist">
                            <li><a href="{{route('admin.usersCustomer')}}">All ({{$totals->total}})</a></li>
                            <li><a href="{{route('admin.usersCustomer',['status'=>'active'])}}">Active ({{$totals->active}})</a></li>
                            <li><a href="{{route('admin.usersCustomer',['status'=>'inactive'])}}">Inactive ({{$totals->inactive}})</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive" style="min-height:300px;">
                    <table class="table  table-hover">
                        <thead  class="thead-light">
                            <tr>
                                <th style="min-width: 100px; width: 100px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox"  id="checkall" >  <label class="custom-control-label" for="checkall">All <span class="checkCounter"></span> </label>
                                    </div>
                                </th>
                                <th style="min-width: 100px; width: 100px;">Image</th>
                                <th style="min-width: 200px; width: 200px;">Name</th>
                                <th style="min-width: 150px;">Email</th>
                                <th style="min-width: 80px;">Status</th>
                                <th style="min-width: 100px;">Join Date</th>
                                <th style="min-width: 80px; width: 80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $i=>$user)
                            <tr>
                                <td>
                                    @if($user->id==Auth::id()) @else
                                    <div class="custom-control custom-control-inline custom-control-nolabel custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox" name="checkid[]" value="{{$user->id}}" id="ckb1">  <label class="custom-control-label" for="ckb1">ID </label>
                                    </div>
                                    @endif
                                    {{$users->currentpage()==1?$i+1:$i+($users->perpage()*($users->currentpage() - 1))+1}}
                                </td>
                                <td style="padding: 0 3px; text-align: center;">
                                    <span>
                                        <img src="{{asset($user->image())}}" style="max-width: 60px; max-height: 50px;" />
                                    </span>
                                </td>
                                <td><a href="{{route('admin.usersCustomerAction',['edit',$user->id])}}" class="invoice-action-view mr-1">{{$user->name}}</a></td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @if($user->status)
                                    <span class="badge badge-success">Active </span>
                                    @else
                                    <span class="badge badge-danger">Inactive </span>
                                    @endif
                                </td>
                                <td>{{$user->created_at->format('d M Y h:i A')}}</td>
                                <td style="text-align:center;">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success btn-ico" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-arrow"></div>
                                            <a href="{{route('admin.usersCustomerAction',['edit',$user->id])}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit </a>
                                            <a href="{{route('admin.usersCustomerAction',['delete',$user->id])}}" onclick="return confirm('Are You Want To Delete')" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
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
		 <h4 class="modal-title">Add User</h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		   <span aria-hidden="true">&times; </span>
		 </button>
	   </div>
	   <div class="modal-body">
	   		<div class="form-group">
			 <label for="name">Name* </label>
             <div class="controls">
                 <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Name" required="">
				@if ($errors->has('name'))
				<p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
				@endif
				</div>
         	</div>
			 <div class="form-group">
				<label for="name">Email* </label>
				<div class="controls">
					<input type="email" class="form-control {{$errors->has('email')?'error':''}}" name="email" placeholder="Enter Email" required="">
					@if ($errors->has('email'))
					<p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('email') }}</p>
					@endif
				</div>
         	</div>
	   </div>
	   <div class="modal-footer">
		 <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
		 <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</button>
	   </div>
	   </form>
	 </div>
   </div>
 </div>




@endsection 
@push('js') 
@endpush
