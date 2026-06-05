
<div class="ProAttributesItems">
    @if($product->productAttibutes->count() > 0)
    <span class="btn"  style="border: 1px solid #e7e3e3;margin-bottom: 10px;background: #f7f7f7;" data-toggle="modal" data-target="#AddAttributes" >Edit Attributes</span>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered">
            @foreach($product->productAttibutes()->whereHas('attribute')->select('reff_id')->groupBy('reff_id')->get() as $attri)
            <tr>
                <th style="width: 200px;min-width: 200px;vertical-align: middle;">{{$attri->attribute->name}}</th>
                <td style="padding: 3px;min-width:300px;">
                    @foreach($product->productAttibutes()->whereHas('attributeItem')->where('reff_id',$attri->reff_id)->get() as $item)
                    <span style="vertical-align: middle;border: 1px solid #dfd9d9;display: inline-block;margin-bottom: 5px;padding: 5px 10px;border-radius: 5px;">{{$item->attributeItem?$item->attributeItem->name:'Not Found'}}</span>
                    @endforeach

                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @else
    <span class="btn"  style="border: 1px solid #e7e3e3;margin-bottom: 10px;background: #f7f7f7;" data-toggle="modal" data-target="#AddAttributes" >Add Attributes</span>
    <br>
    <span style="color: #b7b7b7;">
        Adding new attributes helps the product to have many attributes.
    </span>
    @endif
</div>
<hr>

<div class="ProVariationAttribute">
    @if($product->productVariationAttibutes->count() > 0)
    <span class="btn" style="border: 1px solid #e7e3e3;margin-bottom: 10px;background: #e8edef;"  data-toggle="modal" data-target="#AddAttributesVaritaionItem" >Add Variation Item</span>
    <span class="btn deleteBtnStatus variationItemsDeleteBtn" 
    data-url="{{route('admin.productsUpdateAjax',['attributesItemDeletesIds',$product->id])}}"
    style="border: 1px solid #e7e3e3;margin-bottom: 10px;background: #ff425c;color: white;display:none;"  
    onclick="return confirm('Are you want to variation item delete?')">
    <i class="fa fa-trash"></i></span>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>
                <label style="cursor: pointer; margin-bottom: 0;"> <input class="checkbox" type="checkbox" class="form-control" id="checkall" /> ID </label>
                </th>
                <th>Image</th>
                @foreach($product->productVariationAttibutes as $att)
                <th>{{$att->attribute?$att->attribute->name:'Not found'}}</th>
                @endforeach
                <th>Price</th>
                <th>Quantity</th>
                <th></th>
            </tr>
            @foreach($product->productVariationAttributeItems as $vi=>$variationItem)
            <tr>
                <td>
                <input class="checkbox" type="checkbox" name="checkid[]" value="{{$variationItem->id}}" />
                    {{$vi+1}}
                </td>
                <td>
                    <img src="{{asset($product->image())}}" style="max-width:100px;max-height:40px;">
                </td>
                @foreach($variationItem->attributeVatiationItems as $att)
                <td>
                    {{$att->attribute_item_value}}
                </td>
                @endforeach
                <td>
                    {{priceFullFormat($variationItem->final_price)}}
                </td>
                <td>{{$variationItem->quantity}}</td>
                <td style="padding: 2px;">
                    <span class="btn btn-sm btn-danger"><i class="fa fa-edit"></i></span>
                </td>
            </tr>
            @endforeach

            @if($product->productVariationAttributeItems->count()==0)
            <tr>
                <td colspan="{{5+$product->productVariationAttibutes->count()}}" style="text-align:center;color: #afafaf;"> No Variation Item</td>
            </tr>
            @endif
        </table>
    </div>
    
    @else
    <span class="btn" style="border: 1px solid #e7e3e3;margin-bottom: 10px;background: #e8edef;"  data-toggle="modal" data-target="#AddAttributesVaritaion" >Add Variation Attributes</span>
    <br>
    <span style="color: #b7b7b7;">
         Adding new <b>Variation</b> attributes helps the product to have many price Variation.
    </span>
    <br>
    <br>
    @endif
</div>


