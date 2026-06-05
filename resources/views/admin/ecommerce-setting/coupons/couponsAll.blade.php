@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Coupons List')}}</title>
@endsection 
@push('css')
<style type="text/css"></style>
@endpush 
@section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Coupons List</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.ecommerceCouponsAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Coupon</a>
            <a href="{{route('admin.ecommerceCoupons')}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

@include(adminTheme().'alerts')


<div class="card">
    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
        <h4 class="card-title">Coupons List</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <form action="{{route('admin.ecommerceCoupons')}}">
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
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th>SL</th>
                        <th>Coupon Code</th>
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


@endsection @push('js')

<script>

          

</script>

@endpush
