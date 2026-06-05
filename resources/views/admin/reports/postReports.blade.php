@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Post Reports')}}</title>
@endsection @push('css')

<style type="text/css">
    .dataTables_wrapper table {
        display: block;
        width: 100%;
        min-height: 0.01%;
        overflow-x: auto;
    }
    td.dataTables_empty {
        width: 1%;
    }
</style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Posts Reports</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Posts Report</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.reportsAll','posts')}}"> <i class="fa-solid fa-rotate"></i> </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
                @include(adminTheme().'alerts')

                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div id="accordion">
                                <div
                                    class="card-header collapsed"
                                    data-toggle="collapse"
                                    data-target="#collapseTwo"
                                    aria-expanded="false"
                                    aria-controls="collapseTwo"
                                    id="headingTwo"
                                    style="background: #f5f7fa; padding: 10px; cursor: pointer; border: 1px solid #00b5b8;"
                                >
                                    Search click Here..
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" style="border: 1px solid #00b5b8; border-top: 0;">
                                    <div class="card-body">
                                        <form action="{{route('admin.reportsAll','posts')}}">
                                            <div class="row">
                                                <div class="col-md-3 mb-0">
                                                    <div class="form-group">
                                                        <select class="form-control" name="category">
                                                            <option value="">Select Category</option>
                                                            @foreach($categories as $ctg)
                                                            <option value="{{$ctg->id}}" {{request()->category==$ctg->id?'selected':''}}>{{$ctg->name}}</option>
                                                            @foreach($ctg->subctgs as $ctg)
                                                            <option value="{{$ctg->id}}" {{request()->category==$ctg->id?'selected':''}}>- {{$ctg->name}}</option>
                                                            @endforeach @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3 mb-0">
                                                    <div class="form-group">
                                                        <select class="form-control" name="status">
                                                            <option value="">Select Status</option>
                                                            <option value="active" {{request()->status=='active'?'selected':''}}>Active</option>
                                                            <option value="inactive" {{request()->status=='inactive'?'selected':''}}>Inactive</option>
                                                            <option value="topView" {{request()->status=='topView'?'selected':''}}>Top View</option>
                                                            <option value="topComment" {{request()->status=='topComment'?'selected':''}}>Top Comments</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-0">
                                                    <div class="input-group">
                                                        <input type="date" name="startDate" value="{{request()->startDate?Carbon\Carbon::parse(request()->startDate)->format('Y-m-d') :''}}" class="form-control {{$errors->has('startDate')?'error':''}}" />
                                                        <input type="date" value="{{request()->endDate?Carbon\Carbon::parse(request()->endDate)->format('Y-m-d') :''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-0">
                                                    <div class="input-group">
                                                        <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Product Name" class="form-control {{$errors->has('search')?'error':''}}" />
                                                        <button type="submit" class="btn btn-success rounded-0">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3; padding: 1rem;">
                        <h4 class="card-title" style="padding: 5px;">Posts Reports</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <table class="table table-striped table-bordered dataex-html5-export">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>view</th>
                                        <th>comments</th>
                                        <th>Category</th>
                                        <th>Author</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($posts) 
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>{{$post->id}}</td>
                                        <td>{{$post->name}}</td>
                                        <td>{{$post->status}}</td>
                                        <td>{{$post->view}}</td>
                                        <td>{{$post->postComments->count()}}</td>
                                        <td>@foreach($post->postCategories as $i=>$ctg){{$i==0?'':'-'}} {{$ctg->name}}@endforeach</td>
                                        <td>{{$post->user?$post->user->name:''}}</td>
                                        <td>{{$post->created_at->format('d-m-Y')}}</td>
                                    </tr>
                                    @endforeach 
                                    @endif
                                </tbody>
                                <tfoot>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>view</th>
                                    <th>comments</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Date</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection 

@push('js')


@endpush
