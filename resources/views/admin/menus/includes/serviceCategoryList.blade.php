<div class="accordion-item">
    <h2 class="accordion-header" id="productList">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-productList" >
    Product Categories
    </button>
    </h2>
    <div id="flush-productList" class="accordion-collapse collapse" aria-labelledby="productList">
        <div class="accordion-body p-1">
            <form action="{{route('admin.menusItemsPost',$menu->id)}}" method="post">
                @csrf
                <input type="hidden" name="parent" value="{{$parent->id}}" />

                <div style="border: 1px solid #e9e9ea;padding: 5px;">
                    <ul style="padding-left: 10px;">
                        @foreach($productCategories as $i=>$productCtg)
                            <li>
                                <label>
                                    <input type="checkbox" 
                                        name="productCategories[]" 
                                        value="{{$productCtg->id}}" 
                                        > {{$productCtg->name}}
                                </label>

                                @if($productCtg->subctgs->count() >0)
                                    @include(adminTheme().'menus.includes.serviceCategorySubList',['subcategories' => $productCtg->subctgs,'i'=>1])
                                @endif

                            </li>

                        @endforeach
                    </ul>
                </div>
                <div class="row" style="margin:0 -5px;;">
                    <div class="col-6" style="padding:5px;">
                        <label>
                            <input type="checkbox" 
                                > Select All
                        </label>
                    </div>
                    <div class="col-6" style="padding:5px;">
                        <button type="submit" class="btn btn-sm btn-block btn-primary" onclick="return confirm('Are You Want To Add?')"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>