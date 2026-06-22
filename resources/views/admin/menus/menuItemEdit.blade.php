@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Items Update')}}</title>
@endsection
@push('css')
<style type="text/css">
    .listmenu ul {
        margin: 0;
        padding: 0;
    }
    .listmenu ul li {
        list-style: none;
        margin: 5px;
        padding: 10px;
        border: 1px solid gray;
    }
    .menumanage {
        float: right;
    }
</style>
@endpush
@section('contents')

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Item Update</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                @if($item->parent_id)
                <a class="dropdown-item" href="{{route('admin.menusAction',['edit',$item->parent_id])}}" >Back Menu </a>
                @else
                <a class="dropdown-item" href="{{route('admin.menus')}}" >Menu List</a>
                @endif
                <a class="dropdown-item" href="{{route('admin.menusAction',['edit',$item->id])}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>

@include(adminTheme().'alerts')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">Items Update</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{route('admin.menusItemsAction',['update',$item->id])}}" method="post" enctype="multipart/form-data">
                        @csrf @if($item->menu_type==1)
                        <h4><b> Name:</b> {{$item->menuName()?:'No Found'}} <span style="color: #d8d8d8;">(Page)</span></h4>
                        @elseif($item->menu_type==2)
                        <h4><b> Name:</b> {{$item->menuName()?:'No Found'}} <span style="color: #d8d8d8;">(Post Category)</span></h4>
                        @elseif($item->menu_type==3)
                        <h4><b> Name:</b> {{$item->menuName()?:'No Found'}} <span style="color: #d8d8d8;">(Product Category)</span></h4>
                        @else
                        <div class="mb-3">
                            <label class="form-label">Menu Name*</label>
                            @if ($errors->has('name'))
                            <p style="color: red; margin: 0;">{{ $errors->first('name') }}</p>
                            @endif
                            <input type="text" name="name" value="{{$item->name}}" class="form-control" placeholder="Enter Menu Name" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Menu Link</label>
                            @if ($errors->has('link'))
                            <p style="color: red; margin: 0;">{{ $errors->first('link') }}</p>
                            @endif
                            <input type="text" name="link" value="{{$item->slug}}" placeholder="Enter Menu Link" class="form-control" />
                        </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Menu Icon (Font Icon class)</label>
                            @if ($errors->has('icon'))
                            <p style="color: red; margin: 0;">{{ $errors->first('icon') }}</p>
                            @endif
                            <input type="text" name="icon" value="{{$item->icon}}" placeholder="Enter Font Icon" class="form-control" />
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-8">
                                <label class="form-label">Menu Image (1X1)</label>
                                @if ($errors->has('image'))
                                <p style="color: red; margin: 0;">{{ $errors->first('image') }}</p>
                                @endif
                                <input type="file" name="image" class="form-control" />
                            </div>
                            <div class="mb-3 col-lg-4" style="position: relative;">
                                @if($item->imageFile)
                                
                                @isset(json_decode(Auth::user()->permission->permission, true)['menus']['add'])
                                <span style="position: absolute; right: 10px; top: 0px;">
                                    <a href="{{route('admin.mediesDelete',$item->imageFile->id)}}" class="mediaDelete" style="font-size: 25px; color: red;"><i class="fa fa-times-circle"></i></a>
                                </span>
                                @endisset

                                <img src="{{asset($item->image())}}" style="max-width: 50px;" />
                                @else
                                <span>No Image</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Target New Window</label>
                                <div class="i-checks">
                                    <label style="cursor: pointer;"> <input name="target" {{$item->target?'checked':''}} type="checkbox" > <i></i> Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-md rounded-0 btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection 
@push('js') 
@endpush
