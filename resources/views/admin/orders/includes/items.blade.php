<div class="table-responsive m-t">
    <table class="table table-sm table-bordered table-striped">
        <thead>
            <tr>
            <th style="min-width: 50px;width:50px;padding: 8px 10px;">SL</th>
            <th style="min-width: 70px;width:50px">Image</th>
            <th style="min-width: 300px;">Items</th>
            <th style="min-width: 120px;">QTY/Price</th>
            <th style="width: 250px;">Total</th>
        </tr>
    </thead>
    <tbody>

        @foreach($order->items as $i=>$item)
        <tr>
            <td style="padding: 8px 10px;">{{ $i+1 }}</td>
            
            <td>

                @if($item->product)
                <img src="{{asset($item->product->image())}}" style="max-height: 40px;max-width: 100%;">
                @endif
            </td>
            
            <td>
                <div><strong>{{ $item->product_name }}</strong></div>
                <small>
                ID:{{ $item->product_id }}
                
                @if($item->color)
                , Color: {{ $item->color }} 
                @endif
                
                @if($item->bar_code)
                , BarCode: {{ $item->bar_code }} 
                @endif
                
                @if($item->sku_code)
                , SKU: {{ $item->sku_code }} 
                @endif
                
                @if($item->weight_unit && $item->weight_amount)
                , Weight: {{ $item->weight_amount }} {{ $item->weight_unit }} 
                @endif
                
                @if($item->dimensions_unit && $item->dimensions_length || $item->dimensions_width  || $item->dimensions_height )
                , Dimensions($item->dimensions_unit): L-{{ $item->dimensions_length }} W-{{ $item->dimensions_width }} H-{{ $item->dimensions_height }}
                @endif

                @if($item->size)
                , Size: {{ $item->size }}
                @endif
                </small>
                
            </td>
            <td>{{ $item->quantity }} X {{priceFormat($item->price)}}</td>
                <td>
                {{ priceFullFormat($item->total_price) }}
            </td>
        </tr>
        @endforeach
    </tbody>

    <thead>
        <tr>
            <th colspan="4"></th>
            <th>

                <div class="input-group input-group-sm">

                    <select class="form-control" name="order_status" id="order_status" >
                        <option	option {{ $order->order_status == 'pending' ? 'selected' : '' }} value="pending">Pending</option>
                        <option {{ $order->order_status == 'confirmed' ? 'selected' : '' }} value="confirmed">Confirmed</option>
                        <option {{ $order->order_status == 'shipped' ? 'selected' : '' }} value="shipped">Shipped</option>
                        <option {{ $order->order_status == 'delivered' ? 'selected' : '' }} value="delivered">Delivered</option>
                        <option {{ $order->order_status == 'cancelled' ? 'selected' : '' }} value="cancelled">Cancelled</option>   
                    </select>
                </div>
                <div class="input-group input-group-sm" style="width: 250px;margin:5px 0;">
                    @if(general()->sms_status)
                        <label style="cursor: pointer;padding: 0 5px;"><input type="checkbox" name="mail_sms"> Send SMS</label>
                    @endif
                    @if(general()->mail_status)
                        <label style="cursor: pointer;padding: 0 5px;"><input type="checkbox" name="mail_send"> Send Mail</label>
                    @endif
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit" style="width: 100%;background-color: #e91e63 !important;border-color: #e91e63;">
                        <i class="fa fa-check"></i>
                        Order Update
                    </button>
                </div>
            </th>
        </tr>
    </thead>
    </table>
</div>