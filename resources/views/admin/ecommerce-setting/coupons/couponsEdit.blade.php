@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Coupon Edit')}}</title>
@endsection 
@push('css')
<style type="text/css">
    
    .searchResult {
        position: absolute;
        width: 100%;
        background: #f3f3f3;
        display:none;
        z-index: 99;
        max-height: 200px;
        overflow: auto;
    }
    
    .searchSection {
        position: relative;
    }
    
    .searchResult ul {
        margin: 0;
        padding: 0;
    }
    
    .searchResult ul li {
        list-style: none;
        padding: 5px 15px;
        border-bottom: 1px solid #e1e1e1;
        cursor: pointer;
    }
    .productGrid {
        border: 1px solid #e3e3e3;
        padding: 5px;
        text-align: center;
    }
    
    .productGrid img {
        max-width: 100%;
        max-height: 100px;
        margin: auto;
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
                    <li class="breadcrumb-item active" aria-current="page">Coupon Edit</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.ecommerceCouponsAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Coupon</a>
            <a href="{{route('admin.ecommerceCoupons')}}" type="button" class="btn btn-success mr-2">Back</a>
            <a href="{{route('admin.ecommerceCouponsAction',['update',$coupon->id])}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

@include(adminTheme().'alerts')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">Coupon Edit</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{route('admin.ecommerceCouponsAction',['update',$coupon->id])}}" method="post">
                            @csrf
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="width: 150px;">Coupon Code </td>
                                    <td style="width: 300px;min-width: 300px;">
                                        <input type="text" name="name" value="{{$coupon->name}}" class="form-control form-control-sm" placeholder="Enter Coupon Code" required="">
                                        @if ($errors->has('name'))
                                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" name="discount" value="{{$coupon->amounts>0?$coupon->amounts:''}}" class="form-control form-control-sm" placeholder="Discount" required="">
                                            <select class="form-control form-control-sm" name="discount_type">
                                                <option value="0" {{$coupon->menu_type==0?'selected':''}} >Percentage(%)</option>
                                                <option value="1" {{$coupon->menu_type==1?'selected':''}} >Flat({{general()->currency}})</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('discount'))
                                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('discount') }}</p>
                                        @endif
                                        @if ($errors->has('discount_type'))
                                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('discount_type') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shopping</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" name="min_shopping" value="{{$coupon->min_shopping>0?$coupon->min_shopping:''}}" class="form-control form-control-sm" placeholder="Min Shopping" >
                                            <input type="number" name="max_shopping" value="{{$coupon->max_shopping>0?$coupon->max_shopping:''}}" class="form-control form-control-sm" placeholder="Max Shopping" >
                                        </div>
                                        @if ($errors->has('min_shopping'))
                                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('min_shopping') }}</p>
                                        @endif
                                        @if ($errors->has('max_shopping'))
                                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('max_shopping') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Validity Date</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="date" name="start_date" value="{{$coupon->start_date?carbon\carbon::parse($coupon->start_date)->format('Y-m-d'):''}}" class="form-control form-control-sm" >
                                            <input type="date" name="end_date" value="{{$coupon->end_date?carbon\carbon::parse($coupon->end_date)->format('Y-m-d'):''}}" class="form-control form-control-sm" >
                                        </div>
                                        @if ($errors->has('start_date'))
                                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('start_date') }}</p>
                                        @endif
                                        @if ($errors->has('end_date'))
                                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('end_date') }}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Coupon Descriptioin</td>
                                    <td>
                                        <textarea type="text" name="description" rows="5"  class="form-control form-control-sm" placeholder="Enter Coupon Description">{!!$coupon->description!!}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Coupon Type</td>
                                    <td>
                                        <select class="form-control form-control-sm" name="coupon_type" required="">
                                            <option value="order" {{$coupon->location=='product'?'selected':''}}>Order Coupon</option>
                                            <option value="product" {{$coupon->location=='product'?'selected':''}}>Product</option>
                                            <option value="category" {{$coupon->location=='category'?'selected':''}}>Categories</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Categories</td>
                                    <td>
                                        <select data-placeholder="Search Category..." name="categories[]" class="select2 form-control" multiple="multiple">
                                            <option vaue="">Select Categories</option>
                                            @foreach($categories as $ctg)
                                            <option value="{{$ctg->id}}"
                                            @foreach($coupon->couponCtgs as $postctg)
                                            {{$postctg->reff_id==$ctg->id?'selected':''}} 
                                            @endforeach
                                            >{{$ctg->name}}</option>
                                            @if($ctg->subCtgs()->where('status','active')->count() > 0)
                                            @foreach($ctg->subCtgs()->where('status','active')->get() as $subCtg)
                                            <option value="{{$subCtg->id}}" 
                                            @foreach($coupon->couponCtgs as $postctg)
                                            {{$postctg->reff_id==$subCtg->id?'selected':''}} 
                                            @endforeach
                                            > - {{$subCtg->name}}</option>
                                            @endforeach
                                            @endif
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        <select class="form-control form-control-sm" name="status" required="">
                                            <option value="">Select Status</option>
                                            <option value="active" {{$coupon->status=='active'?'selected':''}}>Active</option>
                                            <option value="inactive" {{$coupon->status=='inactive'?'selected':''}}>Inactive</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Action</td>
                                    <td>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">Coupon Products</h4>
            </div>
            <div class="card-content">
                <div class="card-body" style="min-height: 400px;">
                <div class="row m-0">
                    <div class="col-md-6" style="padding:5px;">
                        <div class="form-group searchSection">
                            <input type="text" class="form-control searchProduct" data-url="{{route('admin.ecommerceCouponsAction',['search-product',$coupon->id])}}" placeholder="Enter Search Product">
                            <div class="searchResult"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="couponProduucts">
                    @include(adminTheme().'ecommerce-setting.includes.couponProductsList')
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
    $('.searchProduct').click(function(){
        $(".searchResult").show();
    });
    
    $(document).click(function(event){
        if (!$(event.target).closest('.searchSection').length) {
            $(".searchResult").hide();
        }
    });
    
    
    $('.searchProduct').keyup(function(){
        var search =$(this).val();
        var url =$(this).data('url');
        
        if(url){
            $.ajax({
              url: url,
              type: 'GET',
              dataType: 'json',
              cache: false,
              data:{'search':search}
            })
            .done(function(data) {
                $(".searchResult").empty().append(data.view);
            })
            .fail(function() {
              // alert("error");
            });
        }
        
    });

    $(document).on("click", ".searchResult ul li", function(){
        $(this).remove();
        var url =$(this).data('url');
        if(url){
            $.ajax({
              url: url,
              type: 'GET',
              dataType: 'json',
              cache: false,
            })
            .done(function(data) {
                $(".couponProduucts").empty().append(data.view);
            })
            .fail(function() {
              // alert("error");
            });
        }
        
    });
    
    $(document).on('click','.checkAll',function(){
        var checked = $(this).prop('checked');
        $('input.counponCheck:checkbox').prop('checked', checked);
    });
    
    $(document).on('click','.counponProductDelete',function(){
        var checkedId = [];
        $('.counponCheck').each(function(){
            if($(this).prop('checked')) {
                checkedId.push($(this).val());
            }
        });
        var url =$(this).data('url');
        if(confirm('Are You Want To Delete?')){
            $.ajax({
              url: url,
              type: 'GET',
              dataType: 'json',
              cache: false,
              data:{'checkedId':checkedId}
            })
            .done(function(data) {
                $(".couponProduucts").empty().append(data.view);
            })
            .fail(function() {
              // alert("error");
            });
        }
        
    });
    
    
    
    
    
});
</script>

@endpush
