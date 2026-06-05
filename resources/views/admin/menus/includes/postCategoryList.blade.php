<div class="card card-expansion-item mt-0 mb-2">
    <div class="card-header border-0" id="postList">
        <button
            class="btn btn-reset collapsed"
            data-toggle="collapse"
            data-target="#collapsePostList"
            aria-expanded="false"
            aria-controls="collapsePostList"
        >
            <span class="collapse-indicator mr-2"><i class="fa fa-fw fa-caret-right"></i></span>
            <span>Post Categories</span>
        </button>
    </div>
    <div id="collapsePostList" class="collapse" aria-labelledby="postList" data-parent="#accordion">
        <div class="card-body pt-0">
            <form action="{{route('admin.menusItemsPost',$menu->id)}}" method="post">
                @csrf
                <input type="hidden" name="parent" value="{{$parent->id}}" />
                <div class="form-group" style="margin-bottom: 5px;">
                    <label for="name">Select Categories</label>
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