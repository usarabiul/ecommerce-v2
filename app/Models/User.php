<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'show_password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'show_password' => 'string',
        ];
    }

    public function identities() {
        return $this->hasMany(SocialIdentity::class);
     }
 
     public function permission(){
         return $this->belongsTo(Permission::class);
     }
 
     public function addedBy(){
         return $this->belongsTo(User::class,'addedby_id');
     }
 
     public function orders(){
         return $this->hasMany(Order::class,'user_id')->where('order_status','<>','temp');
     }
     
     public function comments(){
         return $this->hasMany(Review::class,'addedby_id')->where('type',1);
     }
     
     public function reviews(){
         return $this->hasMany(ProductReview::class);
     }
     
     public function imageFile(){
         return $this->hasOne(Media::class,'src_id')->where('src_type',6)->where('use_Of_file',1);
     }
 
     public function image($type=null){
         
         if($this->imageFile){
             if($type=='sm'){
                return $this->imageFile->file_url_sm; 
             }elseif($type=='md'){
                return $this->imageFile->file_url_md;
             }elseif($type=='lg'){
                return $this->imageFile->file_url_lg;
             }else{
                return $this->imageFile->file_url; 
             }
         }else{
             return 'medies/profile.png';
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
         return $this->hasOne(Media::class,'src_id')->where('src_type',6)->where('use_Of_file',2);
     }
 
     public function banner(){
         
         if($this->bannerFile){
             return $this->bannerFile->file_url;
         }else{
             return 'app-assets/images/carousel/22.jpg';
         }
     }
     
     public function bannerName(){
         
         if($this->bannerFile){
             return $this->bannerFile->file_rename;
         }else{
             return 'no-banner.png';
         }
     }
     
     public function countryN(){
         return $this->belongsTo(Country::class,'country');
     }
     
     public function divitionN(){
         return $this->belongsTo(Country::class,'division');
     }
 
     public function districtN(){
         return $this->belongsTo(Country::class,'district');
     }
     
     
     public function cityN(){
         return $this->belongsTo(Country::class,'city');
     }
 
 
     public function user(){
         return $this->belongsTo(User::class,'id');
     }
 
     public function fullAddress(){
 
         $addr =$this->address_line1;
 
         if($this->cityN){
            $addr .=', '.$this->cityN->name;
         }
 
         if($this->districtN){
            $addr .=', '.$this->districtN->name;
         }
 
         if($this->postal_code){
            $addr .=' - '.$this->postal_code;
         }
 
         if($this->divitionN){
            $addr .=', '.$this->divitionN->name;
         }
 
         return $addr;
         
     }
     
     public function posts(){
         return $this->hasMany(Post::class,'addedby_id')->where('type',1);;
     }


}
