@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Review Edit')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush 
@section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" >Review Edit</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.productsReview')}}" class="btn btn-outline-success mr-2">BACK</a>
            <a href="{{route('admin.productsReviewAction',['edit',$review->id])}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

@include(adminTheme().'alerts')
<form action="{{route('admin.productsReviewAction',['update',$review->id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Review Edit</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label>Review Rating</label>
                            @if ($errors->has('rating'))
                            <p style="color: red; margin: 0;">{{ $errors->first('rating') }}</p>
                            @endif
                            <select class="form-control" name="rating">
                                <option value="5" {{$review->rating==5?'selected':''}} >5 Start</option>
                                <option value="4" {{$review->rating==4?'selected':''}}>4 Start</option>
                                <option value="3" {{$review->rating==3?'selected':''}}>3 Start</option>
                                <option value="2" {{$review->rating==2?'selected':''}}>2 Start</option>
                                <option value="1" {{$review->rating==1?'selected':''}}>1 Start</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Review Content</label>
                            @if ($errors->has('review'))
                            <p style="color: red; margin: 0;">{{ $errors->first('review') }}</p>
                            @endif
                            <textarea name="review" rows="5" class="form-control" placeholder="Write Description">{!!$review->content!!}</textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Name*</label>
                                @if ($errors->has('name'))
                                <p style="color: red; margin: 0;">{{ $errors->first('name') }}</p>
                                @endif
                                <input type="text" name="name" value="{{$review->name}}" class="form-control" placeholder="Enter Your Name" />
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Email</label>
                                @if ($errors->has('email'))
                                <p style="color: red; margin: 0;">{{ $errors->first('email') }}</p>
                                @endif
                                <input type="email" name="email" value="{{$review->email}}" class="form-control" placeholder="Enter Your Email" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Status</label>
                                <div class="i-checks">
                                    <label style="cursor: pointer;"> <input name="status" type="checkbox" {{$review->status=='active'?'checked':''}}> <i></i> Approved</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Featured</label>
                                <div class="i-checks">
                                    <label style="cursor: pointer;"> <input name="featured" type="checkbox" {{$review->featured?'checked':''}}> <i></i> Active</label>
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


@endsection 
@push('js')

@endpush
