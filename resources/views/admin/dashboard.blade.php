@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Dashboard')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')


<header class="page-title-bar">
    <div class="d-flex justify-content-between">
        <h1 class="page-title">Dashboard</h1>
    </div>
</header>
<div class="page-section">
    <div class="section-block">
        <div class="metric-row">
            <div class="col-lg-12">
                <div class="metric-row metric-flush">
                    <div class="col">
                        <a href="user-teams.html" class="metric metric-bordered align-items-center">
                            <h2 class="metric-label">Products</h2>
                            <p class="metric-value h3">
                                <sub><i class="oi oi-people"></i></sub> <span class="value">{{number_format($reports['products'])}}</span>
                            </p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="user-projects.html" class="metric metric-bordered align-items-center">
                            <h2 class="metric-label">Pages</h2>
                            <p class="metric-value h3">
                                <sub><i class="oi oi-fork"></i></sub> <span class="value">{{number_format($reports['blogs'])}}</span>
                            </p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="user-tasks.html" class="metric metric-bordered align-items-center">
                            <h2 class="metric-label">Blogs</h2>
                            <p class="metric-value h3">
                                <sub><i class="fa fa-tasks"></i></sub> <span class="value">{{number_format($reports['pages'])}}</span>
                            </p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="user-tasks.html" class="metric metric-bordered align-items-center">
                            <h2 class="metric-label">Users</h2>
                            <p class="metric-value h3">
                                <sub><i class="fa fa-tasks"></i></sub> <span class="value">{{number_format($reports['users'])}}</span>
                            </p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="user-tasks.html" class="metric metric-bordered align-items-center">
                            <h2 class="metric-label">Orders</h2>
                            <p class="metric-value h3">
                                <sub><i class="fa fa-tasks"></i></sub> <span class="value">{{number_format($reports['orders'])}}</span>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 
@push('js')
<script>
</script>
@endpush