<div class="card card-expansion-item mt-0 mb-2">
    <div class="card-header border-0" id="customLink">
        <button
            class="btn btn-reset collapsed"
            data-toggle="collapse"
            data-target="#collapsecustomLink"
            aria-expanded="false"
            aria-controls="collapsecustomLink"
        >
            <span class="collapse-indicator mr-2"><i class="fa fa-fw fa-caret-right"></i></span>
            <span>Custom Link</span>
        </button>
    </div>
    <div id="collapsecustomLink" class="collapse" aria-labelledby="customLink" data-parent="#accordion">
        <div class="card-body pt-0">
            <form action="{{route('admin.menusItemsPost',$menu->id)}}" method="post">
                @csrf
                <input type="hidden" name="parent" value="{{$parent->id}}" />
                <div class="card-body" style="padding:10px;">
                    <div class="mb-3">
                        <label class="form-label">Menu Name</label>
                        <input
                            type="text"
                            class="form-control form-control-sm {{$errors->has('menuname')?'error':''}}"
                            name="menuname"
                            placeholder="Enter Menu Name"
                            value="{{old('menuname')}}"
                            required=""
                        />
                        @if ($errors->has('menuname'))
                        <div class="invalid-feedback">{{ $errors->first('menuname') }}</div>
                        @endif
                    </div>
                    <div class="mb-3" style="margin-bottom: 5px;">
                        <label class="form-label">Menu Link</label>
                        <input
                            type="text"
                            class="form-control form-control-sm {{$errors->has('menulink')?'error':''}}"
                            name="menulink"
                            placeholder="Enter Menu Link"
                            value="{{old('menulink')}}"
                            required=""
                        />
                        @if ($errors->has('menulink'))
                        <div class="invalid-feedback">{{ $errors->first('menulink') }}</div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-sm btn-block btn-primary"><i class="fa fa-plus"></i> Add</button>
                </div>
            </form>
        </div>
    </div>
</div>