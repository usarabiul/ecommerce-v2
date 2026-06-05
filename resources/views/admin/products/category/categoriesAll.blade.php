@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Categories List')}}</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Categories List</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.productsCategoriesAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Category</a>
            <a href="{{route('admin.productsCategories')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
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
            <form action="{{route('admin.productsCategories')}}">
                <div class="row">
                    <div class="col-md-12 mb-0">
                        <div class="input-group">
                            <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Category Name" class="form-control {{$errors->has('search')?'error':''}}" />
                            <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <form action="{{route('admin.productsCategories')}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-1">
                            <select class="form-control form-control-sm rounded-0" name="action" required="">
                                <option value="">Select Action</option>
                                <option value="1">Category Active</option>
                                <option value="2">Category InActive</option>
                                <option value="3">Category Featured</option>
                                <option value="4">Category Unfeatured</option>
                                <option value="5">Category Delete</option>
                            </select>
                            <button class="btn btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <ul class="statuslist">
                            <li><a href="{{route('admin.productsCategories')}}">All ({{$totals->total}})</a></li>
                            <li><a href="{{route('admin.productsCategories',['status'=>'active'])}}">Active ({{$totals->active}})</a></li>
                            <li><a href="{{route('admin.productsCategories',['status'=>'inactive'])}}">Inactive ({{$totals->inactive}})</a></li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive" style="min-height:300px;">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="min-width: 100px;width:100px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox"  id="checkall" >  <label class="custom-control-label" for="checkall">All <span class="checkCounter"></span> </label>
                                    </div>
                                </th>
                                <th style="min-width: 300px;">Category Name</th>
                                <th style="min-width: 200px;width: 200px;">Parent Category</th>
                                <th style="max-width: 80px;width: 80px;text-align:center;">Image</th>
                                <th style="min-width: 60px;width: 60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $i=>$category)
                            <tr>
                                <td>
                                    <input class="checkbox" type="checkbox" name="checkid[]" value="{{$category->id}}" />
                                    {{$i+1}}
                                </td>
                                <td>
                                    <a href="{{route('productCategory',$category->slug?:Str::slug($category->name))}}" target="_blank">{{$category->name}}</a><br />
                                    
                                    @if($category->status=='active')
                                    <span class="badge badge-success">Active </span>
                                    @elseif($category->status=='inactive')
                                    <span class="badge badge-danger">Inactive </span>
                                    @else
                                    <span class="badge badge-danger">Draft </span>
                                    @endif 

                                    @if($category->featured==true)
                                    <span><i class="fa fa-star" style="color: #1ab394;"></i></span>
                                    @endif
                                    <span style="font-size: 14px;color: #ccc;">
                                        <i class="fa fa-user" style="color: #1ab394;"></i>
                                        {{$category->user?$category->user->name:'No Author'}}
                                    </span>
                                    <span style="font-size: 14px;color: #ccc;"><i class="fas fa-calendar" style="color: #1ab394;"></i> {{$category->created_at->format('d-m-Y')}}</span>
                                </td>
                                <td>
                                    @if($category->parent)
                                    <span>{{$category->parent->name}}</span>
                                    @else
                                    <span class="badge badge-primary">SELF PARENT</span>
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
                                            <a href="{{route('admin.productsCategoriesAction',['edit',$category->id])}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit </a>
                                            <a href="{{route('admin.productsCategoriesAction',['delete',$category->id])}}" onclick="return confirm('Are You Want To Delete')" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
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
