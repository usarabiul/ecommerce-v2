
@if($menu)
<span class="parentMenu" data-id="{{$menu->id}}" style="display: block;text-align: center;border-bottom: 1px solid #efefef;cursor: pointer;padding: 10px;">
@if($menu->parent_id)
<span class="backMenus" data-id="{{$menu->parent_id}}"><span><i class="fa fa-arrow-left"></i> Back </span>{{$menu->menuName()?:'No Found'}} </span>
@else
{{$menu->location}}
@endif
</span>
<form action="{{route('admin.menusAction',['updateSerial',$menu->id])}}">
<table class="table">
    <tbody class="table sortable sortable-list">
    @foreach($menu->subMenus as $menuli)
    <tr class="ui-sortable-handle">
        <td>
            <input type="hidden" name="menuids[]" value="{{$menuli->id}}" />
            <span>{{$menuli->menuName()?:'No Found'}} <span style="font-size: 14px;color: #d3cece;">(Sub: {{$menuli->subMenus->count()}})</span></span>
        </td>
        <td style="width: 20%;">
            <span class="addNewLabelMenu" data-id="{{$menuli->id}}" ><i class="fa fa-plus"></i></span> | 
            <span class="removeItem" data-id="{{$menuli->id}}" data-parent="{{$menuli->parent_id}}"><i class="fa fa-trash"></i></span>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
<span style="text-align: center;display: block;cursor: pointer;border-top: 1px solid #efefef;padding: 10px;color: #009688;" data-id="{{$menu->id}}" class="addMenu"> <i class="fa fa-plus"></i> Add Menu..</span>
<button type="submit" class="btn btn-sm btn-success">Update Serial</button>
</form>
@else
<span style="text-align: center;display: block;cursor: pointer;border-top: 1px solid #efefef;padding: 10px;color: #009688;" class="addMenu"> <i class="fa fa-plus"></i> Add Menu..</span>
@endif

<script type="text/javascript">
  $( function() {
          $( ".sortable" ).sortable();
          $( ".sortable" ).disableSelection();
      } );

</script>