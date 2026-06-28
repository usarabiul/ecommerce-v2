@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('General Setting')}}</title>
@endsection 
@push('css')
<style type="text/css"></style>
@endpush 
@section('contents')


@include(adminTheme().'alerts')

<div class="card">
    <div class="card-header d-flex">
        <h4 class="card-title">Promotion List</h4>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{route('admin.ecommercePromotion')}}" title="Reload" class="btn btn-primary"><i class="bx bx-refresh"></i></a>
                <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown </span>
                </button>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                    <a class="dropdown-item" href="{{route('admin.ecommercePromotionAction','create')}}" ><i class="bx bx-plus"></i> Add Client </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{route('admin.ecommercePromotion')}}">
                <div class="row">
                    <div class="col-md-12 mb-0">
                        <div class="input-group">
                            <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Coupon Code" class="form-control {{$errors->has('search')?'error':''}}" />
                            <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
             <div class="table-responsive">
                <table class="table mb-0  table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Validity</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($coupons as $i=>$coupon)
                    <tr>
                        <td>{{$i+1}}</td>
                        <td>{{$coupon->name}}</td>
                        <td>
                            
                            @if($coupon->start_date && $coupon->end_date)
                                {{carbon\carbon::parse($coupon->start_date)->format('d-m-Y')}} - 
                                {{carbon\carbon::parse($coupon->end_date)->format('d-m-Y')}} 
                                
                                @if(Carbon\Carbon::now() < carbon\carbon::parse($coupon->end_date))
                                ( {{carbon\carbon::now()->diffInDays(carbon\carbon::parse($coupon->end_date))+1}} Days)
                                @else
                                <span class="text-danger">(Expired)</span>
                                @endif
                            @elseif($coupon->start_date && $coupon->end_date==null)
                                <span>{{carbon\carbon::parse($coupon->start_date)->format('d-m-Y')}} - Unlimited</span>
                            @elseif($coupon->start_date==null && $coupon->end_date)
                                <span>{{carbon\carbon::parse($coupon->end_date)->format('d-m-Y')}}
                                @if(Carbon\Carbon::now() < carbon\carbon::parse($coupon->end_date))
                                ( {{carbon\carbon::now()->diffInDays(carbon\carbon::parse($coupon->end_date))+1}} Days)
                                @else
                                <span class="text-danger">(Expired)</span>
                                @endif
                                </span>
                            @else
                                <span>Unlimited</span>
                            @endif
                            
                        </td>
                        <td>
                            
                            @if($coupon->menu_type==1)
                            <span>{{priceFullFormat($coupon->amounts)}}</span>
                            @else
                            <span>{{$coupon->amounts>0?$coupon->amounts:0}}%</span>
                            @endif
                            
                            @if($coupon->min_shopping>0 && $coupon->max_shopping>0)
                            (Min:{{priceFullFormat($coupon->min_shopping)}} -  Max:{{priceFullFormat($coupon->max_shopping)}})
                            @elseif($coupon->min_shopping>0)
                            (Min:{{priceFullFormat($coupon->min_shopping)}})
                            @elseif($coupon->max_shopping>0)
                            (Max:{{priceFullFormat($coupon->max_shopping)}})
                            @endif
                            
                        </td>
                        <td>
                            @if($coupon->status=='active')
                            <span class="badge badge-success">{{ucfirst($coupon->status)}}</span>
                            @else
                            <span class="badge badge-danger">{{ucfirst($coupon->status)}}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('admin.ecommerceCouponsAction',['edit',$coupon->id])}}" class="badge badge-success" ><i class="fa fa-edit"></i></a>
                            <a href="{{route('admin.ecommerceCouponsAction',['delete',$coupon->id])}}" class="badge badge-danger" onclick="return confirm('Are You Want To Delete?')"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    @if($coupons->count()==0)
                        <tr>
                            <td colspan="6" class="text-center">No Result Found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
             </div>
            {{$coupons->links('pagination')}}
        </div>
    </div>
</div>


@endsection 
@push('js')

<script>

          

</script>

@endpush