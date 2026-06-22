<div class="accordion-item">
    <h2 class="accordion-header" id="postList">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-postList" >
    Post Categories
    </button>
    </h2>
    <div id="flush-postList" class="accordion-collapse collapse" aria-labelledby="postList">
        <div class="accordion-body p-1">
            <form action="{{route('admin.menusItemsPost',$menu->id)}}" method="post">
                @csrf
                <input type="hidden" name="parent" value="{{$parent->id}}" />
                <div class="mb-3" style="margin-bottom: 5px;">
                    <label class="form-label" >Select Categories</label>
                    <select name="blogCategories[]" class="selectpicker form-control" title="Select Category" multiple="multiple" required="">
                        @foreach($blogCategories as $i=>$bctg)
                        <option value="{{$bctg->id}}">{{$bctg->name}}</option>
                        @if($bctg->subctgs->count() >0) @include(adminTheme().'menus.includes.postCategorySubList',['subcategories' => $bctg->subctgs,'i'=>1]) @endif
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-block btn-primary" ><i class="fa fa-plus"></i> Add</button>
            </form>
        </div>
    </div>
</div>