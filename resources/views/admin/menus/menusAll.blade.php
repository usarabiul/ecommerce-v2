@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Menus List')}}</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Menus List</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.menusAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Menu</a>
            <a href="{{route('admin.menus')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>


@include(adminTheme().'alerts')
<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Menus List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="min-width: 50px;width:50px;">S:L</th>
                            <th style="min-width: 300px;">Menu Name</th>
                            <th style="max-width: 150px;width:150px;">Location</th>
                            <th style="max-width: 100px;width:100px;">Items</th>
                            <th style="min-width: 60px;width: 60px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $i=>$menu)
                        <tr>
                            <td>
                                {{$i+1}}
                            </td>
                            <td>
                                <span>{{$menu->name}}</span><br />
                                @if($menu->status=='active')
                                <span class="badge badge-success">Active </span>
                                @elseif($menu->status=='inactive')
                                <span class="badge badge-danger">Inactive </span>
                                @else
                                <span class="badge badge-danger">Draft </span>
                                @endif
                                @if($menu->featured==true)
                                <span><i class="fa fa-star" style="color: #1ab394;"></i></span>
                                @endif
                                <span style="font-size: 10px;">
                                    <i class="fa fa-user" style="color: #1ab394;"></i>
                                    {{$menu->user?$menu->user->name:'No Author'}}
                                </span>
                            </td>
                            <td>
                                {{ucfirst($menu->location)}}
                            </td>
                            <td style="text-align: center;">
                                {{$menu->MenuItems->count()}} items
                            </td>
                            <td style="text-align:center;">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-success btn-ico" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-arrow"></div>
                                        <a href="{{route('admin.menusAction',['edit',$menu->id])}}" class="dropdown-item"><i class="fa fa-edit"></i> Manage </a>
                                        <a href="{{route('admin.menusAction',['delete',$menu->id])}}" onclick="return confirm('Are You Want To Delete')" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($menus->count()==0)
                            <tr>
                                <td colspan="5" class="text-center">No Result Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{$menus->links('pagination')}}
            </div>
        </div>
    </div>
</div>

@endsection @push('js') @endpush
