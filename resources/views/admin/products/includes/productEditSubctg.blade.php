<ul>
  @foreach($subcategories as $subctg)
  <li>
    <div class="custom-control custom-checkbox">
       <input type="checkbox" class="custom-control-input" value="{{$subctg->id}}" id="category_{{$subctg->id}}" name="categoryid[]" @foreach($product->productCtgs as $postctg) {{$postctg->reff_id==$subctg->id?'checked':''}}  @endforeach  />
       <label class="custom-control-label" for="category_{{$subctg->id}}">{{$subctg->name}}</label>
    </div>
  </li>
  @if($subctg->subctgs->count() >0)
  @include('admin.products.includes.productEditSubctg',['subcategories' => $subctg->subctgs,'i'=>$i+1])
  @endif
  @endforeach
</ul>