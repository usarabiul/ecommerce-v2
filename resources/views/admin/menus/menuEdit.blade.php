@extends(adminTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Menu Update')}}</title>
@endsection 
@push('css')

<style type="text/css">
    .listmenu ul {
        margin: 0;
        padding: 0;
    }
    .listmenu ul li {
        list-style: none;
        margin: 5px;
        padding: 10px;
        border: 1px solid gray;
    }
    .menumanage {
        float: right;
    }
    .select2-container--default .select2-search--inline .select2-search__field {
        width: 100% !important;
    }
    .collapse {
        border-top: 1px solid #e9e9ea;
        padding-top: 10px;
    }
    .menuItemList ul{
        list-style: none;
        padding-left: 25px;
        max-height: 300px;
        overflow: auto;
    }
</style>
@endpush 
@section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Menu Update</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            @if($menu->parent_id)
            <a href="{{route('admin.menusAction',['edit',$menu->parent_id])}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-arrow-left"></i> BACK</a>
            @else
            <a href="{{route('admin.menus')}}" type="button" class="btn btn-outline-success mr-2"><i class="fas fa-arrow-left"></i> BACK</a>
            @endif
            <a href="{{route('admin.menusAction',['edit',$menu->id])}}" type="button" class="btn btn-primary"><i class="fas fa-spinner"></i></a>
        </div>
    </div>
</header>


@include(adminTheme().'alerts')

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">Add Items</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div id="accordion" class="card-expansion menuItemList">
                        <!--Custom menus Items -->
                        @include(adminTheme().'menus.includes.customLink')

                        <!--Page menus Items -->
                        @include(adminTheme().'menus.includes.pagesList')

                        <!--Post Category Items -->
                        @include(adminTheme().'menus.includes.postCategoryList')

                        <!--Service Category Items -->
                        @include(adminTheme().'menus.includes.serviceCategoryList')

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                <h4 class="card-title">Menu Config</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{route('admin.menusAction',['update',$menu->id])}}" method="post" >
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Menu Name(*) </label>
                                    <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Menu Name" value="{{$parent->name?:old('name')}}" required="" />
                                    @if ($errors->has('name'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Menu Location</label>
                                    <div class="input-group">
                                        <select class="form-control" name="location">
                                            <option value="">Select Location</option>
                                            <option value="Header Menus" {{$parent->location=='Header Menus'?'selected':''}}>Header Menus</option>
                                            <option value="Categories Menu" {{$parent->location=='Categories Menu'?'selected':''}}>Categories Menu</option>
                                            <option value="Popular Categories" {{$parent->location=='Popular Categories'?'selected':''}}>Popular Categories</option>
                                            <option value="Footer Two" {{$parent->location=='Footer Two'?'selected':''}}>Footer Two</option>
                                            <option value="Footer Three" {{$parent->location=='Footer Three'?'selected':''}}>Footer Three</option>
                                            <option value="Footer Four" {{$parent->location=='Footer Four'?'selected':''}}>Footer Four</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        
                        <p>
                        <b> @if($menu->parent_id) {{$menu->menuName()?:'No Found'}} @else Primary @endif : </b>
                            Label

                            <span style="float: right;">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you Want To Delete?')">Delete</button>
                                <label style="cursor: pointer; margin-bottom: 0;"> <input class="checkbox" type="checkbox" class="form-control" id="checkall" /> All <span class="checkCounter"></span> </label>
                            </span>
                        </p>
                        <div class="listmenu">
                            <ul  class="sortable">
                                @foreach($menu->subMenus as $menuli)
                                <li @if(!$menuli->
                                    menuName()) style="border: 1px solid red;" @endif >
                                    <span class="dragable" style="cursor: move;">
                                        <input type="hidden" name="menuids[]" value="{{$menuli->id}}" />
                                        @if($menuli->icon)
                                        <span>{!!$menuli->icon!!}</span>
                                        @elseif($menuli->imageFile)
                                        <img src="{{asset($menuli->image())}}" width="25px" />
                                        @endif {{$menuli->menuName()?:'No Found'}}

                                        <span style="color: #d8d8d8;">
                                            ( @if($menuli->menu_type==1) Page @elseif($menuli->menu_type==2) Post Category @elseif($menuli->menu_type==3) Service Category @elseif($menuli->menu_type==0) Custom @endif )
                                        </span>
                                        <strong>Sub: {{$menuli->subMenus->count()}}</strong>
                                    </span>
                                    <span class="menumanage">
                                        <a href="{{route('admin.menusItemsAction',['edit',$menuli->id])}}" style="margin: 0 10px; color: #7bdc00;"><i class="fa fa-edit"></i></a>
                                        <a href="{{route('admin.menusAction',['edit',$menuli->id])}}" style="margin: 0 10px;"><i class="fa fa-plus"></i></a>
                                        
                                        
                                        <label><i class="fa fa-trash text-danger"></i> <input class="checkbox" type="checkbox" name="deleteItems[]" value="{{$menuli->id}}"></label>
                         
                                        <span> </span>
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                            @if($menu->subMenus->count()==0)
                                <h4 style="text-align: center;font-size: 30px;color: #e7e7e7;">No Menu Item </h4>
                            @endif
                        </div>
                        <hr />
                        <div class="row">
                            <div class="form-group col-6">
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input type="checkbox" class="custom-control-input checkbox" name="status" {{$parent->status=='active'?'checked':''}} id="ckb1">  <label class="custom-control-label" for="ckb1">Active </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('js')
<<script>
    $(document).ready(function(){
        $('.checkbox').click('');
    });
</script>
@endpush
