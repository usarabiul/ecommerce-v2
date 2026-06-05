<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Str;
use Hash;
use File;
use DB;
use Image;
use Artisan;
use Session;
use Validator;
use Redirect,Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Transaction;
use App\Models\PostExtra;
use App\Models\Review;
use App\Models\General;
use App\Models\Country;
use App\Models\Order;
use App\Models\Media;
use App\Models\Attribute;
use App\Models\Permission;
use App\Models\PostAttribute;
use App\Models\PostContentElement;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    public function resizeImage($loadImage,$type,$w,$h){
        
        $img =	$type.$loadImage->file_rename;
        $fullpath ="public/".$loadImage->file_path.'/'.$img;
        $path = public_path($loadImage->file_path.'/');
        
        $image = Image::make($loadImage->file_url);
        $image->fit($w,$h);
        $image->save($path.$img);
        
        if($type=='sm'){
           $loadImage->file_url_sm=$fullpath;
           $loadImage->save(); 
        }elseif($type=='md'){
           $loadImage->file_url_md=$fullpath;
           $loadImage->save(); 
        }elseif($type=='lg'){
           $loadImage->file_url_lg=$fullpath;
           $loadImage->save(); 
        }
        
        return true;
    }
    
    public function dashboard(){
        
        ///Reports  Summery Dashboard
        $productsTotal=Post::where('type',2)
        ->where('status','active')
        ->count();

        $blogsTotal=Post::where('type',1)
        ->where('status','active')
        ->count();

        $pagesTotal=Post::where('type',0)
        ->where('status','active')
        ->count();

        $userTotal=User::where('customer',true)
        ->where('status',1)
        ->count();
        
        $ordersTotal=Order::where('order_type','customer_order')
        ->whereNotIn('order_status',['temp','cancelled','returned'])
        ->count();

        $reports=array(
                    "products"=>$productsTotal,
                    "blogs"=>$blogsTotal,
                    "pages"=>$pagesTotal,
                    "users"=>$userTotal,
                    "orders"=>$ordersTotal,
                );
        ///Reports  Summery Dashboard
        
        return view(adminTheme().'dashboard',compact('reports'));
      
    }



    public function myProfile(Request $r){

      $user =Auth::user();
      if($r->isMethod('post')){
        if($r->actionType=='profile'){
          $check = $r->validate([
            'name' => 'required|max:100|unique:users,name,'.$user->id,
            'email' => 'required|max:100|unique:users,email,'.$user->id,
            'mobile' => 'nullable|max:20|unique:users,mobile,'.$user->id,
            'gender' => 'nullable|max:10',
            'address' => 'nullable|max:191',
            'division' => 'nullable|numeric',
            'district' => 'nullable|numeric',
            'city' => 'nullable|numeric',
            'postal_code' => 'nullable|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
          ]);
  
          $user->name =$r->name;
          $user->mobile =$r->mobile;
          $user->email =$r->email;
          $user->gender =$r->gender;
          $user->address_line1 =$r->address;
          $user->division =$r->division;
          $user->district =$r->district;
          $user->city =$r->city;
          $user->postal_code =$r->postal_code;
          ///////Image UploadStart////////////
          if($r->hasFile('image')){
            $file =$r->image;
            $src  =$user->id;
            $srcType  =6;
            $fileUse  =1;
            $author=Auth::id();
            
            $loadImage =uploadFile($file,$src,$srcType,$fileUse,$author);
            // //Resize Image 
            // $w=250;
            // $h=250;
            // $type='md';
            // $this->resizeImage($loadImage,$type,$w,$h);
            
            // //Resize Image 
            // $w=50;
            // $h=50;
            // $type='sm';
            // $this->resizeImage($loadImage,$type,$w,$h);
            
            // //Resize Image 
            // $w=400;
            // $h=400;
            // $type='lg';
            // $this->resizeImage($loadImage,$type,$w,$h);
            
          }
          ///////Image Upload End////////////
          $user->save();
  
          Session()->flash('success','Your Updated Are Successfully Done!');
  
        }
        if($r->actionType=='change-password'){
  
          $check = $r->validate([
              'old_password' => 'required|string|min:8',
              'password' => 'required|string|min:8|confirmed|different:old_password',
          ]);
  
          if(Hash::check($r->old_password, $user->password)){
            $user->password_show=$r->password;
            $user->password=Hash::make($r->password);
            $user->update();
            Session()->flash('success','Your Are Successfully Done');
          }else{
            Session()->flash('error','Current Password Are Not Match');
          }
        }
        return back();
      }
        
      return view(adminTheme().'users.myProfile',compact('user'));
      
    }


    //Medias Library Route
  public function medies(Request $r){

    //Check Authorized User
    $allPer = empty(json_decode(Auth::user()->permission->permission, true)['medies']['all']);

    //Media Delete All Selected Images Start
    if($r->actionType=='allDelete'){

      $check = $r->validate([
          'mediaid.*' => 'required|numeric',
      ]);

      for ($i=0; $i < count($r->mediaid); $i++) { 
        $media =Media::find($r->mediaid[$i]);
        if($media){

          if($allPer && $media->addedby_id!=Auth::id()){
            //You are unauthorized Try!!;
          }else{

            if(File::exists($media->file_url)){
                File::delete($media->file_url);
            }
            $media->delete();

          }

        }
      }

      Session()->flash('success','Your Are Successfully Deleted');
      return redirect()->back();
    }

    //Media Delete All Selected Images End


    $medies =Media::latest()->where('src_type',0)
    ->where(function($q) use ($r,$allPer) {

      // Check Permission
      if($allPer){
        $q->where('addedby_id',auth::id()); 
      }

    })
    ->select(['id','file_url','file_size','file_type','file_name','alt_text','caption','description','addedby_id'])
    ->paginate(50);

    if($r->ajax())
      {

          return Response()->json([
              'success' => true,
              'view' => View(adminTheme().'medies.includes.mediesAll',[
                  'medies'=>$medies
              ])->render()
          ]);
      }

    return view(adminTheme().'medies.medies',compact('medies'));
  }

  public function mediesCreate(Request $r){

      $check = $r->validate([
          'images.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff,pdf,docx,zip,rar,mp4,webm,mov,wmv,mp3|max:25600',
      ]);

      if(!$check){
          Session::flash('error','Need To validation');
          return back();
      }   

     $files=$r->file('images');
      if($files){
          foreach($files as $file){

              $file =$file;
              $src  =null;
              $srcType  =0;
              $fileUse  =0;
              $fileStatus=false;
              $author=Auth::id();
              uploadFile($file,$src,$srcType,$fileUse,$author,$fileStatus);
              
          }
      }

    Session()->flash('success','Your Are Successfully Done');
     return redirect()->back();
     
  }
    
    public function mediesFileUpload(Request $request){
        
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/'), $fileName);
        }

        
        
        if($request->ajax()){
            return Response()->json([
                  'success' => true,
              ]);
        }
    }
   
    
  public function mediesEdit(Request $r, $id){
    $media =Media::find($id);
    if(!$media){
      Session()->flash('error','This File Are Not Found');
      return redirect()->back();
    }

    if($media->src_type==0){
      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['medies']['all']);
      if($allPer && $media->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.medies');
      }
    }

    if($r->isMethod('post')){
         $media->alt_text=$r->alt_text;
         $media->caption=$r->caption;
         $media->description=$r->description;
         $media->editedby_id=auth::id();
         $media->save();
         Session()->flash('success','Your Are Successfully Done');
         return redirect()->back();
    }

    return view(adminTheme().'medies.mediaImageEdit',compact('media'));
  }


  public function mediesDelete(Request $request,$id){

     if($request->ajax())
    {
   
    $media =Media::find($id);
    if(!$media){
      Session()->flash('error','This File Are Not Found');
     return Response()->json([
              'success' => false
          ]);
     }
     
    if(File::exists($media->file_url)){
          File::delete($media->file_url);
    }
    if(File::exists($media->file_url_sm)){
        File::delete($media->file_url_sm);
    }
    if(File::exists($media->file_url_md)){
        File::delete($media->file_url_md);
    }
    if(File::exists($media->file_url_lg)){
        File::delete($media->file_url_lg);
    }
    $media->delete();
      return Response()->json([
              'success' => true
          ]);
    }      

  }

  //Medias Library Route End

  // Page Management Function Start
    
  public function pages(Request $r){

    $allPer = empty(json_decode(Auth::user()->permission->permission, true)['pages']['all']);
      // Filter Action Start

    if($r->action){
      if($r->checkid){

      $datas=Post::latest()->where('type',0)->whereIn('id',$r->checkid)->get();

      foreach($datas as $data){
        if($allPer && $data->addedby_id!=Auth::id()){
          // You are unauthorized Try!!
        }else{

          if($r->action==1){
            $data->status='active';
            $data->save();
          }elseif($r->action==2){
            $data->status='inactive';
            $data->save();
          }elseif($r->action==3){
            $data->fetured=true;
            $data->save();
          }elseif($r->action==4){
            $data->fetured=false;
            $data->save();
          }elseif($r->action==5){
            //Page Extra Data Delete
            PostExtra::where('type',0)->where('src_id',$data->id)->delete();
            
            //Page Media File Delete
            $medias =Media::latest()->where('src_type',1)->where('src_id',$data->id)->get();
            foreach($medias as $media){
              if(File::exists($media->file_url)){
                File::delete($media->file_url);
              }
              $media->delete();
            }

            $data->delete();

          }

        }


      }

      Session()->flash('success','Action Successfully Completed!');

      }else{
        Session()->flash('info','Please Need To Select Minimum One Post');
      }

      return redirect()->back();
    }

    //Filter Action End

    $pages=Post::latest()->where('type',0)->where('status','<>','temp')
    ->where(function($q) use ($r,$allPer) {

        if($r->search){
            $q->where('name','LIKE','%'.$r->search.'%');
        }
        
        if($r->startDate || $r->endDate)
        {
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
           $q->where('status',$r->status); 
        }

      // Check Permission
      if($allPer){
       $q->where('addedby_id',auth::id()); 
      }

    })
    ->select(['id','name','slug','view','type','template','created_at','addedby_id','status','featured'])
    ->paginate(25)->appends([
      'search'=>$r->search,
      'status'=>$r->status,
      'startDate'=>$r->startDate,
      'endDate'=>$r->endDate,
    ]);

    //Total Count Result
    $total = DB::table('posts')->where('status','<>','temp')
    ->where('type',0)
    ->selectRaw('count(*) as total')
    ->selectRaw("count(case when status = 'active' then 1 end) as active")
    ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
    ->first();

    return view(adminTheme().'pages.pagesAll',compact('pages','total'));

  }

  public function pagesAction(Request $r,$action,$id=null){

      if($action=='create'){
        $page =Post::where('type',0)->where('status','temp')->where('addedby_id',Auth::id())->first();
        if(!$page){
          $page =new Post();
          $page->type =0;
          $page->status ='temp';
          $page->addedby_id =Auth::id();
        }
        $page->created_at =Carbon::now();
        $page->save();
  
        return redirect()->route('admin.pagesAction',['edit',$page->id]);
      }
      $page =Post::find($id);
      if(!$page){
        Session()->flash('error','This Page Are Not Found');
        return redirect()->route('admin.pages');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['pages']['all']);
      if($allPer && $page->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.pages');
      }

      if($action=='update' && $r->isMethod('post')){

        $check = $r->validate([
            'name' => 'required|max:200',
            'slug' => 'nullable|max:240',
            'template' => 'nullable|max:100',
            'seo_title' => 'nullable|max:120',
            'seo_description' => 'nullable|max:200',
            'seo_keyword' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff,webp|max:2048',
        ]);


        $page->name=$r->name;
        $page->short_description=$r->short_description;
        $page->description=$r->description;
        $page->seo_title=$r->seo_title;
        $page->seo_description=$r->seo_description;
        $page->seo_keyword=$r->seo_keyword;
        $page->template=$r->template?:null;
        ///////Image Upload Start////////////
        if($r->hasFile('image')){
          $file =$r->image;
          $src  =$page->id;
          $srcType  =1;
          $fileUse  =1;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Image Upload End////////////

        ///////Image Upload Start////////////
        if($r->hasFile('banner')){
          $file =$r->banner;
          $src  =$page->id;
          $srcType  =1;
          $fileUse  =2;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Image Upload End////////////
        $page->auto_slug=$r->slug?true:false;
        $slug =Str::slug($r->slug?:$r->name);
        if($slug==null){
          $page->slug=$page->id;
        }else{
          if(Post::where('type',0)->where('slug',$slug)->whereNotIn('id',[$page->id])->count() >0){
          $page->slug=$slug.'-'.$page->id;
          }else{
          $page->slug=$slug;
          }
        }

        if($r->created_at){
          $page->created_at =$r->created_at;
        }
        $page->status =$r->status?'active':'inactive';
        $page->featured =$r->featured?1:0;
        $page->editedby_id =Auth::id();
        $page->save();
        
        //Gallery posts
        if($r->galleries){

          $page->postTags()->whereNotIn('reff_id',$r->galleries)->delete();

          for ($i=0; $i < count($r->galleries); $i++) {
            $tag = $page->postTags()->where('reff_id',$r->galleries[$i])->first();

              if($tag){}else{
              $tag =new PostAttribute();
              $tag->type=2;
              $tag->src_id=$page->id;
              $tag->reff_id=$r->galleries[$i];
              }
              $tag->save();
         }
       }else{
        $page->postTags()->delete();
       }
        
        
        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

      }

      if($action=='delete'){
        
        //Page Extra Data Delete
        PostExtra::where('type',0)->where('src_id',$page->id)->delete();

        //Page Media File Delete
        $medies =Media::where('src_type',1)->where('src_id',$page->id)->get();
        foreach ($medies as  $media) {
            if(File::exists($media->file_url)){
                File::delete($media->file_url);
            }
            $media->delete();
        }

        //Page Delete
        $page->delete();
        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

      }

      $extraDatas=PostExtra::where('src_id',$id)->get();
      
      $galleries=Attribute::latest()->where('type',4)->where('status','<>','temp')->where('parent_id',null)
      ->select(['id','name'])
      ->get();

      return view(adminTheme().'pages.pageEdit',compact('page','extraDatas','galleries'));
  }


  // Page Management Function End

  //Clients Function

    public function clients(Request $r){
      
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['clients']['all']);
      
      // Filter Action Start
      if($r->action){
        if($r->checkid){

        $datas=Attribute::latest()->where('type',3)->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){
          if($allPer && $data->addedby_id!=Auth::id()){
            // You are unauthorized Try!!
          }else{

            if($r->action==1){
              $data->status='active';
              $data->save();
            }elseif($r->action==2){
              $data->status='inactive';
              $data->save();
            }elseif($r->action==3){
              $data->fetured=true;
              $data->save();
            }elseif($r->action==4){
              $data->fetured=false;
              $data->save();
            }elseif($r->action==5){
              
              $medias =Media::latest()->where('src_type',3)->where('src_id',$data->id)->get();
              foreach($medias as $media){
                if(File::exists($media->file_url)){
                  File::delete($media->file_url);
                }
                $media->delete();
              }

              $data->delete();
            }

          }


        }

        Session()->flash('success','Action Successfully Completed!');

        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }

        return redirect()->back();
      }

      //Filter Action End

      $clients=Attribute::latest()->where('type',3)->where('status','<>','temp')
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
      $totals = DB::table('attributes')
      ->where('type',3)
      ->selectRaw('count(*) as total')
      ->selectRaw("count(case when status = 'active' then 1 end) as active")
      ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
      ->first();

      return view(adminTheme().'clients.clientsAll',compact('clients','totals'));
    }

    public function clientsAction(Request $r,$action,$id=null){
      // Add Client Action Start
      if($action=='create'){

        $client =Attribute::where('type',3)->where('status','temp')->where('addedby_id',Auth::id())->first();
        if(!$client){
          $client =new Attribute();
          $client->type =3;
          $client->status ='temp';
          $client->addedby_id =Auth::id();
        }
        $client->created_at=Carbon::now();
        $client->save();

        return redirect()->route('admin.clientsAction',['edit',$client->id]);

      } 

      // Add Client Action End
      
      
      $client =Attribute::where('type',3)->find($id);
      if(!$client){
        Session()->flash('error','This Client Are Not Found');
        return redirect()->route('admin.clients');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['clients']['all']);
      if($allPer && $client->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.clients');
      }

      // Update Client Action Start
      if($action=='update'){
          $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'seo_desc' => 'nullable|max:250',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
        ]);

        $client->name=$r->name;
        $client->short_description=$r->short_description;
        $client->description=$r->description;
        $client->seo_title=$r->seo_title;
        $client->seo_description=$r->seo_description;
        $client->seo_keyword=$r->seo_keyword;

        ///////Image UploadStart////////////
      
        if($r->hasFile('image')){
          $file =$r->image;
          $src  =$client->id;
          $srcType  =3;
          $fileUse  =1;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        
        ///////Image Upload End////////////

        ///////Banner Upload End////////////

        if($r->hasFile('banner')){

          $file =$r->banner;
          $src  =$client->id;
          $srcType  =3;
          $fileUse  =2;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);

        }

        ///////Banner Upload End////////////

        $slug = Str::slug($r->name);
        if (!$slug) {
            $client->slug = $client->id;
        } else {
            $exists = Attribute::where('type',3)->where('slug', $slug)->where('id', '!=', $client->id)->exists();
            $client->slug = $exists ? $slug . '-' . $client->id : $slug;
        }
        $createDate = $r->created_at ? Carbon::parse($r->created_at . ' ' . Carbon::now()->format('H:i:s')) : Carbon::now();
        if (!$createDate->isSameDay($client->created_at)) {
          $client->created_at = $createDate;
        }

        $client->status =$r->status?'active':'inactive';
        $client->featured =$r->featured?1:0;
        $client->editedby_id =Auth::id();
        $client->save();

        Session()->flash('success','Your Are Successfully Updated');
        return redirect()->back();

      }

      // Update Client Action End


      // Delete Client Action Start
      if($action=='delete'){
        $medias =Media::latest()->where('src_type',3)->where('src_id',$client->id)->get();
        foreach($medias as $media){
          if(File::exists($media->file_url)){
            File::delete($media->file_url);
          }
          $media->delete();
        }

        $client->delete();

        Session()->flash('success','Your Are Successfully Deleted');
        return redirect()->route('admin.clients');

      }
      // Delete Client Action End

      return view(adminTheme().'clients.clientsEdit',compact('client'));
    }


    //Clients Function End


    //Sliders Function
    public function sliders(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['sliders']['all']);

      // Add Slider Action Start
      if($r->actionType=='addSlider'){
        $slider =Attribute::latest()->where('type',1)->where('addedby_id',Auth::id())->where('status','temp')->first();
        if(!$slider){
        $slider =new Attribute();
        $slider->type =1;
        $slider->status ='temp';
        $slider->addedby_id =Auth::id();
        $slider->save();
        }else{
        $slider->created_at =Carbon::now();
        $slider->save();
        }
        return redirect()->route('admin.slidersEdit',$slider->id);
      }
      // Add Slider Action End

      $sliders=Attribute::latest()->where('type',1)->where('status','<>','temp')->where('parent_id',null)
      ->where(function($q) use ($allPer) {
          // Check Permission
          if($allPer){
           $q->where('addedby_id',auth::id()); 
          }
      })
      ->select(['id','name','location','type','created_at','addedby_id','status','featured'])
      ->paginate(25);

      

      return view(adminTheme().'sliders.slidersAll',compact('sliders'));
    }

    public function slidersAction(Request $r,$action,$id=null){
      //Create Slider Start
      if($action=='create'){
        $slider =Attribute::latest()->where('type',1)->where('addedby_id',Auth::id())->where('status','temp')->first();
        if(!$slider){
        $slider =new Attribute();
        $slider->type =1;
        $slider->status ='temp';
        $slider->addedby_id =Auth::id();
        $slider->save();
        }else{
        $slider->created_at =Carbon::now();
        $slider->save();
        }
        return redirect()->route('admin.slidersAction',['edit',$slider->id]);
      }
      //Create Slider End
      
      $slider =Attribute::where('type',1)->find($id);
      if(!$slider){
        Session()->flash('error','This Slider Are Not Found');
        return redirect()->route('admin.sliders');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['sliders']['all']);
      if($allPer && $slider->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.sliders');
      }

      // Update Slider Action Start
      if($action=='update'){

        $check = $r->validate([
            'name' => 'required|max:191',
            'location' => 'nullable|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:25600',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
        ]);

        $activeSlider =Attribute::where('type',1)->where('location',$r->location)->whereNotIn('id',[$slider->id])->first();
      
        if($activeSlider && $r->location){
          Session::flash('error','This Location Have Already a slider');
          return back();
        }

        $slider->name=$r->name;
        $slider->description=$r->description;
        $slider->location=$r->location;
        $slider->seo_title=$r->vediolink;

        ///////Image UploadStart////////////

        if($r->hasFile('image')){
          $file =$r->image;
          $src  =$slider->id;
          $srcType  =3;
          $fileUse  =1;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        
        ///////Image Upload End////////////

        ///////Banner Upload End////////////

        if($r->hasFile('banner')){

          $file =$r->banner;
          $src  =$slider->id;
          $srcType  =3;
          $fileUse  =2;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);

        }

        ///////Banner Upload End////////////

        ///////Gallery Images UploadStart////////////
        
        $files=$r->file('images');
        if($files){
          
            foreach($files as $file)
            {

              $slide =new Attribute();
              $slide->type =1;
              $slide->parent_id =$slider->id;
              $slide->status ='active';
              $slide->addedby_id =Auth::id();
              $slide->save();

              $src  =$slide->id;
              $srcType  =3;
              $fileUse  =1;
              $author  =Auth::id();
              uploadFile($file,$src,$srcType,$fileUse,$author);

            }
          } 

        ///////Slide Drag Update Start////////////
        if(isset($r->slideid)){
          for ($i=0; $i < count($r->slideid); $i++) { 
            $slide =$slider->sliderItems->find($r->slideid[$i]);
            if($slide){
              $slide->view=$i;
              $slide->save();
            }
          }
        }
        ///////Slide Drag Update End////////////

        $slug =Str::slug($r->name);
        if($slug==null){
          $slider->slug=$slider->id;
        }else{
          if(Attribute::where('type',1)->where('slug',$slug)->whereNotIn('id',[$slider->id])->count() >0){
          $slider->slug=$slug.'-'.$slider->id;
          }else{
          $slider->slug=$slug;
          }
        }
        $slider->status =$r->status?'active':'inactive';
        $slider->editedby_id =Auth::id();
        $slider->save();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

      }
      // Update Slider Action End

      // Delete Slider Action Start
      if($action=='delete'){
         //Sub Slider Items Delete
          foreach($slider->sliderItems as $slide){

            //Galleries  Media File Delete
              $medies =Media::where('src_type',3)->where('src_id',$slide->id)->get();
              foreach ($medies as  $media) {
                  if(File::exists($media->file_url)){
                      File::delete($media->file_url);
                  }
                  $media->delete();
              }

            $slide->delete();

          }

        //Galleries  Media File Delete
        $sliderMedies =Media::where('src_type',3)->where('src_id',$slider->id)->get();
        foreach ($sliderMedies as  $media) {
              if(File::exists($media->file_url)){
                  File::delete($media->file_url);
              }
              $media->delete();
          }

        $slider->delete();

        Session()->flash('success','Your Are Successfully Deleted');
        return redirect()->route('admin.sliders');

      }
      // Delete Slider Action End

      return view(adminTheme().'sliders.slidersEdit',compact('slider'));
    }

    public function slideAction(Request $r,$action,$id){
      $slide =Attribute::where('type',1)->find($id);
      if(!$slide){
        Session()->flash('error','This Slide Are Not Found');
        return redirect()->route('admin.sliders');
      }

      // Update Slide Slider Action Start
      if($action=='update'){
          $check = $r->validate([
              'name' => 'nullable|max:191',
              'buttonText' => 'nullable|max:200',
              'buttonLink' => 'nullable|max:200',
              'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
          ]);

        $slide->name =$r->name;
        $slide->seo_title=$r->buttonText?:null;
        $slide->seo_description=$r->buttonLink?:null;
        $slide->description=$r->description;

        ///////Image UploadStart////////////

        if($r->hasFile('image')){
          $file =$r->image;
          $src  =$slide->id;
          $srcType  =3;
          $fileUse  =1;
          $author  =Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        
        ///////Image Upload End////////////

        $slug =Str::slug($r->name);
        if($slug==null){
          $slide->slug=$slide->id;
        }else{
          if(Attribute::where('type',1)->where('slug',$slug)->whereNotIn('id',[$slide->id])->count() >0){
          $slide->slug=$slug.'-'.$slide->id;
          }else{
          $slide->slug=$slug;
          }
        }
        $slide->status =$r->status?'active':'inactive';
        $slide->editedby_id =Auth::id();
        $slide->save();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();

      }
      // Update Slide Slider Action End

      // Delete Slide Slider Action Start
      if($action=='delete'){
        //Galleries  Media File Delete
        $medies =Media::where('src_type',3)->where('src_id',$slide->id)->get();
          foreach ($medies as  $media) {
              if(File::exists($media->file_url)){
                  File::delete($media->file_url);
              }
              $media->delete();
          }
        $slide->delete();
        Session()->flash('success','Your Are Successfully Deleted');
        return redirect()->back();
      }
      // Delete Slide Slider Action End


      return  view(adminTheme().'sliders.slideEdit',compact('slide'));
    }

    //Sliders Function End


    //Galleries Function Start

    public function galleries(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['galleries']['all']);

      $galleries=Attribute::latest()->where('type',4)->where('status','<>','temp')->where('parent_id',null)
      ->where(function($q) use ($allPer) {
          // Check Permission
          if($allPer){
           $q->where('addedby_id',auth::id()); 
          }
      })
      ->select(['id','name','location','type','created_at','addedby_id','status','featured'])
      ->paginate(25);
      return view(adminTheme().'galleries.galleriesAll',compact('galleries'));

    }

    public function galleriesAction(Request $r,$action,$id=null){

      //Create Gallery Start
      if($action=='create'){
        $gallery =Attribute::latest()->where('type',4)->where('addedby_id',Auth::id())->where('status','temp')->first();
        if(!$gallery){
        $gallery =new Attribute();
        $gallery->type =4;
        $gallery->status ='temp';
        $gallery->addedby_id =Auth::id();
        $gallery->save();
        }else{
        $gallery->created_at =Carbon::now();
        $gallery->save();
        }
        return redirect()->route('admin.galleriesAction',['edit',$gallery->id]);
      }
      //Create Gallery End

      $gallery =Attribute::where('type',4)->find($id);
      if(!$gallery){
        Session()->flash('error','This Gallery Are Not Found');
        return redirect()->route('admin.galleries');
      }

      //Check Authorized User
      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['galleries']['all']);
      if($allPer && $gallery->addedby_id!=Auth::id()){
        Session()->flash('error','You are unauthorized Try!!');
        return redirect()->route('admin.galleries');
      }

      //Update Gallery Start
      if($action=='update'){

        $check = $r->validate([
            'name' => 'required|max:191',
            'location' => 'nullable|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:25600',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
        ]);

        $activeGallery =Attribute::where('type',4)->where('location',$r->location)->whereNotIn('id',[$gallery->id])->first();
      
        if($activeGallery && $r->location){
          Session::flash('error','This Location Have Already a Gallery');
          return back();
        }
        
        $gallery->name=$r->name;
        $gallery->description=$r->description;
        $gallery->location=$r->location;

        ///////Image UploadStart////////////

        if($r->hasFile('image')){
          $file =$r->image;
          $src  =$gallery->id;
          $srcType  =3;
          $fileUse  =1;
          $author =Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Image Upload End////////////

        ///////Banner Upload End////////////

        if($r->hasFile('banner')){

          $file =$r->banner;
          $src  =$gallery->id;
          $srcType  =3;
          $fileUse  =2;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);

        }

        ///////Banner Upload End////////////

        ///////Gallery Images UploadStart////////////
        
        $files=$r->file('images');
        if($files){
          
            foreach($files as $file)
            {
              
              $file =$file;
              $src  =$gallery->id;
              $srcType  =3;
              $fileUse  =3;
              $fileStatus=false;
              $author=Auth::id();
              uploadFile($file,$src,$srcType,$fileUse,$author,$fileStatus);
            }
          }
            
        ///////Gallery Images Upload End////////////
     
        $slug =Str::slug($r->name);
        if($slug==null){
          $gallery->slug=$gallery->id;
        }else{
          if(Attribute::where('type',1)->where('slug',$slug)->whereNotIn('id',[$gallery->id])->count() >0){
          $gallery->slug=$slug.'-'.$gallery->id;
          }else{
          $gallery->slug=$slug;
          }
        }
        $gallery->status =$r->status?'active':'inactive';
        $gallery->editedby_id =Auth::id();
        $gallery->save();

        if(isset($r->imageid)){

          for ($i=0; $i < count($r->imageid); $i++) { 
              $image =$gallery->galleryImages()->where('id',$r->imageid[$i])->first();
  
              if($image){
                $image->drag=$i;
                $image->alt_text=$r->imageName[$i];
                $image->description=$r->imageDescription[$i];
                $image->save();
              }
          }
  
        }
        
        if(isset($r->checkid)){
  
          for ($i=0; $i < count($r->checkid); $i++) { 
              $image =$gallery->galleryImages()->where('id',$r->checkid[$i])->first();
              if($image){
                if(File::exists($image->file_url)){
                    File::delete($image->file_url);
                }
               $image->delete();
              }
            
            }
        }

        
        Session()->flash('success','Your Are Successfully Update');
        return redirect()->back();

      }
      //Update Gallery End

      //Delete Gallery Start
      if($action=='delete'){

        //Galleries  Media all File Delete
        $galleryMedies =Media::where('src_type',3)->where('src_id',$gallery->id)->get();

        foreach ($galleryMedies as  $media) {
              if(File::exists($media->file_url)){
                  File::delete($media->file_url);
              }
              $media->delete();
          }

        $gallery->delete();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->route('admin.galleries');

      }
      //Delete Gallery End

      return view(adminTheme().'galleries.galleriesEdit',compact('gallery'));

    }

    //Galleries Function End


    public function themeSetting(Request $r){
      return view(adminTheme().'theme-setting.themeSetting');
    }



    // User Management Function Start

    public function usersAdmin(Request $r){

      //Filter Actions Start
      if($r->action){
        if($r->checkid){
  
        $datas=User::latest()->whereIn('status',[0,1])->where('admin',true)->whereIn('id',$r->checkid)->get();
  
        foreach($datas as $data){
  
            if($r->action==1){
              $data->status=1;
              $data->save();
            }elseif($r->action==2){
              $data->status=0;
              $data->save();
            }elseif($r->action==3){
              $data->fetured=true;
              $data->save();
            }elseif($r->action==4){
              $data->fetured=false;
              $data->save();
            }elseif($r->action==5){
              
              //User Media File Delete
              $data->admin=false;
              $data->addedby_at=null;
              $data->permission_id=null;
              $data->addedby_id=null;
              $data->save();
  
            }
  
        }
  
        Session()->flash('success','Action Successfully Completed!');
  
        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }
  
        return redirect()->back();
      }
  
      //Filter Action End

    
      $users =User::latest()->whereIn('status',[0,1])->where('admin',true)
      ->where(function($q) use($r) {
  
          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
              $q->orWhere('email','LIKE','%'.$r->search.'%');
              $q->orWhere('mobile','LIKE','%'.$r->search.'%');
          }

          if($r->role){
             $q->where('permission_id',$r->role);
          }
          
          if($r->startDate || $r->endDate)
          {
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
  
              $q->whereDate('addedby_at','>=',$from)->whereDate('addedby_at','<=',$to);
          }
  
      })
      ->select(['id','permission_id','name','email','mobile','addedby_at','addedby_id','status'])
      ->paginate(25)->appends([
        'search'=>$r->search,
        'startDate'=>$r->startDate,
        'endDate'=>$r->endDate,
      ]);

      //Total Count Results
      $totals = DB::table('users')->whereIn('status',[0,1])->where('admin',true)
      ->selectRaw('count(*) as total')
      ->selectRaw("count(case when status = 1 then 1 end) as active")
      ->selectRaw("count(case when status = 0 then 1 end) as inactive")
      ->first();
      
      $roles =Permission::latest()->where('status','active')->get();

      return view(adminTheme().'users.admins.users',compact('users','totals','roles'));
    }
  
    public function usersAdminAction (Request $r,$action,$id=null){
      
      //Add Admin User Start
      if($action=='create' && $r->isMethod('post')){
  
        if(filter_var($r->username, FILTER_VALIDATE_EMAIL)){
          $hasUser =User::latest()->whereIn('status',[0,1])->where('email',$r->username)->first();
        }else{
          $hasUser =User::latest()->whereIn('status',[0,1])->where('mobile',$r->username)->first();
        }
    
        if(!$hasUser){
            Session()->flash('error','This User Are Not Register');
            return redirect()->route('admin.usersAdmin');
        }
    
        if($hasUser->admin){
            Session()->flash('error','This User Are already Admin Authorize');
            return redirect()->route('admin.usersAdmin');
        }
    
        $hasUser->admin=true;
        $hasUser->permission_id=1;
        $hasUser->addedby_at=Carbon::now();
        $hasUser->addedby_id=Auth::id();
        $hasUser->save();
       
        Session()->flash('success','User Are Successfully Admin Authorize Done!');
        return redirect()->route('admin.usersAdminAction',['edit',$hasUser->id]);
  
      }
      //Add Admin User End
  
  
      $user=User::whereIn('status',[0,1])->where('admin',true)->find($id);

      if(!$user){
        Session()->flash('error','This Admin User Are Not Found');
        return redirect()->route('admin.usersAdmin');
      }
  
        //Update User Profile Start
        if($action=='update' && $r->isMethod('post')){
  
            $check = $r->validate([
                 'name' => 'required|max:100|unique:users,name,'.$user->id,
                 'email' => 'required|max:100|unique:users,email,'.$user->id,
                 'mobile' => 'nullable|max:20|unique:users,mobile,'.$user->id,
                 'gender' => 'nullable|max:10',
                 'address' => 'nullable|max:191',
                 'division' => 'nullable|numeric',
                 'district' => 'nullable|max:191',
                 'city' => 'nullable|max:191',
                 'postal_code' => 'nullable|max:20',
                 'role' => 'nullable|numeric',
                 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
     
             ]);
     
           $user->name =$r->name;
           $user->mobile =$r->mobile;
           $user->email =$r->email;
           $user->gender =$r->gender;
           $user->address_line1 =$r->address;
           $user->division =$r->division;
           $user->district =$r->district;
           $user->city =$r->city;
           $user->postal_code =$r->postal_code;
           $user->permission_id =$r->role;
           
           ///////Image UploadStart////////////
           if($r->hasFile('image')){
             $file =$r->image;
             $src  =$user->id;
             $srcType  =6;
             $fileUse  =1;
             $author=Auth::id();
             uploadFile($file,$src,$srcType,$fileUse,$author);
           }
           ///////Image Upload End////////////
     
           $user->status=$r->status?true:false;
           $user->save();
     
           Session()->flash('success','Your Updated Are Successfully Done!');
           return redirect()->route('admin.usersAdminAction',['edit',$user->id]);
        }
        //Update User Profile End
  
        //Update User Password Start
        if($action=='change-password' && $r->isMethod('post')){
          
          $validator = Validator::make($r->all(), [
              'old_password' => 'required|string|min:8',
              'password' => 'required|string|min:8|confirmed|different:old_password',
          ]);
        
          if($validator->fails()){
              return redirect()->route('admin.usersAdminAction',['edit',$user->id])->withErrors($validator)->withInput();
          }
  
          if(Hash::check($r->old_password, $user->password)){
            $user->password_show=$r->password;
            $user->password=Hash::make($r->password);
            $user->update();
  
            Session()->flash('success','Your Are Successfully Done');
            return redirect()->route('admin.usersAdminAction',['edit',$user->id]);
          }else{
            Session()->flash('error','Current Password Are Not Match');
            return redirect()->route('admin.usersAdminAction',['edit',$user->id]);
          }
        }
        //Update User Password End
  
        //Delete User End
        if($action=='delete'){
          $user->admin=false;
          $user->addedby_at=null;
          $user->permission_id=null;
          $user->addedby_id=null;
          $user->save();
  
          Session()->flash('success','Admin User Are Removed Successfully Done');
          return redirect()->route('admin.usersAdmin');
        }
        //Delete User End
        $roles =Permission::latest()->where('status','active')->get();
  
        return view(adminTheme().'users.admins.editUser',compact('user','roles'));
  
    }
  
  
  
    public function usersCustomer(Request $r){

      //Filter Actions Start
      if($r->action){
        if($r->checkid){
  
        $datas=User::latest()->whereIn('status',[0,1])->whereIn('id',$r->checkid)->get();
  
        foreach($datas as $data){
  
            if($r->action==1){
              $data->status=1;
              $data->save();
            }elseif($r->action==2){
              $data->status=0;
              $data->save();
            }elseif($r->action==3){
              $data->fetured=true;
              $data->save();
            }elseif($r->action==4){
              $data->fetured=false;
              $data->save();
            }elseif($r->action==5){
              
              $userFiles =Media::latest()->where('src_type',6)->where('src_id',$data->id)->get();
              foreach ($userFiles as $media) {
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
  
      $users =User::latest()->whereIn('status',[0,1])
      ->where(function($q) use($r) {
  
          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
              $q->orWhere('email','LIKE','%'.$r->search.'%');
              $q->orWhere('mobile','LIKE','%'.$r->search.'%');
          }
          
          if($r->startDate || $r->endDate)
          {
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
  
      })
      ->select(['id','permission_id','name','email','mobile','created_at','addedby_id','status'])
        ->paginate(25)->appends([
          'search'=>$r->search,
          'startDate'=>$r->startDate,
          'endDate'=>$r->endDate,
        ]);

      //Total Count Results
      $totals = DB::table('users')->whereIn('status',[0,1])
      ->selectRaw('count(*) as total')
      ->selectRaw("count(case when status = 1 then 1 end) as active")
      ->selectRaw("count(case when status = 0 then 1 end) as inactive")
      ->first();
  
      return view(adminTheme().'users.customers.users',compact('users','totals'));
    }
  
  
    public function usersCustomerAction(Request $r,$action,$id=null){
       
      //Add New User Start
      if($action=='create' && $r->isMethod('post')){

        $user =User::where('email',$r->email)->orWhere('name',$r->name)->first();
        if(!$user){
          $password=Str::random(8);
          $user =new User();
          $user->name =$r->name;
          $user->email =$r->email;
          $user->password_show=$password;
          $user->password=Hash::make($password);
          $user->save();
        }
        
        return redirect()->route('admin.usersCustomerAction',['edit',$user->id]);
      }
      //Add New User End
      
      
      $user=User::whereIn('status',[0,1])->find($id);
      if(!$user){
        Session()->flash('error','This User Are Not Found');
        return redirect()->route('admin.usersCustomer');
      }
  
      //Update User Profile Start
      if($action=='update' && $r->isMethod('post')){

          $check = $r->validate([
                'name' => 'required|max:100|unique:users,name,'.$user->id,
                'email' => 'required|max:100|unique:users,email,'.$user->id,
                'mobile' => 'nullable|max:20|unique:users,mobile,'.$user->id,
                'gender' => 'nullable|max:10',
                'address' => 'nullable|max:191',
                'division' => 'nullable|numeric',
                'district' => 'nullable|numeric',
                'city' => 'nullable|numeric',
                'created_at' => 'nullable|date|max:50',
                'postal_code' => 'nullable|max:20',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
            ]);
    
          $user->name =$r->name;
          $user->mobile =$r->mobile;
          $user->email =$r->email;
          $user->gender =$r->gender;
          $user->address_line1 =$r->address;
          $user->division =$r->division;
          $user->district =$r->district;
          $user->city =$r->city;
          $user->postal_code =$r->postal_code;
          $user->created_at =$r->created_at?:Carbon::now();
    
          ///////Image UploadStart////////////
          if($r->hasFile('image')){
    
            $file =$r->image;
            $src  =$user->id;
            $srcType  =6;
            $fileUse  =1;
            $author =Auth::id();
            uploadFile($file,$src,$srcType,$fileUse,$author);
          }
          ///////Image Upload End////////////
    
          $user->status=$r->status?true:false;
          $user->save();
    
          Session()->flash('success','Your Updated Are Successfully Done!');
          return redirect()->back();
  
        }
        //Update User Profile End
  
        //Update User Password Change Start
        if($action=='change-password' && $r->isMethod('post')){
     
          $validator = Validator::make($r->all(), [
              'old_password' => 'required|string|min:8',
              'password' => 'required|string|min:8|confirmed|different:old_password',
          ]);
         
          if($validator->fails()){
              return redirect()->back()->withErrors($validator)->withInput();
          }
  
          if(Hash::check($r->old_password, $user->password)){
            $user->password_show=$r->password;
            $user->password=Hash::make($r->password);
            $user->update();
           
            Session()->flash('success','Your Are Successfully Done');
            return redirect()->back();
          }else{
          Session()->flash('error','Current Password Are Not Match');
          return redirect()->back();
          }
  
        }
        //Update User Password Change End
  
        //Delete User Start
        if($action=='delete'){
  
          $userFiles =Media::latest()->where('src_type',6)->where('src_id',$user->id)->get();
          foreach ($userFiles as $media) {
              if(File::exists($media->file_url)){
                    File::delete($media->file_url);
                }
              $media->delete();
          }
          $user->delete();
          Session()->flash('success','User Are Deleted Successfully Deleted!');
          return redirect()->back();
        }
        //Delete User End
  
      return view(adminTheme().'users.customers.editUser',compact('user'));
  
    }

    public function subscribes(Request $r){
  
    // Filter Action Start
      if($r->action){
        if($r->checkid){

        PostExtra::latest()->where('type',1)->whereIn('id',$r->checkid)->delete();

        Session()->flash('success','Action Successfully Completed!');

        }else{
          Session()->flash('info','Please Need To Select Minimum One Post');
        }
        
        return redirect()->back();
      }

      //Filter Action End

    $subscribes =PostExtra::where('type',1)
    ->where(function($q) use ($r){

      if($r->search){
            $q->where('name','LIKE','%'.$r->search.'%');
      }
        
      if($r->startDate || $r->endDate)
        {
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

    })
    ->select(['id','name','created_at'])
    ->paginate(50)->appends([
      'search'=>$r->search,
      'startDate'=>$r->startDate,
      'endDate'=>$r->endDate,
    ]);

    return view(adminTheme().'users.subscribes.subscribeAll',compact('subscribes'));
  }


public function userRoles(Request $r){
  $allPer = empty(json_decode(Auth::user()->permission->permission, true)['services']['all']);

  $roles =Permission::latest()
  ->where('status','active')
  ->where(function($q) use($r,$allPer) {

      if($r->search){
          $q->where('name','LIKE','%'.$r->search.'%');
      }
      
      if($r->startDate || $r->endDate)
      {
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

     // Check Permission
      if($allPer){
       $q->where('addedby_id',auth::id()); 
      }

  })
  ->select(['id','name','created_at','addedby_id','status'])
    ->paginate(25)->appends([
      'search'=>$r->search,
      'startDate'=>$r->startDate,
      'endDate'=>$r->endDate,
    ]);

  return view(adminTheme().'users.roles.userRoles',compact('roles'));
}


  public function userRoleAction(Request $r,$action,$id=null){

    if($action=='create'){
        $role  =Permission::where('addedby_id',Auth::id())->where('status','temp')->first();
        if(!$role){
          $role = new Permission();
          $role->status='temp';
          $role->addedby_id=Auth::id();
        }
        $role->created_at=Carbon::now();
        $role->save();

        return redirect()->route('admin.userRoleAction',['edit',$role->id]);
    }

    $role=Permission::find($id);
    if(!$role){
      Session()->flash('error','This Role Are Not Found');
      return redirect()->route('admin.userRoles');
    }
    
    if($action=='update'){

      //Role Update
      $check = $r->validate([
          'name' => 'required|max:100',
      ]);

      $role->name =$r->name;
      if($role->id==1){
      $role->permission =$r->permission;
      }else{
        $role->permission =$r->permission;  
      }
      $role->status ='active';
      $role->save();

      Session()->flash('success','Role Updated Are Successfully Done!');
      return redirect()->back();
    }
    
    if($action=='delete'){
      //Role Delete
      $role->delete();

      Session()->flash('success','Role Deleted Are Successfully Done!');
      return redirect()->route('admin.userRoles');

    }

    return view(adminTheme().'users.roles.userRoleEdit',compact('role'));

  }
    
  // User Management Function End


  // Setting Function Start
  public function setting(Request $r,$type){

    $general =General::first();
    if($type=='general'){
      return view(adminTheme().'setting.general',compact('general','type'));
    }else if($type=='mail'){
      return view(adminTheme().'setting.mail',compact('general','type'));
    }else if($type=='sms'){
      return view(adminTheme().'setting.sms',compact('general','type'));
    }else if($type=='social'){
      return view(adminTheme().'setting.social',compact('general','type'));
    }else if($type=='document'){
      return view(adminTheme().'setting.document',compact('general','type'));
    }else if($type=='logo'){

      if(File::exists($general->logo)){
            File::delete($general->logo);
      }
      $general->logo=null;
      $general->save();

      Session()->flash('success','Logo Deleted Are Successfully Done!');
      return redirect()->back();
    }else if($type=='favicon'){
       if(File::exists($general->favicon)){
            File::delete($general->favicon);
      }
      $general->favicon=null;
      $general->save();

      Session()->flash('success','Logo Deleted Are Successfully Done!');
      return redirect()->back();
    }else if($type=='banner'){
       if(File::exists($general->banner)){
            File::delete($general->banner);
      }
      $general->banner=null;
      $general->save();

      Session()->flash('success','Banner Deleted Are Successfully Done!');
      return redirect()->back();
    }else if($type=='cache-clear'){

      //return view(adminTheme().'setting.cacheDatabase',compact('general','type'));
      
      Artisan::call('cache:clear');
      Artisan::call('config:clear');
      Artisan::call('config:cache');
      Artisan::call('view:clear');
      Artisan::call('route:clear');
      Artisan::call('clear-compiled');

      Session()->flash('success','Cache Clear Are Successfully Done!');

      return redirect(url('/ecom9/admin/dashboard'));

    }else{
      return redirect()->route('admin.setting','general');
    }

  }


  public function settingUpdate(Request $r,$type){


    $general =General::first();

    if($type=='general'){

        $check = $r->validate([
            'title' => 'nullable|max:100',
            'subtitle' => 'nullable|max:200',
            'mobile' => 'nullable|max:100',
            'email' => 'nullable|max:100',
            'website' => 'nullable|max:100',
            'meta_author' => 'nullable|max:100',
            'meta_title' => 'nullable|max:200',
            'meta_description' => 'nullable|max:200',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:2048',
        ]);

        $general->title=$r->title;
        $general->subtitle=$r->subtitle;
        $general->mobile=$r->mobile;
        $general->email=$r->email;
        $general->address_one=$r->address_one;
        $general->address_two=$r->address_two;
        $general->website=$r->website;
        $general->meta_author=$r->meta_author;
        $general->meta_title=$r->meta_title;
        $general->meta_keyword=$r->meta_keyword;
        $general->meta_description=$r->meta_description;
        $general->script_head=$r->script_head;
        $general->script_body=$r->script_body;
        $general->copyright_text=$r->footer_text;
        

        ///////Image UploadStart////////////

        if($r->hasFile('logo')){

          $file=$r->logo;

          if(File::exists($general->logo)){
                File::delete($general->logo);
          }

          $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
          $fullName = basename($file->getClientOriginalName());
          $ext =$file->getClientOriginalExtension();
          $size =$file->getSize();

          $year =carbon::now()->format('Y');
          $month =carbon::now()->format('M');
          $folder = $month.'_'.$year;

          $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
          $path ="medies/".$folder;
          $fullPath ="public/medies/".$folder.'/'.$img;

          $file->move(public_path($path), $img);
          $general->logo =$fullPath;

      }

         ///////Image UploadStart////////////

        if($r->hasFile('favicon')){

            $file=$r->favicon;

            if(File::exists($general->favicon)){
                  File::delete($general->favicon);
            }

            $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
            $fullName = basename($file->getClientOriginalName());
            $ext =$file->getClientOriginalExtension();
            $size =$file->getSize();

            $year =carbon::now()->format('Y');
            $month =carbon::now()->format('M');
            $folder = $month.'_'.$year;

            $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
            $path ="medies/".$folder;
            $fullPath ="public/medies/".$folder.'/'.$img;
            
            $file->move(public_path($path), $img);
            $general->favicon =$fullPath;

        }
        
        if($r->hasFile('banner')){

            $file=$r->banner;

            if(File::exists($general->banner)){
                  File::delete($general->banner);
            }

            $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
            $fullName = basename($file->getClientOriginalName());
            $ext =$file->getClientOriginalExtension();
            $size =$file->getSize();

            $year =carbon::now()->format('Y');
            $month =carbon::now()->format('M');
            $folder = $month.'_'.$year;

            $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
            $path ="medies/".$folder;
            $fullPath ="public/medies/".$folder.'/'.$img;
            
            $file->move(public_path($path), $img);
            $general->banner =$fullPath;

        }
        $general->save();

        Session()->flash('success','General Updated Are Successfully Done!');

    }


    if($type=='mail'){

      $check = $r->validate([
            'mail_from_address' => 'nullable|max:1',
            'mail_from_name' => 'nullable|max:100',
            'mail_driver' => 'nullable|max:100',
            'mail_host' => 'nullable|max:100',
            'mail_port' => 'nullable|max:100',
            'mail_encryption' => 'nullable|max:100',
            'mail_username' => 'nullable|max:100',
            'mail_password' => 'nullable|max:100',
        ]);

      $general->mail_from_address=$r->mail_from_address;
      $general->mail_from_name=$r->mail_from_name;
      $general->mail_driver=$r->mail_driver;
      $general->mail_host=$r->mail_host;
      $general->mail_port=$r->mail_port;
      $general->mail_encryption=$r->mail_encryption;
      $general->mail_username=$r->mail_username;
      $general->mail_password=$r->mail_password;
      $general->mail_status=$r->mail_status?true:false;
      $general->save();

      Session()->flash('success','Mail Updated Are Successfully Done!');

    }
    
    if($type=='send-testing-mail'){

        $check = $r->validate([
            'mail_address' => 'required|max:200',
        ]);
        
        if(general()->mail_status && $r->mail_address){
            //Mail Data
            
            $data =array(
                    'title'=>'Test mail for testing',
                    'name'=>'Demo Name',
                    'phone'=>'+8801745454545',
                    'email'=>'demoemail@gmail.com',
                    'subject'=>'Testing Contact Mail for Admin',
                    'comment'=>'We are testing for email testing form admin access . i hope all are successfully working.',
                );
            $datas =array('r'=>$data);
            $template ='mails.ContactMail';
            
            $toEmail =$r->mail_address?:general()->mail_address;
            $toName =general()->mail_from_name;
            $subject =$r->mail_type.' Mail Testing Form '.general()->title;
            
            $attachments=null;
            if(1==2){
                $attachments[]=array(
                        'path'=>asset($r->bannerFile->file_url),
                        'name'=>$r->bannerFile->file_name,
                        'mime'=>$r->bannerFile->mime_type
                    );
            }
            
            $isSend =sendMail($toEmail,$toName,$subject,$datas,$template);

            if($isSend){
                Session()->flash('success','Mail Send Are Successfully Done!.Please chack Email address');
            }else{
                Session()->flash('error','Mail Send Are Fail!.Please chack Email Setup Configration.');
            }
            
        }else{
                Session()->flash('error','Please Active your Mail status.');
        }
        
        
        return redirect()->route('admin.setting','mail');
        
    }

    if($type=='sms'){
        
        $check = $r->validate([
            'sms_type' => 'nullable|max:50',
            'sms_senderid' => 'nullable|max:50',
            'sms_url_nonmasking' => 'nullable|max:200',
            'sms_url_masking' => 'nullable|max:200',
            'sms_username' => 'nullable|max:50',
            'sms_password' => 'nullable|max:50',
        ]);
        
      $general->sms_type=$r->sms_type;
      $general->sms_senderid=$r->sms_senderid;
      $general->sms_url_nonmasking=$r->sms_url_nonmasking;
      $general->sms_url_masking=$r->sms_url_masking;
      $general->sms_username=$r->sms_username;
      $general->sms_password=$r->sms_password;
      $general->sms_status=$r->sms_status?true:false;
      $general->save();

      Session()->flash('success','SMS Updated Are Successfully Done!');

    }

    if($type=='social'){
      

        $check = $r->validate([
            'facebook_link' => 'nullable|max:200',
            'twitter_link' => 'nullable|max:200',
            'instagram_link' => 'nullable|max:200',
            'linkedin_link' => 'nullable|max:200',
            'pinterest_link' => 'nullable|max:200',
            'youtube_link' => 'nullable|max:200',
            'whatsapp_link' => 'nullable|max:100',
            'messanger_link' => 'nullable|max:100',
            'fb_app_id' => 'nullable|max:100',
            'fb_app_secret' => 'nullable|max:100',
            'fb_app_redirect_url' => 'nullable|max:200',
            'google_client_id' => 'nullable|max:100',
            'google_client_secret' => 'nullable|max:100',
            'google_client_redirect_url' => 'nullable|max:200',
            'tw_app_id' => 'nullable|max:100',
            'tw_app_secret' => 'nullable|max:100',
            'tw_app_redirect_url' => 'nullable|max:200',
        ]);

        $general->facebook_link=$r->facebook_link;
        $general->twitter_link=$r->twitter_link;
        $general->instagram_link=$r->instagram_link;
        $general->linkedin_link=$r->linkedin_link;
        $general->pinterest_link=$r->pinterest_link;
        $general->youtube_link=$r->youtube_link;
        $general->whatsapp_link=$r->whatsapp_link;
        $general->messanger_link=$r->messanger_link;
        $general->fb_app_id=$r->fb_app_id;
        $general->fb_app_secret=$r->fb_app_secret;
        $general->fb_app_redirect_url=$r->fb_app_redirect_url;
        $general->google_client_id=$r->google_client_id;
        $general->google_client_secret=$r->google_client_secret;
        $general->google_client_redirect_url=$r->google_client_redirect_url;
        $general->tw_app_id=$r->tw_app_id;
        $general->tw_app_secret=$r->tw_app_secret;
        $general->tw_app_redirect_url=$r->tw_app_redirect_url;
        $general->save();

        Session()->flash('success','Advance Updated Are Successfully Done!');

    }

    
    return redirect()->route('admin.setting',$type);


  }

  // Setting Function End
  
  public function contentEditor(Request $r,$action,$id){
    $primary =Post::find($id);
    if(!$primary){
      return abort(404);
    }
    $datas =PostContentElement::where('parent_id',null)->where('src_id',$primary->id)->orderBy('drag')->get();

    return view(adminTheme().'setting.contentEditor',compact('primary','action','datas'));
  }

  public function contentEditorAction(Request $r,$action,$id=null){

    if($action=='addSection'){
    $data =new PostContentElement();
    $data->src_id=$id;
    $data->type=0;
    $data->status='active';
    $data->save();
    }
    
    if($action=='copySection'){
        $parentData =PostContentElement::find($id);
        if($parentData){
            $newParent =new PostContentElement();
            $newParent->src_id=$parentData->src_id;
            $newParent->parent_id=$parentData->parent_id;
            $newParent->drag=$parentData->drag;
            $newParent->item_type=$parentData->item_type;
            $newParent->grid_column=$parentData->grid_column;
            $newParent->class_name=$parentData->class_name;
            $newParent->type=$parentData->type;
            $newParent->status='active';
            $newParent->save();
            
            foreach($parentData->subItems as $i=>$item){
                $data =new PostContentElement();
                $data->parent_id=$newParent->id;
                $data->name=$item->name;
                $data->content=$item->content;
                $data->drag=$item->drag;
                $data->item_type=$item->item_type;
                $data->grid_column=$item->grid_column;
                $data->class_name=$item->class_name;
                $data->type=$item->type;
                $data->status='active';
                $data->save();
            }
            
        }
    }
    
    if($action=='deleteSection'){
        $data =PostContentElement::find($id);
        if($data){
          $data->delete();
        }
    }
    
    if($action=='serialSection'){
        if($r->serialIds){
            for($i=0;$i < count($r->serialIds); $i++){
                $data =PostContentElement::find($r->serialIds[$i]);
                if($data){
                    $data->drag=$i;
                    $data->save();
                }
            }
        }
    }
    
    if($action=='addItem'){
        $parentData =PostContentElement::find($id);
        if($parentData){
            $data =new PostContentElement();
            $data->parent_id=$parentData->id;
            $data->item_type=$r->itemtype;
            $data->type=0;
            $data->status='active';
            $data->save();
        }
    }
    
    if($action=='updateItem'){
    $data =PostContentElement::find($id);
    if($data){
        
        if($r->itemtype=='paragraph'){
            $check = $r->validate([
                'gridColumn' => 'nullable|numeric',
                'paraText' => 'nullable|max:10000',
            ]);
            
            $data->content=$r->paraText;
            $data->class_name=$r->className;
            $data->save();
        }elseif($r->itemtype=='heading'){
            $check = $r->validate([
                'gridColumn' => 'nullable|numeric',
                'headingName' => 'nullable|max:10',
                'headingText' => 'nullable|max:1000',
            ]);
            $data->name=$r->headingName?:'h1';
            $data->content=$r->headingText;
            $data->class_name=$r->className;
            $data->save();
        }elseif($r->itemtype=='image'){
            $check = $r->validate([
                'gridColumn' => 'nullable|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff|max:10240',
            ]);
            
            ///////Image UploadStart////////////
              if($r->hasFile('image')){
        
                $file =$r->image;
                $src  =$data->id;
                $srcType  =8;
                $fileUse  =1;
                $author =Auth::id();
                uploadFile($file,$src,$srcType,$fileUse,$author);
              }
            ///////Image Upload End////////////
        
            $data->class_name=$r->className;
            $data->save();
        }elseif($r->itemtype=='gridColumn'){
            $check = $r->validate([
                'gridColumn' => 'nullable|numeric',
                'className' => 'nullable|max:100',
            ]);
            
            $data->grid_column=$r->gridColumn?:12;
            $data->class_name=$r->className;
            $data->save();
        }elseif($r->itemtype=='serial'){
            $check = $r->validate([
                'serial' => 'nullable|numeric',
                'className' => 'nullable|max:100',
            ]);
            
            $data->drag=$r->serial?:0;
            $data->class_name=$r->className;
            $data->save();
        }
        
    }
    }
    
    if($action=='copyItem'){
        $parentData =PostContentElement::find($id);
        if($parentData){
            $newParent =new PostContentElement();
            $newParent->src_id=$parentData->src_id;
            $newParent->parent_id=$parentData->parent_id;
            $newParent->name=$parentData->name;
            $newParent->content=$parentData->content;
            $newParent->drag=$parentData->drag;
            $newParent->item_type=$parentData->item_type;
            $newParent->grid_column=$parentData->grid_column;
            $newParent->class_name=$parentData->class_name;
            $newParent->type=$parentData->type;
            $newParent->status='active';
            $newParent->save();
        }
        
        foreach($parentData->subItems as $i=>$item){
            $data =new PostContentElement();
            $data->parent_id=$newParent->id;
            $data->name=$item->name;
            $data->content=$item->content;
            $data->drag=$item->drag;
            $data->item_type=$item->item_type;
            $data->grid_column=$item->grid_column;
            $data->class_name=$item->class_name;
            $data->type=$item->type;
            $data->status='active';
            $data->save();
        }
        
        
    }
    if($action=='deleteItem'){
    $data =PostContentElement::find($id);
    if($data){
        $this->deleteItemAndSubItems($data);
    }
    }
    
    if($r->ajax()){
        return Response()->json([
              'success' => true
          ]);
    }

    Session()->flash('success','You Are Successfully Done!');
    return redirect()->back();

  }
  
    public function deleteItemAndSubItems($item) {
        // Delete related media files
        // $media = Media::where('src_type', 3)->where('src_id', $item->id)->get();
        // foreach ($media as $m) {
        //     if (File::exists($m->file_url)) {
        //         File::delete($m->file_url);
        //     }
        //     $m->delete();
        // }
    
        // Delete the item itself
        $item->delete();
    
        // Recursively delete sub-items
        foreach ($item->subItems as $sub) {
            $this->deleteItemAndSubItems($sub);
        }
    }
    


}
