@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Attribute Edit')}}</title>
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
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Attribute Edit</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.productsAttributes')}}" class="btn btn-outline-success mr-2">BACK</a>
            <a href="{{route('admin.productsAttributesAction',['edit',$attribute->id])}}" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

@include(adminTheme().'alerts')

<div class="row">
    <div class="col-md-4">
        <form action="{{route('admin.productsAttributesAction',['update',$attribute->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Attribute Edit</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Attribute Name(*) </label>
                            <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Attribute Name" value="{{$attribute->name?:old('name')}}" required="" />
                            @if ($errors->has('name'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        <!-- <div class="form-group">
				<label for="description">Description </label>
				<textarea name="description" class="form-control {{$errors->has('description')?'error':''}}" placeholder="Enter Description">{!!$attribute->description!!}</textarea>
				@if ($errors->has('description'))
				<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('description') }}</p>
				@endif
				</div> -->
                        <div class="form-group">
                            <label>Attribute Type <small>(How to show Attribute)</small></label>
                            <select class="form-control" name="type">
                                <option value="1" {{$attribute->view==1?'selected':''}} >Text</option>
                                <option value="2" {{$attribute->view==2?'selected':''}} >Color</option>
                                <option value="3" {{$attribute->view==3?'selected':''}} >Image</option>
                            </select>
                            @if ($errors->has('type'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('type') }}</p>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="status">Attribute Status</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="status" name="status" {{$attribute->status=='active'?'checked':''}}/>
                                    <label class="custom-control-label" for="status">Active</label>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="featured">Variation Status</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="featured" name="featured" {{$attribute->featured?'checked':''}}/>
                                    <label class="custom-control-label" for="featured">Active</label>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="status">Date</label>
                                <input type="date" class="form-control" name="created_at" value="{{$attribute->created_at->format('Y-m-d')}}" required="" />
                                @if ($errors->has('created_at'))
                                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('created_at') }}</p>
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">Attribute Items</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- <div class="row">
							<div class="col-md-4">
								
							</div>
							<div class="col-md-8">
								<form action="{{route('admin.productsAttributesAction',['edit',$attribute->id])}}">
								<div class="input-group">
									<input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Attribute Item Name" class="form-control {{$errors->has('search')?'error':''}}">
									<button type="submit" class="btn btn-success rounded-0">Search</button>
								</div>
								</form>
							</div>
						</div>
						
						
						<hr> -->
                    <form action="{{route('admin.productsAttributesAction',['items-delete',$attribute->id])}}">
                        <div class="row">
                            <div class="col-md-4">
                                @if($items->count() > 0)
                                <div class="input-group mb-1">
                                    <select class="form-control form-control-sm rounded-0" name="action" required="">
                                        <option value="">Select Action</option>
                                        <option value="5">Delete</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <a href="javascript:void(0)" class="btn btn-md btn-success" data-toggle="modal" data-target="#AddItem"><i class="fa fa-plus"></i> Add Item</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="min-width: 100px; width: 100px;">
                                            <label style="cursor: pointer; margin-bottom: 0;"> <input class="checkbox" type="checkbox" class="form-control" id="checkall" /> All <span class="checkCounter"></span> </label>
                                        </th>
                                        <th>Item Name</th>
                                        <th style="min-width: 100px; width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $i=>$item)
                                    <tr>
                                        <td>
                                            <input class="checkbox" type="checkbox" name="checkid[]" value="{{$item->id}}" />
                                            {{$i+1}}
                                        </td>
                                        <td>
                                            <span>
                                                {{$item->name}}
                                            </span>
                                            @if($attribute->view==2)
                                            <span style="background:{{$item->icon?:'#000'}};height: 10px;width: 50px;display: inline-block;"></span>
                                            @elseif($attribute->view==3)
                                            <img src="{{asset($item->image())}}" style="max-width: 50px;" />
                                            @endif
                                        </td>
                                        <td class="center">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editItem_{{$item->id}}"> <i class="fa fa-edit"></i> Edit</a>
                                        </td>
                                    </tr>
                                    @endforeach @if($items->count()==0)
                                    <tr>
                                        <td colspan="3" style="text-align: center;">
                                            <span>No Item</span>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{$items->links('pagination')}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-left" id="AddItem" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route('admin.productsAttributesItemAction',['create',$attribute->id])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Add Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_name">Item Name(*) </label>
                        <input type="text" class="form-control {{$errors->has('item_name')?'error':''}}" name="item_name" placeholder="Enter Item Name" value="{{old('item_name')}}" required="" />
                        @if ($errors->has('item_name'))
                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('item_name') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        @if($attribute->view==2)
                        <input type="color" name="color" value="{{$attribute->icon?:old('color')}}" />
                        @elseif($attribute->view==3)
                        <input type="file" name="image" class="form-control" />
                        @endif @if ($errors->has('color'))
                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('color') }}</p>
                        @endif @if ($errors->has('image'))
                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('image') }}</p>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($items as $i=>$item)
<!-- Modal -->
<div class="modal fade text-left" id="editItem_{{$item->id}}" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route('admin.productsAttributesItemAction',['update',$item->id])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Update Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_name">Item Name(*) </label>
                        <input type="text" class="form-control {{$errors->has('item_name')?'error':''}}" name="item_name" placeholder="Enter Item Name" value="{{$item->name?:old('item_name')}}" required="" />
                        @if ($errors->has('item_name'))
                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('item_name') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        @if($attribute->view==2)
                        <input type="color" name="color" value="{{$item->icon?:old('color')}}" />
                        @elseif($attribute->view==3)
                        <input type="file" name="image" class="form-control" />
                        <img src="{{asset($item->image())}}" style="margin: 10px 0; max-height: 50px; max-width: 100%;" />
                        @endif @if ($errors->has('color'))
                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('color') }}</p>
                        @endif @if ($errors->has('image'))
                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('image') }}</p>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach 

@endsection 
@push('js') 
@endpush
