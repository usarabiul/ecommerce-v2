<table class="table">
        <thead class="table-light">
            <tr>
                <th style="min-width: 100px;width:100px;">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input checkbox"  id="checkall" >  <label class="custom-control-label" for="checkall">All <span class="checkCounter"></span> </label>
                    </div>
                </th>
                <th style="min-width: 300px;">Clients Name</th>
                <th style="max-width: 80px;width:80px;">Image</th>
                <th style="min-width: 60px;width: 60px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $i=>$client)
            <tr>
                <td>
                    <div class="custom-control custom-control-inline custom-control-nolabel custom-checkbox">
                        <input type="checkbox" class="custom-control-input checkbox" name="checkid[]" value="{{$client->id}}" id="ckb1">  <label class="custom-control-label" for="ckb1">ID </label>
                    </div>
                    {{$clients->currentpage()==1?$i+1:$i+($clients->perpage()*($clients->currentpage() - 1))+1}}
                </td>
                <td>
                    <span>{{$client->name}}</span><br />
                    @if($client->status=='active')
                    <span class="badge badge-success">Active </span>
                    @elseif($client->status=='inactive')
                    <span class="badge badge-danger">Inactive </span>
                    @else
                    <span class="badge badge-danger">Draft </span>
                    @endif

                    @if($client->featured==true)
                    <span><i class="fa fa-star" style="color: #faca51;"></i></span>
                    @endif
                    <span style="color: #ccc;">
                        <i class="fa fa-user" style="color: #1ab394;"></i>
                        {{$client->user?$client->user->name:'No Author'}}
                    </span>
                    <span style="color: #ccc;"><i class="fa fa-calendar" style="color: #1ab394;"></i> {{$client->created_at->format('d-m-Y')}}</span>
                </td>
                <td style="padding: 5px; text-align: center;">
                    <img src="{{asset($client->image())}}" style="max-width: 80px; max-height: 50px;" />
                </td>
                <td style="text-align:center;">
                    <div class="dropdown">
                        <button type="button" class="btn btn-success btn-ico" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                        <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-arrow"></div>
                            <a href="{{route('admin.clientsAction',['edit',$client->id])}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit </a>
                            <a href="{{route('admin.clientsAction',['delete',$client->id])}}" onclick="return confirm('Are You Want To Delete')" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
            @if($clients->count()==0)
                <tr>
                    <td colspan="5" class="text-center">No Result Found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{$clients->links('pagination')}}