@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Category Edit')}}</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Category Edit</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.productsCategories')}}" class="btn btn-outline-primary mr-2" >BACK</a>
            <a href="{{route('admin.productsCategoriesAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Category</a>
            <a href="{{route('admin.productsCategoriesAction',['edit',$category->id])}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>


@include(adminTheme().'alerts')

<form action="{{route('admin.productsCategoriesAction',['update',$category->id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Category Edit</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Category Name(*) </label>
                            <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Category Name" value="{{$category->name?:old('name')}}" required="" />
                            @if ($errors->has('name'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Parent Category</label>
                            <select name="parent_id" class="form-control">
                                <option value="">Select Category</option>

                                @foreach($parents as $parent) @if($parent->id==$category->id) @else
                                <option value="{{$parent->id}}" {{$parent->id==$category->parent_id?'selected':''}}>{{$parent->name}}</option>
                                @if($parent->subctgs->count() > 0) @include('admin.products.includes.editSubcategory',['subcategories' =>$parent->subctgs, 'i'=>1]) @endif @endif @endforeach
                            </select>
                            @if ($errors->has('parent_id'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('parent_id') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="description">Description </label>
                            <textarea name="description" class="{{$errors->has('description')?'error':''}} tinyEditor" placeholder="Enter Description">{!!$category->description!!}</textarea>
                            @if ($errors->has('description'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('description') }}</p>
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
                        <div class="form-group">
                            <label for="seo_title">SEO Meta Title</label>
                            <input type="text" class="form-control {{$errors->has('seo_title')?'error':''}}" name="seo_title" placeholder="Enter SEO Meta Title" value="{{$category->seo_title?:old('seo_title')}}" />
                            @if ($errors->has('seo_title'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_title') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="seo_description">SEO Meta Description </label>
                            <textarea name="seo_description" class="form-control {{$errors->has('seo_description')?'error':''}}" placeholder="Enter SEO Meta Description">{!!$category->seo_description!!}</textarea>
                            @if ($errors->has('seo_description'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_description') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="seo_keyword">SEO Meta Keyword </label>
                            <textarea name="seo_keyword" class="form-control {{$errors->has('seo_keyword')?'error':''}}" placeholder="Enter SEO Meta Keyword">{!!$category->seo_keyword!!}</textarea>
                            @if ($errors->has('seo_keyword'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_keyword') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Category Images</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="image">Category Image</label>
                            <input type="file" name="image" class="form-control {{$errors->has('image')?'error':''}}" />
                            @if ($errors->has('image'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('image') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <img src="{{asset($category->image())}}" style="max-width: 100px;" />
                            @if($category->imageFile)
                            <a href="{{route('admin.mediesDelete',$category->imageFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="banner">Category Banner</label>
                            <input type="file" name="banner" class="form-control {{$errors->has('banner')?'error':''}}" />
                            @if ($errors->has('banner'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('banner') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <img src="{{asset($category->banner())}}" style="max-width: 200px;" />
                            @if($category->bannerFile)
                            <a href="{{route('admin.mediesDelete',$category->bannerFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Category Action</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="status">Category Status</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="status" name="status" {{$category->status=='active'?'checked':''}}/>
                                    <label class="custom-control-label" for="status">Active</label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label for="fetured">Featured</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="featured" name="featured" {{$category->featured?'checked':''}}/>
                                    <label class="custom-control-label" for="featured">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Published Date</label>
                            <input type="date" class="form-control form-control-sm" name="created_at" value="{{$category->created_at->format('Y-m-d')}}">
                            @if ($errors->has('created_at'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('created_at') }}</p>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection 
@push('js')

@endpush
