<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    public function imageFile(){
    	return $this->hasOne(Media::class,'src_id')->where('src_type',1)->where('use_Of_file',1);
    }

    public function image(){
        if($this->imageFile){
            return $this->imageFile->file_url;
        }else{
            return 'medies/noimage.jpg';
        }
    }
    
    public function imageName(){
        
        if($this->imageFile){
            return $this->imageFile->file_rename;
        }else{
            return 'noimage.jpg';
        }
    }

    public function bannerFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',1)->where('use_Of_file',2);
    }

    public function banner(){
        if($this->bannerFile){
            return $this->bannerFile->file_url;
        }else{
            return 'medies/no-banner.png';
        }
    }
    
    public function bannerName(){
        
        if($this->bannerFile){
            return $this->bannerFile->file_rename;
        }else{
            return 'no-banner.png';
        }
    }

    public function galleryFiles(){
        return $this->hasMany(Media::class,'src_id')->where('src_type',1)->where('use_Of_file',3);
    }

    public function hoverImage(){
        if($this->galleryFiles()->first()){
            return $this->galleryFiles()->first()->file_url;
        }else{
            return  $this->image();
        }
    }

    //Image and Banner Functions End


    //Post Category tag, comments Functions 
    public function postCtgs(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',1)->orderBy('drag','asc');
    }

    public function postTags(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',2)->orderBy('drag','asc');
    }

    public function postComments(){
        return $this->hasMany(Review::class,'src_id')->where('type',1);
    }
    
    public function postCategories(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',1)->orderBy('drag', 'asc');
    }
    
     public function Tags(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',2)->orderBy('drag', 'asc');
    }

    public function ctgPosts(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',1);
    }

    public function tagPosts(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',2);
    }

    public function relatedPosts(){
        return Post::whereHas('ctgPosts',function($q){
                    $q->whereIn('reff_id',$this->ctgPosts->pluck('reff_id'));
                })
                ->whereNot('id',$this->id)
                ->where('status','active')
                ->whereDate('created_at','<=',date('Y-m-d'));
 
    }

    //Post Category tag, comments Functions End

     //Product Functions 
    public function productCtgs(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',0)->orderBy('drag','asc');
    }

    public function productTags(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',4)->orderBy('drag','asc');
    }

    public function productTagsList(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',4)->orderBy('drag', 'asc');
    }

    public function productCategories(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',0)->orderBy('drag', 'asc');
    }

    public function ctgProducts(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',0);
    }

    public function extraAttribute(){
        return $this->hasMany(PostExtra::class,'src_id')->where('type',2)->orderBy('drag','asc');
    }

    public function productAttibutes(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',3)->where('parent_id','<>',null)->orderBy('drag','asc');
    }
    
    public function productSizes(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',3)->where('parent_id','<>',null)->where('reff_id',73)->orderBy('drag','asc')->whereHas('attributeItem')->whereHas('attribute');
    }

    public function productColors(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',3)->where('parent_id','<>',null)->where('reff_id',68)->orderBy('drag','asc');
    }

    public function productVariationAttibutes(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',6)->where('parent_id',null)->orderBy('drag','asc');
    }

    public function productVariationAttributeItems(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',7)->orderBy('drag','asc');
    }
    
    public function productVariationAttributeItemsList(){
        return $this->hasMany(PostAttributeVariation::class,'product_id');
    }
    
    public function productVariationAttributeItemsCheck($ids){
        $status=true;
        if($ids){
            foreach($this->productVariationAttributeItems as $item){
                $hasItem =$item->attributeVatiationItems()->whereIn('attribute_item_id',$ids)->count();
                if($hasItem==$item->attributeVatiationItems()->count()){
                    $status=false;
                }
            }
        }

        return $status;
    }

    public function productAttibutesGroup(){
        return $this->hasMany(PostAttribute::class, 'src_id')
        ->latest()
        ->where('type', 3)
        ->whereNotNull('parent_id')
        ->whereHas('attribute')->select('reff_id')->groupBy('reff_id');
    }
    
    public function productFullStock(){
        return $this->productBranchesStock()+$this->quantity;
    }
    
    public function productWeight(){
        $unit =$this->weight_unit;
        $UnitWeight =$this->weight_amount;
        if($UnitWeight >= 1000){
            if($unit=='gram'){
                $unit='Kg';
            }elseif($unit=='ml'){
                $unit='Liter';
            }
            $UnitWeight=$UnitWeight/1000;
        }
        return $UnitWeight.' '.$unit;
    }
    
    public function productWeightUnit(){
        $UnitWeight =$this->weight_amount;
        if($UnitWeight >= 1000){
            $UnitWeight=$UnitWeight/1000;
        }
        return $UnitWeight;
    }

    public function offerPrice(){
        $price =$this->final_price;
        return $price;
    }

    public function offPercentage()
    {
        if ($this->regular_price && $this->offerPrice() && $this->regular_price > $this->offerPrice()) {
            $discount = $this->regular_price - $this->offerPrice();
            $percentage = ($discount / $this->regular_price) * 100;

            return round($percentage, 2);
        }

        return null; // Return null if the calculation is not possible
    }

    public function productLabel(){
        $label =null;
        $createdAt =$this->created_at;
        if ($createdAt->isFuture()) {
            $label = '<span class="badge bg-primary">Upcoming</span>';
        }else if ($createdAt->gte(Carbon::today()->subDays(5))) {
            $label = '<span class="badge bg-info">Latest</span>';
        }else if ($this->regular_price > $this->offerPrice()) {
            $label =  '<span class="badge bg-success">Offer</span>';
        }else{
            $label = '<span class="badge bg-danger">Sale</span>';
        }
        return $label;
    }

    public function productActiveSkus(){
        $sizeAttri = $this->productAttibutes->where('reff_id',73)->first();
        $colorAttri = $this->productAttibutes->where('reff_id',68)->first();
        if($sizeAttri && $colorAttri){
            return $this->hasMany(PostAttribute::class,'src_id')->where('type',6)->where('reff_id','<>',null)->where('parent_id','<>',null)->orderBy('drag','asc');
        }elseif($sizeAttri){
            return $this->hasMany(PostAttribute::class,'src_id')->where('type',6)->where('reff_id','<>',null)->where('parent_id',null)->orderBy('drag','asc');
        }else{
            return $this->hasMany(PostAttribute::class,'src_id')->where('type',6)->where('reff_id',null)->where('parent_id','<>',null)->orderBy('drag','asc');
        }
    }
    
    public function productReviews(){
        return $this->hasMany(Review::class,'src_id')->where('type',0);
    }
    
    public function totalReviewer(){
       return $this->productReviews->where('status','active')->count();
    }
    
    public function totalRatings(){
       return $this->productReviews->where('status','active')->sum('rating');
    }
    
    public function productRating(){
        $reviews =$this->productReviews->where('status','active')->count();
        $totalRating =$this->productReviews->where('status','active')->sum('rating');
        $averageRating =0;
        if($reviews > 0 && $totalRating > 0){
            (int)$averageRating =number_format($totalRating/$reviews,1);
        }
        if($averageRating > 5){
            $averageRating =5;
        }
        return $averageRating;
    }

    public function starRatingPercentages(){

        $reviews = $this->productReviews->where('status', 'active');
        $totalReviews = $reviews->count();
        $starCounts = [
            5 => 0, // 1 star
            4 => 0, // 2 stars
            3 => 0, // 3 stars
            2 => 0, // 4 stars
            1 => 0  // 5 stars
        ];

        // Count reviews for each star rating
        foreach ($starCounts as $star => $count) {
            $starCounts[$star] = $reviews->where('rating', $star)->count();
        }
        $starPercentages = [];
        foreach ($starCounts as $star => $count) {
            $starPercentages[$star] = ($totalReviews > 0) ? round(($count / $totalReviews) * 100, 2) : 0;
        }
        return $starPercentages;
    }

    public function brand(){
        return $this->belongsTo(Attribute::class,'brand_id');
    }
    
    public function productMinQty(){
        return $this->min_order_quantity?:1;
    }
    
    public function productMaxQty(){
        if($this->quantity){
            return $this->max_order_quantity?:$this->quantity;
        }else{
            return 999999999;
        }
    }

    public function productMinPrice(){
        return $this->productActiveSkus()->min('price');
    }
    
    public function productMaxPrice(){
        return $this->productActiveSkus()->max('price');
    }
    
    
    
    public function stockStatus(){
        $status = true;
        if($this->stock_status){
            if($this->quantity){
                if($this->quantity > $this->stock_out_limit){
                    if($this->quantity==0){
                        $status = false;
                    }
                }else{
                    $status = false;
                }
            }
        }else{
         $status = false;  
        }
        
        
        return $status;
    }

    public function relatedProducts(){
        return Post::whereHas('productCtgs',function($q){
                    $q->whereIn('reff_id',$this->productCtgs->pluck('reff_id'));
                })
                ->whereNot('id',$this->id)
                ->where('status','active')
                ->whereDate('created_at','<=',date('Y-m-d'));
 
    }
    
    public function transfers(){
        return $this->hasMany(OrderItem::class,'product_id')->whereHas('order',function($q){
            $q->where('order_type','transfers_order');
        });
    }
    
    public function purchases(){
        return $this->hasMany(OrderItem::class,'product_id')->whereHas('order',function($q){
            $q->where('order_type','purchase_order');
        });
    }
    
    public function salesAll(){
        return $this->hasMany(OrderItem::class,'product_id')->whereHas('order',function($q){
            $q->whereIn('order_type',['pos_order','customer_order']);
        });
    }
    
    
    public function wishlists()
    {
        return $this->hasMany(WishList::class,'product_id')->where('type',0);
    }

    
    public function comparelists()
    {
        return $this->hasMany(WishList::class,'product_id')->where('type',1);
    }
    

    public function isWl()
    {
        return (bool) $this->wishlists()->where('cookie', Cookie::get('carts'))->where('type',0)->count();
    }
    
    public function isCP()
    {
        return (bool) $this->comparelists()->where('cookie', Cookie::get('carts'))->where('type',1)->count();
    }

    //Product Functions End
    
    public function user(){
    	return $this->belongsTo(User::class,'addedby_id');
    }

}
