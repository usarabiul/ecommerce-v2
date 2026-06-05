@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle(ucfirst($type).' Setting')}}</title>
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

    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">Mail Setting</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
            <form action="{{route('admin.settingUpdate',$type)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Mail Form Address </label>
                        <input type="text" name="mail_from_address" value="{{ $general->mail_from_address }}" placeholder="Mail From Address" class="form-control  {{$errors->has('mail_from_address')?'is-invalid':''}}" />
                        @if ($errors->has('mail_from_address'))
                        <div class="invalid-feedback">{{ $errors->first('mail_from_address') }}</div>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Mail Form Name</label>
                        <input type="text" name="mail_from_name" value="{{ $general->mail_from_name }}" placeholder="Mail From Name" class="form-control  {{$errors->has('mail_from_name')?'is-invalid':''}}" />
                        @if ($errors->has('mail_from_name'))
                        <div class="invalid-feedback">{{ $errors->first('mail_from_name') }}</div>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Mail Driver</label>
                        <select name="mail_driver" class="form-control  {{$errors->has('mail_driver')?'is-invalid':''}}">
                            <option value="smtp" {{$general->mail_driver=='smtp'?'selected':''}}>SMTP</option>
                            <option value="mailgun" {{$general->mail_driver=='mailgun'?'selected':''}}>Mailgun</option>
                            <option value="sendmail" {{$general->mail_driver=='sendmail'?'selected':''}}>Sendmail</option>
                            <option value="mail" {{$general->mail_driver=='mail'?'selected':''}}>Mail</option>
                        </select>
                        @if ($errors->has('mail_driver'))
                        <div class="invalid-feedback">{{ $errors->first('mail_driver') }}</div>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Mail Host</label>
                        <input type="text" name="mail_host" value="{{ $general->mail_host }}" placeholder="Mail Host" class="form-control  {{$errors->has('mail_host')?'is-invalid':''}}" />
                        @if ($errors->has('mail_host'))
                        <div class="invalid-feedback">{{ $errors->first('mail_host') }}</div>
                        @endif
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Mail Port</label>
                        <input type="text" name="mail_port" value="{{ $general->mail_port }}" placeholder="Mail Port" class="form-control  {{$errors->has('mail_port')?'is-invalid':''}}" />
                        @if ($errors->has('mail_port'))
                        <div class="invalid-feedback">{{ $errors->first('mail_port') }}</div>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Mail Encryption</label>
                        <select name="mail_encryption" class="form-control  {{$errors->has('mail_encryption')?'is-invalid':''}}">
                            <option value="tls" {{$general->mail_encryption=='tls'?'selected':''}}>TLS</option>
                            <option value="ssl" {{$general->mail_encryption=='ssl'?'selected':''}}>SSL</option>
                            <option value="" {{$general->mail_encryption==null?'selected':''}}>Null</option>
                        </select>
                        @if ($errors->has('mail_encryption'))
                        <div class="invalid-feedback">{{ $errors->first('mail_encryption') }}</div>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Mail Username</label>
                        <input type="text" name="mail_username" value="{{ $general->mail_username }}" placeholder="Mail Username  " class="form-control  {{$errors->has('mail_username')?'is-invalid':''}}" />
                        @if ($errors->has('mail_username'))
                        <div class="invalid-feedback">{{ $errors->first('mail_username') }}</div>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Mail Password</label>
                        <div class="input-group">
                            <input type="password" name="mail_password" value="{{$general->mail_password}}" placeholder="Mail Password" class="form-control  password {{$errors->has('mail_password')?'is-invalid':''}}" />
                            <div class="input-group-text showPassword">
                                <i class="fa fa-eye-slash"></i>
                            </div>
                        </div>
                        @if ($errors->has('mail_password'))
                        <div class="invalid-feedback">{{ $errors->first('mail_password') }}</div>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Mail Status</label><br>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="mail_status" {{$general->mail_status?'checked':''}} >Active <small>(Mail System Active)</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                </form>

                <form action="{{route('admin.settingUpdate','send-testing-mail')}}" method="post">
                    @csrf
                    <h4 style="color: #ff7e93;font-weight: bold;">Send Testing Mail</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Send Mail Address</label>
                            <input type="email" class="form-control" name="mail_address" value="{{old('mail_address')?:$general->mail_from_address}}" placeholder="Enter Mail Address" required="">
                            @if ($errors->has('mail_address'))
                            <div class="invalid-feedback">{{ $errors->first('mail_address') }}</div>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Action</label><br>
                            <button type="submit" class="btn btn-md btn-danger"><i class="fa fa-paper-plane"></i> Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>





@endsection 
@push('js') 

@endpush
