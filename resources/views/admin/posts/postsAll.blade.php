@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Posts List')}}</title>
@endsection 

@push('css')
<style type="text/css">

</style>
@endpush 

@section('contents')


<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Posts List</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.postsAction','create')}}" ><i class="bx bx-plus"></i> Add Post </a>
                <a class="dropdown-item" href="{{route('admin.posts')}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>



@include(adminTheme().'alerts')

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Posts List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{route('admin.posts')}}">
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group">
                            <input type="date" name="startDate" value="{{request()->startDate?Carbon\Carbon::parse(request()->startDate)->format('Y-m-d') :''}}" class="form-control {{$errors->has('startDate')?'error':''}}" />
                            <input type="date" value="{{request()->endDate?Carbon\Carbon::parse(request()->endDate)->format('Y-m-d') :''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}" />
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group">
                            <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Post Name, Category Name" class="form-control {{$errors->has('search')?'error':''}}" />
                            <button type="submit" class="btn btn-success rounded-0">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <form action="{{route('admin.posts')}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-1">
                            <select class="form-control form-control-sm rounded-0" name="action" required="">
                                <option value="">Select Action</option>
                                <option value="1">Active</option>
                                <option value="2">InActive</option>
                                <option value="3">Feature</option>
                                <option value="4">Un-feature</option>
                                <option value="5">Delete</option>
                            </select>
                            <button class="btn btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <ul class="statuslist">
                            <li><a href="{{route('admin.posts')}}">All ({{$total->total}})</a></li>
                            <li><a href="{{route('admin.posts',['status'=>'active'])}}">Active ({{$total->active}})</a></li>
                            <li><a href="{{route('admin.posts',['status'=>'inactive'])}}">Inactive ({{$total->inactive}})</a></li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive" style="min-height:300px;">
                    <table class="table mb-0  table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 100px;width:100px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox"  id="checkall" >  <label class="custom-control-label" for="checkall">All <span class="checkCounter"></span> </label>
                                    </div>
                                </th>
                                <th style="min-width: 300px;">Post Name</th>
                                <th style="min-width: 80px;width:80px;">Image</th>
                                <th style="min-width: 200px;">Category</th>
                                <th style="min-width: 60px;width:60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $i=>$post)
                            <tr>
                                <td>
                                    <div class="custom-control custom-control-inline custom-control-nolabel custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox" name="checkid[]" value="{{$post->id}}" id="ckb1">  <label class="custom-control-label" for="ckb1">ID </label>
                                    </div>
                                    {{ $posts->firstItem() + $i }}
                                </td>
                                <td>
                                    <span><a href="{{route('blogView',$post->slug?:'no-title')}}" target="_blank">{{$post->name}}</a></span><br />
                                    <span><i class="bx bx-show" style="color: #1ab394;"></i> 0</span>
                                    @if($post->status=='active')
                                    <span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">Active </span>
                                    @elseif($post->status=='inactive')
                                    <span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Inactive </span>
                                    @else
                                    <span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Draft </span>
                                    @endif

                                    @if($post->featured==true)
                                    <span><i class="bx bx-star" style="color: #1ab394;"></i></span>
                                    @endif

                                    <span><i class="bx bx-calendar" style="color: #1ab394;"></i> {{$post->created_at->format('d-m-Y')}}</span>

                                    <span>
                                        <i class="bx bx-comment" style="color: #1ab394;"></i> ({{$post->postComments->where('status','<>','temp')->count()}})
                                    </span>
                                </td>
                                <td style="padding: 5px;">
                                    <img src="{{asset($post->image())}}" style="max-width: 80px; max-height: 50px;" />
                                </td>
                                <td>
                                    @foreach($post->postCategories as $i=>$ctg) {{$i==0?'':'-'}} {{$ctg->name}} @endforeach
                                </td>
                                
                                <td style="text-align:center;padding: 3px;">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary split-bg-primary" data-bs-toggle="dropdown">	
                                            <span class="bx bx-dots-vertical"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                            <a class="dropdown-item" href="{{route('blogView',$post->slug?:'no-slug')}}" target="_blank"><i class="bx bxs-show"></i> View </a>
                                            <a class="dropdown-item" href="{{route('admin.postsComments',$post->id)}}"><i class="bx bxs-message"></i> Comments </a>
                                            <a class="dropdown-item" href="{{route('admin.postsAction',['edit',$post->id])}}"><i class="bx bxs-edit"></i> Edit </a>
                                            <a class="dropdown-item text-danger" href="{{route('admin.postsAction',['delete',$post->id])}}" onclick="return confirm('Are you sure you want to delete this post?')">
                                                <i class="bx bxs-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">No Posts Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{$posts->links('pagination')}}
                </div>
            </form>
        </div>
    </div>
</div>


@endsection 

@push('js')
<script type="text/javascript">
    
</script>
@endpush
