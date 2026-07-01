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
        <h4 class="card-title mt-2 mb-0">Promotion List</h4>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{route('admin.ecommercePromotion')}}" title="Reload" class="btn btn-primary"><i class="bx bx-refresh"></i></a>
                <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown </span>
                </button>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                    <a class="dropdown-item" href="{{route('admin.ecommercePromotionAction','create')}}" ><i class="bx bx-plus"></i> Add Promotion </a>
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
                            <input type="text" name="search" value="{{request()->search?request()->search:''}}" placeholder="Promotion Code" class="form-control {{$errors->has('search')?'error':''}}" />
                            <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
             <div class="table-responsive" style="min-height:300px;">
                <table class="table mb-0  table-hover">
                    <thead class="table-light">
                    <tr>
                        <th style="min-width: 40px;width: 40px;">SL</th>
                        <th style="min-width: 150px;">Coupon Name</th>
                        <th style="min-width: 160px;width: 160px;">Type</th>
                        <th style="min-width: 260px;width: 260px;">Validity</th>
                        <th style="min-width: 100px;width: 100px;">Discount</th>
                        <th style="min-width: 100px;width: 100px;">Status</th>
                        <th style="min-width: 80px;width: 80px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($promotions as $i=>$promotion)
                    <tr>
                        <td>{{$i+1}}</td>
                        <td>{{$promotion->name}}</td>
                        <td>
                            {{$promotion->location}}
                        </td>
                        <td>
                            
                            @php
                                $now = \Carbon\Carbon::now();
                                $startDate = $promotion->start_date ? \Carbon\Carbon::parse($promotion->start_date) : null;
                                $endDate = $promotion->end_date ? \Carbon\Carbon::parse($promotion->end_date) : null;
                                $daysLeft = $endDate ? (int) $now->diffInDays($endDate) + 1 : 0;
                            @endphp

                            @if($startDate && $endDate)
                                {{ $startDate->format('d-m-Y') }} - {{ $endDate->format('d-m-Y') }}
                                
                                @if($now->lessThan($endDate))
                                    ({{ $daysLeft }} Days)
                                @else
                                    <span class="text-danger">(Expired)</span>
                                @endif

                            @elseif($startDate && !$endDate)
                                <span>{{ $startDate->format('d-m-Y') }} - Unlimited</span>

                            @elseif(!$startDate && $endDate)
                                <span>
                                    {{ $endDate->format('d-m-Y') }}
                                    @if($now->lessThan($endDate))
                                        ({{ $daysLeft }} Days)
                                    @else
                                        <span class="text-danger">(Expired)</span>
                                    @endif
                                </span>

                            @else
                                <span>Unlimited</span>
                            @endif
                            
                        </td>
                        <td>
                            
                            @if($promotion->menu_type==1)
                            <span>{{priceFullFormat($promotion->amount)}}</span>
                            @else
                            <span>{{$promotion->amount>0?$promotion->amount:0}}%</span>
                            @endif
                        </td>
                        
                        <td>
                            @if($promotion->status=='active')
                            <span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">{{ucfirst($promotion->status)}}</span>
                            @else
                            <span class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">{{ucfirst($promotion->status)}}</span>
                            @endif
                        </td>
                        <td style="text-align:center;padding: 3px;">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary split-bg-primary" data-bs-toggle="dropdown">	
                                    <span class="bx bx-dots-vertical"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                    <a class="dropdown-item" href="{{route('pageView',$promotion->slug?:'no-slug')}}" target="_blank"><i class="bx bxs-show"></i> View </a>
                                    <a class="dropdown-item" href="{{route('admin.ecommercePromotionAction',['edit',$promotion->id])}}"><i class="bx bxs-edit"></i> Edit </a>
                                    <a class="dropdown-item text-danger" href="{{route('admin.ecommercePromotionAction',['delete',$promotion->id])}}" onclick="return confirm('Are you sure you want to delete?')">
                                        <i class="bx bxs-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($promotions->count()==0)
                        <tr>
                            <td colspan="7" class="text-center">No Result Found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
             </div>
            {{$promotions->links('pagination')}}
        </div>
    </div>
</div>


@endsection 
@push('js')

<script>

          

</script>

@endpush