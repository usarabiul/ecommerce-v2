@extends(general()->adminTheme.'.layouts.app')
@section('title')
<title>{{websiteTitle('Post Comments List')}}</title>
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

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Comments List</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.postsCommentsAll')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

@include(general()->adminTheme.'.alerts')
<div class="card">
	<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
		<h4 class="card-title">Comments List</h4>
	</div>
	<div class="card-content">
		<div class="card-body">
		<form action="{{route('admin.postsCommentsAll')}}">
			<div class="row">
				<div class="col-md-12 mb-0">
					<div class="input-group">
						<input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Comments Title, email, website" class="form-control {{$errors->has('search')?'error':''}}">
						<button type="submit" class="btn btn-success rounded-0">Search</button>
					</div>
				</div>
			</div>
		</form>
		<hr>
		<form action="{{route('admin.postsCommentsAll')}}">
		<div class="row">
			<div class="col-md-4">
				<div class="input-group mb-1">
					<select class="form-control form-control-sm rounded-0" name="action" required="">
						<option value="1">Approve</option>
						<option value="2">Un-approve</option>
						<option value="3">Feature</option>
						<option value="4">Un-feature</option>
						<option value="5">Delete</option>
					</select>
					<button class="btn btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
				</div>
			</div>
		</div>
			<div class="table-responsive" style="min-height:300px;" >


			<table class="table table-hover" >
				<thead class="thead-light">
					<tr>
						<th width="5%"></th>
						<th width="20%">Author</th>
						<th>Comments</th>
						<th width="20%">In Response To</th>
						<th width="25%">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($comments as $i=>$comment)
					<tr class="{{$comment->status=='inactive'?'inactive':''}}">
						<td>
							<input class="checkbox" type="checkbox" name="checkid[]" value="{{$comment->id}}"> 
						</td>
						<td class="commentauthor">
						@if($comment->user)
						<span><img src="{{asset($comment->user->image())}}"></span>
						@else
						<span><img src="{{asset('public/medies/profile.png')}}"></span>
						@endif

						@if($comment->website==null)
						<span>{{$comment->name}}</span>
						@else
						<a href="//{{$comment->website}}" rel="nofollow" target="_blank">{{$comment->name}}</a>
						@endif     	       
						<a href="mailto:{{$comment->email}}">{{$comment->email}}</a>

						
						<br>
						@if($comment->status=='active')
						<span><i class="fa fa-check" style="color: #1ab394;"></i></span>
						<a href="{{route('admin.postsCommentsAction',['status',$comment->id])}}" class="badge btn-danger" style="color: black !important;">Unapprove</a>
						@else
						<span><i class="fa fa-times" style="color: #ed5565;"></i></span>
						<a href="{{route('admin.postsCommentsAction',['status',$comment->id])}}"  class="badge btn-success">Approved</a>
						@endif
						
						</td>
						<td>
						<span>
						{!!$comment->content!!}
						</span>
						</td>
						<td>
							@if($comment->post)
							<a href="{{route('admin.postsAction',['edit',$comment->post->id])}}" target="_blank">{{$comment->post->name}}</a>
							<br>
							<a href="{{route('blogView',$comment->post->slug)}}" target="_blank" class="badge btn-info">Post View</a><br>
							<a href="{{route('admin.postsComments',$comment->post->id)}}" class="badge badge-success"><i class="fa fa-comments"></i> View ({{$comment->post->postComments->where('status','<>','temp')->count()}})</a>
							@endif
						</td>
						<td class="center">
						<a href="{{route('admin.postsCommentsAction',['edit',$comment->id])}}" class="btn btn-md btn-info">Edit</a>
						<a href="{{route('admin.postsCommentsAction',['replay',$comment->id])}}" class="btn btn-md btn-info"><i class="fa fa-reply"></i></a>
						<a href="{{route('admin.postsCommentsAction',['delete',$comment->id])}}" onclick="return confirm('Are You Want To Delete?')" class="btn btn-md btn-danger" ><i class="fa fa-trash"></i></a>
							<br>
							<span>{{$comment->created_at->format('d-m-Y h:i A')}}</span>       
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			{{$comments->links('pagination')}}
			</div>
		</form>
		</div>
	</div>
</div>



@endsection
@push('js')

@endpush