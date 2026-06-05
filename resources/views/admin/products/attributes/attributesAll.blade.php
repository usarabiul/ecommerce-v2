@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Attributes List')}}</title>
@endsection @push('css')
<style type="text/css">
	.itemBtn {
		display: inline-block;
		padding: 5px 15px;
		background: #efeded;
		border-radius: 3px;
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
                    <li class="breadcrumb-item active" >Attributes List</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.productsAttributesAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Attribute</a>
            <a href="{{route('admin.productsAttributes')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

@include(adminTheme().'alerts')

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Attributes List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive" style="min-height:300px;" >
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 250px;min-width: 250px;">Attributes</th>
                            <th style="min-width: 350px;">Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attributes as $i=>$attribute)
                        <tr>
                            <td>
                                <b>Name:</b> <span>{{$attribute->name}}</span>
                                @if($attribute->view==3)
                                <small style="color:#a3a3a3;">(Image)</small>
								@elseif($attribute->view==2)
                                <small style="color:#a3a3a3;">(Color)</small>
								@else
                                <small style="color:#a3a3a3;">(Text)</small>
								@endif
                                <br />
								@if($attribute->description)
								<p>{{$attribute->description}}</p>
								@endif
								<b>Status:</b>
                                @if($attribute->status=='active')
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">Inactive</span>
                                @endif
                                @if($attribute->featured)
                                <span class="badge" style="background-color: #ff864a;"><i class="fa fa-star"></i></span>
                                @endif
                                <br>
                                <b>Date:</b> {{$attribute->created_at->format('d-m-Y')}}
								<br>
								<a href="{{route('admin.productsAttributesAction',['edit',$attribute->id])}}" class="btn btn-md btn-info"><i class="fa fa-edit"></i> Config</a>
								<a href="{{route('admin.productsAttributesAction',['delete',$attribute->id])}}"  onclick="return confirm('Are you want to delete?')" class="btn btn-md btn-danger"><i class="fa fa-trash"></i></a>

                            </td>
                            <td>
                               @foreach($attribute->subAttributes as $item)
								<span class="itemBtn">
									{{$item->name}}

									@if($attribute->view==2)
									<span style="background:{{$item->icon?:'#000'}};height: 12px;width: 30px;display: inline-block;"></span>
									@elseif($attribute->view==3)
									<img src="{{asset($item->image())}}" style="max-height: 25px;box-shadow: 0 0 4px 0px #bfbfbf;border-radius: 3px;">
									@endif 

								</span>
							   @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$attributes->links('pagination')}}
            </div>
        </div>
    </div>
</div>

@endsection @push('js') @endpush
