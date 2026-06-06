
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Oops!</strong> {{ session('error') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
  <strong>Info!</strong> {{ session('info') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning!</strong> {{ session('warning') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('errors'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning!</strong> Need to validation
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
