
    <div class="table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;max-width: 60px;">Drag</th>
                    <th style="width: 200px;max-width: 200px;">Image</th>
                    <th style="max-width: 300px;">Info</th>
                </tr>
            </thead>
            <tbody class="sortable">
                @foreach($slider->sliderItems as $i=>$slide)
                    <tr>
                        <td class="dragable" style="cursor: move;text-align: center;background: #e7e7e7;font-size: 35px;">
                            <i class="fa fa-arrows-v"></i>
                        </td>
                        <td>
                            <input type="hidden" name="slideid[]" value="{{$slide->id}}">
                            <img src="{{asset($slide->image())}}" style="max-width: 100px;margin-bottom:5px;">
                            @if($slide->bannerFile)
                            |
                            <img src="{{asset($slide->banner())}}" style="max-width: 100px;margin-left:5px;margin-bottom:5px;">
                            @endif
                            <br>
                            <a href="{{route('admin.slideAction',['edit',$slide->id])}}" class="btn btn-sm btn-success mr-3">Edit</a>
                            <a href="{{route('admin.slideAction',['delete',$slide->id])}}" class="btn btn-sm btn-danger" onclick="return confirm('Are Your Want To Delete?')">Delete</a>
                        </td>
                        <td>
                            <span><b>Name: </b>{{$slide->name}}</span><br>
                            <span><b>Description:</b> {{Str::limit($slide->description,150)}}</span>
                            @if($slide->seo_title || $slide->seo_description)
                            <br><b>Link:</b> {{$slide->seo_title}} : {{$slide->seo_description}}
                            @endif
                            <br>
                            @if($slide->status=='active')
                            <span class="badge badge-success">Active </span>
                            @elseif($slide->status=='inactive')
                            <span class="badge badge-danger">Inactive </span>
                            @else
                            <span class="badge badge-danger">Draft </span>
                            @endif
                        </td>
                    </tr>
            @endforeach
            @if($slider->sliderItems->count()==0)
                <tr>
                    <td colspan="2" style="text-align:center;">No Slide Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>