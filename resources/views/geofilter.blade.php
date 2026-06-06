<option value="">Select Option</option>
@foreach($datas as $data)
<option value="{{$data->id}}">{{$data->name}}</option>
@endforeach