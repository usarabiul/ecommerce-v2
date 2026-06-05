@if($gallery->galleryImages->count()>0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <th style="width:50px;min-width:50px;">Drag</th>
                    <th style="width:250px;min-width:250px;">
                        <label>
                            <input type="checkbox" class="form-check-input m-0" id="checkall"  > All <span class="checkCounter"></span>
                        </label>
                        Item Delete
                    </th>
                    <th style="min-width:300px;">Content</th>
                </tr>
            </thead>
            <tbody class="sortable">
            @foreach($gallery->galleryImages as $i=>$image)
                <tr>
                    <td class="dragable" style="cursor: move;text-align: center;background: #e7e7e7;font-size: 35px;">
                        <i class="fa fa-arrows-v"></i>
                    </td>
                    <td>
                        
                        <div class="mb-1">
                            <span class="form-label">Title</span>
                            <input type="text" class="form-control"  name="imageName[]" placeholder="Enter Title" value="{{$image->alt_text}}">
                        </div>
                            <img src="{{asset($image->file_url)}}" style="max-width: 100px;">
                        <br>
                        <input type="hidden" name="imageid[]" value="{{$image->id}}">
                        <label>
                            <input type="checkbox" class="form-check-input" name="checkid[]" value="{{$image->id}}" >
                        </label>
                        <b>SL:</b> {{$i+1}}
                        
                    </td>
                    <td>
                        <span>Description</span>
                        <textarea class="form-control" rows="4" name="imageDescription[]" placeholder="Write Description">{{$image->description}}</textarea>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@else
    <h4 style="text-align: center;font-size: 30px;color: #e7e7e7;">No Gallery Item </h4>
@endif