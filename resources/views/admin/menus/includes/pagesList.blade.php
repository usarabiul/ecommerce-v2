<div class="accordion-item">
    <h2 class="accordion-header" id="pageList">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-pageList" >
    Pages
    </button>
    </h2>
    <div id="flush-pageList" class="accordion-collapse collapse" aria-labelledby="pageList">
        <div class="accordion-body p-1">
             <form action="{{route('admin.menusItemsPost',$menu->id)}}" method="post">
                @csrf
                <input type="hidden" name="parent" value="{{$parent->id}}" />
                <div class="mb-3" style="margin-bottom: 5px;">
                    <label class="form-label">Select Pages</label>
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