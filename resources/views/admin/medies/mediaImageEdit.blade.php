@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Media Update')}}</title>
@endsection @push('css')
<style type="text/css">
    .imagediv img {
        max-width: 100%;
    }
</style>
@endpush @section('contents')

<div class="page-content">

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Media Update</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.dashboard')}}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.medies')}}">Media Assets</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Media Update</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{route('admin.mediesEdit',$media->id)}}" class="btn btn-primary"><i class="bx bx-refresh"></i></a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->



    @include(adminTheme().'alerts')

    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="imagediv">
                            <img src="{{asset($media->image())}}" />
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>File Information:</h4>

                        <form action="{{route('admin.mediesEdit',$media->id)}}" method="post">
                            @csrf

                            <div class="form-group row mb-3">
                                <label class="col-sm-4 col-form-label">File Size: </label>
                                <div class="col-sm-8">
                                    {{ $media->file_size}} Bytes
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-4 col-form-label">File Url: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control urlcopytext" id="myInput" value="{{asset($media->file_url)}}" />
                                </div>
                                <div class="col-sm-2">
                                    <span class="btn btn-sm btn-success urlcopybtn" onclick="myFunction()">Copy</span>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-4 col-form-label">File Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control {{$errors->has('file_name')?'error':''}}" placeholder="File Name " disabled="" name="file_name" value="{{ $media->file_name}}" />
                                    @if ($errors->has('file_name'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('file_name') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-4 col-form-label">File Alt Tag:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control {{$errors->has('alt_text')?'error':''}}" placeholder="File Alt Tag " name="alt_text" value="{{ $media->alt_text }}" />
                                    @if ($errors->has('alt_text'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('alt_text') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-4 col-form-label">File Caption:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control {{$errors->has('caption')?'error':''}}" placeholder="File Caption " name="caption" value="{{ $media->caption }}" />
                                    @if ($errors->has('caption'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('caption') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-4 col-form-label">File Description:</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control {{$errors->has('description')?'error':''}}" placeholder="File Description " name="description">{!!$media->description!!}</textarea>
                                    @if ($errors->has('description'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-4 col-form-label">Author:</label>
                                <div class="col-sm-8">
                                    <span>
                                        @if($user =App\Models\User::find($media->addedby_id)) {{$user->name}} @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-3">
                                <label class="col-sm-4 col-form-label">Action</label>
                                <div class="col-sm-8">
                                    <button class="btn btn-primary" type="submit">Update</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection @push('js')
<script type="text/javascript">
    function myFunction() {
        var copyText = document.getElementById("myInput");

        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
    }
</script>
@endpush
