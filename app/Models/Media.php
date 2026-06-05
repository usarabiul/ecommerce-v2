<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    public function image(){
    	
		if($this->file_url){
			if($this->file_type==1){
			return $this->file_url;
			}elseif($this->file_type==2){
			return 'public/medies/defultpdf.png';
			}elseif($this->file_type==3){
			return 'public/medies/defultdocx.png';
			}elseif($this->file_type==4){
			return 'public/medies/defultzip.png';
			}elseif($this->file_type==5){
			return 'public/medies/defultvedio.png';
			}else{
			return 'public/medies/defultunknown.png';
			}
		}else{
			return 'public/medies/noimage.jpg';
		}

    }
    
    
    public function imageName(){
        if($this->file_rename){
            return $this->file_rename;
        }else{
            return 'noimage.jpg';
        }
    }
    
    public function user(){
    	return $this->belongsTo(User::class,'addedby_id');
    }
}
