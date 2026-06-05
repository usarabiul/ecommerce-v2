@extends(adminTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Product Edit')}}</title>
@endsection
@push('css')
<style type="text/css">
    .catagorydiv {
        max-height: 300px;
        overflow: auto;
    }
    .catagorydiv ul {
        padding-left: 20px;
    }
    .catagorydiv ul li {
        list-style: none;
    }
    .areaChargeTable tr td{
        padding:4px;
    }

    .areaChargeTable tr th{
        padding:4px;
    }

    .form-control.error {
        border-color: #cf8b8b;
        background: #fed1d1;
    }
    .table tbody+tbody {
        border-top: 0px solid #98A4B8;
    }
    .tagList span {
        display: inline-block;
        padding: 4px 10px;
        border: 1px solid #e1d6d6;
        margin: 5px;
        border-radius: 5px;
    }
    .tagSearchResult {
        position: relative;
    }
    .tagSearchResult span {
        padding: 2px 8px;
        display: inline-block;
        border: 1px solid #ddd8d8;
        border-radius: 3px;
        margin: 3px;
        cursor: pointer;
        background: #f3f3f3;
    }
</style>
@endpush 

@section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Product Edit</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.products')}}" class="btn btn-outline-primary mr-2" >BACK</a>
            <a href="{{route('admin.productsAction','create')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-plus"></i> Add Product</a>
            <a href="{{route('admin.productsAction',['edit',$product->id])}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>

@include(adminTheme().'alerts')
<form action="{{route('admin.productsAction',['update',$product->id])}}" class="mainformDATA" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Product Edit 
                    @if($product->slug)
                    <a href="{{route('productView',$product->slug?:'no-slug')}}" class="badge badge-success float-right" target="_blank">View</a>
                    @endif
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Product Name </label>
                            <input type="text" class="form-control titleForSlug {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Product Name" value="{{$product->name?:old('name')}}" required="" />
                            @if ($errors->has('name'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        <div class="mb-3 input-group">
                            <label class="slugEdit" style="cursor: pointer;width: 130px;padding: 6px;margin:0;background: #c6c9d5;"><span>
                                @if($product->auto_slug)
                                Custom Slug <i class="fa fa-edit"></i>
                                @else    
                                Auto Slug
                                @endif
                            </span></label>
                            <input type="text" class="slugEditData form-control {{$errors->has('slug')?'error':''}}"
                                @if($product->auto_slug) 
                                    name="slug"
                                @else
                                disabled
                                @endif
                            placeholder="Product Slug" value="{{$product->slug?:old('slug')}}" />
                        </div>
                        <div class="form-group">
                            <label for="short_description">Short Description </label>
                            <textarea name="short_description" class="form-control {{$errors->has('short_description')?'error':''}}" placeholder="Enter Short Description">{!!$product->short_description!!}</textarea>
                            @if ($errors->has('short_description'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('short_description') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="description">Description </label>
                            <textarea name="description" class="{{$errors->has('description')?'error':''}} tinyEditor" placeholder="Enter Description">{!!$product->description!!}</textarea>
                            @if ($errors->has('description'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('description') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Product Data</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                            <ul class="nav nav-tabs" role="tablist" style="border-bottom: none;">
                                <li class="nav-item">
                                    <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="tab1" href="#tab1" role="tab" aria-selected="true"><i class="fas fa-cog"></i> General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="tab2" href="#tab2" role="tab" aria-selected="false"><i class="fas fa-truck"></i> Shipping</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="tab3" href="#tab3" role="tab" aria-selected="false"><i class="fas fa-exchange-alt"></i> Attributes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="base-tab4" data-toggle="tab" aria-controls="tab4" href="#tab4" role="tab" aria-selected="false"><i class="fas fa-tags"></i> Others</a>
                                </li>
                            </ul>
                            <div class="tab-content px-1 pt-1" style="border: 1px solid #ddd;">
                                <div class="tab-pane active" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                                    @include('admin.products.includes.productsDataGeneral')
                                </div>
                                <div class="tab-pane" id="tab2" role="tabpanel" aria-labelledby="base-tab2">
                                    @include('admin.products.includes.productsDataShipping')
                                </div>
                                <div class="tab-pane ProAttributesItemsnNew" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                                {{-- 
                                    @include('admin.products.includes.productsDataAttributes')
                                --}}
                                    @include('admin.products.includes.productsDataAttributes2')
                                    </div>
                                <div class="tab-pane" id="tab4" role="tabpanel" aria-labelledby="base-tab4">
                                    @include('admin.products.includes.productsDataOthers')
                                </div>
                            </div>


                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">SEO Optimize</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="seo_title">SEO Meta Title</label>
                            <input type="text" class="form-control {{$errors->has('seo_title')?'error':''}}" name="seo_title" placeholder="Enter SEO Meta Title" value="{{$product->seo_title?:old('seo_title')}}" />
                            @if ($errors->has('seo_title'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_title') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="seo_desc">SEO Meta Description </label>
                            <textarea name="seo_desc" class="form-control {{$errors->has('seo_desc')?'error':''}}" placeholder="Enter SEO Meta Description">{!!$product->seo_desc!!}</textarea>
                            @if ($errors->has('seo_desc'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_desc') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="seo_keyword">SEO Meta Keyword </label>
                            <textarea name="seo_keyword" class="form-control {{$errors->has('seo_keyword')?'error':''}}" placeholder="Enter SEO Meta Keyword">{!!$product->seo_keyword!!}</textarea>
                            @if ($errors->has('seo_keyword'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_keyword') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Product Images</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" name="image" class="form-control {{$errors->has('image')?'error':''}}" />
                            @if ($errors->has('image'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('image') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <img src="{{asset($product->image())}}" style="max-width: 100px;" />
                            @isset(json_decode(Auth::user()->permission->permission, true)['products']['add'])
                            @if($product->imageFile)
                            <a href="{{route('admin.mediesDelete',$product->imageFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                            @endif
                            @endisset
                        </div>
                        <div class="form-group">
                            <label for="gallery_image">Product Gallery</label>
                            <input type="file" name="gallery_image[]" class="form-control {{$errors->has('gallery_image')?'error':''}}"  multiple="" />
                            @if ($errors->has('gallery_image'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('gallery_image') }}</p>
                            @endif
                        </div>
                        <div class="row">
                            @foreach($product->galleryFiles as $gallery)
                            <div class="col-md-4 form-group">
                            <img src="{{asset($gallery->file_url)}}" style="max-width: 60px;max-height: 60px" />

                            @isset(json_decode(Auth::user()->permission->permission, true)['products']['add'])
                            <a href="{{route('admin.mediesDelete',$gallery->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                            @endisset
                            </div>
                            @endforeach
                            
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Product Category</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if ($errors->has('categoryid*'))
                        <p style="color: red; margin: 0; font-size: 10px;">The Category Must Be a Number</p>
                        @endif
                        <div class="catagorydiv">
                            <ul>
                                @foreach($categories as $ctg)
                                <li>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" value="{{$ctg->id}}" id="category_{{$ctg->id}}" name="categoryid[]" @foreach($product->productCtgs as $postctg)
                                        {{$postctg->reff_id==$ctg->id?'checked':''}} @endforeach/>
                                        <label class="custom-control-label" for="category_{{$ctg->id}}">{{$ctg->name}}</label>
                                    </div>
                                    @if($ctg->subctgs->count() >0) @include('admin.products.includes.productEditSubctg',['subcategories' => $ctg->subctgs,'i'=>1]) @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Product Tags</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="tagSearch">
                            <div class="input-group">
                                <input type="text" data-url="{{route('admin.productsAction',['search-tag',$product->id])}}" class="form-control form-control-sm tagSearchInput" placeholder="Enter Tag">
                                <span class="btn btn-success rounded-0 addNewTag" data-url="{{route('admin.productsAction',['new-tag',$product->id])}}" style="cursor:pointer;padding: 8px 15px;"><i class="fa fa-plus"></i></span>
                            </div>
                            <div class="tagSearchResult"></div>
                        </div>
                        <br>
                        <div class="tagList">
                            @include(adminTheme().'products.includes.productTagList')
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Product Brand</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if ($errors->has('brand'))
                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('brand') }}</p>
                        @endif
                        <select name="brand" class="form-control">
                            <option value="">Select Brands</option>
                            @foreach($brands as $i=>$brand)
                            <option value="{{$brand->id}}" {{$product->brand_id==$brand->id?'selected':''}} >{{$brand->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                    <h4 class="card-title">Product Action</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="status">Product Status</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="status" name="status" {{$product->status=='active'?'checked':''}}/>
                                    <label class="custom-control-label" for="status">Active</label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label for="fetured">Product Featured</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="featured" name="featured" {{$product->featured?'checked':''}}/>
                                    <label class="custom-control-label" for="featured">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Published Date</label>
                            <input type="date" class="form-control form-control-sm" name="created_at" value="{{$product->created_at->format('Y-m-d')}}">
                            @if ($errors->has('created_at'))
                            <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('created_at') }}</p>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>



@endsection @push('js')
<script>
    $(document).ready(function(){

        $(".tagSearchResult").hide();
        $(document).on('click', function(e) {
            var container = $(".tagSearch");
            var containerClose = $(".tagSearchResult");
            if (!$(e.target).closest(container).length) {
                containerClose.hide();
            }else{
                containerClose.show();
            }
        });


        $(document).on('click','.SubmitSingleProduct',function() {
            $('.SubmitSingleForm').submit();
        });

        $(document).on('change','.SubmitSingleProduct1',function() {
            $('.SubmitSingleForm').submit();
        });

        ///Product Price Jquery

        $(document).on('change keyup mouseup','.priceUpdate',function(){

            var Rprice =parseInt($('.regular_price').val());

            var Dtype =$('.discounttype').val();

            var discount =parseInt($('.discount').val());

            var final_price=0;

            if(Rprice < 1){
                final_price =0;
            }

            if(Dtype=='percent'){

                if(discount < 100){
                final_price =Rprice - Rprice * discount /100 ;
                }else{
                final_price =Rprice;
                }
                
            }else{

                if(Rprice > discount){
                final_price =Rprice - discount;
                }else{
                final_price =Rprice;
                }

            }
            
            $('.final_price').val(final_price);

        });

            

          ///Product Price Jquery

        ///Product Ajax Update Data Jquery
        $(document).on('change','.productDataAjaxUpdate',function(){

            var url =$(this).data('url');
            var data =$(this).val();

            $.ajax({
                url:url,
                dataType: 'json',
                cache: false,
                data:{data:data},
                success : function(data){

                },error: function () {
                    alert('error');
                }
            });

        });
        ///Product Ajax Update Data Jquery

        ///Product Attribute Filters Jquery
        $(document).on('change','.attributesItemFilter',function(){
            var url =$(this).data('url');
            var attriID =$(this).val();

            $.ajax({
                url:url,
                dataType: 'json',
                cache: false,
                data:{attriID:attriID},
                success : function(data){
                $('.AttributeItems').empty().append(data.viewData);
                },error: function () {
                    alert('error');
                }
            });

        });
        ///Product Attribute Filters Jquery

        ///Product Attribute Add Jquery

        $(document).on('click','.attributesVariationAddItem',function(){
            var url =$(this).data('url');
            var attriID = [];
            $('input[name="attributesVariationAddItemId[]"]:checked').each(function() {
                attriID.push($(this).val());
            });
            attributeAjaxAction(url,attriID);
            
        });
        
        $(document).on('click','.variationItemsDeleteBtn',function(){
            var url =$(this).data('url');
            var attriID = [];
            $('input[name="checkid[]"]:checked').each(function() {
                attriID.push($(this).val());
            });
            attributeAjaxAction(url,attriID);
            // alert(attriID);
        });
        
        $(document).on('click','.variationItemAttribute',function(){
            var url =$(this).data('url');
            var formData = $('.varitaionItemproduct').find('select, input').serialize();
            $.ajax({
                url:url,
                dataType: 'json',
                cache: false,
                data:formData,
                success : function(data){
                    
                $('#AddAttributesVaritaionItem').modal('hide');
                setTimeout(function() {
                    $('.ProAttributesItemsnNew').empty().append(data.viewData);
                }, 200);

                },error: function () {
                    alert('error');
                }
            });
            //attributeAjaxAction(url,attriID);
            //alert(formData);
        });

        $(document).on('click','.attributesAddItem',function(){
            var url =$(this).data('url');
            var attriID = [];
            $('input[name="attributesAddItemId[]"]:checked').each(function() {
                attriID.push($(this).val());
            });
            
            attributeAjaxAction(url,attriID);
        });
        
        function attributeAjaxAction(url,attriID){
            $.ajax({
                url:url,
                dataType: 'json',
                cache: false,
                data:{attriID:attriID},
                success : function(data){
                    
                $('#AddAttributes').modal('hide');
                $('#AddAttributesVaritaion').modal('hide');
                setTimeout(function() {
                    $('.ProAttributesItemsnNew').empty().append(data.viewData);
                }, 200);

                },error: function () {
                    alert('error');
                }
            });
        }




        $(document).on('click','.attributesItemAdd',function(){
            var url =$(this).data('url');
            var attriID =$('.attributesItemAddId').val();
            if(attriID){
                $.ajax({
                    url:url,
                    dataType: 'json',
                    cache: false,
                    data:{attriID:attriID},
                    success : function(data){
                    $('.attributesItemAddId').val('');
                    $('.AttributeItemsList').empty().append(data.viewData);
                    $('.priceVariationDiv').empty().append(data.viewData2);
                    },error: function () {
                        alert('error');
                    }
                });
            }else{
                $('.attributesItemAddId').addClass('error'); 
            }
        });
        ///Product Attribute Add Jquery

        ///Product Attribute Update Jquery
        $(document).on('change','.attributesItemColor',function(){
            var url =$(this).data('url');
            var attriID =$(this).data('id');
            var attriValue =$(this).val();
            if(attriID && attriValue){
                $.ajax({
                    url:url,
                    dataType: 'json',
                    cache: false,
                    data:{attriID:attriID,attriValue:attriValue},
                    success : function(data){
                    $('.AttributeItemsList').empty().append(data.viewData);
                    },error: function () {
                        alert('error');
                    }
                });
            }
        });

        $(document).on('change','.attributesItemImage',function(){
            var url =$(this).data('url');
            var attriID =$(this).data('id');

            var file_data = $(this).prop('files')[0];
            var form_data = new FormData();

            form_data.append('attriValue', file_data);
            form_data.append('attriID', attriID);

            $.ajax({
                url:url,
                type:'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data:form_data,
                success : function(data){
                $('.AttributeItemsList').empty().append(data.viewData);
                },error: function () {
                    alert('error');
                }
            });

        });

        ///Product Attribute Update Jquery

        ///Product Attribute Delete Jquery
        $(document).on('click','.attributesItemDelete',function(){
            var url =$(this).data('url');
            var attriID =$(this).data('id');

            if(confirm('Are You Want To Delete?') && attriID){
                $.ajax({
                  url:url,
                  dataType: 'json',
                  cache: false,
                  data:{attriID:attriID},
                   success : function(data){
                    $('.AttributeItemsList').empty().append(data.viewData);
                    $('.priceVariationDiv').empty().append(data.viewData2);
                   },error: function () {
                      alert('error');
                    }
                });
            }
          
        });
        ///Product Attribute Delete Jquery

        ///Product Extra Attribute Add Jquery

        $(document).on('click','.extraAttributeTitle,.extraAttributeValue,.attributesItemAddId',function(){
            $('.extraAttributeTitle').removeClass('error');
            $('.extraAttributeValue').removeClass('error');
            $('.attributesItemAddId').removeClass('error');
        });


        $(document).on('click','.extraAttributeAdd',function(){

            var url =$(this).data('url');
            var title =$('.extraAttributeTitle').val();
            var value =$('.extraAttributeValue').val();

            if(title==''){
                $('.extraAttributeTitle').addClass('error');
            }

            if(value==''){
                $('.extraAttributeValue').addClass('error');
            }

            if(title && value){
                
                $.ajax({
                    url:url,
                    dataType: 'json',
                    cache: false,
                    data:{title:title,value:value},
                    success : function(data){
                    $('.extraAttributeTitle').val('');
                    $('.extraAttributeValue').val('')
                    $('.extraAttributeList').empty().append(data.viewData);
                    },error: function () {
                        alert('error');
                    }
                });

            }

        });
        ///Product Extra Attribute Add Jquery

        ///Product Extra Attribute Delete Jquery

        $(document).on('click','.extraAttributeDelete',function(){

                var url =$(this).data('url');
                var attriID =$(this).data('id');

                if(confirm('Are You Want To Delete?')){

                    $.ajax({
                      url:url,
                      dataType: 'json',
                      cache: false,
                      data:{attriID:attriID},
                       success : function(data){
                        $('.extraAttributeList').empty().append(data.viewData);
                       },error: function () {
                          alert('error');
                        }
                    });

                }

        });


        ///Product Extra Attribute Delete Jquery

        ///Product Extra Attribute Delete Jquery
        $(document).on('click','.priceVariationStatus',function(){
            var url =$(this).data('url');
            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
               success : function(data){
               $('.priceVariationDiv').empty().append(data.viewData);
               },error: function () {
                  alert('error');
                }
            });

        });
          
        ///Product Extra Attribute Delete Jquery

        ///Product Extra Attribute Delete Jquery
        $(document).on('click','.variationItemsAdd',function(){

            var url =$(this).data('url');
            var form =$('.mainformDATA');
            var data =form.serialize();
            var color =$('.variationColor').val();
            var size =$('.variationSize').val();
            $.ajax({
              url:url,
              dataType: 'json',
              cache: false,
              data:{color:color,size:size},
               success : function(data){
               $('.priceVariationDiv').empty().append(data.viewData);
               },error: function () {
                  alert('error');
                }
            });

        });
          
        ///Product Variationi item Delete Jquery
        $(document).on('click','.variationItemsDelete',function(){
            
            var url =$(this).data('url');
            var skuId =$(this).data('id');

            if(confirm('Are You Want To Delete?') && skuId){
                $.ajax({
                  url:url,
                  dataType: 'json',
                  cache: false,
                  data:{skuId:skuId},
                   success : function(data){
                    $('.priceVariationDiv').empty().append(data.viewData);
                   },error: function () {
                      alert('error');
                    }
                });
            }
          
        });

        $(document).on('change','.variationItemsUpdate',function(){

            var url =$(this).data('url');
            var item_id =$(this).data('id');
            var item_key =$(this).val();

            $.ajax({
                url:url,
                dataType: 'json',
                cache: false,
                data:{item_id:item_id,item_key:item_key},
                success : function(data){
                //$('.priceVariationDiv').empty().append(data.viewData);
                },error: function () {
                    alert('error');
                }
            });
          
        });
          
        ///Product Extra Attribute Delete Jquery

        ///Product Tags Jquery Start

        $(document).on('click','.addNewTag',function(){
            var url =$(this).data('url');
            var key =$('.tagSearchInput').val();
            var status=true;
            if(key=='undefined' || key=='' || key==null){
                status=false;
                $('.tagSearchInput').addClass('error');
            }
            if(key.length < 3){
                alert('Minimum 3 Chatecter');
                status=false;
            }
            if(status){
                $('.tagSearchInput').val('');
                $('.tagSearchResult').empty();
                $.ajax({
                    url:url,
                    dataType: 'json',
                    cache: false,
                    data:{key:key},
                    success : function(data){
                        $('.tagList').empty().append(data.viewData);
                    },error: function () {
                        //alert('error');
                        }
                });
            }
        });

        $(document).on('click','.tagList .remove',function(){
            var url =$(this).data('url');
            var key;
            $.ajax({
                url:url,
                dataType: 'json',
                cache: false,
                data:{key:key},
                success : function(data){
                    $('.tagList').empty().append(data.viewData);
                },error: function () {
                    //alert('error');
                }
            });
        });
        $(document).on('click','.tagSearchResult span',function(){
            var url =$(this).data('url');
            var key;
            $.ajax({
                url:url,
                dataType: 'json',
                cache: false,
                data:{key:key},
                success : function(data){
                    $('.tagList').empty().append(data.viewData);
                },error: function () {
                    //alert('error');
                }
            });
        });

        $('.tagSearchInput').keyup(function(){
            $(this).removeClass('error');
            var url =$(this).data('url');
            var key =$(this).val();
            $.ajax({
                url:url,
                dataType: 'json',
                cache: false,
                data:{key:key},
                success : function(data){
                    $('.tagSearchResult').empty().append(data.viewData);
                },error: function () {
                    //alert('error');
                }
            });

        });

        

          


    });
</script>

@endpush
