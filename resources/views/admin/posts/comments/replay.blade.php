@extends(general()->adminTheme.'.layouts.app')
@section('title')
<title>{{websiteTitle('Comment Replay')}}</title>
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
     <h3 class="content-header-title mb-0">Comment Replay</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Comment Replay</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       	<a class="btn btn-outline-primary" href="{{route('admin.postsComments',$comment->src_id)}}">BACK</a>
       	<a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
 
	

 <div class="content-body">
 	<!-- Basic Elements start -->
	 <section class="basic-elements">
	 	@include(general()->adminTheme.'.alerts')
	     <div class="row">
	        <div class="col-md-6">
	         		
	            <div class="card">
	             	<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
					 	<h4 class="card-title">Comment Replay</h4>
				 	</div>
	                 <div class="card-content">
	                     <div class="card-body">
	                     	<table class="table table-bordered">
	                     		<tr>
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
							            @endif       <br>     
							            <a href="mailto:{{$comment->email}}">{{$comment->email}}</a>

							            </td>
	                     		</tr>
	                     		<tr>
	                     			<td><b>Comment:</b> {!!$comment->content!!}</td>
	                     		</tr>
	                     	</table>
                        	<form action="{{route('admin.postsCommentsAction',['replay',$comment->id])}}" method="post" enctype="multipart/form-data">
				                  @csrf
				                <div class="form-group">
				                  <label>Comment Name*</label>
				                  @if ($errors->has('name'))
				                    <p style="color: red;margin: 0;">{{ $errors->first('name') }}</p>
				                  @endif
				                  <input type="text" name="name" value="{{Auth::user()->name}}" class="form-control" placeholder="Enter Your Name">
				                </div>
				                <div class="form-group">
				                  <label>Comment Email*</label>
				                  @if ($errors->has('email'))
				                    <p style="color: red;margin: 0;">{{ $errors->first('email') }}</p>
				                  @endif
				                  <input type="email" name="email" value="{{Auth::user()->email}}" class="form-control" placeholder="Enter Your Email">
				                </div>
				                <div class="form-group">
				                  <label>Comment Website*</label>
				                  @if ($errors->has('website'))
				                    <p style="color: red;margin: 0;">{{ $errors->first('website') }}</p>
				                  @endif
				                  <input type="text" name="website" value="{{route('index')}}" class="form-control" placeholder="Enter Your Website">
				                </div>
				                <div class="form-group">
				                  <label>Comment Content</label>
				                  @if ($errors->has('content'))
				                    <p style="color: red;margin: 0;">{{ $errors->first('content') }}</p>
				                  @endif
				                  <textarea name="content" rows="5" class="form-control" placeholder="Write Description"></textarea>
				                </div>

				                <div class="row">
				                    <div class="form-group col-lg-6">
				                        <label>Comment Status</label>
				                    <div class="i-checks"><label style="cursor: pointer;"> <input name="status"  type="checkbox"> <i></i> Active</label></div>
				                    </div>
				                    <div class="form-group col-lg-6">
				                        <label>Comment Fetured</label>
				                      <div class="i-checks"><label style="cursor: pointer;"> <input name="fetured"  type="checkbox"> <i></i> Active</label></div>
				                    </div>
				                </div>
				                
				                <div class="form-group">
				                  <button type="submit" class="btn btn-success">Update</button>
				                </div>
				                </form>
		                 </div>
		             </div>
		        </div>

			</div>
			<div class="col-md-6">
	         		
				<div class="card">
					<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
						<h4 class="card-title">Comment Replay List</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
  							<table class="table table-bordered">
  								<tr>
								  	<th>Author</th>
  									<th>Comments</th>
								</tr>
								@foreach($comment->replays as $reply)
								<tr>
									<td>
										<b>Name:</b>{{$reply->name}}<br>
										<b>Email:</b>{{$reply->email}}<br>
									</td>
									<td>{!!$reply->content!!}</td>
								</tr>
								@endforeach
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	 </section>
	 <!-- Basic Inputs end -->
</div>


@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
      $('.summernote').summernote({
        placeholder: 'Write Content Here..',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline',]],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture',]],
          ['view', ['fullscreen', 'codeview']]
        ]
      });
    </script>

@endpush