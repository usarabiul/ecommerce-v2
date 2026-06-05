@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle(ucfirst($type).' Setting')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

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

    @include(adminTheme().'alerts')
    <form action="{{route('admin.settingUpdate','sms')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">SMS Setting</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">

                            <label class="form-label">SMS Type</label>
                            <select name="sms_type" class="form-control {{$errors->has('sms_type')?'is-invalid':''}}">
                                <option value="smtp" {{$general->sms_type=='Non Masking'?'selected':''}}>Non-Masking</option>
                                <option value="mailgun" {{$general->sms_type=='Masking'?'selected':''}}>Masking</option>
                            </select>
                            @if ($errors->has('sms_type'))
                            <div class="invalid-feedback">{{ $errors->first('sms_type') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">SMS Sender ID</label>
                            <input type="text" name="sms_senderid" value="{{ $general->sms_senderid }}" placeholder="SMS Sender ID" class="form-control {{$errors->has('sms_senderid')?'is-invalid':''}}" />
                            @if ($errors->has('sms_senderid'))
                            <div class="invalid-feedback">{{ $errors->first('sms_senderid') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label" >SMS Url Non-Masking</label>
                            <input type="text" name="sms_url_nonmasking" value="{{ $general->sms_url_nonmasking }}" placeholder="SMS Url Non-Masking" class="form-control {{$errors->has('sms_url_nonmasking')?'is-invalid':''}}" />
                            @if ($errors->has('sms_url_nonmasking'))
                            <div class="invalid-feedback">{{ $errors->first('sms_url_nonmasking') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label" >SMS Url Masking</label>
                            <input type="text" name="sms_url_masking" value="{{ $general->sms_url_masking }}" placeholder="SMS Url Masking" class="form-control {{$errors->has('sms_url_masking')?'is-invalid':''}}" />
                            @if ($errors->has('sms_url_masking'))
                            <div class="invalid-feedback">{{ $errors->first('sms_url_masking') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label" >SMS Username</label>
                            <input type="text" name="sms_username" value="{{ $general->sms_username }}" placeholder="SMS Username  " class="form-control {{$errors->has('sms_username')?'is-invalid':''}}" />
                            @if ($errors->has('sms_username'))
                            <div class="invalid-feedback">{{ $errors->first('sms_username') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label" >SMS Password</label>
                            <div class="input-group">
                                <input type="password" name="sms_password" value="{{$general->sms_password}}" placeholder="SMS Password" class="form-control password {{$errors->has('sms_password')?'is-invalid':''}}" />
                                <div class="input-group-text showPassword">
                                    <i class="fa fa-eye-slash"></i>
                                </div>
                            </div>
                            @if ($errors->has('sms_password'))
                            <div class="invalid-feedback">{{ $errors->first('sms_password') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label" >SMS Status</label><br>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="sms_status" {{$general->maisms_statusl_status?'checked':''}} >Active <small>(SMS System Active)</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <button type="submit" class="btn btn-primary btn-md">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


@endsection @push('js') @endpush
