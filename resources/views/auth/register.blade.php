@extends(welcomeTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('register')}}</title>
@endsection 
@section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{websiteTitle('Register')}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('register')}}" />
<link rel="canonical" href="{{route('register')}}">
@endsection 
@push('css')
<style>
    .lostpassheader{
        text-align:center;   
    }
    .login-part {
        border: 1px solid #d5d5d5;
        padding: 25px;
        border-radius: 10px;
    }
</style>
@endpush 
@section('contents')

<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{route('index')}}">Home </a>
            <span></span> Register
        </div>
    </div>
</div>

<section class="pt-50 pb-50">
	<div class="container">
	    <div class="row">
	        <div class="col-md-3"></div>
	        <div class="col-md-6">
				<div class="login_wrap widget-taber-content p-30 background-white border-radius-10 mb-md-5 mb-lg-0 mb-sm-5">
					<div class="padding_eight_all bg-white">
						<div class="heading_s1">
							<h3 class="mb-30">Register</h3>
						</div>
						@include(welcomeTheme().'alerts')
						<form action="{{route('register')}}" method="post" >
							@csrf
							<div class="form-group">
								<label>Your Name*</label>
								<input type="text" required="" value="{{old('name')}}" name="name" placeholder="Your Name">
								@if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
							</div>
							<div class="form-group">
								<label>Your Mobile/Email*</label>
								<input type="text" required="" value="{{old('email_or_mobile')}}" name="email_or_mobile" placeholder="Your Email / Mobile">
								@if ($errors->has('email_or_mobile'))
                                <span class="text-danger">{{ $errors->first('email_or_mobile') }}</span>
                                @endif
							</div>
							<div class="form-group">
								<label>Password*</label>
								<div class="input-group">
									<input required="" type="password" class="password" value="{{old('password')}}" name="password" placeholder="Password" style="flex: 1 1 auto;width: 1%;">
									<div class="input-group-text showPassword">
										<i class="fa fa-eye-slash"></i>
									</div>
								</div>
								@if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
							</div>
                            <div class="login_footer form-group">
                                <div class="chek-form">
                                    <div class="custome-checkbox">
                                        <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox12" value="">
                                        <label class="form-check-label" for="exampleCheckbox12"><span>I agree to terms &amp; Policy.</span></label>
                                    </div>
                                </div>
                                <a href="#"><i class="fi-rs-book-alt mr-5 text-muted"></i>Lean more</a>
                            </div>
							<div class="form-group text-center">
								<button type="submit" class="btn btn-fill-out btn-block hover-up" name="register">Register</button>
							</div>
							<div class="text-center" >
								I have already account? <a href="{{route('login')}}">Login</a>
							</div>
						</form>
					</div>
				</div>
	        </div>
	        <div class="col-md-3"></div>
	    </div>

	</div>
</section>

@endsection 
@push('js')

<script>
    $(document).ready(function() {

    });
</script>
@endpush