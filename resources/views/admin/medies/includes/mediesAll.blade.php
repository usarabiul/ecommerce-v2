@foreach($medies as $media)
<li>
    <div class="mediaImagediv" data-bs-toggle="modal"  data-bs-target="#default{{$media->id}}">
        <img src="{{asset($media->image())}}" />
    </div>

    <div style="top: 0; position: absolute;">
        <input type="checkbox" name="mediaid[]" value="{{$media->id}}" />
    </div>

    <div style="top: 0; right: 0; position: absolute; background: #ffffff; padding: 2px 5px;">
        <a href="{{route('admin.mediesEdit',$media->id)}}" target="_blank"> <i class="fa fa-edit"></i></a>
    </div>

    <!-- Modal -->
    <div class="modal fade text-left" id="default{{$media->id}}" tabindex="-1" role="dialog" >
       <div class="modal-dialog modal-dialog-centered" role="document">
    	 <div class="modal-content">
    	   <div class="modal-header">
    		 <h4 class="modal-title" id="myModalLabel1">File Details</h4>
    		 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    	   </div>
    	   <div class="modal-body">
    	   	    <div class="row">
    	   	        <div class="col-md-4">
    	   	            <div>
    	   	                <img src="{{asset($media->image())}}" >
    	   	            </div>
    	   	        </div>
    	   	        <div class="col-md-8">
    	   	            <table class="table table-borderless mediaDetails">
    	   	                <tr>
    	   	                    <th style="padding: 5px;width: 120px;">File Size</th>
    	   	                    <td style="padding: 5px;">: {{ $media->file_size}} Bytes</td>
    	   	                </tr>
    	   	                <tr>
    	   	                    <th style="padding: 5px;">Url </th>
    	   	                    <td style="padding: 5px;"><input type="text" class="form-control form-control-sm urlcopytext" id="myInput" value="{{asset($media->file_url)}}" /></td>
    	   	                </tr>
    	   	                <tr>
    	   	                    <th style="padding: 5px;">File Name </th>
    	   	                    <td style="padding: 5px;">: {{$media->file_name}}</td>
    	   	                </tr>
    	   	                <tr>
    	   	                    <th style="padding: 5px;">File Alt Tag</th>
    	   	                    <td style="padding: 5px;">: {{$media->alt_text}}</td>
    	   	                </tr>
    	   	                <tr>
    	   	                    <th style="padding: 5px;">File Caption</th>
    	   	                    <td style="padding: 5px;">: {{$media->caption}}</td>
    	   	                </tr>
    	   	                <tr>
    	   	                    <th style="padding: 5px;">File Description</th>
    	   	                    <td style="padding: 5px;">: {!!$media->description!!}</td>
    	   	                </tr>
    	   	                <tr>
    	   	                    <th style="padding: 5px;">Author</th>
    	   	                    <td style="padding: 5px;">: {{$media->user?$media->user->name:'no Author'}}</td>
    	   	                </tr>
    	   	            </table>
    	   	        </div>
    	   	    </div>
    	   </div>

    	 </div>
       </div>
     </div>
    
</li>
@endforeach
