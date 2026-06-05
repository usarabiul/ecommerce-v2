@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Products List')}}</title>
@endsection 

@push('css')
<style type="text/css"></style>
@endpush 

@section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Products List</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.productsAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Product</a>
            <a href="{{route('admin.products')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>


@include(adminTheme().'alerts')


<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Products List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{route('admin.products')}}">
                <div class="row">
                    <div class="col-md-4 mb-1">
                        <div class="input-group">
                            <input type="date" name="startDate" value="{{request()->startDate?Carbon\Carbon::parse(request()->startDate)->format('Y-m-d') :''}}" class="form-control {{$errors->has('startDate')?'error':''}}" />
                            <input type="date" value="{{request()->endDate?Carbon\Carbon::parse(request()->endDate)->format('Y-m-d') :''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}" />
                        </div>
                    </div>
                    <div class="col-md-2 mb-1">
                        <select class="form-control" name="category">
                            <option value="">Select Category</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-1">
                        <select class="form-control" name="brand">
                            <option value="">Select Brand</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-1"> 
                        <div class="input-group">
                            <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Product Name, Category Name" class="form-control {{$errors->has('search')?'error':''}}" />
                            <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <form action="{{route('admin.products')}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-1">
                            <select class="form-control form-control-sm rounded-0" name="action" required="">
                                <option value="">Select Action</option>
                                <option value="1">Products Active</option>
                                <option value="2">Products InActive</option>
                                <option value="3">Products Feature</option>
                                <option value="4">Products Unfeature</option>
                                <option value="5">Products Delete</option>
                            </select>
                            <button class="btn btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                        <ul class="statuslist">
                            <li><a href="{{route('admin.products')}}">All ({{$totals->total}})</a></li>
                            <li><a href="{{route('admin.products',['status'=>'active'])}}">Active ({{$totals->active}})</a></li>
                            <li><a href="{{route('admin.products',['status'=>'inactive'])}}">Inactive ({{$totals->inactive}})</a></li>
                            <li><a href="{{route('admin.products',['status'=>'featured'])}}">Featured ({{$totals->featured}})</a></li>
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
                                <th style="min-width: 350px;">Product Name</th>
                                <th style="min-width: 80px;width: 80px;text-align:center;">Image</th>
                                <th style="min-width: 200px;">Catagory/Brand</th>
                                <th style="min-width: 80px;width:80px;">Status</th>
                                <th style="min-width: 60px;width:60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach($products as $i=>$product)
                            <tr>
                                <td>
                                    <div class="custom-control custom-control-inline custom-control-nolabel custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox" name="checkid[]" value="{{$product->id}}" id="ckb1">  <label class="custom-control-label" for="ckb1">ID </label>
                                    </div>
                                    {{$products->currentpage()==1?$i+1:$i+($products->perpage()*($products->currentpage() - 1))+1}}
                                    <br>
                                    <a href="{{route('admin.productsReview',['product_id'=>$product->id])}}" style="color: #ccc;"><i class="fa fa-star" style="color: #ffc107;"></i> {{number_format($product->totalReviewer())}}</a>
                                </td>
                                <td>
                                    <span><a href="{{route('productView',$product->slug?:'no-slug')}}" target="_blank">{{$product->name}}</a></span>
                                    <br/>
                                    @if($product->featured==true)
                                    <span><i class="fa fa-bolt" style="color: #ea6759;"></i></span>
                                    @endif
                                    <span style="color: #ccc;">{{priceFullFormat($product->final_price)}}</span>
                                    
                                    <span style="color: #ccc;"><i class="fa fa-calendar" style="color: #1ab394;"></i> {{$product->created_at->format('d-m-Y')}}</span>
                                    
                                    <span style="color: #ccc;">
                                        <i class="fa fa-user" style="color: #1ab394;"></i>
                                        {{Str::limit($product->user?$product->user->name:'No Author',15)}}
                                    </span>

                                </td>
                                <td style="padding: 5px; text-align: center;">
                                    <img src="{{asset($product->image())}}" style="max-width: 70px; max-height: 50px;" />
                                </td>
                                <td>
                                    {{ $product->productCategories->pluck('name')->implode(' - ') }}

                                    @if($product->brand)
                                    <br>
                                    <span style="color: #ccc;"><b style="color: #1ab394;">Brand:</b> {{$product->brand->name}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->status=='active')
                                    <span class="badge badge-success">Active </span>
                                    @elseif($product->status=='inactive')
                                    <span class="badge badge-danger">Inactive </span>
                                    @else
                                    <span class="badge badge-danger">Draft </span>
                                    @endif 
                                    
                                </td>
                                <td style="text-align:center;">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success btn-ico" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-arrow"></div>
                                            <a href="{{route('admin.productsAction',['edit',$product->id])}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit </a>
                                            <a href="{{route('admin.productsAction',['view',$product->id])}}" class="dropdown-item"><i class="fa fa-eye"></i> View </a>
                                            <a href="{{route('admin.productsAction',['delete',$product->id])}}" onclick="return confirm('Are You Want To Delete')" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{$products->links('pagination')}}
                </div>
            </form>
        </div>
    </div>
</div>


@endsection @push('js') @endpush
