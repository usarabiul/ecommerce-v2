<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Str;
use File;
use DB;
use Session;
use Redirect,Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Review;
use App\Models\Country;
use App\Models\Media;
use App\Models\Attribute;
use App\Models\Permission;
use App\Models\PostAttribute;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    //Post Function
    public function posts(Request $r){

      $allPer = empty(json_decode(Auth::user()->permission->permission, true)['posts']['all']);

      // Filter Action Start
      if($r->action){
        if ($r->filled('checkid')) {
          $datas =Post::where('type', 1)->whereIn('id', $r->checkid);
          if (in_array($r->action, [1, 2, 3, 4])) {
            if ($r->action == 1) $datas->update(['status' => 'active']);
            if ($r->action == 2) $datas->update(['status' => 'inactive']);
            if ($r->action == 3) $datas->update(['featured' => true]);
            if ($r->action == 4) $datas->update(['featured' => false]);
          }elseif($r->action==5){
            foreach($datas as $data){
                $data->medias->each(function ($media) {
                    if (File::exists($media->file_url)) File::delete($media->file_url);
                    $media->delete();
                });
                $data->postCtgs()->delete();
                $data->postTags()->delete();
                $data->postComments()->delete();
                $data->delete();
            }
          }
          session()->flash('success', 'Action Successfully Completed!');
        } else {
            session()->flash('info', 'Please select at least one post');
        }
      }
      //Filter Action End


      $posts=Post::latest()->where('type',1)->where('status','<>','temp')
        ->where(function($q) use ($r,$allPer) {

          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
              $q->orWhereHas('postCategories',function($qq) use($r){
                  $qq->where('name','LIKE','%'.$r->search.'%');
              });
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
            if($r->status=='featued'){
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
      ->select(['id','name','slug','created_at','addedby_id','status','featured'])
      ->paginate(25)->appends([
        'search'=>$r->search,
        'status'=>$r->status,
        'startDate'=>$r->startDate,
        'endDate'=>$r->endDate,
      ]);
      
      //Total Count Results
      $total= Post::where('status','<>','temp')
      ->where('type',1)
      ->selectRaw('count(*) as total')
      ->selectRaw("count(case when status = 'active' then 1 end) as active")
      ->selectRaw("count(case when status = 'inactive' then 1 end) as inactive")
      ->selectRaw("count(case when featured = true then 1 end) as featured")
      ->first();


      return view(adminTheme().'posts.postsAll',compact('posts','total'));
    }

    public function postsAction(Request $r,$action,$id=null){
      
      //Add Post  Start
      if($action=='create'){
        $post =Post::where('type',1)->where('status','temp')->where('addedby_id',Auth::id())->first();
        if(!$post){
          $post =new Post();
          $post->type =1;
          $post->status ='temp';
          $post->addedby_id =Auth::id();
        }
        $post->created_at=Carbon::now();
        $post->save();
        return redirect()->route('admin.postsAction',['edit',$post->id]);
      }
      //Add Post  End
      
      $post =Post::where('type',1)->find($id);
      if(!$post){
        Session()->flash('error','This Post Are Not Found');
        return redirect()->route('admin.posts');
      }

      //Check Authorized User
        $allPer = empty(json_decode(Auth::user()->permission->permission, true)['posts']['all']);
        if($allPer && $post->addedby_id!=Auth::id()){
          Session()->flash('error','You are unauthorized Try!!');
          return redirect()->route('admin.posts');
        }

      //Update Post  Start
      if($action=='update'){

        $check = $r->validate([
            'name' => 'required|max:200',
            'seo_title' => 'nullable|max:300',
            'seo_description' => 'nullable|max:500',
            'catagoryid.*' => 'nullable|numeric',
            'tagskey' => 'nullable|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $preTagArray =array_map('trim', explode(',', $post->tags));

        $post->name=$r->name;
        $post->short_description=$r->short_description;
        $post->description=$r->description;
        $post->tags=$r->tagskey;
        $post->seo_title=$r->seo_title;
        $post->seo_description=$r->seo_description;
        $post->seo_keyword=$r->seo_keyword;

        ///////Image Uploard Start////////////
        if($r->hasFile('image')){
   
          $file =$r->image;
          $src  =$post->id;
          $srcType  =1;
          $fileUse  =1;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Image Uploard End////////////

        ///////Banner Uploard Start////////////
        if($r->hasFile('banner')){
          $file =$r->banner;
          $src  =$post->id;
          $srcType  =1;
          $fileUse  =2;
          $author=Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Banner Uploard End////////////
        $post->auto_slug = $r->slug ? true : false;
        $slug = Str::slug($r->slug ?: $r->name);
        if (!$slug) {
            $post->slug = $post->id;
        } else {
            $exists = Post::where('type', 1)->where('slug', $slug)->where('id', '!=', $post->id)->exists();
            $post->slug = $exists ? $slug . '-' . $post->id : $slug;
        }
        $createDate = $r->created_at ? Carbon::parse($r->created_at . ' ' . Carbon::now()->format('H:i:s')) : Carbon::now();
        if (!$createDate->isSameDay($post->created_at)) {
          $post->created_at = $createDate;
        }

        $post->status =$r->status?'active':'inactive';
        $post->featured =$r->featured?1:0;
        $post->editedby_id =Auth::id();
        $post->save();

        

          //Category posts
          if ($r->categoryid) {
              $post->postCtgs()->whereNotIn('reff_id', $r->categoryid)->delete();
              foreach ($r->categoryid as $index => $categoryId){
                $ctg = $post->postCtgs()->where('reff_id', $categoryId)->first();
                if (!$ctg) {
                    $ctg = new PostAttribute();
                    $ctg->src_id = $post->id;
                    $ctg->reff_id = $categoryId;
                    $ctg->type = 1;
                }
                $ctg->drag = $index;
                $ctg->save();
              }
          } else {
              $post->postCtgs()->delete();
          }


          // Post tag Entry
          $tagsArray = array_unique(array_map('trim', explode(',', $post->tags)));
          $diffArray = array_diff($preTagArray, $tagsArray);
          foreach ($diffArray as $tag) {
              $tagPostCount = Post::where('type',1)->where('tags', 'LIKE', '%' . $tag . '%')
                                  ->count();
              if ($tagPostCount == 0) {
                  Attribute::where('type', 7)->where('name', $tag)->delete();
              }
          }

          if($post->tags){
            //Recheck new entry
            foreach($tagsArray as $tag){
              $tagPost =Attribute::where('type',7)->where('name',$tag)->first();
              if(!$tagPost){
                $tagPost =new Attribute();
                $tagPost->type =7;
                $tagPost->name =$tag;
                $tagPost->slug =Str::slug($tag);
                $tagPost->status ='active';
                $tagPost->addedby_id =Auth::id();
                $tagPost->save();
              }
            }
          }
          
          Session()->flash('success','Your Are Successfully Updated');
          return redirect()->back();

      }
      //Update Post  End

      //Delete Post  Start
      if($action=='delete'){
          $medias =Media::latest()->where('src_type',1)->where('src_id',$post->id)->get();
            foreach($medias as $media){
              if(File::exists($media->file_url)){
                File::delete($media->file_url);
              }
              $media->delete();
            }

          $post->postCtgs()->delete();
          $post->postTags()->delete();
          $post->postComments()->delete();
          $post->delete();

          Session()->flash('success','Your Are Successfully Deleted!');
          return redirect()->route('admin.posts');
      }
      //Delete Post  End

      $categories =Attribute::where('type',6)->where('status','<>','temp')->where('parent_id',null)->get();
      $tags =Attribute::where('type',7)->where('status','<>','temp')->where('parent_id',null)->get();
      
      return view(adminTheme().'posts.postEdit',compact('post','categories','tags'));
    }


    //Post Function End


    //Post Comments Function Start

    public function postsCommentsAll(Request $r){

      // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Review::latest()->where('type',1)->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

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
              if($data->post){
                $post =$data->post;
                $post->comments-=1;
                $post->save();
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

      $comments =Review::latest()->where('type',1)->where('status','<>','temp')
      ->where(function($q) use ($r) {
            if($r->search){
                $q->where('name','LIKE','%'.$r->search.'%');
                $q->orWhere('email','LIKE','%'.$r->search.'%');
                $q->orWhere('website','LIKE','%'.$r->search.'%');
            }
      })
      ->select(['id','src_id','parent_id','name','email','title','website','content','type','status','addedby_id','created_at'])
      ->paginate(25)->appends([
        'search'=>$r->search,
      ]);
      return view(adminTheme().'posts.comments.postsCommentsAll',compact('comments'));
    }

    public function postsComments(Request $r,$id){
      
      $post =Post::where('type',1)->find($id);
      if(!$post){
        Session()->flash('error','This Post Are Not Found');
        return redirect()->route('admin.posts');
      }

      //Add Comment Post  Start
      if($r->actionType=='addComment'){
        $comment =Review::where('type',1)->where('addedby_id',Auth::id())->where('src_id',$post->id)->where('status','temp')->first();
        if(!$comment){
        $comment =new Review();
        $comment->type =1;
        $comment->src_id =$post->id;
        $comment->addedby_id =Auth::id();
        $comment->save();
        }else{
        $comment->created_at =Carbon::now();
        $comment->save();
        }
        return redirect()->route('admin.postsCommentsAction',['edit',$comment->id]);
      }
      //Add Comment Post  End

      // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Review::latest()->where('type',1)->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

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
              if($data->post){
                $post =$data->post;
                $post->comments-=1;
                $post->save();
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
      
      $comments =Review::latest()->where('type',1)->where('src_id',$post->id)->where('status','<>','temp')
      ->where(function($q) use ($r) {

            if($r->search){
                $q->where('name','LIKE','%'.$r->search.'%');
                $q->orWhere('email','LIKE','%'.$r->search.'%');
                $q->orWhere('website','LIKE','%'.$r->search.'%');
            }

      })
      ->select(['id','src_id','parent_id','name','email','title','website','content','type','status','addedby_id','created_at'])
      ->paginate(25)->appends([
        'search'=>$r->search,
      ]);;
      
      return view(adminTheme().'posts.comments.postsComments',compact('comments','post'));
    }

    public function postsCommentsAction(Request $r,$action,$id){

      $comment =Review::where('type',1)->find($id);
      if(!$comment){
        Session()->flash('error','This Post Comment Are Not Found');
        return redirect()->route('admin.postsCommentsAll');
      }

      if($action=='replay'){

        if($r->isMethod('post')){

          $check = $r->validate([
            'name' => 'required|max:191',
            'email' => 'nullable|max:100',
            'website' => 'nullable|max:200',
          ]);
  
          $replay =new Review();
          $replay->src_id=$comment->src_id;
          $replay->parent_id=$comment->id;
          $replay->name=$r->name;
          $replay->email=$r->email;
          $replay->website=$r->website;
          $replay->content=$r->content;
          $replay->status =$r->status?'active':'inactive';
          $replay->fetured =$r->fetured?true:false;
          $replay->addedby_id=Auth::id();
          $replay->type=1;
          $replay->save();
    
          Session()->flash('success','Your Are Successfully Done');
          return redirect()->route('admin.postsCommentsAction',['replay',$comment->id]);
        }
        return view(adminTheme().'posts.comments.replay',compact('comment'));
      }

      //Status Comment Post  Start
      if($action=='status'){
          if($comment->status=='active'){
          $comment->status='inactive';
          }else{
          $comment->status='active';
          }
          $comment->save();
          Session()->flash('success','Your Are Successfully Done');
          return redirect()->back();
      }
      //Status Comment Post  End

      //Update Comment Post  Start
      if($action=='update'){

        $check = $r->validate([
          'name' => 'required|max:191',
          'email' => 'nullable|max:100',
          'website' => 'nullable|max:200',
        ]);      

        if($comment->status=='temp' && $comment->post){
          $post =$comment->post;
          $post->review+=1;
          $post->save();
        }

        $comment->name=$r->name;
        $comment->email=$r->email;
        $comment->website=$r->website;
        $comment->content=$r->content;
        $comment->status =$r->status?'active':'inactive';
        $comment->fetured =$r->fetured?true:false;
        $comment->editedby_id=Auth::id();
        $comment->save();

        Session()->flash('success','Your Are Successfully Updated');
        return redirect()->back();
      }
      //Update Comment Post  End

      //Status Comment Post  Start
      if($action=='delete'){
        if($comment->post){
            $post =$comment->post;
            if($post->review>0){
              $post->review-=1;
              $post->save();
            }
        }
        //$comment->replays()->delete();
        $comment->delete();
        Session()->flash('success','Your Are Successfully Deleted!');
        return redirect()->route('admin.postsCommentsAll');
      }

      return view(adminTheme().'posts.comments.CommentEdit',compact('comment'));
    } 

    //Post Comments Function End

    //Post Category Function
    public function postsCategories(Request $r){

    // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Attribute::where('type',6)->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

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

              //Post Category sub Category replace
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

    $categories =Attribute::latest()->where('type',6)->where('status','<>','temp')
    ->where(function($q) use ($r) {

        if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
        }

    })
    ->select(['id','name','slug','parent_id','status','addedby_id','created_at'])
    ->paginate(25)->appends([
        'search'=>$r->search,
      ]);
      
    return view(adminTheme().'posts.category.postsCategories',compact('categories'));

    }

    public function postsCategoriesAction(Request $r,$action,$id=null){
      
      //Add Category  Start
      if($action=='create'){
        $category =Attribute::latest()->where('type',6)->where('addedby_id',Auth::id())->where('status','temp')->first();
        if(!$category){
        $category =new Attribute();
        $category->type =6;
        $category->status ='temp';
        $category->addedby_id =Auth::id();
        $category->save();
        }else{
        $category->created_at =Carbon::now();
        $category->save();
        }
        return redirect()->route('admin.postsCategoriesAction',['edit',$category->id]);
      }
      //Add Category  End
      
      
      
      $category =Attribute::where('type',6)->find($id);
      if(!$category){
        Session()->flash('error','This Category Are Not Found');
        return redirect()->route('admin.postsCategories');
      }

      //Update Category Start
      if($action=='update'){

        $check = $r->validate([
            'name' => 'required|max:191',
            'seo_title' => 'nullable|max:200',
            'seo_desc' => 'nullable|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

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
          $author  =Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Image Uploard End////////////

        ///////Banner Uploard Start////////////
        if($r->hasFile('banner')){
   
          $file =$r->image;
          $src  =$category->id;
          $srcType  =3;
          $fileUse  =2;
          $author  =Auth::id();
          uploadFile($file,$src,$srcType,$fileUse,$author);
        }
        ///////Banner Uploard End////////////
        $slug = Str::slug($r->name);
        if (!$slug) {
            $category->slug = $category->id;
        } else {
            $exists = Attribute::where('type', 6)->where('slug', $slug)->where('id', '!=', $category->id)->exists();
            $category->slug = $exists ? $slug . '-' . $category->id : $slug;
        }
        $createDate = $r->created_at ? Carbon::parse($r->created_at . ' ' . Carbon::now()->format('H:i:s')) : Carbon::now();
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
      //Update Category End

      //Delete Category Start
      if($action=='delete'){
        
          //Category Media File Delete
          $medias =Media::latest()->where('src_type',3)->where('src_id',$category->id)->get();
          foreach($medias as $media){
            if(File::exists($media->file_url)){
              File::delete($media->file_url);
            }
            $media->delete();
          }

          //Post Category sub Category replace
          foreach($category->subctgs as $subctg){
            $subctg->parent_id=$category->parent_id;
            $subctg->save();
          }
          
          $category->delete();

        Session()->flash('success','Your Are Successfully Done');
        return redirect()->route('admin.postsCategories');
      }
      //Delete Category End

      $parents =Attribute::where('type',6)->where('status','<>','temp')->where('parent_id',null)->get();

      return view(adminTheme().'posts.category.postCategoryEdit',compact('category','parents'));
    }

    //Post Category Function End

    //Post Tags Function
    public function postsTags(Request $r){

      // Filter Action Start

      if($r->action){
        if($r->checkid){

        $datas=Attribute::where('type',7)->whereIn('id',$r->checkid)->get();

        foreach($datas as $data){

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

    $tags =Attribute::latest()->where('type',7)->where('status','<>','temp')
    ->where(function($q) use ($r) {

          if($r->search){
              $q->where('name','LIKE','%'.$r->search.'%');
          }

    })
    ->select(['id','name','parent_id','status','addedby_id','created_at'])
    ->paginate(25)->appends([
        'search'=>$r->search,
      ]);
      
      return view(adminTheme().'posts.tags.postsTags',compact('tags'));

    }

    public function postsTagsAction(Request $r,$action,$id=null){
      
      //Add Tag Start
      if($action=='create'){
        $tag =Attribute::latest()->where('type',7)->where('addedby_id',Auth::id())->where('status','temp')->first();
        if(!$tag){
        $tag =new Attribute();
        $tag->type =7;
        $tag->status ='temp';
        $tag->addedby_id =Auth::id();
        $tag->save();
        }else{
        $tag->created_at =Carbon::now();
        $tag->save();
        }
        return redirect()->route('admin.postsTagsAction',['edit',$tag->id]);
      }
      //Add Tag End
      
      $tag =Attribute::where('type',7)->find($id);
      if(!$tag){
        Session()->flash('error','This Tag Are Not Found');
        return redirect()->route('admin.postsTags');
      }

      //update Tag Start
      if($action=='update'){
          $check = $r->validate([
              'name' => 'required|max:191',
          ]);

          $checkTag =Attribute::where('type',7)->whereNotIn('id',[$tag->id])->where('name',$r->name)->first();

          if($checkTag){
            Session::flash('error','Tag Name Can not use Dublicate!');
              return back();
          }

          $tag->name=$r->name;
          $tag->description=$r->description;
          $slug =Str::slug($r->name);
        if($slug==null){
          $tag->slug=$tag->id;
        }else{
          if(Attribute::where('type',7)->where('slug',$slug)->whereNotIn('id',[$tag->id])->count() >0){
          $tag->slug=$slug.'-'.$tag->id;
          }else{
          $tag->slug=$slug;
          }
        }
        $tag->status =$r->status?'active':'inactive';
        $tag->fetured =$r->fetured?1:0;
        $tag->editedby_id =Auth::id();
        $tag->save();
        Session()->flash('success','Your Are Successfully Done');
        return redirect()->back();
      }
      //update Tag End

      //update Tag Start
      if($action=='delete'){
        $tag->delete();
        Session()->flash('success','Your Are Successfully Deleted!');
        return redirect()->route('admin.postsTags');
      }
      //update Tag End
      
      return view(adminTheme().'posts.tags.postsTagsEdit',compact('tag'));
    }

    //Post Tags Function End


}
