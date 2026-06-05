<input type="hidden" class="newMenuItem" data-type="{{$menu_type}}" value="{{$item_id}}">
<div class="form-group">
    <label>Select Menu</label>
    <select class="form-control ajaxMenuSelect" name="location">
        <option value="">Select Location</option>
        <option value="Top Header" >Top Header</option>
        <option value="Header Menus" >Header Menus</option>
        <option value="Footer Two" >Footer Two</option>
        <option value="Footer Three" >Footer Three</option>
    </select>
</div>
<div class="menuListBarSection">
    <span class="loader"><img src="{{asset('public/medies/loading.gif')}}"></span>
    <div class="menuListBar">
    
    </div>
</div>