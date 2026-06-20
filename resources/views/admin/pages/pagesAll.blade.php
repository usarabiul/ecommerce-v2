@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Pages List')}}</title>
@endsection 
@push('css')
<style type="text/css"></style>
@endpush 
@section('contents')

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pages List</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.pagesAction','create')}}" ><i class="bx bx-plus"></i> Add Page </a>
                <a class="dropdown-item" href="{{route('admin.pages')}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>


@include(adminTheme().'alerts')

    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">Pages List</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <form action="{{route('admin.pages')}}">
                            <div class="input-group mb-3">
                                <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Page Name" class="form-control {{$errors->has('search')?'error':''}}" />
                                <button type="submit" class="btn btn-success rounded-0">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                        <ul class="statuslist mt-1 mb-3">
                            <li><a href="{{route('admin.pages')}}">All ({{$total->total}})</a></li>
                            <li><a href="{{route('admin.pages',['status'=>'active'])}}">Active ({{$total->active}})</a></li>
                            <li><a href="{{route('admin.pages',['status'=>'inactive'])}}">Inactive ({{$total->inactive}})</a></li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive" style="min-height:300px;">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="min-width: 60px;width: 60px;text-align: center;">SL</th>
                                <th style="min-width: 300px;">Name</th>
                                <th style="min-width: 70px; width: 70px;text-align: center;">Image</th>
                                <th style="min-width: 100px; width: 100px;">Status</th>
                                <th style="min-width: 60px; width: 60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $i=>$page)
                            <tr>
                                <td class="text-center" ><strong class="text-black">{{ $pages->firstItem() + $i }}</strong></td>
                                <td>
                                    <span>
                                        {{$page->name}}
                                        @if($page->template)
                                        <span style="color: #ccc;">({{$page->template}})</span>
                                        @endif
                                        </span>
                                        <br />
                                    @if($page->featured==true)
                                    <span><i class="bx bx-star" style="color: #faca51;"></i></span>
                                    @endif

                                    <span style="color: #ccc;"><i class="bx bx-calendar" style="color: #1ab394;"></i> {{$page->created_at->format('d-m-Y')}}</span>
                                    <span style="color: #ccc;">
                                        <i class="bx bx-user" style="color: #1ab394;"></i>
                                        {{Str::limit($page->user?$page->user->name:'No Author',15)}}
                                    </span>
                                </td>
                                <td style="padding:0 5px;text-align: center;">
                                    <img src="{{asset($page->image())}}" style="max-width: 60px;max-height: 60px;" />
                                </td>
                                <td>
                                    @if($page->status=='active')
                                    <span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">Active </span>
                                    @elseif($page->status=='inactive')
                                    <span class="badge rounded-pill text-danger bg-light-success p-2 text-uppercase px-3">Inactive </span>
                                    @else
                                    <span class="badge rounded-pill text-danger bg-light-success p-2 text-uppercase px-3">Draft </span>
                                    @endif
                                </td>
                                <td style="text-align:center;padding: 3px;">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary split-bg-primary" data-bs-toggle="dropdown">	
                                            <span class="bx bx-dots-vertical"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                            <a class="dropdown-item" href="{{route('pageView',$page->slug?:'no-slug')}}" target="_blank"><i class="bx bxs-show"></i> View </a>
                                            <a class="dropdown-item" href="{{route('admin.pagesAction',['edit',$page->id])}}"><i class="bx bxs-edit"></i> Edit </a>
                                            <a class="dropdown-item text-danger" href="{{route('admin.pagesAction',['delete',$page->id])}}" onclick="return confirm('Are you sure you want to delete this page?')">
                                                <i class="bx bxs-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No Result Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{$pages->links('pagination')}}
                </div>
        </div>
        </div>
    </div>



@endsection @push('js') @endpush
