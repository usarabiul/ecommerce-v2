@extends(adminTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Product View')}}</title>
@endsection 
@push('css')
<style type="text/css">
    .card .card-header::before {
        content: '';
        width: 0;
        height: 0;
        border-top: 20px solid #37a000;
        border-right: 20px solid transparent;
        position: absolute;
        left: 0;
        top: 0;
    }
</style>
@endpush 
@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Products List</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Products List</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            @isset(json_decode(Auth::user()->permission->permission, true)['products']['add'])
            <a class="btn btn-outline-primary" href="{{route('admin.products')}}">Back All</a>
            <a class="btn btn-outline-success" href="{{route('admin.productsAction',['edit',$product->id])}}">Edit</a>
            @endisset
            <a class="btn btn-outline-primary" href="{{route('admin.productsAction',['view',$product->id])}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>


@include(adminTheme().'alerts')

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Products View</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="row">
            
                <div class="col-md-4">
                <div class="productImage">
                    <img src="{{asset($product->image())}}" style="max-width:100%;">
                </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                    <tr>
                        <th style="min-width:250px;width:250px;">Name</th>
                        <td>{{$product->name}}</td>
                    </tr> 
                    <tr>
                        <th>Price</th>
                        <td>{{general()->currency}} {{priceFormat($product->final_price)}}</td>
                    </tr>  
                    <tr>
                        <th>Category </th>
                        <td>
                        @foreach($product->productCategories as $i=>$ctg)
                            {{$i==0?'':'-'}} {{$ctg->name}} 
                            @endforeach
                        </td>
                    </tr> 
                    <tr>
                        <th>Brand </th>
                        <td>{{$product->brand?$product->brand->name:''}}</td>
                    </tr> 
                    <tr>
                        <th>Stock </th>
                        <td>{{$product->quantity}} Qty</td>
                    </tr>
                    <tr>
                        <th>SKU </th>
                        <td>{{$product->sku_code}}</td>
                    </tr>  
                    <tr>
                        <th>Barcode </th>
                        <td>
                            {{$product->bar_code}} 
                            @if($product->bar_code)
                            <a class="badge badge-sm badge-primary" style="color: white;padding: 5px 15px;" data-toggle="modal" data-target="#barcode">
                                <i class="fa fa-barcode"></i> Barcode Print</a>
                            @endif
                        </td>
                    </tr>   
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Sales Report</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive">     
                <table class="table table-bordered">
                    <tr>
                        <th>SL</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Quantity</th>
                        <th>Sale Price</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                    
                    @foreach($product->salesAll()->latest()->limit(100)->get() as $i=>$sale)
                    <tr>
                        <td>{{$i+1}}</td>
                        <td>
                            @if($sale->order)
                            
                            @if($sale->order->order_type=='pos_order')
                            <a href="{{route('admin.invoice',$sale->order->id)}}" target="_blank">
                            @else
                            <a href="{{route('admin.posOrdersInvoice',$sale->order->id)}}" target="_blank">
                            @endif
                                {{$sale->order->invoice}}
                            </a>
                            @else
                            <span>No Found</span>
                            @endif
                        </td>
                        <td>{{$sale->order?$sale->order->name?:'Guest':'Not Found'}}</td>
                        <td>{{$sale->quantity}}</td>
                        <td>{{$sale->price}}</td>
                        <td>{{$sale->total_price}}</td>
                        <td>{{$sale->order?$sale->order->created_at->format('d-m-Y'):''}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>



 <!-- Modal -->
 <div class="modal fade text-left" id="barcode" >
   <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">

       <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel1">Barcode</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times; </span>
         </button>
       </div>
       <div class="modal-body" style="max-height:300px;overflow: auto;">
        @if($product->bar_code)
            <div class="printArea printable-content PrintAreaContact" >
                
               <div class="BarcodSection">
                @for($i=0;$i < 50;$i++)
                <img id="barcode1"/>
                <script>
                var name ="{{$product->bar_code}}";
                JsBarcode("#barcode1", name ,{format:"CODE128",});
                </script>
                @endfor
               </div>

            </div>
        @else
        <p>No Barcode</p>
        @endif
       </div>
       <div class="modal-footer">
         <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
         <button type="submit" id="PrintAction" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
       </div>

     </div>
   </div>
 </div>

@endsection 

@push('js')



@endpush
