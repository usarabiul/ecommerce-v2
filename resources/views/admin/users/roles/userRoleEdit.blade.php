@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Role Update')}}</title>
@endsection @push('css')
<style type="text/css">
    label {
        cursor: pointer;
        margin: 0;
    }
</style>
@endpush 
@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Role Update</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Role Update</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.userRoles')}}">
                Back
            </a>
            <a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
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
                            <form action="{{route('admin.userRoleAction',['update',$role->id])}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="controls">
                                                <label>Role Name </label>
                                                <input type="text" class="form-control" name="name" placeholder="Role name" value="{{$role->name}}" required="" />
                                                @if ($errors->has('name'))
                                                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            @include(adminTheme().'users.includes.rolesPermission')
                                        </div>
                                    </div>
                                    @isset(json_decode(Auth::user()->permission->permission, true)['adminRoles']['add'])
                                    <div class="col-12 mt-1">
                                        <button type="submit" class="btn btn-primary btn-md rounded-0 glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save changes</button>
                                    </div>
                                    @endisset
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js')

<script type="text/javascript">
    $(document).ready(function () {
        $("#checkall").click(function () {
            var checked = $(this).prop("checked");
            $("input:checkbox").prop("checked", checked);
        });
    });
</script>

@endpush
