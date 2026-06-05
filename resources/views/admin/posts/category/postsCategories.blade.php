@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Post Categories')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Categories List</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.postsCategoriesAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Category</a>
            <a href="{{route('admin.postsCategories')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

@include(adminTheme().'alerts')

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Categories List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{route('admin.postsCategories')}}">
                <div class="row">
                    <div class="col-md-12 mb-0">
                        <div class="input-group">
                            <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Category Name" class="form-control {{$errors->has('search')?'error':''}}" />
                            <button type="submit" class="btn btn-success rounded-0">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr />
            <form action="{{route('admin.postsCategories')}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-1">
                            <select class="form-control form-control-sm rounded-0" name="action" required="">
                                <option value="">Select Action</option>
                                <option value="1">Active</option>
                                <option value="2">InActive</option>
                                <option value="3">Featured</option>
                                <option value="4">Un-featured</option>
                                <option value="5">Deleted</option>
                            </select>
                            <button class="btn btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" style="min-height:300px;" >
                    <table class="table table-hover">
                        <thead class="thead-light" >
                            <tr>
                                <th style="min-width: 100px;width:100px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox"  id="checkall" >  <label class="custom-control-label" for="checkall">All <span class="checkCounter"></span> </label>
                                    </div>
                                </th>
                                <th style="min-width: 300px;">Category Name</th>
                                <th style="min-width: 200px;">Parent CTG</th>
                                <th style="max-width: 80px;width:80px;">Image</th>
                                <th style="min-width: 60px; width: 60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $i=>$category)
                            <tr>
                                <td>
                                    <div class="custom-control custom-control-inline custom-control-nolabel custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox" name="checkid[]" value="{{$category->id}}" id="ckb1">  <label class="custom-control-label" for="ckb1">ID </label>
                                    </div>
                                    {{$categories->currentpage()==1?$i+1:$i+($categories->perpage()*($categories->currentpage() - 1))+1}}
                                </td>
                                <td>
                                    <a href="{{route('blogCategory',$category->slug?:'no-title')}}" target="_blank">{{$category->name}}</a><br />
                                    @if($category->status=='active')
                                    <span class="badge badge-success">Active </span>
                                    @elseif($category->status=='inactive')
                                    <span class="badge badge-danger">Inactive </span>
                                    @else
                                    <span class="badge badge-danger">Draft </span>
                                    @endif

                                    @if($category->featured==true)
                                    <span><i class="fa fa-star" style="color: #faca51;"></i></span>
                                    @endif
                                    <span style="color: #ccc;"><i class="fa fa-calendar" style="color: #1ab394;"></i> {{$category->created_at->format('d-m-Y')}}</span>
                                    <span style="color: #ccc;">
                                        <i class="fa fa-user" style="color: #1ab394;"></i>
                                        {{Str::limit($category->user?$category->user->name:'No Author',15)}}
                                    </span>
                                </td>
                                <td>
                                    @if($category->parent)
                                    <span>{{$category->parent->name}}</span>
                                    @else
                                    <span class="badge badge-primary">PARENT CTG</span>
                                    @endif
                                </td>
                                <td style="padding: 5px; text-align: center;">
                                    <img src="{{asset($category->image())}}" style="max-width: 80px; max-height: 50px;" />
                                </td>
                                <td style="text-align:center;">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success btn-ico" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-arrow"></div>
                                            <a href="{{route('admin.postsCategoriesAction',['edit',$category->id])}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit </a>
                                            <a href="{{route('admin.postsCategoriesAction',['delete',$category->id])}}" onclick="return confirm('Are You Want To Delete')" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$categories->links('pagination')}}
                </div>
            </form>
        </div>
    </div>
</div>

@endsection @push('js') @endpush
