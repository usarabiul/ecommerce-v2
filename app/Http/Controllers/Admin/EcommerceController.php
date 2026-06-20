<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Str;
use Hash;
use File;
use DB;
use Session;
use Validator;
use Redirect,Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Transaction;
use App\Models\PostExtra;
use App\Models\Review;
use App\Models\Country;
use App\Models\Media;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Attribute;
use App\Models\Permission;
use App\Models\PostAttribute;
use App\Models\PostAttributeVariation;
use GuzzleHttp\Client;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EcommerceController extends Controller
{

 	// Product management Function
     public function products(Request $r){
      
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['products']['all']);
      // Filter Action Start

      if($r->action){
        if($r->checkid){
          $datas=Post::latest()->where('type',2)->whereIn('id',$r->checkid);
          if($r->action==1){
            $datas->update(['status'=>'active']);
          }elseif($r->action==2){
            $datas->update(['status'=>'inactive']);
          }elseif($r->action==3){
            $datas->update(['featured'=>true]);
          }elseif($r->action==4){
            $datas->update(['featured'=>false]);
          }elseif($r->action==5){
            foreach($datas->get(['id']) as $data){
              foreach($data->allFiles as $media){
                if(File::exists($media->file_url)){
                  File::delete($media->file_url);
                }
                $media->delete();
              }
              $data->productCtgs()->delete();
              $data->productTags()->delete();
              $data->productAttibutes()->delete();
              $data->extraAttribute()->delete();
              $data->productSkus()->delete();
              $data->delete();
            }
          }
          Session()->flash('success','Action Successfully Completed!');
        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }
        return redirect()->back();
      }

      //Filter Action End
      
      $products =Post::latest()->where('type',2)->where('status','<>','temp')
        ->where(function($q) use ($r,$allPer) {

            if($r->search){
                $q->where('name','LIKE','%'.$r->search.'%');
            }
            
            if($r->startDate || $r->endDate){
                if($r->startDate){
                    $from =$r->startDate;
                }else{
                    $from=Carbon::now()->format('Y-m-d');
                }
                if($r->endDate){
                    $to =$r->endDate;
                }else{
                    $to=Carbon::now()->format('Y-m-d');
                }
                $q->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to);
            }

            if($r->status){
              if($r->status=='featured'){
                $q->where('featured',true); 
              }else{
                $q->where('status',$r->status); 
              }
            }

            // Check Permission
            if($allPer){
             $q->where('addedby_id',auth::id()); 
            }


        })
        ->select(['id','name','final_price','slug','view','type','brand_id','created_at','addedby_id','status','featured'])
        ->paginate(25)->appends([
          'search'=>$r->search,
          'status'=>$r->status,
          'startDate'=>$r->startDate,
          'endDate'=>$r->endDate,
        ]);
        
        
        //Total Count Results
        $totals = Post::where('type',2)->where('status','<>','temp')
        ->selectRaw('count(*) as total')
        ->selectRaw("count(case when status = 'active' then 1 end) as active")
        ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
        ->selectRaw("count(case when featured = true then 1 end) as featured")
        ->first();

        return view(adminTheme().'products.productsAll',compact('products','totals'));
    }

    public function productsAction(Request $r,$action,$id=null){
        
        if($action=='create'){
          $product =Post::where('type',2)->where('status','temp')->where('addedby_id',Auth::id())->first();
          if(!$product){
            $product =new Post();
            $product->type =2;
            $product->status ='temp';
            $product->addedby_id =Auth::id();
          }
          $product->created_at =Carbon::now();
          $product->save();
          return redirect()->route('admin.productsAction',['edit',$product->id]);
        }

        $product =Post::where('type',2)->find($id);
        if(!$product){
          Session()->flash('error','This Product Are Not Found');
          return redirect()->route('admin.products');
        }

        //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['products']['all']);
        if($allPer && $product->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.products');
        }


        if($action=='view'){
          return view(adminTheme().'products.productsView',compact('product'));
        }

        if($action=='update'){
          $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'seo_description' => 'nullable|max:250',
            'catagoryid.*' => 'nullable|numeric',
            'brand' => 'nullable|numeric',
            'tags.*' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gallery_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
          ]);

          if(!$check){
              Session::flash('error','Need To validation');
              return back();
          }

       
          $product->name=$r->name;
          $product->short_description=$r->short_description;
          $product->description=$r->description;
          $product->seo_title=$r->seo_title;
          $product->seo_description=$r->seo_description;
          $product->seo_keyword=$r->seo_keyword;
          $product->brand_id=$r->brand;

          ///////Image Uploard Start////////////
          if($r->hasFile('image')){
            $file =$r->image;
            $src  =$product->id;
            $srcType  =1;
            $fileUse  =1;
            $author=Auth::id();
            uploadFile($file,$src,$srcType,$fileUse,$author);
          }
          ///////Image Uploard End////////////

          ///////Gallery Uploard Start////////////
          $files=$r->file('gallery_image');
          if($files){
              foreach($files as $file)
              {
                  $file =$file;
                  $src  =$product->id;
                  $srcType  =1;
                  $fileUse  =3;
                  $author=Auth::id();
                  $fileStatus=false;
                  uploadFile($file,$src,$srcType,$fileUse,$author,$fileStatus);
              }
          }
          ///////Gallery Uploard End////////////
          $product->auto_slug=$r->slug?true:false;
          $slug =Str::slug($r->slug?:$r->name);
          if($slug==null){
          $product->slug=$product->id;
          }else{
          if(Post::where('type',2)->where('slug',$slug)->whereNotIn('id',[$product->id])->count() >0){
          $product->slug=$slug.'-'.$product->id;
          }else{
          $product->slug=$slug;
          }
          }
          if($r->created_at){
            $product->created_at =$r->created_at;
          }
          $product->status =$r->status?'active':'inactive';
          $product->featured =$r->featured?1:0;
          $product->editedby_id =Auth::id();
          $product->save();

      //Category posts
      if($r->categoryid){

      $product->productCtgs()->whereNotIn('reff_id',$r->categoryid)->delete();

       for ($i=0; $i < count($r->categoryid); $i++) {

        $ctg = $product->productCtgs()->where('reff_id',$r->categoryid[$i])->first();

        if(!$ctg){
        $ctg =new PostAttribute();
        $ctg->src_id=$product->id;
        $ctg->reff_id=$r->categoryid[$i];
        $ctg->type=0;
        }
        $ctg->drag=$i;
        $ctg->save();
       }

     }else{
        $product->productCtgs()->delete();
       }


       //Tags posts
      // if($r->tags){

      // $product->productTags()->whereNotIn('reff_id',$r->tags)->delete();

      //  for ($i=0; $i < count($r->tags); $i++) {

      //   $tag = $product->productTags()->where('reff_id',$r->tags[$i])->first();

      //   if(!$tag){
      //   $tag =new PostAttribute();
      //   $tag->src_id=$product->id;
      //   $tag->reff_id=$r->tags[$i];
      //   $tag->type=4;
      //   }
      //   $tag->drag=$i;
      //   $tag->save();
      //  }

      // }else{
      //   $product->productTags()->delete();
      // }
       
      //Tags posts
       
        //Attribute Serialize Date
        if($r->attributeSerial){
          for ($i=0; $i < count($r->attributeSerial); $i++) {
            $data = $product->productAttibutes()->find($r->attributeSerial[$i]);
            if($data){
              $data->drag=$i;
              $data->save();
            }
          }
        }

        if($r->extraAttributeSerial){
          for ($i=0; $i < count($r->extraAttributeSerial); $i++) {
            $data = $product->extraAttribute()->find($r->extraAttributeSerial[$i]);
            if($data){
              $data->drag=$i;
              $data->save();
            }
          }
        }
        //Attribute Serialize Date

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();
      }
      
       //Tags Products Start
      if($action=='add-tag' || $action=='remove-tag' || $action=='new-tag'){

        if($action=='add-tag'){
          $tagPost = $product->productTags()->where('reff_id',$r->tag_id)->first();
          if(!$tagPost){
            $tagPost =new PostAttribute();
            $tagPost->type=4;
            $tagPost->src_id=$product->id;
            $tagPost->reff_id=$r->tag_id;
            $tagPost->drag=$product->productTags()->count();
            $tagPost->save();
          }
        }
        if($action=='new-tag'){
          
          $tag =Attribute::where('type',10)->where('status','<>','temp')->where('parent_id',null)->where('name',$r->key)->first();
          if(!$tag){
            $tag =new Attribute();
            $tag->type=10;
            $tag->status='active';
            $tag->name=$r->key;
            $tag->save();
          }

          $tagPost = $product->productTags()->where('reff_id',$tag->id)->first();
          if(!$tagPost){
            $tagPost =new PostAttribute();
            $tagPost->type=4;
            $tagPost->src_id=$product->id;
            $tagPost->reff_id=$tag->id;
            $tagPost->drag=$product->productTags()->count();
            $tagPost->save();
          }

        }
        if($action=='remove-tag'){
          $product->productTags()->where('id',$r->tag_id)->delete();
        }
        if($r->ajax()){
          $viewData = view(adminTheme().'products.includes.productTagList',compact('product'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);
        }
      }

      if($action=='search-tag'){

        $tags =Attribute::where('type',10)->where('status','<>','temp')->where('parent_id',null)
              ->where(function($q)use($r){
                  if($r->key){
                    $q->where('name','like','%'.$r->key.'%');
                  }else{
                    $q->where('name','---000---');
                  }
              })
              ->limit(10)
              ->get();
        if($r->ajax()){
          $viewData = view(adminTheme().'products.includes.productTagSearch',compact('product','tags'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);
        }
      }

      if($action=='delete'){
        foreach($product->allFiles as $media){
          if(File::exists($media->file_url)){
            File::delete($media->file_url);
          }
          $media->delete();
        }
        $product->productCtgs()->delete();
        $product->productTags()->delete();
        $product->productAttibutes()->delete();
        $product->extraAttribute()->delete();
        $product->productSkus()->delete();
        $product->delete();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();
      }

      $attriMessage=null;
      $categories =Attribute::where('type',0)->where('status','<>','temp')->where('parent_id',null)->get();
      $brands =Attribute::where('type',2)->where('status','<>','temp')->where('parent_id',null)->get();
      $attributes =Attribute::where('type',9)->where('status','<>','temp')->where('parent_id',null);

      //return $product->productSkus;
      //return $product->productActiveSkus;
      // $hasIds =$r->riation; //riation[]
      // //return $hasIds;
      // $status=true;
      // foreach($product->productVariationAttributeItems as $item){
      //   $hasItem =$item->attributeVatiationItems()->whereIn('attribute_item_id',$hasIds)->count();
      //   if($hasItem==$item->attributeVatiationItems()->count()){
      //     $status=false;
      //   }
      // }

      // return  $status;
      // return $product->productVariationAttributeItemsList;
      // return $product->productVariationAttributeItems;

      return view(adminTheme().'products.productsEdit',compact('product','categories','brands','attributes','attriMessage'));

    }


    public function productsUpdateAjax(Request $r,$column,$id){

      $product =Post::where('type',2)->find($id);
      $attriMessage='';
      if($r->ajax() && $product){

        //Product Attribute Filters
        if($column=='attributesItemFilter'){
          $attri =Attribute::where('type',9)->where('parent_id',null)->find($r->attriID);
          if($attri){
            $viewData = view(adminTheme().'products.includes.attritubeItems',compact('product','attri'))->render();
            return Response()->json([
                'success' => true,
                'viewData' => $viewData,
            ]);

          }
        }

        //Product Attribute
        if($column=='attributesItemAddIds' || $column=='attributesVariationItemAddIds' || $column=='attributesVariationItemAdd' || $column=='attributesItemDeletesIds'){
            
            if($column=='attributesItemAddIds'){
                if (!empty($r->attriID)) {
                    $product->productAttibutes()->whereNotIn('parent_id', $r->attriID)->delete();
                    
                    for ($i = 0; $i < count($r->attriID); $i++) {
                        $data = $product->productAttibutes()->where('parent_id', $r->attriID[$i])->first();
                        if (!$data) {
                            $data = new PostAttribute();
                            $data->src_id = $product->id;           // Product ID
                            $data->parent_id = $r->attriID[$i];     // Attribute Item ID
                            $attri = Attribute::find($r->attriID[$i]); // Find the main attribute using the current attriID
                            $data->reff_id = $attri?$attri->parent_id:null;     // Main Attribute ID (parent of the current attribute)
                            $data->type = 3;
                            $data->addedby_id = auth::id();         // The currently authenticated user's ID
                            $data->save();
                        }
                    }
                }else{
                    $product->productAttibutes()->delete();
                }
            }
            
            
            if($column=='attributesVariationItemAddIds'){
              if (!empty($r->attriID)) {
                  $product->productVariationAttibutes()->whereNotIn('parent_id', $r->attriID)->delete();
                  
                  for ($i = 0; $i < count($r->attriID); $i++) {
                      $data = $product->productVariationAttibutes()->where('parent_id', $r->attriID[$i])->first();
                      if (!$data) {
                          $data = new PostAttribute();
                          $data->src_id = $product->id;           // Product ID
                          $data->reff_id = $r->attriID[$i];     // Attribute Item ID
                          // $attri = Attribute::find($r->attriID[$i]); // Find the main attribute using the current attriID
                          // $data->reff_id = $attri->parent_id;     // Main Attribute ID (parent of the current attribute)
                          $data->type = 6;
                          $data->addedby_id = auth::id();         // The currently authenticated user's ID
                          $data->save();
                      }
                  }
              }else{
                  $product->productVariationAttibutes()->delete();
              }
            }

            if($column=='attributesVariationItemAdd'){
                $ids =$r->itemsIds;
                if($r->itemsIds && $product->productVariationAttributeItemsCheck($r->itemsIds)){
                  $data = new PostAttribute();
                  $data->src_id = $product->id;
                  $data->price = $r->variation_price?:0;
                  $data->discount = $r->variation_discount?:0;
                  $data->discount_type = $r->variation_discount_type?:'percent';

                  if($data->discount_type=='flat' && $data->discount < $data->price){
                    $data->final_price =$data->price - $data->discount;
                  }elseif($data->discount_type=='percent' &&  $data->discount < 100 || $data->price > 0){
                    $data->final_price =$data->price - ($data->price * $data->discount/100);
                  }else{
                    $data->final_price=$data->price;
                  }

                  $data->quantity = $r->variation_quantity?:0;
                  $data->stock = $r->variation_stock?true:false;
                  $data->type = 7;
                  $data->addedby_id = auth::id();
                  $data->save();

                  for($i=0; $i <count($r->itemsIds);$i++){
                    $attri =Attribute::where('type',9)->find($r->itemsIds[$i]);
                    if($attri){
                      $item = new PostAttributeVariation();
                      $item->src_id =$data->id;
                      $item->product_id =$product->id;
                      $item->attribute_id =$attri->parent_id;
                      $item->attribute_item_id =$attri->id;
                      $item->attribute_item_value =$attri->name;
                      $item->save();
                    }
                  }
              }

            }

            if($column=='attributesItemDeletesIds'){
              if (!empty($r->attriID)) {
                $items =$product->productVariationAttributeItems()->whereIn('id',$r->attriID)->get();
                foreach($items as $item){
                  $item->attributeVatiationItems()->delete();
                  $item->delete();
                }

                if($product->productVariationAttributeItems()->count()==0){
                  $product->productVariationAttibutes()->delete();
                }
                
              }

            }
            
            
          $attributes =Attribute::where('type',9)->where('status','<>','temp')->where('parent_id',null);
          $viewData = view(adminTheme().'products.includes.productsDataAttributes2',compact('product','attributes','attriMessage'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);

        }



        if($column=='attributesItemAdd' || $column=='attributesItemDelete' || $column=='attributesItemColor' || $column=='attributesItemImage'){


          if($column=='attributesItemAdd'){
            $attri =Attribute::where('type',9)->where('parent_id','<>',null)->find($r->attriID);
            if($attri){
              $data =$product->productAttibutes()->where('parent_id',$attri->id)->first();
              if(!$data){
                $data =new PostAttribute();
                $data->src_id=$product->id; //Product ID
                $data->parent_id=$attri->id; //Attribute Items ID
                $data->reff_id=$attri->parent_id; //Main Attribute ID
                $data->type=3;
                $data->addedby_id=auth::id();
                $data->save();
              }else{
                $attriMessage='<span class="text-danger">Already Added Attribute item!</span>';
              }
            }
          }

          if($column=='attributesItemColor'){
            $data =$product->productAttibutes()->find($r->attriID);
            if($data){
              $data->value_1=$r->attriValue?:null;
              $data->save();
            }
          }


          if($column=='attributesItemImage'){
            $data =$product->productAttibutes()->find($r->attriID);
            if($r->hasFile('attriValue')){
              $file =$r->attriValue;
              $src  =$data->id;
              $srcType  =8;
              $fileUse  =1;
              $author=Auth::id();
              uploadFile($file,$src,$srcType,$fileUse,$author);
            }
            ///////Image Uploard End////////////
        }

        if($column=='attributesItemDelete'){
            $data =$product->productAttibutes()->find($r->attriID);
            if($data){
              //More Additional Work..
              if($data->imageFile){
                if(File::exists($data->imageFile->file_url)){
                      File::delete($data->imageFile->file_url);
                  }
              }

              if($data->reff_id=='73'){
                $product->productSkus()->where('reff_id',$data->parent_id)->delete();
              }elseif($data->reff_id=='68'){
                $product->productSkus()->where('parent_id',$data->parent_id)->delete();
              }

              $data->delete();
            }
        }

          $viewData = view(adminTheme().'products.includes.attributeItemsList',compact('product','attriMessage'))->render();
          $viewData2 = view(adminTheme().'products.includes.productVariation',compact('product','attriMessage'))->render();
          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
              'viewData2' => $viewData2,
          ]);
        //Product Attribute End
        }


        //Product Variations Start
        if($column=='priceVariationStatus'){
          $product->variation_status=$product->variation_status?false:true;
          $product->save();

          $viewData = view(adminTheme().'products.includes.productVariation',compact('product','attriMessage'))->render();

          return Response()->json([
              'success' => true,
              'viewData' => $viewData,
          ]);

        }

        if($column=='variationItemsPrice' || $column=='variationItemsQuantity'){
          $data =$product->productSkus()->find($r->item_id);
          if($data){
            if($column=='variationItemsPrice'){
              $data->price =$r->item_key?:0;
            }elseif($column=='variationItemsQuantity'){
              $data->duration =$r->item_key?:0;
            }
              $data->save();
          }

          return Response()->json([
            'success' => true,
          ]);
        }

        if($column=='variationItemsAdd' || $column=='variationItemsDelete'){
            
            if($column=='variationItemsDelete'){
                $product->productSkus()->where('sku_id',$r->skuId)->delete();
            }
            
            if($column=='variationItemsAdd'){
    
              if($r->color || $r->size){
                
                $uniIDS =$product->id;
                $uniIDS.=$r->color;
                $uniIDS.=$r->size;
                $checkSku =$product->productSkus()->where('parent_id',$r->color)->where('reff_id',$r->size)->first();
                if($checkSku){
                  $attriMessage ='<span class="text-danger">Already Added Items!!</span>';
                }else{
                    //Sku Items 
                    $data =new PostAttribute();
                    $data->src_id=$product->id; //Product ID
                    $data->parent_id=$r->color; //Attribute Color ID
                    $data->reff_id=$r->size; //Attribute Size ID
                    $data->sku_id=$uniIDS; //Sku  ID
                    $data->type=6;
                    $data->addedby_id=auth::id();
                    $data->save();
                    // for ($i=0; $i < count($r->variationItems); $i++) {
                    //   if($r->variationItems[$i]!=null){
                    //       $attri =Attribute::where('type',9)->where('parent_id','<>',null)->find($r->variationItems[$i]);
                    //       if($attri){
                    //         $data =new PostAttribute();
                    //         $data->src_id=$product->id; //Product ID
                    //         $data->parent_id=$attri->id; //Attribute Items ID
                    //         $data->reff_id=$attri->parent_id; //Main Attribute ID
                    //         $data->sku_id=$uniIDS; //Sku  ID
                    //         $data->type=6;
                    //         $data->addedby_id=auth::id();
                    //         $data->save();
                    //       }
                    //   }
                    // }
                  
                }
    
              }else{
                $attriMessage ='<span class="text-danger">Please Select Variation Items!!</span>';
              }
            }
            
              $viewData = view(adminTheme().'products.includes.productVariation',compact('product','attriMessage'))->render();
              return Response()->json([
                  'success' => true,
                  'viewData' => $viewData,
              ]);
           
            
        }
        
        //Product Variations End


        //Extra Product Attribute
        if($column=='extraAttributeAdd' || $column=='extraAttributeDelete'){

          //Extra Product Attribute Add
          if($column=='extraAttributeAdd'){
            $extraAttribue =new PostExtra();
            $extraAttribue->src_id=$product->id;
            $extraAttribue->type=2;
            $extraAttribue->name=Str::limit($r->title,150);
            $extraAttribue->description=Str::limit($r->value,150);
            $extraAttribue->save(); 
          }

          //Extra Product Attribute Delete
          if($column=='extraAttributeDelete'){
              $extraAttri =PostExtra::where('type',2)->find($r->attriID);
              if($extraAttri){
                $extraAttri->delete();
              }
          }
          

          $viewData = view(adminTheme().'products.includes.extraAttributeList',compact('product'))->render();

          return Response()->json([
                'success' => true,
                'viewData' => $viewData,
            ]);

        }


        if($column=='discount'){
          $product->discount=$r->data?:0;
          $product->save();
        }
        
        if($column=='pos_price'){
          $product->pos_price=$r->data?:0;
          $product->save();
        }
        
        

        if($column=='discount_type'){
          $product->discount_type=$r->data?:null;
          $product->save();
        }

        if($column=='regular_price'){
          $product->regular_price=$r->data?:0;
          $product->save();
        }

        if($column=='discount' || $column=='discount_type' || $column=='regular_price'){

          if($product->discount_type=='flat' && $product->discount < $product->regular_price){
            $product->final_price =$product->regular_price - $product->discount;
          }elseif($product->discount_type=='percent' &&  $product->discount < 100 || $product->regular_price > 0){
            $product->final_price =$product->regular_price - ($product->regular_price * $product->discount/100);
          }else{
            $product->final_price=$product->regular_price;
          }

          $product->save();

        }


        if($column=='purchase_price'){
          $product->purchase_price=$r->data?:0;
          $product->save();
        }

        if($column=='quantity'){
          $product->quantity=$r->data?:null;
          $product->save();
        }

        if($column=='stock_out_limit'){
          $product->stock_out_limit=$r->data?:0;
          $product->save();
        }

        if($column=='sku_code'){
          $product->sku_code=$r->data?:null;
          $product->save();
        }
        
        if($column=='stock_status'){
          $product->stock_status=$r->data==0?0:1;
          $product->save();
        }

        if($column=='bar_code'){
          $product->bar_code=$r->data?:null;
          $product->save();
        }

        if($column=='offer_start_date'){
          $product->offer_start_date=$r->data?:null;
          $product->save();
        }

        if($column=='offer_end_date'){
          $product->offer_end_date=$r->data?:null;
          $product->save();
        }

        if($column=='min_order_quantity'){
          $product->min_order_quantity=$r->data?:1;
          $product->save();
        }

        if($column=='max_order_quantity'){
          $product->max_order_quantity=$r->data?:null;
          $product->save();
        }

        if($column=='weight_unit'){
          $product->weight_unit=$r->data?Str::limit($r->data,100):null;
          $product->save();
        }
        if($column=='weight_amount'){
          $product->weight_amount=$r->data?Str::limit($r->data,50):null;
          $product->save();
        }
        if($column=='dimensions_unit'){
          $product->dimensions_unit=$r->data?Str::limit($r->data,100):null;
          $product->save();
        }

        if($column=='dimensions_length'){
          $product->dimensions_length=$r->data?Str::limit($r->data,50):null;
          $product->save();
        }

        if($column=='dimensions_width'){
          $product->dimensions_width=$r->data?Str::limit($r->data,50):null;
          $product->save();
        }

        if($column=='dimensions_height'){
          $product->dimensions_height=$r->data?Str::limit($r->data,50):null;
          $product->save();
        }


        return Response()->json([
                'success' => true,
            ]);

      }
    }
  // Products Management Function End


  //Products Category Function
  public function productsCategories(Request $r){

    // Filter Action Start
      if($r->action){
        if($r->checkid){
          $datas=Attribute::where('type',0)->whereIn('id',$r->checkid);
          if($r->action==1){
            $datas->update(['status'=>'active']);
          }elseif($r->action==2){
            $datas->update(['status'=>'inactive']);
          }elseif($r->action==3){
            $datas->update(['featured'=>true]);
          }elseif($r->action==4){
            $datas->update(['featured'=>false]);
          }elseif($r->action==5){
            foreach($datas->get(['id']) as $data){
              foreach($data->allFiles as $media){
                if(File::exists($media->file_url)){
                  File::delete($media->file_url);
                }
                $media->delete();
              }
              foreach($data->subctgs as $subctg){
                $subctg->parent_id=$data->parent_id;
                $subctg->save();
              }
              $data->delete();
            }
          }
          Session()->flash('success','Action Successfully Completed!');
        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }
        return redirect()->back();
      }

      //Filter Action End

      $categories =Attribute::latest()->where('type',0)->where('status','<>','temp')
      ->where(function($q) use ($r) {
            if($r->search){
                $q->where('name','LIKE','%'.$r->search.'%');
            }
            if($r->status){
              $q->where('status',$r->status); 
            }
      })
      ->select(['id','name','slug','parent_id','view','type','created_at','addedby_id','status','featured'])
      ->paginate(25)->appends([
          'search'=>$r->search,
          'status'=>$r->status,
        ]);

      //Total Count Results
      $totals = Attribute::where('status','<>','temp')
      ->where('type',0)
      ->selectRaw('count(*) as total')
      ->selectRaw("count(case when status = 'active' then 1 end) as active")
      ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
      ->selectRaw("count(case when status = 'temp' then 1 end) as temp")
      ->first();

      
      return view(adminTheme().'products.category.categoriesAll',compact('categories','totals'));

  }

  public function productsCategoriesAction(Request $r,$action,$id=null){
      if($action=='create'){

        $category =Attribute::where('type',0)->where('status','temp')->where('addedby_id',Auth::id())->first();
        if(!$category){
          $category =new Attribute();
          $category->type =0;
          $category->status ='temp';
          $category->addedby_id =Auth::id();
        }
        $category->created_at =Carbon::now();
        $category->save();
        return redirect()->route('admin.productsCategoriesAction',['edit',$category->id]);
      }
      $category =Attribute::where('type',0)->find($id);
      if(!$category){
        Session()->flash('error','This Category Are Not Found');
        return redirect()->route('admin.productsCategories');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['productsCtg']['all']);
      if($allPer && $category->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.productsCategories');
      }
      
      if($action=='update'){
        $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'seo_description' => 'nullable|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        
        $createDate = $r->created_at ? Carbon::parse($r->created_at . ' ' . Carbon::now()->format('H:i:s')) : Carbon::now();
        
        $category->name=$r->name;
        $category->description=$r->description;
        $category->seo_title=$r->seo_title;
        $category->seo_description=$r->seo_description;
        $category->seo_keyword=$r->seo_keyword;
        if($r->parent_id==$category->parent_id){}else{
          $category->parent_id=$r->parent_id;
        }
        
        ///////Image Uploard Start////////////
        if($r->hasFile('image')){
          $file =$r->image;
          $src  =$category->id;
          $srcType  =3;
          $fileUse  =1;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Image Uploard End////////////

      ///////Banner Uploard End////////////

        if($r->hasFile('banner')){
          $file =$r->banner;
          $src  =$category->id;
          $srcType  =3;
          $fileUse  =2;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }

      ///////Banner Uploard End////////////


        $slug =Str::slug($r->name);
        if($slug==null){
          $category->slug=$category->id;
        }else{
          if(Attribute::where('type',0)->where('slug',$slug)->whereNotIn('id',[$category->id])->count() >0){
          $category->slug=$slug.'-'.$category->id;
          }else{
          $category->slug=$slug;
          }
        }
        if (!$createDate->isSameDay($category->created_at)) {
            $category->created_at = $createDate;
          }
        $category->status =$r->status?'active':'inactive';
        $category->featured =$r->featured?1:0;
        $category->editedby_id =Auth::id();
        $category->save();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();
      }

      if($action=='delete'){
        //Category Media File Delete
        $medias =Media::latest()->where('src_type',3)->where('src_id',$category->id)->get();
        foreach($medias as $media){
          if(File::exists($media->file_url)){
            File::delete($media->file_url);
          }
          $media->delete();
        }

        //Product Category sub Category replace
        foreach($category->subctgs as $subctg){
          $subctg->parent_id=$category->parent_id;
          $subctg->save();
        }
        
        $category->delete();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();
      }

      $parents =Attribute::where('type',0)->where('status','<>','temp')->where('parent_id',null)->get();

      return view(adminTheme().'products.category.categoryEdit',compact('category','parents'));


  }

    //Product Category Function End


    
//Brands Function

public function productsBrands(Request $r){

  $allPer = empty(json_decode(Auth::user()->permission->permission, true)['brands']['all']);

  // Filter Action Start
  if($r->action){
    if($r->checkid){
      $datas=Attribute::latest()->where('type',2)->whereIn('id',$r->checkid);
      if($r->action==1){
        $datas->update(['status'=>'active']);
      }elseif($r->action==2){
        $datas->update(['status'=>'inactive']);
      }elseif($r->action==3){
        $datas->update(['featured'=>true]);
      }elseif($r->action==4){
        $datas->update(['featured'=>false]);
      }elseif($r->action==5){
        foreach($datas->get(['id']) as $data){
          foreach($data->allFiles as $media){
            if(File::exists($media->file_url)){
              File::delete($media->file_url);
            }
            $media->delete();
          }
          $data->delete();
        }
      }
      Session()->flash('success','Action Successfully Completed!');

    }else{
      Session()->flash('info','Please Need To Select Minimum One Post');
    }

    return redirect()->back();
  }

  //Filter Action End

  $brands=Attribute::latest()->where('type',2)->where('status','<>','temp')
    ->where(function($q) use ($r,$allPer) {

      if($r->search){
          $q->where('name','LIKE','%'.$r->search.'%');
      }


      if($r->status){
         $q->where('status',$r->status); 
      }

      // Check Permission
      if($allPer){
       $q->where('addedby_id',auth::id()); 
      }

  })
  ->select(['id','name','slug','type','created_at','addedby_id','status','featured'])
  ->paginate(25)->appends([
    'search'=>$r->search,
    'status'=>$r->status,
  ]);

  //Total Count Results
  $totals = Attribute::where('status','<>','temp')
  ->where('type',2)
  ->selectRaw('count(*) as total')
  ->selectRaw("count(case when status = 'active' then 1 end) as active")
  ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
  ->first();

  return view(adminTheme().'products.brands.brandsAll',compact('brands','totals'));

}

public function productsBrandsAction(Request $r,$action,$id=null){
  // Add Brand Action Start
  if($action=='create'){

    $brand =Attribute::where('type',2)->where('status','temp')->where('addedby_id',Auth::id())->first();
    if(!$brand){
      $brand =new Attribute();
    }
    $brand->type =2;
    $brand->status ='temp';
    $brand->addedby_id =Auth::id();
    $brand->save();

    return redirect()->route('admin.productsBrandsAction',['edit',$brand->id]);
  } 
  // Add Brand Action End
  
  $brand =Attribute::where('type',2)->find($id);
  if(!$brand){
    Session()->flash('error','This Brand Are Not Found');
    return redirect()->route('admin.productsBrands');
  }

  //Check Authorized User
  $allPer = empty(json_decode(Auth::user()->permission->permission, true)['brands']['all']);
  if($allPer && $brand->addedby_id!=Auth::id()){
    Session()->flash('error','You are unauthorized Try!!');
    return redirect()->route('admin.productsBrands');
  }

  // Update Brand Action Start
  if($action=='update'){

      $check = $r->validate([
          'name' => 'required|max:191',
          'seo_title' => 'nullable|max:200',
          'seo_desc' => 'nullable|max:250',
          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);
      
      $createDate = $r->created_at ? Carbon::parse($r->created_at . ' ' . Carbon::now()->format('H:i:s')) : Carbon::now();
      
      $brand->name=$r->name;
      $brand->short_description=$r->short_description;
      $brand->description=$r->description;
      $brand->seo_title=$r->seo_title;
      $brand->short_description=$r->short_description;
      $brand->seo_keyword=$r->seo_keyword;

       ///////Image UploadStart////////////

        if($r->hasFile('image')){
          $file =$r->image;
          $src  =$brand->id;
          $srcType  =3;
          $fileUse  =1;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        
        ///////Image Upload End////////////

        ///////Banner Upload End////////////

        if($r->hasFile('banner')){

          $file =$r->banner;
          $src  =$brand->id;
          $srcType  =3;
          $fileUse  =2;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);

        }

        ///////Banner Upload End////////////

        $slug =Str::slug($r->name);
        if($slug==null){
          $brand->slug=$brand->id;
        }else{
          if(Attribute::where('type',2)->where('slug',$slug)->whereNotIn('id',[$brand->id])->count() >0){
          $brand->slug=$slug.'-'.$brand->id;
          }else{
          $brand->slug=$slug;
          }
        }
        if (!$createDate->isSameDay($brand->created_at)) {
        $brand->created_at = $createDate;
        }
        $brand->status =$r->status?'active':'inactive';
        $brand->featured =$r->featured?1:0;
        $brand->editedby_id =Auth::id();
        $brand->save();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

  }
  // Update Brand Action Start

  // Delete Brand Action Start
  if($action=='delete'){
    foreach($brand->allFiles as $media){
      if(File::exists($media->file_url)){
        File::delete($media->file_url);
      }
      $media->delete();
    }

    $brand->delete();

    Session()->flash('success','Your Are Successfully Done');
    return redirect()->route('admin.productsBrands');
  }
  // Delete Brand Action End

  return view(adminTheme().'products.brands.brandsEdit',compact('brand'));
}

//Brands Function End

    //Product Tags Function
  
  public function productsTags(Request $r){
      if($r->action){
        if($r->checkid){
          $datas=Attribute::where('type',10)->whereIn('id',$r->checkid);
          if($r->action==1){
            $datas->update(['status'=>'active']);
          }elseif($r->action==2){
            $datas->update(['status'=>'inactive']);
          }elseif($r->action==3){
            $datas->update(['featured'=>true]);
          }elseif($r->action==4){
            $datas->update(['featured'=>false]);
          }elseif($r->action==5){
            $datas->delete();
          }
          Session()->flash('success','Action Successfully Completed!');
        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }

        return redirect()->back();
      }

    //Filter Action End

      $tags =Attribute::latest()->where('type',10)->where('status','<>','temp')
      ->where(function($q) use($r){
        if($r->search){
          $q->where('name','like','%'.$r->search.'%');
        }
        
      })
      ->paginate(25);

      //Total Count Results
    $totals = Attribute::where('type',10)->where('status','<>','temp')
    ->selectRaw('count(*) as total')
    ->selectRaw("count(case when status = 'active' then 1 end) as active")
    ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
    ->first();

      return view(adminTheme().'products.tags.tagsAll',compact('tags','totals'));
  }

  public function productsTagsAction(Request $r,$action,$id=null){
      if($action=='create'){
        $check = $r->validate([
            'name' => 'required|max:100',
        ]);

        $checkTag =Attribute::where('type',10)->where('name',$r->name)->first();
        if($checkTag){
          Session::flash('error','Tag Name Can not use Dublicate!');
            return back();
        }

        $tag =new Attribute();
        $tag->type =10;
        $tag->status ='active';
        $tag->addedby_id =Auth::id();
        $tag->name=$r->name;
        $tag->slug =Str::slug($r->name);
        $tag->save();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

      }

      $tag =Attribute::where('type',10)->find($id);
      if(!$tag){
        Session()->flash('error','This Tag Are Not Found');
        return redirect()->route('admin.productsTags');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['productsCtg']['all']);
      if($allPer && $tag->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.productsTags');
      }

      if($action=='update'){
        $check = $r->validate([
            'name' => 'required|max:191|unique:attributes,name,'.$tag->id,
        ]);

        $tag->name=$r->name;
        $tag->description=$r->description;
        $tag->status =$r->status?'active':'inactive';
        $tag->addedby_id =Auth::id();
        $tag->save();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();
      }

      if($action=='update'){
        $tag->delete();
        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();
      }

      return view(adminTheme().'products.tags.tagEdit',compact('tag'));
  }

    //Product Tags Function End

    //Product Attributes Function 
  public function productsAttributes(Request $r){
      // Filter Action Start
      if($r->action){
        if($r->checkid){
          $datas=Attribute::latest()->where('type',9)->where('status','<>','temp')->whereIn('id',$r->checkid);
          if($r->action==1){
            $datas->update(['status'=>'active']);
          }elseif($r->action==2){
            $datas->update(['status'=>'inactive']);
          }elseif($r->action==5){
            foreach($datas->get(['id']) as $data){
              foreach($data->subAttributes as $item){
                foreach($item->allFiles as $media){
                  if(File::exists($media->file_url)){
                    File::delete($media->file_url);
                  }
                  $media->delete();
                }
                $item->delete();
              }
              $data->delete();
            }
          }
          Session()->flash('success','Action Successfully Completed!');
        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }
        return redirect()->back();
      }
      //Filter Action End

      $attributes=Attribute::latest()->where('type',9)->where('status','<>','temp')->where('parent_id',null)
        ->where(function($q) use ($r) {
          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }
        })
        ->paginate(25)->appends([
          'search'=>$r->search,
        ]);

      return view(adminTheme().'products.attributes.attributesAll',compact('attributes'));
  }

  public function productsAttributesAction(Request $r,$action,$id=null){
    if($action=='create'){
      $attribute =Attribute::latest()->where('type',9)->where('addedby_id',Auth::id())->where('status','temp')->first();
      if(!$attribute){
      $attribute =new Attribute();
      $attribute->type =9;
      $attribute->status ='temp';
      $attribute->addedby_id =Auth::id();
      $attribute->save();
      }else{
      $attribute->parent_id =null;
      $attribute->created_at =Carbon::now();
      $attribute->save();
      }
      return redirect()->route('admin.productsAttributesAction',['edit',$attribute->id]);
    }

    $attribute =Attribute::where('type',9)->where('parent_id',null)->find($id);
    if(!$attribute){
      Session()->flash('error','This Attribute Are Not Found');
      return redirect()->route('admin.productsAttributes');
    }

    if($action=='update'){
      $check = $r->validate([
          'name' => 'required|max:100',
          'created_at' => 'required|date',
      ]);
      if(!$check){
          Session::flash('error','Need To validation');
          return back();
      }
      $hasAttribute =Attribute::where('type',9)->where('parent_id',null)->where('id', '!=', $id)->where('name',$r->name)->first();
      if($hasAttribute){
        Session::flash('error', $r->name.' attribute has already been added');
        return back();
      }

      //View =1=text,2=color,3=image
      $attribute->name=$r->name;
      $attribute->description=$r->description;
      $attribute->view=$r->type;
      $slug =Str::slug($r->name);
      if($slug==null){
        $attribute->slug=$attribute->id;
      }else{
        if(Attribute::where('type',9)->where('slug',$slug)->whereNotIn('id',[$attribute->id])->count() >0){
        $attribute->slug=$slug.'-'.$attribute->id;
        }else{
        $attribute->slug=$slug;
        }
      }
      $attribute->status =$r->status?'active':'inactive';
      $attribute->featured =$r->featured?1:0;
      $attribute->editedby_id =Auth::id();
      $attribute->created_at =$r->created_at?:Carbon::now();
      $attribute->save();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }
    
    if($action=='delete'){
      foreach($attribute->subAttributes as $item){
        foreach($item->allFiles as $media){
          if(File::exists($media->file_url)){
            File::delete($media->file_url);
          }
          $media->delete();
        }
        $item->delete();
      }
      $attribute->delete();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }


    if($r->checkid){
      $datas=$attribute->subAttributes()->whereIn('id',$r->checkid)->get();
      foreach($datas as $data){
          if($r->action==5){
            foreach($data->allFiles as $media){
              if(File::exists($media->file_url)){
                File::delete($media->file_url);
              }
              $media->delete();
            }
            $data->delete();
          }
      }
      Session()->flash('success','Action Successfully Completed!');
      return back();
    }

    $items=$attribute->subAttributes()
        ->where(function($q) use ($r) {
          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }
      })
      ->paginate(50)->appends([
        'search'=>$r->search,
      ]);


    return view(adminTheme().'products.attributes.attributesEdit',compact('attribute','items'));
  }

  public function productsAttributesItemAction(Request $r,$action,$id){
    if($action=='create'){

      $attribute =Attribute::where('type',9)->find($id);
      if(!$attribute){
        Session()->flash('error','This Attribute Item Are Not Found');
        return redirect()->route('admin.productsAttributes');
      }

      $check = $r->validate([
        'item_name' => 'required|max:100',
        'color' => 'nullable|max:100',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);

      $hasAttribute =$attribute->subAttributes()->where('name',$r->item_name)->first();
      if($hasAttribute){
        Session()->flash('error',$r->item_name.' Attribute Item Are Already Found');
        return redirect()->back()->withInput();
      }

      $attributeItem =new Attribute();
      $attributeItem->type =9;
      $attributeItem->parent_id =$attribute->id;
      $attributeItem->name=$r->item_name;
      $attributeItem->description=$r->description;
      $attributeItem->icon=$r->color;

      ///////Image Uploard Start////////////
        if($r->hasFile('image')){
          $file =$r->image;
          $src  =$attributeItem->id;
          $srcType  =3;
          $fileUse  =1;
          $author =Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Image Uploard End////////////


      $slug =Str::slug($attributeItem->name);
      if($slug==null){
        $attributeItem->slug=$attributeItem->id;
      }else{
        if(Attribute::where('type',9)->where('slug',$slug)->whereNotIn('id',[$attributeItem->id])->count() >0){
        $attributeItem->slug=$slug.'-'.$attributeItem->id;
        }else{
        $attributeItem->slug=$slug;
        }
      }
      $attributeItem->status ='active';
      $attributeItem->addedby_id =Auth::id();
      $attributeItem->save();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    $attribute =Attribute::where('type',9)->find($id);
    if(!$attribute){
      Session()->flash('error','This Attribute Item Are Not Found');
      return redirect()->route('admin.productsAttributes');
    }
    $parentAttribute =$attribute->parent;
    if(!$parentAttribute){
      Session()->flash('error','This Attribute Item Are Not Found');
      return redirect()->route('admin.productsAttributes');
    }

    if($action=='update'){

      $check = $r->validate([
          'item_name' => 'required|max:100',
          'color' => 'nullable|max:100',
          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);
      
      $hasAttribute =$parentAttribute->subAttributes()->where('id','<>',$attribute->id)->where('name',$r->item_name)->first();
      if($hasAttribute){
        Session()->flash('error',$r->item_name.' Attribute Item Are Already Found');
        return redirect()->back();
      }
      //View =1=text,2=color,3=image
      $attribute->name=$r->item_name;
      $attribute->description=$r->description;
      $attribute->icon=$r->color;

      ///////Image Uploard Start////////////
        if($r->hasFile('image')){
          $file =$r->image;
          $src  =$attribute->id;
          $srcType  =3;
          $fileUse  =1;
          $author =Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Image Uploard End////////////


      $slug =Str::slug($attribute->name);
      if($slug==null){
        $attribute->slug=$attribute->id;
      }else{
        if(Attribute::where('type',9)->where('slug',$slug)->whereNotIn('id',[$attribute->id])->count() >0){
        $attribute->slug=$slug.'-'.$attribute->id;
        }else{
        $attribute->slug=$slug;
        }
      }
      $attribute->status ='active';
      $attribute->editedby_id =Auth::id();
      $attribute->save();
      Session()->flash('success','Your Are Successfully Done');
      return redirect()->back();
    }

    return view(adminTheme().'products.attributes.attributesItemEdit',compact('attribute'));

  }

  public function productsReview(Request $r){

    // Filter Action Start
    if($r->action){
      if($r->checkid){
        $datas=Review::latest()->where('type',0)->where('status','<>','temp')->whereIn('id',$r->checkid);
        if($r->action==1){
          $datas->update(['status'=>'active']);
        }elseif($r->action==2){
          $datas->update(['status'=>'inactive']);
        }elseif($r->action==3){
          $datas->update(['featured'=>true]);
        }elseif($r->action==4){
          $datas->update(['featured'=>false]);
        }elseif($r->action==5){
          foreach($datas->get(['id']) as $data){
            $data->replays()->delete();
            $data->delete();
          }
        }
        Session()->flash('success','Action Successfully Completed!');
      }else{
        Session()->flash('info','Please Need To Select Minimum One Post');
      }
      return redirect()->back();
    }
    //Filter Action End

    $reviews=Review::latest()->where('type',0)->where('status','<>','temp')
        ->where('parent_id',null)
        ->where(function($q) use ($r) {
          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }
          if($r->product_id){
            $q->where('src_id',$r->product_id);
          }
        })
        ->paginate(25)->appends([
          'search'=>$r->search,
          'product_id'=>$r->product_id,
        ]);

    return view(adminTheme().'products.reviews.reviewsAll',compact('reviews'));
  }

  public function productsReviewAction(Request $r,$action,$id=null){
    $review =Review::where('type',0)->find($id);
    if(!$review){
      Session()->flash('error','This Review Are Not Found');
      return redirect()->route('admin.productsReview');
    }

    if($action=='update'){
      
      $check = $r->validate([
        'name' => 'required|max:100',
        'email' => 'nullable|max:100',
        'rating' => 'required|numeric|min:1|max:5',
        'review' => 'nullable|max:5000',
      ]);      

      $review->name=$r->name;
      $review->email=$r->email;
      $review->rating=$r->rating?:5;
      $review->content=$r->review;
      $review->status =$r->status?'active':'inactive';
      $review->featured =$r->featured?true:false;
      $review->editedby_id=Auth::id();
      $review->save();

      Session()->flash('success','Your Are Successfully Updated');
      return redirect()->back();     
    }

    if($action=='replay'){
        $replay =$review->replays()->first();
        if(!$replay){
          $replay =new Review();
          $replay->parent_id=$review->id;
          $replay->type=1;
          $replay->save();
        }
      if($r->isMethod('post')){

        $replay->src_id=$review->src_id;
        $replay->name=Auth::user()->name;
        $replay->email=Auth::user()->email;
        $replay->content=$r->replay;
        $replay->status =$r->status?'active':'inactive';
        $replay->addedby_id=Auth::id();
        $replay->save();
  
        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();
      }
      
      return view(adminTheme().'products.reviews.replay',compact('review','replay'));
    }

    return view(adminTheme().'products.reviews.reviewEdit',compact('review'));
  }

  public function ecommerceSetting(Request $r){
        $general =general();

            
        if($r->isMethod('post')){
            $check = $r->validate([
              'currency' => 'nullable|max:10',
              'currency_decimal' => 'nullable|numeric',
              'currency_position' => 'nullable|numeric',
              'inside_dhaka_shipping_charge' => 'nullable|numeric',
              'outside_dhaka_shipping_charge' => 'nullable|numeric',
              'tax' => 'nullable|numeric',
              'tax_status' => 'nullable|numeric',
            ]);
  
            $general->currency=$r->currency;
            $general->currency_decimal=$r->currency_decimal;
            $general->currency_position=$r->currency_position;
            $general->inside_dhaka_shipping_charge=$r->inside_dhaka_shipping_charge?:0;
            $general->outside_dhaka_shipping_charge=$r->outside_dhaka_shipping_charge?:0;
            $general->tax=$r->tax?:0;
            $general->tax_status=$r->tax_status?:0;
            $general->save();

            Session::flash('success','General Information Are Update Successfully Done!');
            return redirect()->back();
        }
   

      return view(adminTheme().'ecommerce-setting.settings');
    }
    
    public function ecommerceCoupons(Request $r){
        $coupons =Attribute::latest()->where('type',13)->where('status','<>','temp')->where('parent_id',null)
                    ->where(function($q) use ($r) {
                      if($r->search){
                        $q->where('name','LIKE','%'.$r->search.'%');
                      }
                    })
                    ->paginate(10);
        return view(adminTheme().'ecommerce-setting.coupons.couponsAll',compact('coupons'));
    }
    
    public function ecommerceCouponsAction(Request $r,$action,$id=null){
 
        if($action=='create'){
            $coupon =Attribute::where('type',13)->where('status','temp')->where('addedby_id',Auth::id())->first();
            if(!$coupon){
                $coupon =new Attribute();
                $coupon->type=13;
                $coupon->status='temp';
                $coupon->addedby_id=Auth::id();
            }
            $coupon->created_at=Carbon::now();
            $coupon->save();
            
            return redirect()->route('admin.ecommerceCouponsAction',['edit',$coupon->id]);
            
        }
        
        $coupon =Attribute::where('type',13)->find($id);
        if(!$coupon){
            Session()->flash('error','Coupon Are Not Found');
            return redirect()->route('admin.ecommerceCoupons');
        }
        
        if($action=='search-product'){
            
            $products =Post::where('type',2)->where('status','active')->where('name','like','%'.$r->search.'%')->limit(10)->get(['id','name']);
            
            if($r->ajax()){
                $view = view(adminTheme().'ecommerce-setting.includes.searchProduct',compact('products','coupon'))->render();
                  return Response()->json([
                      'view' => $view
                  ]);
            }
        }
        
        if($action=='add-product'){
            
            $postProduct =$coupon->couponProductPosts()->where('reff_id',$r->product_id)->first();
            if(!$postProduct){
                $postProduct =new PostAttribute();
                $postProduct->src_id=$coupon->id;
                $postProduct->reff_id=$r->product_id;
                $postProduct->type=6;
                $postProduct->save();
            }
            if($r->ajax()){
                $view = view(adminTheme().'ecommerce-setting.includes.couponProductsList',compact('coupon'))->render();
                  return Response()->json([
                      'view' => $view
                  ]);
            }
        }
        
        if($action=='delete-product'){
            
            $coupon->couponProductPosts()->whereIn('id',$r->checkedId)->delete();
            
            if($r->ajax()){
                $view = view(adminTheme().'ecommerce-setting.includes.couponProductsList',compact('coupon'))->render();
                  return Response()->json([
                      'view' => $view
                  ]);
            }
        }
        
        if($action=='update'){
            
            $check = $r->validate([
              'name' => 'required|max:100',
              'discount' => 'nullable|numeric',
              'discount_type' => 'nullable|max:100',
              'min_shopping' => 'nullable|numeric',
              'max_shopping' => 'nullable|numeric',
              'start_date' => 'nullable|date',
              'end_date' => 'nullable|date',
              'status' => 'required|max:20',
            ]);
            
            $coupon->name=$r->name;
            $coupon->amounts=$r->discount;
            $coupon->menu_type=$r->discount_type;
            $coupon->min_shopping=$r->min_shopping;
            $coupon->max_shopping=$r->max_shopping;
            $coupon->start_date=$r->start_date;
            $coupon->end_date=$r->end_date;
            $coupon->location=$r->coupon_type?:'order';
            
            $slug =Str::slug($r->name);
            if($slug==null){
              $coupon->slug=$coupon->id;
            }else{
              if(Attribute::where('type',13)->where('slug',$slug)->whereNotIn('id',[$coupon->id])->count() >0){
              $coupon->slug=$slug.'-'.$coupon->id;
              }else{
              $coupon->slug=$slug;
              }
            }
            $coupon->status =$r->status?'active':'inactive';
            $coupon->editedby_id =Auth::id();
            $coupon->save();
            
            //Tags posts
            if($r->categories){
              $coupon->couponCtgs()->whereNotIn('reff_id',$r->categories)->delete();
               for ($i=0; $i < count($r->categories); $i++) {
                $ctg = $coupon->couponCtgs()->where('reff_id',$r->categories[$i])->first();
                if($ctg){}else{
                $ctg =new PostAttribute();
                $ctg->src_id=$coupon->id;
                $ctg->reff_id=$r->categories[$i];
                $ctg->type=5;
                }
                $ctg->drag=$i;
                $ctg->save();
               }
            }else{
                $coupon->couponCtgs()->delete();
            }
            
            Session()->flash('success','Your Are Successfully Done');
            return redirect()->back();

        }
        
        if($action=='delete'){
            $coupon->delete();
            Session()->flash('success','Your Are Successfully Done');
            return redirect()->route('admin.ecommerceCoupons');
        }
        
        $categories =Attribute::where('type',0)->where('status','active')->where('parent_id',null)->get();
        
        return view(adminTheme().'ecommerce-setting.coupons.couponsEdit',compact('coupon','categories'));
    }










}
