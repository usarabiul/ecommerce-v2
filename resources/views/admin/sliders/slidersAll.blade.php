@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Sliders List')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush 
@section('contents')


<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Sliders List</div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><i class="bx bx-menu-alt-left"></i></button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.slidersAction','create')}}" ><i class="bx bx-plus"></i> Add Slider </a>
                <a class="dropdown-item" href="{{route('admin.sliders')}}"><i class="bx bx-refresh"></i> Reload</a>
            </div>
        </div>
    </div>
</div>

@include(adminTheme().'alerts')
<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Sliders List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive" style="min-height:300px;">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 50px;width:50px;">SL</th>
                            <th style="min-width: 300px;">Slider Name</th>
                            <th style="max-width: 100px;width:100px;">Slide Item</th>
                            <th style="max-width: 80px;width:80px;">Image</th>
                            <th style="min-width: 60px;width: 60px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sliders as $i=>$slider)
                        <tr>
                            <td>
                                {{$i+1}}
                            </td>
                            <td>
                                <span>{{$slider->name}} 
                                @if($slider->location)
                                <span style="color:#ccc;">({{$slider->location}})</span>
                                @endif
                                </span>
                                <br />
                                @if($slider->status=='active')
                                <span class="badge badge-success">Active </span>
                                @elseif($slider->status=='inactive')
                                <span class="badge badge-danger">Inactive </span>
                                @else
                                <span class="badge badge-danger">Draft </span>
                                @endif
                                @if($slider->featured==true)
                                <span><i class="fa fa-star" style="color: #1ab394;"></i></span>
                                @endif
                                <span style="color:#ccc;">
                                    <i class="fa fa-user" style="color: #1ab394;"></i>
                                    {{$slider->user?$slider->user->name:'No Author'}}
                                </span>
                            </td>
                            <td>{{$slider->sliderItems->count()}} Items</td>
                            <td style="padding: 5px; text-align: center;">
                                <img src="{{asset($slider->image())}}" style="max-width: 80px; max-height: 50px;" />
                            </td>
                            <td style="text-align:center;">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-success btn-ico" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-arrow"></div>
                                        <a href="{{route('admin.slidersAction',['edit',$slider->id])}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit </a>
                                        <a href="{{route('admin.slidersAction',['delete',$slider->id])}}" onclick="return confirm('Are You Want To Delete')" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($sliders->count()==0)
                            <tr>
                                <td colspan="5" class="text-center">No Result Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{$sliders->links('pagination')}}
            </div>
        </div>
    </div>
</div>

@endsection @push('js') @endpush
