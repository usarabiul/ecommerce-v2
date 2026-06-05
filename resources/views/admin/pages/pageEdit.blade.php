@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Page Edit')}}</title>
@endsection 
@push('css')

<style type="text/css">

</style>
@endpush 
@section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Page Edit</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.pagesAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Page</a>
            <a href="{{route('admin.pages')}}" type="button" class="btn btn-success mr-2"> Back</a>
            <a href="{{route('admin.pagesAction',['edit',$page->id])}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

@include(adminTheme().'alerts')
<form action="{{route('admin.pagesAction',['update',$page->id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Page Edit 
                        @if($page->slug)
                        <a href="{{route('pageView',$page->slug)}}" class="badge badge-success float-right" target="_blank">View</a>
                        @endif
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label">Name 
                                @if($page->template)
                                <span style="color: #ccc;">({{$page->template}})</span>
                                @endif
                            </label>
                            <input type="text" class="form-control titleForSlug {{$errors->has('name')?'is-invalid':''}}" name="name" placeholder="Enter Name" value="{{old('name')?:$page->name}}" required="" />
                            @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="mb-3 input-group">
                            <label class="slugEdit" style="cursor: pointer;width: 130px;padding: 6px;margin:0;background: #c6c9d5;"><span>
                                @if($page->auto_slug)
                                Custom Slug <i class="fa fa-edit"></i>
                                @else    
                                Auto Slug
                                @endif
                            </span></label>
                            <input type="text" class="slugEditData form-control {{$errors->has('slug')?'error':''}}"
                                @if($page->auto_slug) 
                                    name="slug"
                                @else
                                disabled
                                @endif
                            placeholder="Post Slug" value="{{$page->slug?:old('slug')}}" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Short Description </label>
                            <textarea name="short_description" class="form-control {{$errors->has('short_description')?'is-invalid':''}}" placeholder="Enter Short Description">{!!old('short_description')?:$page->short_description!!}</textarea>
                            @if ($errors->has('short_description'))
                            <div class="invalid-feedback">{{ $errors->first('short_description') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description </label>
                            <textarea name="description" class="{{$errors->has('description')?'is-invalid':''}} tinyEditor" placeholder="Enter Description">{!!old('description')?:$page->description!!}</textarea>
                            @if ($errors->has('description'))
                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">SEO Optimize</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">SEO Meta Title</label>
                            <input type="text" class="form-control {{$errors->has('seo_title')?'is-invalid':''}}" name="seo_title" placeholder="Enter SEO Meta Title" value="{{old('seo_title')?:$page->seo_title}}" />
                            @if ($errors->has('seo_title'))
                            <div class="invalid-feedback">{{ $errors->first('seo_title') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SEO Meta Description </label>
                            <textarea name="seo_description" class="form-control {{$errors->has('seo_description')?'is-invalid':''}}" placeholder="Enter SEO Meta Description">{!!old('seo_description')?:$page->seo_description!!}</textarea>
                            @if ($errors->has('seo_description'))
                            <div class="invalid-feedback">{{ $errors->first('seo_description') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SEO Meta Keyword </label>
                            <textarea name="seo_keyword" class="form-control {{$errors->has('seo_keyword')?'is-invalid':''}}" placeholder="Enter SEO Meta Keyword">{!!old('seo_keyword')?:$page->seo_keyword!!}</textarea>
                            @if ($errors->has('seo_keyword'))
                            <div class="invalid-feedback">{{ $errors->first('seo_keyword') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Page Images</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" accept="image/*" class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}" />
                            @if ($errors->has('image'))
                            <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <img src="{{asset($page->image())}}" style="max-width: 100px;" />
                            @isset(json_decode(Auth::user()->permission->permission, true)['pages']['add'])
                            @if($page->imageFile)
                            <a href="{{route('admin.mediesDelete',$page->imageFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                            @endif
                            @endisset
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Banner</label>
                            <input type="file" name="banner"  accept="image/*" class="form-control {{$errors->has('banner')?'is-invalid':''}}" />
                            @if ($errors->has('banner'))
                            <div class="invalid-feedback">{{ $errors->first('banner') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <img src="{{asset($page->banner())}}" style="max-width: 200px;" />
                            @isset(json_decode(Auth::user()->permission->permission, true)['pages']['add'])
                            @if($page->bannerFile)
                            <a href="{{route('admin.mediesDelete',$page->bannerFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                            @endif
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Page Action</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Page Template</label>
                            <select class="selectpicker form-control" name="template" title="Select Template">
                                <option value="" {{$page->template==null?'selected':''}} >Default Template</option>
                                <option value="Front Page" {{$page->template=='Front Page'?'selected':''}}>Front Page</option>
                                <option value="Privacy Policy" {{$page->template=='Privacy Policy'?'selected':''}}>Privacy Policy</option>
                                <option value="Latest Blog" {{$page->template=='Latest Blog'?'selected':''}}>Latest Blog</option>
                                <option value="Latest Product" {{$page->template=='Latest Product'?'selected':''}}>Latest Product</option>
                                <option value="About Us" {{$page->template=='About Us'?'selected':''}}>About Us</option>
                                <option value="Contact Us" {{$page->template=='Contact Us'?'selected':''}}>Contact Us</option>
                                <option value="Galleries" {{$page->template=='Galleries'?'selected':''}}>Galleries</option>
                                <option value="All Brands" {{$page->template=='All Brands'?'selected':''}}>All Brands</option>
                                <option value="All Clients" {{$page->template=='All Clients'?'selected':''}}>All Clients</option>
                            </select>
                            @if ($errors->has('template'))
                            <div class="invalid-feedback">{{ $errors->first('template') }}</div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">  Status</label><br>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="status" {{$page->status=='active'?'checked':''}} >Active
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Featured</label><br>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="featured" {{$page->featured?'checked':''}} >Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Published Date</label>
                            <input type="date" class="form-control form-control-sm" name="created_at" value="{{$page->created_at->format('Y-m-d')}}">
                            @if ($errors->has('created_at'))
                            <div class="invalid-feedback">{{ $errors->first('created_at') }}</div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Save changes</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


@endsection @push('js')
<script>

</script>

@endpush
