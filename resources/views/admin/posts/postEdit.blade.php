@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Post Edit')}}</title>
@endsection 

@push('css')

<style type="text/css">
    .catagorydiv {
        max-height: 300px;
        overflow: auto;
    }
    .catagorydiv ul {
        padding-left: 20px;
    }
    .catagorydiv ul li {
        list-style: none;
    }
</style>
@endpush 
@section('contents')

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Post Edit</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('admin.posts')}}" type="button" class="btn btn-primary">Back</a>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.postsAction','create')}}"><i class="bx bx-plus"></i> Add Post</a>
                <a class="dropdown-item" href="{{route('admin.postsAction',['edit',$post->id])}}"><i class="bx bx-refresh"></i> reload</a>
            </div>
        </div>
    </div>
</div>


@include(adminTheme().'alerts')
<form action="{{route('admin.postsAction',['update',$post->id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Post Edit</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label">Post Name </label>
                            <input type="text" class="form-control titleForSlug {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Post Name" value="{{$post->name?:old('name')}}" required="" />
                            @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="mb-3 input-group">
                            <label class="slugEdit" style="cursor: pointer;width: 30px;padding: 4px 8px;margin:0;background: #c6c9d5;">
                                <span>
                                    @if($post->auto_slug)
                                    Custom Slug <i class="bx bx-edit"></i>
                                    @else    
                                    <i class="bx bx-shuffle"></i>
                                    @endif
                                </span>
                            </label>
                            <input type="text" class="slugEditData form-control form-control-sm {{$errors->has('slug')?'error':''}}"
                                @if($post->auto_slug) 
                                    name="slug"
                                @else
                                disabled
                                @endif
                            placeholder="Post Slug" value="{{$post->slug?:old('slug')}}" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Short Description </label>
                            <textarea name="short_description" class="form-control {{$errors->has('short_description')?'error':''}}" placeholder="Enter Short Description">{!!$post->short_description!!}</textarea>
                            @if ($errors->has('short_description'))
                            <div class="invalid-feedback">{{ $errors->first('short_description') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description </label>
                            <textarea name="description" class="{{$errors->has('description')?'error':''}} tinyEditor" placeholder="Enter Description">{!!$post->description!!}</textarea>
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
                            <input type="text" class="form-control {{$errors->has('seo_title')?'error':''}}" name="seo_title" placeholder="Enter SEO Meta Title" value="{{$post->seo_title?:old('seo_title')}}" />
                            @if ($errors->has('seo_title'))
                            <div class="invalid-feedback">{{ $errors->first('seo_title') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SEO Meta Description </label>
                            <textarea name="seo_description" class="form-control {{$errors->has('seo_description')?'error':''}}" placeholder="Enter SEO Meta Description">{!!$post->seo_description!!}</textarea>
                            @if ($errors->has('seo_description'))
                            <div class="invalid-feedback">{{ $errors->first('seo_description') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SEO Meta Keyword </label>
                            <textarea name="seo_keyword" class="form-control {{$errors->has('seo_keyword')?'error':''}}" placeholder="Enter SEO Meta Keyword">{!!$post->seo_keyword!!}</textarea>
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
                    <h4 class="card-title">Post Images</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Post Image</label>
                            <input type="file" name="image" accept="image/*" data-name="PostImage" class="form-control uploadImage {{$errors->has('image')?'error':''}}" />
                            @if ($errors->has('image'))
                            <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <img src="{{asset($post->image())}}" class="PostImage" style="max-width: 100px;" />
                            @if($post->imageFile)
                            <a href="{{route('admin.mediesDelete',$post->imageFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Post Banner</label>
                            <input type="file" name="banner" accept="image/*" data-name="PostIBanner" class="form-control uploadImage {{$errors->has('banner')?'error':''}}" />
                            @if ($errors->has('banner'))
                            <div class="invalid-feedback">{{ $errors->first('banner') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <img src="{{asset($post->banner())}}" class="PostIBanner" style="max-width: 200px;" />
                            @if($post->bannerFile)
                            <a href="{{route('admin.mediesDelete',$post->bannerFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Post Category</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if ($errors->has('categoryid*'))
                        <div class="invalid-feedback">The Category Must Be a Number</div>
                        @endif
                        <div class="catagorydiv">
                            <ul >
                                @foreach($categories as $ctg)
                                <li>
                                    <label>
                                        <input type="checkbox" class="form-check-input" name="categoryid[]" value="{{$ctg->id}}"

                                        @foreach($post->postCtgs as $postctg)
                                        {{$postctg->reff_id==$ctg->id?'checked':''}} 
                                        @endforeach
                                        
                                        >
                                        {{$ctg->name}}
                                    </label>
                                    @if($ctg->subCtgs->count() >0) @include(adminTheme().'posts.includes.postsEditSubctg',['subcategories' => $ctg->subCtgs,'i'=>1]) @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Post Tags</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <label class="form-label">Tag Comma (,) multiple</label>
                        <textarea id="hero-demo" name="tagskey">{!!$post->tags?:old('tagskey')!!}</textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Post Action</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label">Status</label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="status" {{$post->status=='active'?'checked':''}} >Active
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label">Featured</label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="featured" {{$post->featured?'checked':''}} >Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Published Date</label>
                            <input type="date" class="form-control form-control-sm" name="created_at" value="{{$post->created_at->format('Y-m-d')}}">
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

@endsection 
@push('js')
    <script>
       $(function() {
            $('#hero-demo').tagEditor({
                placeholder: 'Enter tags ...',
            });
        });
    </script>
@endpush
