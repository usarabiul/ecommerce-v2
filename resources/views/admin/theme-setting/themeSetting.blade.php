@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Theme Setting')}}</title>
@endsection 
@push('css')

<style type="text/css">
    .ProductGridSection {
        border: 1px solid gray;
        padding: 5px;
        text-align: center;
    }

    .ProductGrid {
        min-height: 120px;
    }

    .ProductGrid img {
        max-width: 100%;
        max-height: 115px;
    }
</style>

@endpush 
@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Theme Setting</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Theme Setting</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.themeSetting')}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
                @include(adminTheme().'alerts')
                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">theme Setting</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <h1>Pending...</h1>
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
<script type="text/javascript"></script>
@endpush
