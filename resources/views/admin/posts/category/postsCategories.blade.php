@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Post Categories')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush 

@section('contents')

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Categories List</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.postsCategoriesAction','create')}}" ><i class="bx bx-plus"></i> Add Category </a>
                <a class="dropdown-item" href="{{route('admin.postsCategories')}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>

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
                        <thead class="table-light" >
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
                            @forelse($categories as $i=>$category)
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
                                    <span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">Active </span>
                                    @elseif($category->status=='inactive')
                                    <span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Inactive </span>
                                    @else
                                    <span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Draft </span>
                                    @endif

                                    @if($category->featured==true)
                                    <span><i class="bx bx-star" style="color: #faca51;"></i></span>
                                    @endif
                                    <span style="color: #ccc;"><i class="bx bx-calendar" style="color: #1ab394;"></i> {{$category->created_at->format('d-m-Y')}}</span>
                                    <span style="color: #ccc;">
                                        <i class="bx bx-user" style="color: #1ab394;"></i>
                                        {{Str::limit($category->user?$category->user->name:'No Author',15)}}
                                    </span>
                                </td>
                                <td>
                                    @if($category->parent)
                                    <span>{{$category->parent->name}}</span>
                                    @else
                                    <span class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3">PARENT CTG</span>
                                    @endif
                                </td>
                                <td style="padding: 5px; text-align: center;">
                                    <img src="{{asset($category->image())}}" style="max-width: 80px; max-height: 50px;" />
                                </td>
                                <td style="text-align:center;padding: 3px;">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary split-bg-primary" data-bs-toggle="dropdown">	
                                            <span class="bx bx-dots-vertical"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                            <a class="dropdown-item" href="{{route('admin.postsCategoriesAction',['edit',$category->id])}}"><i class="bx bxs-edit"></i> Edit </a>
                                            <a class="dropdown-item text-danger" href="{{route('admin.postsCategoriesAction',['delete',$category->id])}}" onclick="return confirm('Are you sure you want to delete this category?')">
                                                <i class="bx bxs-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">No Category Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{$categories->links('pagination')}}
                </div>
            </form>
        </div>
    </div>
</div>

@endsection @push('js') @endpush
