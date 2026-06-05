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
    <form action="{{route('admin.settingUpdate',$type)}}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">Social link</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label" >Facebook Link </label>
                            <input type="text" name="facebook_link" value="{{ $general->facebook_link }}" placeholder="Facebook Link" class="form-control {{$errors->has('facebook_link')?'is-invalid':''}}" />
                            @if ($errors->has('facebook_link'))
                            <div class="invalid-feedback">{{ $errors->first('facebook_link') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label" >Twitter Link</label>
                            <input type="text" name="twitter_link" value="{{ $general->twitter_link }}" placeholder="Twitter Link" class="form-control {{$errors->has('twitter_link')?'is-invalid':''}}" />
                            @if ($errors->has('twitter_link'))
                            <div class="invalid-feedback">{{ $errors->first('twitter_link') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label" >Instagram Link</label>
                            <input type="text" name="instagram_link" value="{{ $general->instagram_link }}" placeholder="Instagram Link" class="form-control {{$errors->has('instagram_link')?'is-invalid':''}}" />
                            @if ($errors->has('instagram_link'))
                            <div class="invalid-feedback">{{ $errors->first('instagram_link') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label" >Linkedin Link</label>
                            <input type="text" name="linkedin_link" value="{{ $general->linkedin_link }}" placeholder="Linkedin Link" class="form-control {{$errors->has('linkedin_link')?'is-invalid':''}}" />
                            @if ($errors->has('linkedin_link'))
                            <div class="invalid-feedback">{{ $errors->first('linkedin_link') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Youtube Link</label>
                            <input type="text" name="youtube_link" value="{{ $general->youtube_link }}" placeholder="Youtube Link" class="form-control {{$errors->has('youtube_link')?'is-invalid':''}}" />
                            @if ($errors->has('youtube_link'))
                            <div class="invalid-feedback">{{ $errors->first('youtube_link') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Pinterest Link</label>
                            <input type="text" name="pinterest_link" value="{{ $general->pinterest_link }}" placeholder="Pinterest Link" class="form-control {{$errors->has('pinterest_link')?'is-invalid':''}}" />
                            @if ($errors->has('pinterest_link'))
                            <div class="invalid-feedback">{{ $errors->first('pinterest_link') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Whatsapp Link</label>
                            <input type="text" name="whatsapp_link" value="{{ $general->whatsapp_link }}" placeholder="Whatsapp Link" class="form-control {{$errors->has('whatsapp_link')?'is-invalid':''}}" />
                            @if ($errors->has('whatsapp_link'))
                            <div class="invalid-feedback">{{ $errors->first('whatsapp_link') }}</div>
                            @endif
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                            <label class="form-label">Messanger Link</label>
                            <input type="text" name="messanger_link" value="{{ $general->messanger_link }}" placeholder="Messanger Link" class="form-control {{$errors->has('messanger_link')?'is-invalid':''}}" />
                            @if ($errors->has('messanger_link'))
                            <div class="invalid-feedback">{{ $errors->first('messanger_link') }}</div>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-md">Save changes</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">Social Ingreation</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                            <label class="form-label" >Facebook App ID </label>
                            <input type="text" name="fb_app_id" value="{{ $general->fb_app_id }}" placeholder="Facebook App ID" class="form-control {{$errors->has('fb_app_id')?'error':''}}" />
                            @if ($errors->has('fb_app_id'))
                            <div class="invalid-feedback">{{ $errors->first('fb_app_id') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                            <label class="form-label" >Facebook App Secret</label>
                            <input type="text" name="fb_app_secret" value="{{ $general->fb_app_secret }}" placeholder="Facebook App Secret" class="form-control {{$errors->has('fb_app_secret')?'error':''}}" />
                            @if ($errors->has('fb_app_secret'))
                            <div class="invalid-feedback">{{ $errors->first('fb_app_secret') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                            <label class="form-label" >Facebook Redirect Url</label>
                            <input
                                type="text"
                                name="fb_app_redirect_url"
                                value="{{ $general->fb_app_redirect_url }}"
                                placeholder="Facebook Redirect Url"
                                class="form-control {{$errors->has('fb_app_redirect_url')?'error':''}}"
                            />
                            @if ($errors->has('fb_app_redirect_url'))
                            <div class="invalid-feedback">{{ $errors->first('fb_app_redirect_url') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                            <label class="form-label">Google App ID </label>
                            <input type="text" name="google_client_id" value="{{ $general->google_client_id }}" placeholder="Google App ID" class="form-control {{$errors->has('google_client_id')?'error':''}}" />
                            @if ($errors->has('google_client_id'))
                            <div class="invalid-feedback">{{ $errors->first('google_client_id') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                            <label class="form-label" >Google App Secret</label>
                            <input
                                type="text"
                                name="google_client_secret"
                                value="{{ $general->google_client_secret }}"
                                placeholder="Google App Secret"
                                class="form-control {{$errors->has('google_client_secret')?'error':''}}"
                            />
                            @if ($errors->has('google_client_secret'))
                            <div class="invalid-feedback">{{ $errors->first('google_client_secret') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                            <label class="form-label" >Google Redirect Url</label>
                            <input
                                type="text"
                                name="google_client_redirect_url"
                                value="{{ $general->google_client_redirect_url }}"
                                placeholder="Google Redirect Url"
                                class="form-control {{$errors->has('google_client_redirect_url')?'error':''}}"
                            />
                            @if ($errors->has('google_client_redirect_url'))
                            <div class="invalid-feedback">{{ $errors->first('google_client_redirect_url') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                            <label class="form-label" >Twitter App ID </label>
                            <input type="text" name="tw_app_id" value="{{ $general->tw_app_id }}" placeholder="Twitter App ID" class="form-control {{$errors->has('tw_app_id')?'error':''}}" />
                            @if ($errors->has('tw_app_id'))
                            <div class="invalid-feedback">{{ $errors->first('tw_app_id') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                            <label class="form-label" >Twitter App Secret</label>
                            <input type="text" name="tw_app_secret" value="{{ $general->tw_app_secret }}" placeholder="Twitter App Secret" class="form-control {{$errors->has('tw_app_secret')?'error':''}}" />
                            @if ($errors->has('tw_app_secret'))
                            <div class="invalid-feedback">{{ $errors->first('tw_app_secret') }}</div>
                            @endif
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 mb-3">
                            <label class="form-label" >Twitter Redirect Url</label>
                            <input
                                type="text"
                                name="tw_app_redirect_url"
                                value="{{ $general->tw_app_redirect_url }}"
                                placeholder="Twitter Redirect Url"
                                class="form-control {{$errors->has('tw_app_redirect_url')?'error':''}}"
                            />
                            @if ($errors->has('tw_app_redirect_url'))
                            <div class="invalid-feedback">{{ $errors->first('tw_app_redirect_url') }}</div>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-md">Save changes</button>
                </div>
            </div>
        </div>
    </form>


@endsection @push('js') @endpush
