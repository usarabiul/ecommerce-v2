<ul>
  @foreach($subcategories as $subctg)
  <li>
      <label>
          <input type="checkbox" 
              name="productCategories[]" 
              value="{{$subctg->id}}" 
              > {{$subctg->name}}
      </label>
      @if($subctg->subctgs->count() >0)
      @include(adminTheme().'menus.includes.serviceCategorySubList',['subcategories' => $subctg->subctgs,'i'=>$i+1])
      @endif
  </li>
  @endforeach
</ul>