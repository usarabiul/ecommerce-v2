@foreach($subcategories as $subCategory)

@if($subCategory->id==$category->id) 

@else
<option value="{{$subCategory->id}}" {{$subCategory->id==$category->parent_id?'selected':''}}>{{str_repeat('-',$i)}} {{$subCategory->name}}</option>

@if($subCategory->subctgs->count() > 0)
@include(adminTheme().'posts.includes.editSubcategory',['subcategories' =>$subCategory->subctgs,'i'=>$i+1])
@endif

@endif
@endforeach