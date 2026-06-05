@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Review Replay')}}</title>
@endsection

@push('css')
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


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Review Replay</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Review Replay</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       	<a class="btn btn-outline-primary" href="{{route('admin.productsReview')}}">BACK</a>
       	<a class="btn btn-outline-primary" href="{{route('admin.productsReviewAction',['replay',$review->id])}}">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
 
@include(adminTheme().'alerts')
<div class="row">
	<div class="col-md-6">
			
		<div class="card">
			<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
				<h4 class="card-title">Review Replay</h4>
			</div>
				<div class="card-content">
					<div class="card-body">
					<table class="table table-bordered">
						<tr>
							<td class="commentauthor">
								<span><img src="{{asset($review->image())}}"></span>
								{{$review->name}}
								<br>     
								{{$review->email}}
								</td>
						</tr>
						<tr>
							<td>
								<b>Rating:</b> {{$review->rating}} Star
								<br>
								<b>Review:</b> {!!$review->content!!}
							</td>
						</tr>
					</table>
					<form action="{{route('admin.productsReviewAction',['replay',$review->id])}}" method="post">
							@csrf
						<div class="form-group">
							<label>Replay Content</label>
							@if ($errors->has('replay'))
							<p style="color: red;margin: 0;">{{ $errors->first('replay') }}</p>
							@endif
							<textarea name="replay" rows="5" class="form-control" placeholder="Write replay">{!!$replay->content!!}</textarea>
						</div>
						<div class="row">
							<div class="form-group col-lg-6">
								<label>Status</label>
								<div class="i-checks"><label style="cursor: pointer;"> <input name="status"  type="checkbox" {{$replay->status=='active'?'checked':''}} > <i></i> Active</label></div>
							</div>
						</div>
						
						<div class="form-group">
							<button type="submit" class="btn btn-success">Replay</button>
						</div>
					</form>
					</div>
				</div>
		</div>
	</div>
</div>


@endsection
@push('js')

@endpush