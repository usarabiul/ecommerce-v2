@extends('admin.layouts.app') @section('title')
<title>{{websiteTitle('Order Manage')}}</title>
@endsection @push('css')
<style type="text/css">
	.searchModel {
		position: relative;
	}

	.searchResult {
		position: absolute;
		width: 100%;
		top: 36px;
	}

	.searchResult ul {
		background: white;
		width: 100%;
		display: block;
		border: 1px solid #cfc5c5;
		padding: 0;
		margin: 0;
		list-style: none;
		border-bottom: none;
	}

	.searchResult ul li a {
		display: block;
		padding: 5px 10px;
		border-bottom: 1px solid #cfc5c5;
		text-decoration: none;
	}

	.searchResult ul li a:hover {
		background: #dcd9d9;
	}

</style>
@endpush @section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Order Manage</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.orders')}}" type="button" class="btn btn-outline-success mr-2">Back</a>
            <a href="{{route('admin.ordersAction',['invoice',$order->id])}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-view"></i> Invoice</a>
            <a href="{{route('admin.ordersAction',['edit',$order->id])}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
				@include('admin.alerts')
				<form action="{{route('admin.ordersAction',['update',$order->id])}}" method="post">
					@csrf
					<div class="card">
						<div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
							<h4 class="card-title">Customer Info</h4>
						</div>
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="table-responsived">
										<table class="table table-borderless">
											<tr>
												<th>INVOICE:</th>
												<td>{{$order->invoice}}</td>
											</tr>
											
											<tr>
												<th>Name*:</th>
												<td style="padding:1px;">
													<input type="text" class="form-control" name="name" value="{{$order->name}}" placeholder="Enter Name" required="" />	
												</td>
											</tr>
											<tr>
												<th>Mobile*:</th>
												<td style="padding:1px;">
													<input type="text" class="form-control" name="mobile" value="{{$order->mobile}}" placeholder="Enter mobile" required="" />	
												</td>
											</tr>
											<tr>
												<th>Email:</th>
												<td style="padding:1px;">
													<input type="email" class="form-control" name="email" value="{{$order->email}}" placeholder="Enter email" />	
												</td>
											</tr>
											<tr>
												<th>Address*:</th>
												<td style="padding:1px;">
													<div class="row">
														<div class="col-md-6 mb-3">
															<select class="form-control" id="district" name="district" required="">
																<option value="">Select District</option>
																@foreach(geoData(3) as $data)
																<option value="{{$data->id}}" {{$data->id==$order->district?'selected':''}} >{{$data->name}}</option>
																@endforeach
															</select>
														</div>
														<div class="col-md-6 mb-3">
															<select class="form-control" id="city" name="city" required="">
																<option value="">Select city</option>
																@if($order->district)
																	@foreach(geoData(4,$order->district) as $data)
																	<option value="{{$data->id}}" {{$data->id==$order->city?'selected':''}} >{{$data->name}}</option>
																	@endforeach
																@endif
															</select>
														</div>
														<div class="col-md-12 mb-3">
															<input type="text" name="address" value="{{$order->address}}" class="form-control" placeholder="Enter address" required="">
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>Note:</th>
												<td style="padding:1px;">
													<textarea class="form-control" name="note" placeholder="Write note" >{{$order->note}}</textarea>
												</td>
											</tr>
										</table>
										</div>
									</div>
									<div class="col-md-6">
										<div class="table-responsive">
										<table class="table table-borderless">
											<tr>
												<th>Grand Total:</th>
												<td>{{priceFullFormat($order->grand_total)}}
												@if($order->return_amount>0)
												<span class="badge badge-success" style="background:#ff9800;">Refund</span> {{priceFullFormat($order->return_amount)}}
												@endif
												</td>
											</tr>
											<tr>
												<th>Paid:</th>
												<td>{{priceFullFormat($order->paid_amount)}}</td>
											</tr>
											<tr>
												<th>Due:</th>
												<td>{{priceFullFormat($order->due_amount)}}</td>
											</tr>
											@if($order->extra_amount)
											<tr>
												<th>Advence:</th>
												<td>{{priceFullFormat($order->extra_amount)}} 
												
												</td>
											</tr>
											@endif
											
											<tr>
												<th>Payment:</th>
												<td>
													@if($order->payment_status=='partial')
													<span class="badge badge-success" style="background:#ff9800;">{{ucfirst($order->payment_status)}}</span>
													@elseif($order->payment_status=='paid')
													<span class="badge badge-success" style="background:#673ab7;">{{ucfirst($order->payment_status)}}</span>
													@else
													<span class="badge badge-success" style="background:#f44336;">{{ucfirst($order->payment_status)}}</span>
													@endif
												</td>
											</tr>
											<tr>
												<th>Order Status:</th>
												<td>
													@if($order->payment_method==null)
													<span class="badge badge-success" style="background:#ff5722;">Pending Payment</span>
													@else
													@if($order->order_status=='confirmed')
													<span class="badge badge-success" style="background:#e91e63;">{{ucfirst($order->order_status)}}</span>
													@elseif($order->order_status=='shipped')
													<span class="badge badge-success" style="background:#673ab7;">{{ucfirst($order->order_status)}}</span>
													@elseif($order->order_status=='delivered')
													<span class="badge badge-success" style="background:#1c84c6;">{{ucfirst($order->order_status)}}</span>
													@elseif($order->order_status=='cancelled')
													<span class="badge badge-success" style="background:#f44336;">{{ucfirst($order->order_status)}}</span>
													@else
													<span class="badge badge-success" style="background:#ff9800;">{{ucfirst($order->order_status)}}</span>
													@endif
													@endif
													
												</td>
											</tr>
											<tr>
												<th>Date:</th>
												<td>{{$order->created_at->format('d-m-Y')}}</td>
											</tr>
											<tr>
												<th>Total Items:</th>
												<td> {{$order->items->count()}} Items</td>
											</tr>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
							<h4 class="card-title">Orders Item</h4>
						</div>
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										
									</div>
									<div class="col-md-4">
										<div class="searchModel">
											<div class="input-group mb-1">
												<input class="form-control searchItem" data-url="{{route('admin.ordersAction',['search',$order->id])}}" placeholder="Search Item" />
												<div class="input-group-text">
													<i class="fa fa-search"></i>
												</div>
											</div>
											<div class="searchResult"></div>
										</div>
									</div>
								</div>

								<div class="itemsCount">
									@include(adminTheme().'orders.includes.items')
									
								</div>
							</div>
						</div>
					</div>
				</form>
                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;padding: 1rem;">
                        <div class="row">
                        	<div class="col-md-6">
                        		<h4 class="card-title" style="padding: 5px;">Order Payment</h4>
                        	</div>
                        	<div class="col-md-6">
                        		<div class="left-tools" style="text-align: right;">
                        			<button class="btn btn-primary" data-toggle="modal" data-target="#payment" style="padding: 8px 15px;border-radius: 0;"> <i class="fas fa-money"></i> payment</button>
                        		</div>
                        	</div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                        	<h4>All Transactions:</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="min-width:150px;">Billing By</th>
                                    <th style="min-width:300px;">Billing Info</th>
                                    <th style="min-width:300px;">Note</th>
                                    <th style="min-width:150px;">Amount</th>
                                </tr>
                               @foreach($order->transactionsAll as $i=>$transaction)
                               <tr>
                                <td>
                                    <b>TNX:</b> {{$transaction->transection_id}}<br>
                                    <b>Method:</b> {{$transaction->method?$transaction->method->name:''}}<br>
                                    
                                    <b>Date:</b> {{$transaction->created_at->format('Y-m-d h:i A')}}
                                </td>
                                <td>
                                    <b>Name:</b> {{$transaction->billing_name}} <br>
                                    <b>Mobile:</b> {{$transaction->billing_mobile}} <br>
                                    <b>E-mail:</b> {{$transaction->billing_email}} <br>
                                </td>
                                <td>
                                    <b>Type: </b>@if($transaction->type==1)
                                    <span class="badge badge-success" style="background:#00bcd4;">Recharge</span>
                                    @elseif($transaction->type==2)
                                    <span class="badge badge-success" style="background:#ff9800;">Re-fund Order</span>
                                    @else
                                    <span class="badge badge-success" style="background:#8bc34a;">Order Payment</span>
                                    @endif <br>
                                    <b>Address:</b> {{$transaction->billing_address}}<br>
                                    <b>Note:</b> {{$transaction->billing_note}}
                                </td>
                                <td>{{$transaction->currency}} {{number_format($transaction->amount,2)}}
                                    <br>
                                    <b>Status:</b> {{ucfirst($transaction->status)}}
                                </td>
                               </tr>
                               @endforeach
                               @if($order->transactionsAll->count()==0)
                               <tr>
                                   <td colspan="4" style="text-align:center;">
                                           <span>No Transaction</span>
                                   </td>
                               </tr>
                               @endif
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


 <!-- Modal -->
 <div class="modal fade text-left" id="payment">
   <div class="modal-dialog" role="document">
	 <div class="modal-content">
	   <div class="modal-header">
		 <h4 class="modal-title">Payment</h4>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		   <span aria-hidden="true">&times; </span>
		 </button>
	   </div>
	   <div class="modal-body">
	   		<ul class="nav nav-tabs" role="tablist" style="border-bottom: none;">
				<li class="nav-item">
					<a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="tab1" href="#tab1" role="tab" aria-selected="true"> Received Payment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="tab2" href="#tab2" role="tab" aria-selected="false"> Refund Payment</a>
				</li>
			</ul>
			<div class="tab-content px-1 pt-1" style="border: 1px solid #ddd;">
				<div class="tab-pane active" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
				<form action="{{route('admin.ordersAction',['payment',$order->id])}}" method="post">
					@csrf
					<input type="hidden" value="0" name="transaction_type">
					<div class="form-group">
						<label>Amount</label>
						<input type="number" class="form-control PayAmount" step="any" name="amount" placeholder="Enter Amount" value="{{$order->due_amount?:''}}">
					</div>
					<div class="form-group">
						<label>Method</label>
						<div class="input-group">
						<select class="form-control" name="method" required="">
							<option value="">Select Method</option>
						@foreach($methods as $method)
							<option value="{{$method->id}}" >{{$method->name}}</option>
							@endforeach
						</select>
						</div>
					</div>
					<div class="form-group">
						<label>Note</label>
						<textarea name="note" class="form-control" placeholder="Write Note.."></textarea>
					</div>
					<div class="input-group input-group-sm" style="width: 250px;margin:5px 0;">
					@if(general()->sms_status)
						<label style="cursor: pointer;padding: 0 5px;"><input type="checkbox" name="mail_sms"> Send SMS</label>
					@endif
					@if(general()->mail_status)
						<label style="cursor: pointer;padding: 0 5px;"><input type="checkbox" name="mail_send"> Send Mail</label>
					@endif
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
					<br>
				</form>
				</div>
				<div class="tab-pane" id="tab2" role="tabpanel" aria-labelledby="base-tab2">
					<form action="{{route('admin.ordersAction',['payment',$order->id])}}" method="post">
						@csrf
						<input type="hidden" value="1" name="transaction_type">
						<div class="form-group">
							<label>Amount</label>
							<input type="number" class="form-control PayAmount" name="amount" placeholder="Enter Amount" value="{{$order->paid_amount}}">
						</div>
						<div class="form-group">
							<label>Method</label>
							<div class="input-group">
							<select class="form-control" name="method" required="">
								<option value="">Select Method</option>
							@foreach($methods as $method)
								<option value="{{$method->id}}">{{$method->name}}</option>
							@endforeach
							</select>
							</div>
						</div>
						<div class="form-group">
							<label>Note</label>
							<textarea name="note" class="form-control" placeholder="Write Note.."></textarea>
						</div>
						<div class="input-group input-group-sm" style="width: 250px;margin:5px 0;">
							@if(general()->sms_status)
								<label style="cursor: pointer;padding: 0 5px;"><input type="checkbox" name="mail_sms"> Send SMS</label>
							@endif
							@if(general()->mail_status)
								<label style="cursor: pointer;padding: 0 5px;"><input type="checkbox" name="mail_send"> Send Mail</label>
							@endif
						</div>
						<button type="submit" class="btn btn-primary">Submit</button>
						<br>
					</form>
				</div>
            </div>
	   </div>
	 </div>
   </div>
 </div>

@endsection 

@push('js')

<script>
    $(document).ready(function(){
		$(".searchResult").hide();
        $(document).on('click', function(e) {
            var container = $(".searchModel");
            var containerClose = $(".searchResult");
            if (!$(e.target).closest(container).length) {
                containerClose.hide();
            }else{
                containerClose.show();
            }
        });

        $('.searchItem').keyup(function(){
			var key =$(this).val();
			var url =$(this).data('url');
			if(key.length < 2){
				$('.searchResult').html('');
				return;
			}

			$.ajax({
				url: url,
				type: 'GET',
				data: { search: key },
				success: function (res) {
					$('.searchResult').html(res.view);
				}
			});
			
		});

        $('.paymentType').click(function(){
            var amount =$(this).data('amount');
            if(amount==0){
                amount='';
            }
            
            
            $('.PayAmount').val(amount);

        });






    });
</script>

@endpush