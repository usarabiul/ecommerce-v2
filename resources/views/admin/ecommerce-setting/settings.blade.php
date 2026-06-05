@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('General Setting')}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush 
@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">General Setting</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">General Setting</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        @include('admin.alerts')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">General Setting</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="table-responsive">
                                            <form action="{{route('admin.ecommerceSetting','general')}}" method="post">
                       				        @csrf
                                            <table class="table table-borderless">
                                            <tr>
                                                <td style="min-width:150px;"></td>
                                                <td style="min-width:350px;"></td>
                                            </tr>
                                             <tr>
                                                 <td>Finance Currency</td>
                                                 <td style="padding: 3px;">
                                                     <div class="form-group">
                	                                     <div class="input-group">
                	                                     <input type="text" name="currency" value="{{ general()->currency }}" placeholder="Finanmce Currency" class="form-control-sm form-control {{$errors->has('currency')?'error':''}}" />
                	                                     <select class="form-control form-control-sm" name="currency_decimal">
                	                                     		<option value="0" {{general()->currency_decimal==0?'selected':''}} >0 Decimal</option>
                	                                     		<option value="1" {{general()->currency_decimal==1?'selected':''}} >0.0 Decimal</option>
                	                                     		<option value="2" {{general()->currency_decimal==2?'selected':''}} >0.00 Decimal</option>
                	                                     </select>
                	                                     <select class="form-control form-control-sm" name="currency_position">
                	                                     		<option value="0" {{general()->currency_position==0?'selected':''}} >Left Position</option>
                	                                     		<option value="1" {{general()->currency_position==1?'selected':''}} >Right Position</option>
                	                                     </select>
                	                                     </div>
                	                                     @if ($errors->has('currency'))
												    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('currency') }}</p>
												    	@endif
                	                                 </div>
                                                 </td>
                                             </tr>
                                             <tr>
                                                 <th>Shipping Charge</th>
                                                 <td style="padding:3px;">
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-sm" name="inside_dhaka_shipping_charge" value="{{general()->inside_dhaka_shipping_charge}}" placeholder="Inside Dhaka Shipping Charge">
                                                        <input type="number" class="form-control form-control-sm" name="outside_dhaka_shipping_charge" value="{{general()->outside_dhaka_shipping_charge}}" placeholder="Outside Dhaka Shipping Charge">
                                                    </div>
                                                 </td>
                                             </tr>
                                             <tr>
                                                 <th>Tax (%)</th>
                                                 <td style="padding:3px;">
                                                     <div class="input-group">
                                                       <input type="number" class="form-control form-control-sm" name="tax" value="{{general()->tax}}" placeholder="Enter Tax">
                                                       <select class="form-control form-control-sm" name="tax_status">
                                                           <option value="1" {{general()->tax_status==1?'selected':''}}>Tax applicable</option>
                                                           <option value="0" {{general()->tax_status==0?'selected':''}}>No Tax</option>
                                                       </select>
                                                     </div>
                                                    
                                                 </td>
                                             </tr>
                                             <tr>
                                                 <th>Action</th>
                                                 <td style="padding:3px;">
                                                    <button type="submit" class="btn btn-info" style="padding:5px 10px;">Update</button>
                                                 </td>
                                             </tr>
                                         </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>


    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js')

<script>

          

</script>

@endpush
