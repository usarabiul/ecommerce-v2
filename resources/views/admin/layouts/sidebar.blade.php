<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset(general()->favicon()) }}" class="logo-icon" alt="logo icon" />
        </div>
        <div>
            <h4 class="logo-text">Rocker</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
    </div>
    
    <!--navigation-->
    <ul class="metismenu" id="menu">

        <!-- Dashboard -->
        <li class="{{ Request::is('admin/dashboard') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        <!-- My Profile -->
        <li class="{{ Request::is('admin/my-profile') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.myProfile') }}">
                <div class="parent-icon"><i class='bx bx-user-circle'></i></div>
                <div class="menu-title">My Profile</div>
            </a>
        </li>

        <!-- Blog Posts -->
        <li class="{{ Request::is('admin/posts*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-file'></i></div>
                <div class="menu-title">Blog Posts</div>
            </a>
            <ul>
                <li class="{{ Request::is('admin/posts') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.posts') }}"><i class='bx bx-radio-circle'></i>Blogs List</a>
                </li>
                <li class="{{ Request::is('admin/posts/create') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.postsAction', ['create']) }}"><i class='bx bx-radio-circle'></i>New Blog</a>
                </li>
                <li class="{{ Request::is('admin/posts/categories*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.postsCategories') }}"><i class='bx bx-radio-circle'></i>Categories</a>
                </li>
                <li class="{{ Request::is('admin/posts/comments*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.postsCommentsAll') }}"><i class='bx bx-radio-circle'></i>Comments</a>
                </li>
            </ul>
        </li>

        <!-- Pages -->
        <li class="{{ Request::is('admin/pages*') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.pages') }}">
                <div class="parent-icon"><i class='bx bx-edit'></i></div>
                <div class="menu-title">Pages</div>
            </a>
        </li>

        <!-- Media Assets -->
        <li class="{{ Request::is('admin/medies*') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.medies') }}">
                <div class="parent-icon"><i class='bx bx-images'></i></div>
                <div class="menu-title">Media Assets</div>
            </a>
        </li>

        <li class="menu-label">Ecommerce Unit</li>

        <!-- Order Management -->
        <li class="{{ Request::is('admin/orders*') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.orders') }}">
                <div class="parent-icon"><i class='bx bx-briefcase'></i></div>
                <div class="menu-title">Order Management</div>
            </a>
        </li>

        <!-- Products -->
        <li class="{{ Request::is('admin/products*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cart'></i></div>
                <div class="menu-title">Products Lists</div>
            </a>
            <ul>
                <li class="{{ (Request::is('admin/products*') && !Request::is('admin/products/categories*') && !Request::is('admin/products/brands*') && !Request::is('admin/products/tags*') && !Request::is('admin/products/attributes*') && !Request::is('admin/products/reviews*')) ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.products') }}"><i class='bx bx-radio-circle'></i>All Products</a>
                </li>
                <li>
                    <a href="{{ route('admin.productsAction', 'create') }}"><i class='bx bx-radio-circle'></i>New Products</a>
                </li>
                <li class="{{ Request::is('admin/products/categories*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.productsCategories') }}"><i class='bx bx-radio-circle'></i>Categories</a>
                </li>
                <li class="{{ Request::is('admin/products/brands*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.productsBrands') }}"><i class='bx bx-radio-circle'></i>Brands</a>
                </li>
                <li class="{{ Request::is('admin/products/tags*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.productsTags') }}"><i class='bx bx-radio-circle'></i>Tags</a>
                </li>
                <li class="{{ Request::is('admin/products/attributes*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.productsAttributes') }}"><i class='bx bx-radio-circle'></i>Attributes</a>
                </li>
                <li class="{{ Request::is('admin/products/reviews*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.productsReview') }}"><i class='bx bx-radio-circle'></i>Reviews</a>
                </li>
            </ul>
        </li>

        <!-- POS Sale -->
        <li class="{{ Request::is('admin/pos-orders*') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.posOrdersAction', 'sale') }}">
                <div class="parent-icon"><i class='bx bx-cart'></i></div>
                <div class="menu-title">POS Sale</div>
            </a>
        </li>

        <!-- Ecommerce Setting -->
        <li class="{{ Request::is('admin/ecommerce*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-store'></i></div>
                <div class="menu-title">Ecommerce Setting</div>
            </a>
            <ul>
                <li class="{{ Request::is('admin/ecommerce/setting*') ? 'mm-active' : '' }}" >
                    <a href="{{ route('admin.ecommerceSetting') }}"><i class='bx bx-radio-circle'></i>Settings</a>
                </li>
                <li class="{{ Request::is('admin/ecommerce/coupons*') ? 'mm-active' : '' }}" >
                    <a href="{{ route('admin.ecommerceCoupons') }}"><i class='bx bx-radio-circle'></i>Coupons</a>
                </li>
                <li class="{{ Request::is('admin/ecommerce/promotion*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.ecommercePromotion') }}"><i class='bx bx-radio-circle'></i>Promotion</a>
                </li>
            </ul>
        </li>

        <li class="menu-label">General Widgets</li>

        <!-- Clients -->
        <li class="{{ Request::is('admin/clients*') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.clients') }}">
                <div class="parent-icon"><i class='bx bx-user-pin'></i></div>
                <div class="menu-title">Clients</div>
            </a>
        </li>

        <!-- Sliders -->
        <li class="{{ Request::is('admin/sliders*') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.sliders') }}">
                <div class="parent-icon"><i class='bx bx-image'></i></div>
                <div class="menu-title">Sliders</div>
            </a>
        </li>

        <!-- Galleries -->
        <li class="{{ Request::is('admin/galleries*') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.galleries') }}">
                <div class="parent-icon"><i class='bx bx-images'></i></div>
                <div class="menu-title">Galleries</div>
            </a>
        </li>

        <!-- Menus List -->
        <li class="{{ Request::is('admin/menus*') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.menus') }}">
                <div class="parent-icon"><i class='bx bx-menu'></i></div>
                <div class="menu-title">Menus List</div>
            </a>
        </li>

        <li class="menu-label">User Management</li>

        <!-- Admin Users -->
        <li class="{{ Request::is('admin/users/admin*') ? 'mm-active' : '' }} {{ Request::is('admin/users/roles*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-shield-quarter'></i></div>
                <div class="menu-title">Admin Users</div>
            </a>
            <ul>
                <li class="{{ Request::is('admin/users/admin*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.usersAdmin') }}"><i class='bx bx-radio-circle'></i>Admin List</a>
                </li> 
                <li class="{{ Request::is('admin/users/roles*') ? 'mm-active' : '' }}" ><a href="{{ route('admin.userRoles') }}"><i class='bx bx-radio-circle'></i>Roles Permission</a></li>
            </ul>
        </li>

        <!-- All Users -->
        <li class="{{ Request::is('admin/users/customer*') ? 'mm-active' : '' }}">
            <a href="{{ route('admin.usersCustomer') }}">
                <div class="parent-icon"><i class='bx bx-group'></i></div>
                <div class="menu-title">All Users</div>
            </a>
        </li>

        <li class="menu-label">Report Management</li>

        <!-- Reports -->
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bar-chart-alt-2'></i></div>
                <div class="menu-title">Reports List</div>
            </a>
            <ul>
                <li><a href="#"><i class='bx bx-radio-circle'></i>Summary Reports</a></li>
                <li><a href="#"><i class='bx bx-radio-circle'></i>Product Reports</a></li>
                <li><a href="#"><i class='bx bx-radio-circle'></i>Order Reports</a></li>
                <li><a href="#"><i class='bx bx-radio-circle'></i>User Reports</a></li>
                <li><a href="#"><i class='bx bx-radio-circle'></i>Blog Reports</a></li>
            </ul>
        </li>

        <li class="menu-label">Apps Setting</li>

        <!-- Settings -->
        <li class="{{ Request::is('admin/setting*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cog'></i></div>
                <div class="menu-title">Settings</div>
            </a>
            <ul>
                <li class="{{ Request::is('admin/setting/general') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.setting','general') }}"><i class='bx bx-radio-circle'></i>General Settings</a>
                </li>
                <li class="{{ Request::is('admin/setting/mail') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.setting','mail') }}"><i class='bx bx-radio-circle'></i>Mail Setting</a>
                </li>
                <li class="{{ Request::is('admin/setting/sms') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.setting','sms') }}"><i class='bx bx-radio-circle'></i>SMS Setting</a>
                </li>
                <li class="{{ Request::is('admin/setting/social') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.setting','social') }}"><i class='bx bx-radio-circle'></i>Social Setting</a>
                </li>
            </ul>
        </li>

        <!-- Support -->
        <li>
            <a href="{{ route('admin.setting','document') }}" target="_blank">
                <div class="parent-icon"><i class='bx bx-support'></i></div>
                <div class="menu-title">Support</div>
            </a>
        </li>

    </ul>
</div>