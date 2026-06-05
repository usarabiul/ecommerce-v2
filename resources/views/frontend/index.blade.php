@extends(welcomeTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle()}}</title>
@endsection 
@section('SEO')
<meta name="title" property="og:title" content="{{general()->meta_title}}" />
<meta name="description" property="og:description" content="{!!general()->meta_description!!}" />
<meta name="keyword" property="og:keyword" content="{{general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset(general()->logo())}}" />
<meta name="url" property="og:url" content="{{route('index')}}" />
<link rel="canonical" href="{{route('index')}}">

@endsection 
@push('css')

@endpush

@section('contents')

<h1>Welcome to {{websiteTitle()}}</h1>

@endsection 
@push('js')
<script>
    $(document).ready(function() {
        console.log('Welcome to {{websiteTitle()}}');
    });
</script>
@endpush