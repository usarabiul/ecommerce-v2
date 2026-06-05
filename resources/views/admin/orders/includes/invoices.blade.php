<div class="invoice-inner invoicePage PrintAreaContact">
    <div class="row">
        <div class="col-8">

            <div class="demo-info">
                <img src="{{asset(general()->logo())}}" alt="company-logo" style="max-width:150px;">
                <h6>{{general()->title}}</h6>
                <p>{!!general()->address!!}</p>
                <p><b>Mobile:</b> {{general()->mobile}}</p>
                <p><b>Email:</b> {{general()->email}}</p>
                <p><b>BIN:</b> 3456</p>
                <p><b>Mushak:</b> 3578</p>
                <p><b>Sold By:</b> {{$order->posByuser?$order->posByuser->name:''}}</p>
                <p><b>Remarks:</b> </p>
            </div>
        </div>
        <div class="col-4">
            <div class="invoice-info">
                <h6>Invoice</h6>
                <p>Invoice No: <b>{{$order->invoice}}</b></p>
                <p>Date: {{$order->created_at->format('d/m/Y')}}</p>
                <p>Time: {{$order->created_at->format('h:i A')}}</p>
                <p class="billingTo">Billing To</p>
                <h5>{{$order->name?:'Guest'}}</h5>
                <p><b>Mobile:</b> {{$order->mobile}}</p>
                <p><b>Address:</b> {{$order->address}}</p>
            </div>
        </div>
    </div>
    
    <div class="invoice-products">
        <table class="table remarkTable">
          <thead>
            <tr>
              <th style="width: 10%;">SL.</th>
              <th style="width: 35%; text-align: center;">Item</th>
              <th style="width: 15%;text-align: center;">Quantity</th>
              <th style="width: 20%;text-align: center;">Rate</th>
              <th style="text-align: end;">Total</th>
            </tr>
          </thead>
          <tbody>
          	@foreach($order->items as $i=>$item)
                <tr>
                  <td>{{$i+1}}</td>
                  <td style="text-align: center;">{{$item->product_name}}</td>
                  <td style="text-align: center;">{{$item->quantity}}</td>
                  <td style="text-align: center;">{{priceFormat($item->price)}}</td>
                  <td style="text-align: end;">{{priceFormat($item->total_price)}}</td>
                </tr>
            @endforeach
            <tr>
            	<th colspan="4" style="text-align: end;border-color: white;">Subtotal</th>
            	<td style="text-align: end;border-color: white;">{{priceFormat($order->total_price)}}</td>
            </tr>
            <tr>
            	<th colspan="4" style="text-align: end;border-color: white;">Discount</th>
            	<td style="text-align: end;border-color: white;">{{priceFormat($order->discount_price)}}</td>
            </tr>
            <tr>
            	<th colspan="4" style="text-align: end;border-color: white;">Total Amount</th>
            	<td style="text-align: end;border-color: white;">{{priceFormat($order->grand_total)}}</td>
            </tr>
            <tr>
            	<th colspan="4" style="text-align: end;border-color: white;">Paid</th>
            	<td style="text-align: end;border-color: white;">{{priceFormat($order->paid_amount)}}</td>
            </tr>
            <tr>
            	<th colspan="4" style="text-align: end;border-color: white;">Due</th>
            	<td style="text-align: end;border-color: white;">{{priceFormat($order->due_amount)}}</td>
            </tr>
          </tbody>
        </table>
        
        <div class="payment-details">
            <span><b>Payment Method</b></span>
            <div class="row">
                <div class="col-8">
                    <table class="table remarkTable">
                      <thead>
                        <tr>
                          <th style="width: 10%;">SL.</th>
                          <th style="width: 40%; text-align: center;">Payment Method</th>
                          <th style="text-align: center;">Payment By</th>
                          <th style="text-align: end;width: 120px;">Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                      	@foreach($order->transections as $transection)
                        <tr>
                          <td>1</td>
                          <td style="text-align: center;">{{$transection->method?$transection->method->name:'No Found'}}</td>
                          <td style="text-align: center;">{{$transection->methodOption?$transection->methodOption->name:'-'}}</td>
                          <td style="text-align: end;">{{priceFullFormat($transection->amount)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3"><b>Total:</b></td>
                            <td style="text-align: end;">{{priceFullFormat($order->transections->sum('amount'))}}</td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        
        <div class="signature-part">
            <div class="row">
                <div class="col-6">
                    ------------------<br>
                    <span><b>Received By</b></span>
                </div>
                <div class="col-6" style="text-align: end;">
                    ------------------<br>
                    <span><b>Authorised By</b></span>
                </div>
            </div>
        </div>
    </div>
</div>