@if($product->productVariationAttibutes->count() > 0)
<!-- Modal -->
<div class="modal fade text-left" id="AddAttributesVaritaionItem" tabindex="-1" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Variation Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times; </span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="varitaionItemproduct">
                    <div class="row">
                        @foreach($product->productVariationAttibutes as $atti)
                        <div class="col-md-4 form-group">
                            <label>{{$atti->attribute?$atti->attribute->name:''}}*</label>
                            <select class="form-control" name="itemsIds[]" required="">
                                <option value="">Select Option</option>
                                @if($attribute =$atti->attribute)
                                @foreach($attribute->subAttributes as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        @endforeach
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Regular Price</label>
                            <input type="number" class="form-control" name="variation_price"  value="" placeholder="Price">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="variation_discount"  value="" placeholder="Price">
                                <select class="form-control" name="variation_discount_type" >
                                    <option value="percent">Percent(%)</option>
                                    <option value="flat">Flat({{general()->currency}})</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Sale Price</label>
                            <input type="number" class="form-control" readonly=""  value="" placeholder="Price">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Quantity</label>
                            <input type="number" class="form-control" name="variation_quantity" value="" placeholder="Price">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Stock Status</label>
                            <select class="form-control" name="variation_stock">
                                <option value="1">Stock In</option>
                                <option value="0">Stock Out</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <br>
                <br>
                <span class="btn btn-success variationItemAttribute" data-url="{{route('admin.productsUpdateAjax',['attributesVariationItemAdd',$product->id])}}" >Save Continue</span>
                <br>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal -->
<div class="modal fade text-left" id="AddAttributesVaritaion" tabindex="-1" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Select Variation Attributes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times; </span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($attributes->where('featured',true)->get() as $attri)
                <label style="margin: 5px;border: 1px solid #dfd9d9;padding: 5px 10px;border-radius: 5px;">
                    <input type="checkbox" value="{{$attri->id}}"  name="attributesVariationAddItemId[]"> {{$attri->name}}
                </label>
                @endforeach
                <br><br>
                <span class="btn btn-success attributesVariationAddItem" data-url="{{route('admin.productsUpdateAjax',['attributesVariationItemAddIds',$product->id])}}" >Save Continue</span>
                <br>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade text-left" id="AddAttributes" tabindex="-1" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Select Attributes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times; </span>
                </button>
            </div>
            <div class="modal-body">
                @php
                  $half = ceil($attributes->where('featured',true)->count() / 2); // Calculate the middle index
                  $firstColumn = $attributes->where('featured',true)->get()->slice(0, $half);
                  $secondColumn = $attributes->where('featured',true)->get()->slice($half);
                @endphp
                <div class="attributeAreaSelect" style="height: 300px;overflow: auto;margin-bottom: 10px;">
                
                <div class="row m-0">
                    <div class="col-md-6">
                    @foreach($firstColumn as $attri)
                        <div class="attributeTitle">
                            <p style="margin-bottom: 5px; font-weight: bold;">
                                {{$attri->name}}
                            </p>
                            <ul style="list-style: none; background: #f6f8fb; padding: 10px; border: 1px solid #dce1e7; border-radius: 5px;">
                                @foreach($attri->subCtgs as $item)
                                    <li>
                                        <label style="margin: 0;">
                                            <input type="checkbox" value="{{$item->id}}"  name="attributesAddItemId[]"
                                            
                                            @foreach($product->productAttibutes()->whereHas('attributeItem')->where('reff_id',$attri->id)->get() as $itm)
                                            {{$itm->parent_id==$item->id?'checked':''}}
                                            @endforeach
                                            
                                            > {{$item->name}}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                    </div>
                    <div class="col-md-6">
                        @foreach($secondColumn as $attri)
                        <div class="attributeTitle">
                            <p style="margin-bottom: 5px; font-weight: bold;">
                                {{$attri->name}}
                            </p>
                            <ul style="list-style: none; background: #f6f8fb; padding: 10px; border: 1px solid #dce1e7; border-radius: 5px;">
                                @foreach($attri->subCtgs as $item)
                                    <li>
                                        <label style="margin: 0;">
                                            <input type="checkbox"  value="{{$item->id}}"  name="attributesAddItemId[]"
                                            @foreach($product->productAttibutes()->whereHas('attributeItem')->where('reff_id',$attri->id)->get() as $itm)
                                                {{$itm->parent_id==$item->id?'checked':''}}
                                            @endforeach
                                            > {{$item->name}}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                    </div>
                </div>

                </div>
                
                <span class="btn btn-success attributesAddItem" data-url="{{route('admin.productsUpdateAjax',['attributesItemAddIds',$product->id])}}" >Save Continue</span>
            </div>
        </div>
    </div>
</div>