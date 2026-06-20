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
    <div class="breadcrumb-title pe-3">Edit Profile</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('admin.usersAdmin')}}" type="button" class="btn btn-primary">Back</a>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.usersAdminAction',['view',$user->id])}}"><i class="bx bx-show"></i> View </a>
                <a class="dropdown-item" href="{{route('admin.usersAdminAction',['edit',$user->id])}}"><i class="bx bx-refresh"></i> reload</a>
            </div>
        </div>
    </div>
</div>

	
@include(adminTheme().'alerts')

<div class="row">
<div class="col-md-7">
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">My Profile</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form action="{{route('admin.usersAdminAction',['update',$user->id])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex">
                        <a href="javascript: void(0);">
                            <img src="{{asset($user->image())}}" class="ProfileImage" alt="profile image" />
                        </a>
                        <div class="media-body mt-75">
                            <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                                <label class="btn btn-info btn-xs mx-2" for="account-upload">Upload new photo </label>
                                <input type="file" name="image" class="uploadImage" data-name="ProfileImage" id="account-upload" hidden="" />
                                @if($user->imageFile)
                                <a href="{{route('admin.mediesDelete',$user->imageFile->id)}}" class="mediaDelete btn btn-xs btn-danger mx-2">Reset </a>
                                @endif
                            </div>
                            @if ($errors->has('image'))
                            <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                            <p class="mx-2"><small>Allowed JPG, GIF or PNG. Max size of 2048kB</small></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Name* </label>
                            <input type="text" class="form-control {{$errors->has('name')?'is-invalid':''}}" name="name" placeholder="Enter Name" value="{{old('name')?:$user->name}}" required="" />
                            @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Email* </label>
                            <input type="email" class="form-control {{$errors->has('email')?'is-invalid':''}}" name="email" placeholder="Enter Email" value="{{old('email')?:$user->email}}" required="" />
                            @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Mobile* </label>
                            <input type="text" class="form-control {{$errors->has('mobile')?'is-invalid':''}}" name="mobile" placeholder="Enter Mobile" value="{{old('mobile')?:$user->mobile}}" />
                            @if ($errors->has('mobile'))
                            <div class="invalid-feedback">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Gender </label>
                            <select class="form-control {{$errors->has('gender')?'is-invalid':''}}" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male" {{$user->gender=='Male'?'selected':''}}>Male</option>
                                <option value="Female" {{$user->gender=='Female'?'selected':''}}>Female</option>
                            </select>
                            @if ($errors->has('gender'))
                            <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Division </label>
                            <select id="division" class="form-control {{$errors->has('division')?'is-invalid':''}}" name="division">
                                <option value="">Select Division</option>
                                @foreach(geoData(2,1) as $data)
                                <option value="{{$data->id}}" {{$data->id==$user->division?'selected':''}}>{{$data->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('division'))
                            <div class="invalid-feedback">{{ $errors->first('division') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">District </label>
                            <select id="district" class="form-control {{$errors->has('district')?'is-invalid':''}}" name="district">
                                @if($user->division==null)
                                <option value="">No District</option>
                                @else
                                <option value="">Select District</option>
                                @foreach(geoData(3,$user->division) as $data)
                                <option value="{{$data->id}}" {{$data->id==$user->district?'selected':''}}>{{$data->name}}</option>
                                @endforeach @endif
                            </select>
                            @if ($errors->has('district'))
                            <div class="invalid-feedback">{{ $errors->first('district') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">City </label>
                            <select id="city" class="form-control {{$errors->has('city')?'is-invalid':''}}" name="city">
                                @if($user->district==null)
                                <option value="">No City</option>
                                @else
                                <option value="">Select City</option>
                                @foreach(geoData(4,$user->district) as $data)
                                <option value="{{$data->id}}" {{$data->id==$user->city?'selected':''}}>{{$data->name}}</option>
                                @endforeach @endif
                            </select>
                            @if ($errors->has('city'))
                            <div class="invalid-feedback">{{ $errors->first('city') }}</div>
                            @endif
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Postal Code</label>
                            <input type="text" class="form-control {{$errors->has('postal_code')?'is-invalid':''}}" name="postal_code" placeholder="Enter Postal Code" value="{{old('postal_code')?:$user->postal_code}}" />
                            @if ($errors->has('postal_code'))
                            <div class="invalid-feedback">{{ $errors->first('postal_code') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                            <label class="form-label">Address Line</label>
                            <input type="text" class="form-control {{$errors->has('address')?'is-invalid':''}}" name="address" placeholder="Enter Address" value="{{old('address')?:$user->address_line1}}" />
                            @if ($errors->has('address'))
                            <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label for="status">User Status</label>
                            <br>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="status" {{$user->status?'checked':''}} >Active
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                                <label>User Role</label>
                                <select name="role" class="form-control {{$errors->has('role')?'error':''}}">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                    <option value="{{$role->id}}" {{$user->permission_id==$role->id?'selected':''}}>{{$role->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('role'))
                                <div class="invalid-feedback">{{ $errors->first('role') }}</div>
                                @endif
                            </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-5">
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">Change Password</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form action="{{route('admin.usersAdminAction',['change-password',$user->id])}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                            <label class="form-label">Old password </label>
                            <div class="input-group">
                                <input type="password" class="form-control {{$errors->has('old_password')?'is-invalid':''}} password " placeholder="Old Password" name="old_password" value="{{old('old_password')?:$user->password_show}}" required="" />
                                <div class="input-group-text showPassword">
                                    <i class="bx bx-hide"></i>
                                </div>
                            </div>
                            @if ($errors->has('old_password'))
                            <div class="invalid-feedback">{{ $errors->first('old_password') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                            <label class="form-label">New Password </label>
                            <input type="password" class="form-control password {{$errors->has('password')?'is-invalid':''}}" name="password" placeholder="New password" required="" />
                            @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                            <label class="form-label">Confirmed Password </label>
                            <input type="password" class="form-control password {{$errors->has('password_confirmation')?'is-invalid':''}}" name="password_confirmation" placeholder="Confirmed password" required="" />
                            @if ($errors->has('password_confirmation'))
                            <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                            @endif
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-danger">Change Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>


@endsection
@push('js')

<script type="text/javascript">


</script>


@endpush