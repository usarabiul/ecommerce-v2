@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Comment Edit')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush 

@section('contents')

<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Comment Edit</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('admin.postsComments',$comment->src_id)}}" type="button" class="btn btn-primary">Back</a>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="{{route('admin.postsCommentsAll')}}">Comments All</a>
                <a class="dropdown-item" href="{{route('admin.postsComments',[$comment->src_id,'actionType'=>'addComment'])}}"><i class="bx bx-refresh"></i> reload</a>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        @include(adminTheme().'alerts')
        <form action="{{route('admin.postsCommentsAction',['update',$comment->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Comment Edit</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Comment Name*</label>
                                    @if ($errors->has('name'))
                                    <p style="color: red; margin: 0;">{{ $errors->first('name') }}</p>
                                    @endif
                                    <input type="text" name="name" value="{{$comment->name}}" class="form-control" placeholder="Enter Your Name" />
                                </div>
                                <div class="form-group">
                                    <label>Comment Email</label>
                                    @if ($errors->has('email'))
                                    <p style="color: red; margin: 0;">{{ $errors->first('email') }}</p>
                                    @endif
                                    <input type="email" name="email" value="{{$comment->email}}" class="form-control" placeholder="Enter Your Email" />
                                </div>
                                <div class="form-group">
                                    <label>Comment Website</label>
                                    @if ($errors->has('website'))
                                    <p style="color: red; margin: 0;">{{ $errors->first('website') }}</p>
                                    @endif
                                    <input type="text" name="website" value="{{$comment->website}}" class="form-control" placeholder="Enter Your Website" />
                                </div>
                                <div class="form-group">
                                    <label>Comment Content</label>
                                    @if ($errors->has('content'))
                                    <p style="color: red; margin: 0;">{{ $errors->first('content') }}</p>
                                    @endif
                                    <textarea name="content" rows="5" class="form-control" placeholder="Write Description">{!!$comment->content!!}</textarea>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label>Comment Status</label>
                                        <div class="i-checks">
                                            <label style="cursor: pointer;"> <input name="status" type="checkbox" {{$comment->status=='active'?'checked':''}}> <i></i> Approved</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Comment Fetured</label>
                                        <div class="i-checks">
                                            <label style="cursor: pointer;"> <input name="fetured" type="checkbox" {{$comment->fetured?'checked':''}}> <i></i> Active</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js')

<script>
    $(".summernote").summernote({
        placeholder: "Write Content Here...",
        tabsize: 2,
        height: 120,
        toolbar: [
            ["style", ["style"]],
            ["font", ["bold", "underline"]],
            ["color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture"]],
            ["view", ["fullscreen", "codeview"]],
        ],
    });
</script>

@endpush
