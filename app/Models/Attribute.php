<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{

    public function imageFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',3)->where('use_Of_file',1);
    }

    public function image(){
        if($this->imageFile){
            return $this->imageFile->file_url;
        }else{
            return 'medies/noimage.jpg';
        }
    }

    public function bannerFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',3)->where('use_Of_file',2);
    }

    public function banner(){
        if($this->bannerFile){
            return $this->bannerFile->file_url;
        }else{
            return 'medies/no-banner.png';
        }
    }
    

    public function galleryImages(){
        return $this->hasMany(Media::class,'src_id')->where('use_Of_file',3)->orderBy('drag','asc');
    }

    //Image and Banner Functions End


    public function subCtgs(){
        return $this->hasMany(Attribute::class,'parent_id')->where('status','<>','temp');
    }

    public function parent(){
        return $this->belongsTo(Attribute::class,'parent_id')->where('status','<>','temp');
    }

    public function subAttributes(){
        return $this->hasMany(Attribute::class,'parent_id')->where('status','<>','temp');
    }
    
    public function posts(){
        return $this->belongsToMany(Post::class,PostAttribute::class,'reff_id','src_id');
    }

    public function activePosts(){
        return Post::whereHas('ctgPosts',function($q){
            $q->where('reff_id',$this->id);
          })
          ->where(function($qq){
            $qq->where('status','active');
          })
          ->whereDate('created_at','<=',date('Y-m-d'));
    }

    public function ctgPosts(){
        return $this->hasMany(PostAttribute::class,'reff_id')->where('type',1);
    }

    public function ctgProducts(){
        return $this->hasMany(PostAttribute::class,'reff_id')->where('type',0);
    }

    public function tagPosts(){
        return $this->hasMany(PostAttribute::class,'reff_id')->where('type',2);
    }

    public function couponCtgs(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',5)->orderBy('drag','asc');
    }
    
    public function couponProductPosts(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',6)->orderBy('drag','asc');
    }
    
    public function couponDiscountAmount($amount=0){
        if($this->menu_type==0){
            if($this->amounts <= 100){
                $amount =($amount * $this->amounts)/100;
            }
        }else{
           $amount =$amount < $this->amounts?$amount:$this->amounts;
        }
        return $amount;
    }

    public function activeProducts(){
        return Post::whereHas('ctgProducts',function($q){
            $q->where('reff_id',$this->id);
          })
          ->where(function($qq){
            $qq->where('status','active');
          })
          ->whereDate('created_at','<=',date('Y-m-d'));
    }

    //Slider Functions Start
    
    public function subSliders(){
        return $this->hasMany(Attribute::class,'parent_id')->where('status','active')->orderBy('view','asc');
    }

    public function sliderItems(){
        return $this->hasMany(Attribute::class,'parent_id')->orderBy('view','asc');
    }



    //Slider Functions End
    

    //Menu Functions Start
    /********
     * Note: menu_type ==1 : Custom Link
     *       menu_type ==2 : blog Category
     *       menu_type ==3 : Service Category
     * 
     * *********/

    public function pageLink(){
       return $this->belongsTo(Post::class,'src_id')->where('type',0)->where('status','<>','temp');
    }

    public function blogCtgLink(){
       return $this->belongsTo(Attribute::class,'src_id')->where('type',6)->where('status','<>','temp');
    }
    
    public function serviceCtgLink(){
       return $this->belongsTo(Attribute::class,'src_id')->where('type',0)->where('status','<>','temp');
    }
    
    public function subMenus(){
        return $this->hasMany(Attribute::class,'parent_id')->where('status','<>','temp')->orderBy('view','asc');
    }
    
    public function MenuItems(){
        return $this->hasMany(Attribute::class,'category_id')->where('status','<>','temp')->orderBy('view','asc');
    }

    //Menu Slug
    public function menuLink(){

        if($this->menu_type==1){
            if($this->pageLink){
                if($this->pageLink->template=='Front Page'){
                    return route("index");
                }else{
                    return route('pageView',$this->pageLink->slug);
                }
            }

        }elseif($this->menu_type==2){
            if($this->blogCtgLink){
                return route('blogCategory',$this->blogCtgLink->slug);
            }
        }elseif($this->menu_type==3){
            if($this->serviceCtgLink){
                return route('productCategory',$this->serviceCtgLink->slug);
            }
        }else{
            return $this->slug;
        }

    }

    //Menu Name
    public function menuName(){

        if($this->menu_type==1){
            if($this->pageLink){
                return $this->pageLink->name;
            }

        }elseif($this->menu_type==2){
            if($this->blogCtgLink){
                return $this->blogCtgLink->name;
            }
        }elseif($this->menu_type==3){
            if($this->serviceCtgLink){
                return $this->serviceCtgLink->name;
            }
        }else{
            return $this->name;
        }
        
    }

    //Menu Name
    public function menuSlug(){

        if($this->menu_type==1){
            if($this->pageLink){
                if($this->pageLink->template=='Front Page'){
                    return "";
                }else{
                    return $this->pageLink->slug;
                }
                
            }

        }elseif($this->menu_type==2){
            if($this->blogCtgLink){
                return $this->blogCtgLink->slug;
            }
        }elseif($this->menu_type==3){
            if($this->serviceCtgLink){
                return $this->serviceCtgLink->slug;
            }
        }else{
            return $this->slug;
        }

    }
    
    //********************************
    //Menu Functions End
    
    public function user(){
        return $this->belongsTo(User::class,'addedby_id');
    }
}