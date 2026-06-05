<div class="card card-expansion-item mt-0 mb-2">
    <div class="card-header border-0" id="pageList">
        <button
            class="btn btn-reset collapsed"
            data-toggle="collapse"
            data-target="#collapsePageList"
            aria-expanded="false"
            aria-controls="collapsePageList"
        >
            <span class="collapse-indicator mr-2"><i class="fa fa-fw fa-caret-right"></i></span>
            <span>Pages</span>
        </button>
    </div>
    <div id="collapsePageList" class="collapse" aria-labelledby="pageList" data-parent="#accordion">
        <div class="card-body pt-0">
            <form action="{{route('admin.menusItemsPost',$menu->id)}}" method="post">
                @csrf
                <input type="hidden" name="parent" value="{{$parent->id}}" />
                <div class="form-group" style="margin-bottom: 5px;">
                    <label for="pages">Select Pages</label>
                    <select  name="pages[]" class="selectpicker form-control"title="Select page" multiple="" required="">
                        @foreach($pages as $i=>$page)
                        <option value="{{$page->id}}">{{$page->name}}

                            @if($page->template)
                            ({{$page->template}})
                            @endif

                        </option>
                        @endforeach
                    </select>
                    @if ($errors->has('pages*'))
                    <p style="color: red; margin: 0; font-size: 10px;">The Page Must Be a Number</p>
                    @endif
                </div>
                <button type="submit" class="btn btn-sm btn-block btn-primary" ><i class="fa fa-plus"></i> Add</button>
            </form>
        </div>
    </div>
</div>