@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Promotion Edit')}}</title>
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
@endpush 
@section('contents')

@include(adminTheme().'alerts')


        <div class="card">
            <div class="card-header d-flex">
                <h5 class="card-title mt-2 mb-0">Promotion Edit</h5>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="{{route('admin.ecommercePromotion')}}" type="button" class="btn btn-primary">Back</a>
                        <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                            <a class="dropdown-item" href="{{route('admin.ecommercePromotionAction','create')}}"><i class="bx bx-plus"></i>Add  Promotion </a>
                            <a class="dropdown-item" href="{{route('admin.ecommercePromotionAction',['edit',$coupon->id])}}"><i class="bx bx-refresh"></i> reload</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{route('admin.ecommercePromotionAction',['update',$coupon->id])}}" method="post">
                            @csrf
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="width: 150px;">Promotion name* </td>
                                    <td style="min-width: 300px;">
                                        <input type="text" name="name" value="{{$coupon->name}}" class="form-control form-control-sm" placeholder="Enter Promotion name" required="">
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
                                    <td>Promotion Descriptioin</td>
                                    <td>
                                        <textarea type="text" name="description" rows="5"  class="form-control form-control-sm" placeholder="Enter Promotion Description">{!!$coupon->description!!}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Promotion Type*</td>
                                    <td>
                                        <select class="form-control form-control-md" name="promotion_type" required="">
                                            <option value="">Select Type</option>
                                            <option value="Specific Categories" {{$coupon->location=='Specific Categories'?'selected':''}}>Specific Categories</option>
                                            <option value="All Products" {{$coupon->location=='All Products'?'selected':''}}>All Products</option>
                                            <option value="Specific Products" {{$coupon->location=='Specific Products'?'selected':''}}>Specific Products</option>
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
                                    <td>Status*</td>
                                    <td>
                                        <select class="form-control form-control-md" name="status" required="">
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
