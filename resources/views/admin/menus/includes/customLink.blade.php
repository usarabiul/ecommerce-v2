<div class="accordion-item">
    <h2 class="accordion-header" id="customLink">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-customLink" >
    Custom Link
    </button>
    </h2>
    <div id="flush-customLink" class="accordion-collapse collapse" aria-labelledby="customLink">
        <div class="accordion-body p-1">
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
