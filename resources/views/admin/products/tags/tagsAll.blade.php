@extends(general()->adminTheme.'.layouts.app')
@section('title')
<title>{{websiteTitle('Tags List')}}</title>
@endsection

@push('css')
<style type="text/css">
    ul.tagListUl {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    ul.tagListUl li {
        display: inline-block;
        background: #e3e3e3;
        padding: 2px 10px;
        border-radius: 5px;
        margin: 3px 1px;
    }

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
                    <li class="breadcrumb-item active" aria-current="page">Tags List</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <button type="button" class="btn btn-outline-success mr-2"  data-toggle="modal" data-target="#AddTag" ><i class="fas fa-plus"></i> Add Tag</button>
            <a href="{{route('admin.productsTags')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>


@include(adminTheme().'alerts')

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Tags List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{route('admin.productsTags')}}">
                <div class="row">
                    <div class="col-md-12 mb-0">
                        <div class="input-group">
                            <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Tag Name" class="form-control {{$errors->has('search')?'error':''}}" />
                            <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <form action="{{route('admin.productsTags')}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-1">
                            <select class="form-control form-control-sm rounded-0" name="action" required="">
                                <option value="">Select Action</option>
                                <option value="1">Tags Active</option>
                                <option value="2">Tags InActive</option>
                                <option value="5">Tags Delete</option>
                            </select>
                            <button class="btn btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <ul class="statuslist">
                            <li><a href="{{route('admin.productsTags')}}">All ({{$totals->total}})</a></li>
                            <li><a href="{{route('admin.productsTags',['status'=>'active'])}}">Active ({{$totals->active}})</a></li>
                            <li><a href="{{route('admin.productsTags',['status'=>'inactive'])}}">Inactive ({{$totals->inactive}})</a></li>
                        </ul>
                    </div>
                </div>
                <div>
                    <ul class="tagListUl">
                        @foreach($tags as $i=>$tag)
                        <li>
                            <div>
                                <input class="checkbox" type="checkbox" name="checkid[]" value="{{$tag->id}}" />
                                <span>
                                    @if($tag->status=='active')
                                    <span><i class="fa fa-check" style="color: #1ab394;"></i></span>
                                    @else
                                    <span><i class="fa fa-times" style="color: #ed5565;"></i></span>
                                    @endif  
                                    {{$tag->name}}
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#EditTag_{{$tag->id}}" ><i class="fa fa-edit"></i></a>
                                </span>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade text-left" id="EditTag_{{$tag->id}}" tabindex="-1" >
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{route('admin.productsTagsAction',['update',$tag->id])}}" method="post">
                                            @csrf
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel1">Edit Tag</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times; </span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="name">Tag Name(*) </label>
                                                    <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Tag Name" value="{{$tag->name?:old('name')}}" required="" />
                                                    @if ($errors->has('name'))
                                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                                                    @endif
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="status">Tag Status</label>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="status_{{$tag->id}}" name="status" {{$tag->status=='active'?'checked':''}}/>
                                                        <label class="custom-control-label" for="status_{{$tag->id}}">Active</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Update Tag</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    {{$tags->links('pagination')}}
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-left" id="AddTag" tabindex="-1" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route('admin.productsTagsAction','create')}}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Add Tag</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="controls">
                            <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Tag" value="" required="" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection @push('js') @endpush
