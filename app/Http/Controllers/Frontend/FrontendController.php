<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Validator;
use App\Models\User;
use App\Models\Post;
use App\Models\Media;
use App\Models\Review;
use App\Models\Country;
use App\Models\Attribute;
use App\Models\PostExtra;
use Carbon\Carbon;
use Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{

    public function geo_filter($id){

      $datas=Country::where('parent_id',$id)->get();

      $geoData =View('geofilter',compact('datas'))->render();

       return Response()->json([
              'success' => true,
              'geoData' => $geoData,
            ]);
    }
    
    public function imageView(Request $r){
        if($r->imageUrl && is_numeric($r->weight) && is_numeric($r->height)){
          $image = Image::make($r->imageUrl)->fit($r->weight,$r->height)->response();
        }else{
          $image = Image::make('public/medies/noimage.jpg')->fit(200,200)->response(); 
        }
        return $image;
    }
    
    public function imageView2(Request $r,$template=null,$image=null){
        $weight=null;
        $height=null;
        
        if(is_numeric($r->w)){
          $weight=$r->w;  
        }
        if(is_numeric($r->h)){
          $height=$r->h;  
        }
        
        $filePath='public/medies/noimage.jpg';
        
        if($image){
            $file =Media::where('file_rename',$image)->select(['file_url'])->first();
            if($file){
                $filePath = $file->file_url;
            }
        }
        
        
        // if($template=='s-profile'){
            
        //     if($image && $image!='profile.png' && $file){
        //         $filePath = $file->file_url;
        //     }else{
        //         $filePath ='public/medies/profile.png';
        //     }
        // }
        
        $mImage =Image::make($filePath);
        if($weight && $height){
            $mImage=$mImage->fit($weight,$height);
        }
        $mImage=$mImage->response();
        
        return $mImage;
    }
    public function siteMapXml(Request $r){
        
      $pages = Post::latest()->where('type',0)->where('status','active')->select(['slug','updated_at','status'])->limit(200)->get();
      $posts = Post::latest()->where('type',1)->where('status','active')->select(['slug','updated_at','status'])->limit(500)->get();
      $products = Post::latest()->where('type',2)->where('status','active')->select(['slug','updated_at','status'])->limit(300)->get();
      
      return response()->view('siteMap',compact('pages','posts','products'))->header('Content-Type', 'text/xml');
    }
    public function language($lang=null){
      if($lang){
          Session::put('lang',$lang);
      }else{
          Session::put('lang','en');
      }
      return redirect()->back();
    }

    public function index(Request $r){
        
    //   Artisan::call('cache:clear');
    //   Artisan::call('config:clear');
    //   Artisan::call('config:cache');
    //   Artisan::call('view:clear');
    //   Artisan::call('route:clear');
    //   Artisan::call('clear-compiled');
    // create new image instance

      $latestProducts =Post::where('type',2)
      ->where('status','active')
      ->whereDate('created_at','<=',Carbon::now())
      ->limit(8)
      ->get();

      $latestPosts =Post::where('type',1)
      ->where('status','active')
      ->whereDate('created_at','<=',Carbon::now())
      ->limit(3)
      ->get();
     
    	return view(welcomeTheme().'index',compact('latestProducts','latestPosts'));
    }

    public function productCategory($slug){
      $category =Attribute::latest()->where('type',0)->where('slug',$slug)->first();
      if(!$category){
        return abort('404');
      }

      $products = $category->activeProducts()
      // ->select(['id','name','slug','addedby_id','created_at','short_description'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(12);

      return view(welcomeTheme().'products.categoryProducts',compact('category','products'));
    }

    public function productView($slug){
      $product =Post::latest()->where('type',2)->where('slug',$slug)->first();
      if(!$product){
        return abort('404');
      }

      $relatedProducts =$product->relatedProducts()->limit(10)->get();

      return view(welcomeTheme().'products.productView',compact('product','relatedProducts'));
    }

    public function blogCategory($slug){
      $category =Attribute::latest()->where('type',6)->where('slug',$slug)->first();
      if(!$category){
        return abort('404');
      }

      $posts = $category->activePosts()->latest()
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->paginate(10);

      return view(welcomeTheme().'blogs.categoryPosts',compact('category','posts'));
    }

    public function blogTag($slug){
      $tag =Attribute::latest()->where('type',7)->where('slug',$slug)->first();
      if(!$tag){
        return abort('404');
      }

      $posts = Post::whereHas('tagPosts',function($q) use($tag){
        $q->where('reff_id',$tag->id);
      })
      ->where(function($qq){
        $qq->where('status','active');
      })
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10);

      return view(welcomeTheme().'blogs.tagPosts',compact('tag','posts'));
    }

    public function blogAuthor($id,$slug){
      $author =User::find($id);
      if(!$author){
        return abort('404');
      }
      $posts =$author->posts()->latest()->where('type',1)->where('status','active')
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10);
      return view(welcomeTheme().'blogs.authorPosts',compact('author','posts'));

    }

    public function blogView($slug){
      $post =Post::where('type',1)->where('slug',$slug)->first();
      if(!$post){
        return abort('404');
      }
      $relatedPosts =$post->relatedPosts()->limit(3)->select(['id','name','slug','short_description','addedby_id','created_at'])->get();
      $comments =$post->postComments()->where('status','active')->select(['id','name','content','created_at'])->paginate(10);
      return view(welcomeTheme().'blogs.blogView',compact('post','relatedPosts','comments'));

    }

    public function blogSearch(Request $r){
      $check = $r->validate([
          'blog_search' => 'required|max:100',
      ]);
      
      $posts =Post::latest()->where('type',1)->where('status','active')
      ->where(function($q) use ($r) {
        if($r->blog_search){
          $q->where('name','LIKE','%'.$r->blog_search.'%');
        }

      })
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10)->appends([
        'search'=>$r->search,
      ]);

      return view(welcomeTheme().'blogs.blogSearch',compact('posts','r'));

    }

    public function blogComments(Request $r,$slug){
      $post =Post::where('type',1)->where('slug',$slug)->first();
      if(!$post){
        return abort('404');
      }

      $check = $r->validate([
          'name' => 'required|max:100',
          'email' => 'required|max:100',
          'website' => 'required|max:100',
          'comment' => 'required|max:1000',
      ]);

      $comments =new Review();

      if(Auth::check()){
      $comments->addedby_id=Auth::id();
      }
      $comments->src_id=$post->id;
      $comments->type=1;
      $comments->name=$r->name;
      $comments->email=$r->email;
      $comments->website=$r->website;
      $comments->content=$r->comment;
      $comments->save();

      Session()->flash('success','Your Comments successfully Submitted.');

      return back();


    }


    public function pageView($slug){
    
      $page =Post::latest()->whereIn('type',[0,1])->where('slug',$slug)->first();

      if(!$page){
        return abort('404');
      }
      
      if($page->type==1){
          
          $post =$page;
          
          $relatedPosts =$post->relatedPosts()->limit(3)->select(['id','name','slug','short_description','addedby_id','created_at'])->get();
          $comments =$post->postComments()->where('status','active')->select(['id','name','content','created_at'])->paginate(10);
          
          return view(welcomeTheme().'blogs.blogView',compact('post','relatedPosts','comments'));
      }
      //If deferent Design or Condition Page Return by ID.

      //Font Home Page
      if($page->template=='Front Page'){
        return redirect()->route('index');
      }

      //Contact Us Page
      if($page->template=='Contact Us'){
        return view(welcomeTheme().'pages.contact',compact('page'));
      }

      //About Us Page
      if($page->template=='About Us'){
        return view(welcomeTheme().'pages.about',compact('page'));
      }
      
      //Clients Page
      if($page->template=='All Clients'){
        return view(welcomeTheme().'pages.clients',compact('page'));
      }

      //Latest Blog Page
      if($page->template=='Latest Blog'){
        $posts = Post::latest()->where('type',1)->where('status','active')
        ->select(['id','name','slug','short_description','addedby_id','created_at'])
        ->whereDate('created_at','<=',date('Y-m-d'))
        ->paginate(10);
        return view(welcomeTheme().'blogs.latestBlogs',compact('posts','page'));
      }

      //Latest Services Page
      if($page->template=='Latest Product'){
        $products = Post::latest()->where('type',2)->where('status','active')
        ->select(['id','name','slug','short_description','addedby_id','created_at'])
        ->whereDate('created_at','<=',date('Y-m-d'))
        ->paginate(12);
        return view(welcomeTheme().'products.latestProducts',compact('products','page'));
      }

      return view(welcomeTheme().'pages.pageView',compact('page'));

    }

    public function contactMail(Request $r){
      //return $r;
      
      $check = $r->validate([
          'name' => 'required|max:100',
          'email' => 'required|max:100',
          'subject' => 'required|max:100',
          'message' => 'nullable|max:500',
      ]);

      if(general()->mail_status && general()->mail_from_address){
            //Mail Data
            $datas =array('r'=>$r);
            $template ='mails.ContactMail';
            $toEmail =general()->mail_from_address;
            $toName =general()->mail_from_name;
            $subject ='Contact Mail Form '.general()->title;
        
            sendMail($toEmail,$toName,$subject,$datas,$template);
        }

      Session()->flash('success','Your form send successfully done. We are response as soon as possible.');
      return back();
    }

    public function search(Request $r){

      $products =Post::where('type',2)
            ->where('status','active')
            ->whereDate('created_at','<=',Carbon::now())
            ->where(function($q)use($r){
              if($r->search){
                $q->where('name','like','%'.$r->search.'%');
              }
            })
            ->paginate(12);

      return view(welcomeTheme().'products.productSearch',compact('products'));

    }

    public function subscribe(Request $r){

      if(filter_var($r->email, FILTER_VALIDATE_EMAIL)){
        $subscribe =PostExtra::latest()->where('type',1)->where('name',$r->email)->first();

        if(!$subscribe){
          
            $subscribe =new PostExtra();
            $subscribe->type=1;
            $subscribe->name=$r->email;
            $subscribe->save();
          $status=true;
          $message ='<p style="color: #009688;"><span>Success:</span> You Are Successfully Subsribe.</p>';
        }else{
          $status=true;
          $message ='<p style="color: #ffc107;"><span>Note:</span> You Are Already Subsribe.Thank You.</p>';
        }


      }else{
        $status=false;
        $message ='<p style="color: #ff5722;"><span>Error:</span> Email Are Not validated</p>';
      }

      if(request()->ajax()){
        
        return Response()->json([
                'success' => $status,
                'message' => $message,
              ]);
      }

      Session()->flash($status?'success':'error',$message);
      return redirect()->back();

    }



}