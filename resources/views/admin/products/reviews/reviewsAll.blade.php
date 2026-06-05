@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Review List')}}</title>
@endsection @push('css')
<style type="text/css">
  .commentauthor img{
    float: left;
    margin-right: 10px;
    margin-top: 1px;
    width: 40px;
  }
  .table-responsive table tr.inactive {
    background: #ffcece;
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
                    <li class="breadcrumb-item active" >Review List</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.productsReview')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>


@include(adminTheme().'alerts')


<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Review List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
			<form action="{{route('admin.productsReview')}}">
				<div class="row">
					<div class="col-md-12 mb-0">
						<div class="input-group">
							<input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Product Title, Reviewer name, email" class="form-control {{$errors->has('search')?'error':''}}">
							<button type="submit" class="btn btn-success rounded-0"><i class="fa fa-search"></i> Search</button>
						</div>
					</div>
				</div>
			</form>
			<hr>
        	<form action="{{route('admin.productsReview')}}">
         	<div class="row">
     			<div class="col-md-4">
     				<div class="input-group mb-1">
     					<select class="form-control form-control-sm rounded-0" name="action" required="">
     						<option value="1">Select Action</option>
     						<option value="1">Verified</option>
     						<option value="2">Un-verify</option>
     						<option value="3">Featured</option>
     						<option value="4">Un-featured</option>
     						<option value="5">Delete</option>
     					</select>
     					<button class="btn btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
     				</div>
     			</div>
     		</div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" >
				    <thead>
				        <tr>
                        	<th style="min-width: 60px;width: 60px;">
								<label style="cursor: pointer; margin-bottom: 0;"> <input class="checkbox" type="checkbox" class="form-control" id="checkall" /> All <span class="checkCounter"></span> </label>
							</th>
				            <th style="min-width: 300px;width: 300px;">Reviewer</th>
				            <th style="min-width: 400px;">Review</th>
				        </tr>
				    </thead>
				    <tbody>
				        @foreach($reviews as $i=>$review)
				        <tr class="{{$review->status=='inactive'?'inactive':''}}">
				            <td colspan="2" class="commentauthor">
                            {{$reviews->currentpage()==1?$i+1:$i+($reviews->perpage()*($reviews->currentpage() - 1))+1}}
							
                            <input class="checkbox" type="checkbox" name="checkid[]" value="{{$review->id}}">
                            <b><i class="fa fa-calendar" style="color:#ff425c;"></i> {{$review->created_at->format('d-m-Y h:i A')}}</b>
							@if($review->featured==true)
							<span><i class="fa fa-bolt" style="color: #ff425c;"></i></span>
							@endif
                            <br>
				            <span><img src="{{asset($review->image())}}"></span>
				            {{$review->name}} 	
                            @if($review->email)       
				            <a href="mailto:{{$review->email}}">{{$review->email}}</a>
                            @endif
                            <br>
                            <br>
                            @if($review->post)
                            <b>Product:</b> <a href="{{route('productView',$review->post->slug?:'no-slug')}}" target="_blank">{{$review->post->name}}</a> <a href="{{route('admin.productsAction',['edit',$review->post->id])}}"><i class="fa fa-edit"></i></a>
                            @endif
				            </td>
				            <td>
				            <span>
				            {!!$review->content!!}
				            </span>
                            <br>
                            <a href="{{route('admin.productsReviewAction',['edit',$review->id])}}" class="btn btn-sm btn-info px-1"><i class="fa fa-edit"></i> Edit</a>
				            
							@if($replay =$review->replays()->where('status','<>','temp')->first())
							<br />
							<b>Replay: </b>
							<span>{!!$replay->content!!}</span>
							<br>
							<a href="{{route('admin.productsReviewAction',['replay',$review->id])}}" class="btn btn-sm btn-success"><i class="fa fa-reply"></i> Edit</a>
							@else
							<a href="{{route('admin.productsReviewAction',['replay',$review->id])}}" class="btn btn-sm btn-success"><i class="fa fa-reply"></i></a>
  							@endif
				            </td>
				        </tr>
				        @endforeach
				    </tbody>
				</table>
                {{$reviews->links('pagination')}}
            </div>
        </form>
        </div>
    </div>
</div>

@endsection @push('js') @endpush
