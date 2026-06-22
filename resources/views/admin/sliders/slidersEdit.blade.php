@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Slider Edit')}}</title>
@endsection @push('css')

<style type="text/css">
    .fileUpload-div {
        border: 2px dotted #e3e3e3;
        padding: 25px;
        text-align: center;
    }

    .fileUpload-div p {
        font-size: 20px;
        color: silver;
        text-transform: uppercase;
    }
    .fileUpload-div label {
        margin: 0;
    }
    .fileUpload-div i {
        font-size: 60px;
        cursor: pointer;
        color: #c6c2c2;
    }
</style>
@endpush @section('contents')

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Slider Edit</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.sliders')}}" >Back </a>
                <a class="dropdown-item" href="{{route('admin.slidersAction',['edit',$slider->id])}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>


@include(adminTheme().'alerts')

<form action="{{route('admin.slidersAction',['update',$slider->id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">Slider Edit</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Slider Name(*) </label>
                        <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Slider Name" value="{{$slider->name?:old('name')}}" required="" />
                        @if ($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Slider Location</label>
                        <select class="form-control" name="location">
                            <option value="">Select Location</option>
                            <option value="Front Page Slider" {{$slider->location=='Front Page Slider'?'selected':''}}>Front Page Slider</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="image" class="form-control {{$errors->has('image')?'error':''}}" />
                        @if ($errors->has('image'))
                        <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-md-2">
                        <img src="{{asset($slider->image())}}" style="max-width: 100px;" />
                        @isset(json_decode(Auth::user()->permission->permission, true)['sliders']['add'])
                        @if($slider->imageFile)
                        <a href="{{route('admin.mediesDelete',$slider->imageFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                        @endif
                        @endisset
                    </div>
                </div>
    
                
                    <div class="fileUpload-div">
                        <div>
                            <p>Click To Upload Images (Multiple)</p>
                        </div>
                        <div>
                            @if ($errors->has('images'))
                            <div class="invalid-feedback">The Tags Must Be (jpeg,png,jpg,gif,svg,webp,bmp,tiff) max:2024 MB</div>
                            @endif
                            <small>(jpeg,png,jpg,gif,svg,webp,bmp,tiff) max:25 MB</small>
                        </div>
                        <div>
                            <label>
                                <input type="file" name="images[]" multiple="" accept="image/*" class="form-control fileUpload" />
                            </label>
                            
                        </div>
                    </div>
                    <br>

                <div>
                    @include(adminTheme().'sliders.includes.slideItems')
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">Slider Status</label> <br>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="status" {{$slider->status=='active'?'checked':''}} >Active
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</form>

@endsection 

@push('js')


@endpush
