@extends(adminTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Subscribers')}}</title>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Subscribers</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Subscribers</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       	<a class="btn btn-outline-primary" href="{{route('admin.subscribes')}}">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
 
	

 <div class="content-body"><!-- Basic Elements start -->
	 <section class="basic-elements">
	     <div class="row">
	         <div class="col-md-12">
				@include(adminTheme().'alerts')
				<div class="card">
					<div class="card-content">
						<div class="card-body">
								<div id="accordion">
							<div class="card-header collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" id="headingTwo" style="background: #f5f7fa;padding: 10px;cursor: pointer;border: 1px solid #00b5b8;">
									Search click Here..
							</div>
							<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" style="border: 1px solid #00b5b8;border-top: 0;">
								<div class="card-body">
							
									<form action="{{route('admin.subscribes')}}">
										<div class="row">
											<div class="col-md-6 mb-1">
												<div class="input-group">
													<input type="date" name="startDate" value="{{request()->startDate?:''}}" class="form-control {{$errors->has('startDate')?'error':''}}">
													<input type="date" value="{{request()->endDate?:''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}">
												</div>
											</div>
											<div class="col-md-6 mb-1">
												<div class="input-group">
													<input type="text" name="search" value="{{request()->search?:''}}" placeholder="Subscriber Email" class="form-control {{$errors->has('search')?'error':''}}">
													<button type="submit" class="btn btn-success btn-sm rounded-0">Search</button>
												</div>
											</div>
										</div>
									</form>

								</div>
							</div>
							</div>
						</div>
					</div>
	         	</div>

	            <div class="card">
	             	<div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
						<h4 class="card-title">Subscribes List</h4>
					</div>
	                 <div class="card-content">
	                    <div class="card-body">
	                     	<form action="{{route('admin.subscribes')}}">
	                     		<div class="row">
                              <div class="col-md-4">
                                  <div class="input-group mb-1">
                                      <select class="form-control form-control-sm rounded-0" name="action" required="">
                                          <option value="">Select Action</option>
                                          <option value="1">Subscribe Delete</option>
                                      </select>
                                      <button class="btn btn-sm btn-primary rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
                                  </div>
                              </div>
                          </div>
			                     <div class="table-responsive">

			                         <table class="table table-bordered table-striped">
			                             <thead>
			                                 <tr>
			                                     <th style="min-width: 60px;">
												 	<label style="cursor: pointer;margin-bottom: 0;">
							                              <input class="checkbox" type="checkbox" class="form-control" id="checkall">  All <span class="checkCounter"></span>
							                        </label>
												</th>
			                                     <th>Name</th>
			                                     <th style="min-width: 150px;">Date</th>
			                                 </tr>
			                             </thead>
			                             <tbody>
			                             		@foreach($subscribes as $i=>$subscribe)
			                                 <tr>
			                                     <th>
													<input class="checkbox" type="checkbox" name="checkid[]" value="{{$subscribe->id}}">
													{{$subscribes->currentpage()==1?$i+1:$i+($subscribes->perpage()*($subscribes->currentpage() - 1))+1}}
												</th>
			                                     <td>{{$subscribe->name}}</td>
			                                     <td>{{$subscribe->created_at->format('d-m-Y')}}</td>
			                                 </tr>
			                                 @endforeach
			                             </tbody>
			                         </table>

			                         
			                     </div>
			                     {{$subscribes->links('pagination')}}
			                    </form>
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

@endpush