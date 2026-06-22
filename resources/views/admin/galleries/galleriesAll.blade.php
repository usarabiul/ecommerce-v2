@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Galleries List')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Galleries List</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.galleriesAction','create')}}" ><i class="bx bx-plus"></i> Add Gallery </a>
                <a class="dropdown-item" href="{{route('admin.galleries')}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>


@include(adminTheme().'alerts')
<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Galleries List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive" style="min-height:300px;">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 50px;width:50px;">S:L</th>
                            <th style="min-width: 300px;">Gallery Name</th>
                            <th style="max-width: 100px;text-align:center;">Items</th>
                            <th style="min-width: 60px;width:60px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($galleries as $i=>$gallery)
                        <tr>
                            <td>{{$galleries->currentpage()==1?$i+1:$i+($galleries->perpage()*($galleries->currentpage() - 1))+1}}</td>
                            <td>
                                <span>{{$gallery->name}}
                                    @if($gallery->location)
                                    <span style="color:#ccc;">({{$gallery->location}})</span>
                                    @endif
                                </span>
                                <br />
                                @if($gallery->status=='active')
                                <span class="badge badge-success">Active </span>
                                @elseif($gallery->status=='inactive')
                                <span class="badge badge-danger">Inactive </span>
                                @else
                                <span class="badge badge-danger">Draft </span>
                                @endif
                                <span style="color:#ccc;">
                                    <i class="fa fa-user" style="color: #1ab394;"></i>
                                    {{$gallery->user?$gallery->user->name:'No Author'}}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                ({{$gallery->galleryImages->count()}}) items
                            </td>
                           
                            <td style="text-align:center;">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-success btn-ico" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-arrow"></div>
                                        <a href="{{route('admin.galleriesAction',['edit',$gallery->id])}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit </a>
                                        <a href="{{route('admin.galleriesAction',['delete',$gallery->id])}}" onclick="return confirm('Are You Want To Delete')" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($galleries->count()==0)
                            <tr>
                                <td colspan="5" class="text-center">No Result Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{$galleries->links('pagination')}}
            </div>
        </div>
    </div>
</div>


@endsection @push('js') @endpush
