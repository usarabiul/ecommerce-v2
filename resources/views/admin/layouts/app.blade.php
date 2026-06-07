<!DOCTYPE html>
 <html lang="en" class="light-theme" >
   <!-- BEGIN: Head-->
   <head>

     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <!-- CSRF Token -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
     <meta name="description" content="" />
     <meta name="keywords" content="" />
     <meta name="author" content="NIT" />
     @yield('title')
    <link rel="apple-touch-icon" href="{{asset(general()->favicon())}}" />
    <link rel="shortcut icon" type="image/x-icon" href="{{asset(general()->favicon())}}" />

    <!--plugins-->
	 <link rel="stylesheet"  href="{{asset(assetLinkAdmin().'/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" />
	 <link rel="stylesheet"  href="{{asset(assetLinkAdmin().'/assets/plugins/simplebar/css/simplebar.css')}}" />
	 <link rel="stylesheet"  href="{{asset(assetLinkAdmin().'/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" />
	 <link rel="stylesheet"  href="{{asset(assetLinkAdmin().'/assets/plugins/metismenu/css/metisMenu.min.css')}}" />
	 <!-- loader-->
	 <link rel="stylesheet"  href="{{asset(assetLinkAdmin().'/assets/css/pace.min.css')}}" />
	 <script src="{{asset(assetLinkAdmin().'/assets/js/pace.min.js')}}"></script>
	 <!-- Bootstrap CSS -->
	 <link rel="stylesheet"  href="{{asset(assetLinkAdmin().'/assets/css/bootstrap.min.css')}}" />
	 <link rel="stylesheet"  href="{{asset(assetLinkAdmin().'/assets/css/bootstrap-extended.css')}}" />
	 <link rel="stylesheet"  href="{{asset(assetLinkAdmin().'/assets/css/app.css')}}" />
	 <link rel="stylesheet"  href="{{asset(assetLinkAdmin().'/assets/css/icons.css')}}" />
	 <!-- Theme Style CSS -->
	 <link rel="stylesheet" href="{{asset(assetLinkAdmin().'/assets/css/dark-theme.css')}}" />
	 <link rel="stylesheet" href="{{asset(assetLinkAdmin().'/assets/css/semi-dark.css')}}" />
	 <link rel="stylesheet" href="{{asset(assetLinkAdmin().'/assets/css/header-colors.css')}}" />

    <style type="text/css">
      ul.statuslist {
          text-align: right;
      }

      ul.statuslist li {
          display: inline-block;
      }

      ul.statuslist li a {
          border: 1px solid #d1cece;
          padding: 3px 6px;
          border-radius: 15px;
          display: inline-block;
          margin: 3px 1px;
          font-size: 12px;
      }
    </style>

     @stack('css')
   </head>
   <body class="default-skin pace-done">
	    <div class="wrapper">
        
        @include(adminTheme().'layouts.sidebar')

          <header>
            <div class="topbar d-flex align-items-center">
              <nav class="navbar navbar-expand gap-3">
                <div class="mobile-toggle-menu">
                  <i class='bx bx-menu'></i>
                </div>

                <div class="search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                  <a href="avascript:;" class="btn d-flex align-items-center"><i class='bx bx-search'></i>Search </a>
                </div>

                <div class="top-menu ms-auto">
                  <ul class="navbar-nav align-items-center gap-1">
                    <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                      <a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
                      </a>
                    </li>
                    
                    <li class="nav-item dark-mode d-none d-sm-flex">
                      <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                      </a>
                    </li>
                    <li class="nav-item dropdown dropdown-app">
                      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a>
                      <div class="dropdown-menu dropdown-menu-end p-0">
                        <div class="app-container p-2 my-2">
                        <!-- <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/slack.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Slack </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/behance.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Behance </p>
                            </div>
                            </div>
                          </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/google-drive.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Dribble </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/outlook.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Outlook </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/github.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">GitHub </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/stack-overflow.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Stack </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/figma.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Stack </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/twitter.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Twitter </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/google-calendar.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Calendar </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/spotify.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Spotify </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/google-photos.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Photos </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/pinterest.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Photos </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/linkedin.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">linkedin </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/dribble.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Dribble </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/youtube.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">YouTube </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/google.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">News </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/envato.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Envato </p>
                            </div>
                            </div>
                            </a>
                          </div>
                          <div class="col">
                          <a href="javascript:;">
                            <div class="app-box text-center">
                            <div class="app-icon">
                              <img src="assets/images/app/safari.png" width="30" alt="" />
                            </div>
                            <div class="app-name">
                              <p class="mb-0 mt-1">Safari </p>
                            </div>
                            </div>
                            </a>
                          </div>
            
                        </div> -->
                        <!--end row-->
            
                        </div>
                      </div>
                    </li>

                    <li class="nav-item dropdown dropdown-large">
                      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">0</span>
                        <i class='bx bx-bell'></i>
                      </a>
                      <!-- <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:;">
                          <div class="msg-header">
                            <p class="msg-header-title">Notifications </p>
                            <p class="msg-header-badge">8 New </p>
                          </div>
                        </a>
                        <div class="header-notifications-list">
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center">
                              <div class="user-online">
                                <img src="assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar" />
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
                            ago </span></h6>
                                <p class="msg-info">The standard chunk of _____ </p>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center">
                              <div class="notify bg-light-danger text-danger">dc
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="msg-name">New Orders  <span class="msg-time float-end">2 min
                            ago </span></h6>
                                <p class="msg-info">You have recived new ______ </p>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center">
                              <div class="user-online">
                                <img src="assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar" />
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="msg-name">Althea Cabardo  <span class="msg-time float-end">14
                            sec ago </span></h6>
                                <p class="msg-info">Many desktop publishing packages </p>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center">
                              <div class="notify bg-light-success text-success">
                                <img src="assets/images/app/outlook.png" width="25" alt="user avatar" />
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="msg-name">Account Created <span class="msg-time float-end">28 min
                            ago </span></h6>
                                <p class="msg-info">Successfully created new email </p>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center">
                              <div class="notify bg-light-info text-info">Ss
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="msg-name">New Product Approved  <span class="msg-time float-end">2 hrs ago </span></h6>
                                <p class="msg-info">Your new product has ________ </p>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center">
                              <div class="user-online">
                                <img src="assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar" />
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="msg-name">Katherine Pechon  <span class="msg-time float-end">15
                            min ago </span></h6>
                                <p class="msg-info">Making this the first ____ generator </p>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center">
                              <div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="msg-name">Your item is shipped  <span class="msg-time float-end">5 hrs
                            ago </span></h6>
                                <p class="msg-info">Successfully shipped your item </p>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center">
                              <div class="notify bg-light-primary">
                                <img src="assets/images/app/github.png" width="25" alt="user avatar" />
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="msg-name">New 24 authors <span class="msg-time float-end">1 day
                            ago </span></h6>
                                <p class="msg-info">24 new authors joined ____ week </p>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center">
                              <div class="user-online">
                                <img src="assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar" />
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="msg-name">Peter Costanzo  <span class="msg-time float-end">6 hrs
                            ago </span></h6>
                                <p class="msg-info">It was popularised in ___ 1960s </p>
                              </div>
                            </div>
                          </a>
                        </div>
                        <a href="javascript:;">
                          <div class="text-center msg-footer">
                            <button class="btn btn-primary w-100">View All Notifications </button>
                          </div>
                        </a>
                      </div> -->
                    </li>
                    <li class="nav-item dropdown dropdown-large">
                      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">  <span class="alert-count">8 </span>
                        <i class='bx bx-shopping-bag'></i>
                      </a>
                      <!-- <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:;">
                          <div class="msg-header">
                            <p class="msg-header-title">My Cart </p>
                            <p class="msg-header-badge">10 Items </p>
                          </div>
                        </a>
                        <div class="header-message-list">
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="position-relative">
                                <div class="cart-product rounded-circle bg-light">
                                  <img src="assets/images/products/11.png" class="" alt="product image" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="cart-product-title mb-0">Men White T-Shirt </h6>
                                <p class="cart-product-price mb-0">1 X $29.00 </p>
                              </div>
                              <div class="">
                                <p class="cart-price mb-0">$250 </p>
                              </div>
                              <div class="cart-product-cancel"><i class="bx bx-x"></i>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="position-relative">
                                <div class="cart-product rounded-circle bg-light">
                                  <img src="assets/images/products/02.png" class="" alt="product image" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="cart-product-title mb-0">Men White T-Shirt </h6>
                                <p class="cart-product-price mb-0">1 X $29.00 </p>
                              </div>
                              <div class="">
                                <p class="cart-price mb-0">$250 </p>
                              </div>
                              <div class="cart-product-cancel"><i class="bx bx-x"></i>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="position-relative">
                                <div class="cart-product rounded-circle bg-light">
                                  <img src="assets/images/products/03.png" class="" alt="product image" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="cart-product-title mb-0">Men White T-Shirt </h6>
                                <p class="cart-product-price mb-0">1 X $29.00 </p>
                              </div>
                              <div class="">
                                <p class="cart-price mb-0">$250 </p>
                              </div>
                              <div class="cart-product-cancel"><i class="bx bx-x"></i>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="position-relative">
                                <div class="cart-product rounded-circle bg-light">
                                  <img src="assets/images/products/04.png" class="" alt="product image" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="cart-product-title mb-0">Men White T-Shirt </h6>
                                <p class="cart-product-price mb-0">1 X $29.00 </p>
                              </div>
                              <div class="">
                                <p class="cart-price mb-0">$250 </p>
                              </div>
                              <div class="cart-product-cancel"><i class="bx bx-x"></i>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="position-relative">
                                <div class="cart-product rounded-circle bg-light">
                                  <img src="assets/images/products/05.png" class="" alt="product image" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="cart-product-title mb-0">Men White T-Shirt </h6>
                                <p class="cart-product-price mb-0">1 X $29.00 </p>
                              </div>
                              <div class="">
                                <p class="cart-price mb-0">$250 </p>
                              </div>
                              <div class="cart-product-cancel"><i class="bx bx-x"></i>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="position-relative">
                                <div class="cart-product rounded-circle bg-light">
                                  <img src="assets/images/products/06.png" class="" alt="product image" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="cart-product-title mb-0">Men White T-Shirt </h6>
                                <p class="cart-product-price mb-0">1 X $29.00 </p>
                              </div>
                              <div class="">
                                <p class="cart-price mb-0">$250 </p>
                              </div>
                              <div class="cart-product-cancel"><i class="bx bx-x"></i>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="position-relative">
                                <div class="cart-product rounded-circle bg-light">
                                  <img src="assets/images/products/07.png" class="" alt="product image" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="cart-product-title mb-0">Men White T-Shirt </h6>
                                <p class="cart-product-price mb-0">1 X $29.00 </p>
                              </div>
                              <div class="">
                                <p class="cart-price mb-0">$250 </p>
                              </div>
                              <div class="cart-product-cancel"><i class="bx bx-x"></i>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="position-relative">
                                <div class="cart-product rounded-circle bg-light">
                                  <img src="assets/images/products/08.png" class="" alt="product image" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="cart-product-title mb-0">Men White T-Shirt </h6>
                                <p class="cart-product-price mb-0">1 X $29.00 </p>
                              </div>
                              <div class="">
                                <p class="cart-price mb-0">$250 </p>
                              </div>
                              <div class="cart-product-cancel"><i class="bx bx-x"></i>
                              </div>
                            </div>
                          </a>
                          <a class="dropdown-item" href="javascript:;">
                            <div class="d-flex align-items-center gap-3">
                              <div class="position-relative">
                                <div class="cart-product rounded-circle bg-light">
                                  <img src="assets/images/products/09.png" class="" alt="product image" />
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="cart-product-title mb-0">Men White T-Shirt </h6>
                                <p class="cart-product-price mb-0">1 X $29.00 </p>
                              </div>
                              <div class="">
                                <p class="cart-price mb-0">$250 </p>
                              </div>
                              <div class="cart-product-cancel"><i class="bx bx-x"></i>
                              </div>
                            </div>
                          </a>
                        </div>
                        <a href="javascript:;">
                          <div class="text-center msg-footer">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                              <h5 class="mb-0">Total </h5>
                              <h5 class="mb-0 ms-auto">$489.00 </h5>
                            </div>
                            <button class="btn btn-primary w-100">Checkout </button>
                          </div>
                        </a>
                      </div> -->
                    </li>
                  </ul>
                </div>
                <div class="user-box dropdown px-3">
                  <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{asset(Auth::user()->image())}}" class="user-img" alt="" />
                    <div class="user-info">
                      <p class="user-name mb-0">{{ Auth::user()->name }} </p>
                      <p class="designattion mb-0">Web Designer </p>
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-user fs-5"></i><span>Profile </span></a>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-cog fs-5"></i><span>Settings </span></a>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-home-circle fs-5"></i><span>Dashboard </span></a>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-dollar-circle fs-5"></i><span>Earnings </span></a>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-download fs-5"></i><span>Downloads </span></a>
                    </li>
                    <li>
                      <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-log-out-circle"></i><span>Logout </span></a>
                    </li>
                  </ul>
                </div>
              </nav>
            </div>
          </header>

        <div class="modal" id="SearchModal" tabindex="-1">
		 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
		   <div class="modal-content">
			 <div class="modal-header gap-2">
			   <div class="position-relative popup-search w-100">
				 <input class="form-control form-control-lg ps-5 border border-3 border-primary" type="search" placeholder="Search" />
				 <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-4"><i class='bx bx-search'></i></span>
			   </div>
			   <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
			 </div>
			 <div class="modal-body">
				 <div class="search-list">
				    <p class="mb-1">Html Templates </p>
				    <div class="list-group">
					   <a href="javascript:;" class="list-group-item list-group-item-action active align-items-center d-flex gap-2 py-1"><i class='bx bxl-angular fs-4'></i>Best Html Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vuejs fs-4'></i>Html5 Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-magento fs-4'></i>Responsive Html5 Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-shopify fs-4'></i>eCommerce Html Templates </a>
				    </div>
				    <p class="mb-1 mt-3">Web Designe Company </p>
				    <div class="list-group">
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-windows fs-4'></i>Best Html Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-dropbox fs-4'></i>Html5 Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-opera fs-4'></i>Responsive Html5 Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-wordpress fs-4'></i>eCommerce Html Templates </a>
				    </div>
				    <p class="mb-1 mt-3">Software Development </p>
				    <div class="list-group">
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-mailchimp fs-4'></i>Best Html Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-zoom fs-4'></i>Html5 Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-sass fs-4'></i>Responsive Html5 Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vk fs-4'></i>eCommerce Html Templates </a>
				    </div>
				    <p class="mb-1 mt-3">Online Shoping Portals </p>
				    <div class="list-group">
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-slack fs-4'></i>Best Html Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-skype fs-4'></i>Html5 Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-twitter fs-4'></i>Responsive Html5 Templates </a>
					   <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vimeo fs-4'></i>eCommerce Html Templates </a>
				    </div>
				 </div>
			 </div>
		   </div>
		 </div>
	   </div>

          

        <div class="page-wrapper">
            <div class="page-content">
            @yield('contents')
            </div>
        </div>
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
      <!--end overlay-->
      <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        @include(adminTheme().'layouts.footer')

      </div>
    
   <!-- Bootstrap JS -->
	 <script src="{{asset(assetLinkAdmin().'/assets/js/jquery.min.js')}}"></script>
	 <script src="{{asset(assetLinkAdmin().'/assets/js/bootstrap.bundle.min.js')}}"></script>
	 <!--plugins-->
	 <script src="{{asset(assetLinkAdmin().'/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	 <script src="{{asset(assetLinkAdmin().'/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	 <script src="{{asset(assetLinkAdmin().'/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	 <script src="{{asset(assetLinkAdmin().'/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
     <script src="{{asset(assetLinkAdmin().'/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
	 <script src="{{asset(assetLinkAdmin().'/assets/plugins/chartjs/js/chart.js')}}"></script>
	 <script src="{{asset(assetLinkAdmin().'/assets/js/index.js')}}"></script>
	 <!--app JS-->
	 <script src="{{asset(assetLinkAdmin().'/assets/js/app.js')}}"></script>

    <script src="{{asset(assetLinkAdmin().'/javascript/tag-editor.js')}}"></script>
    <script src="{{asset('tinymce/tinymce.min.js')}}"></script>
    
     <script>
      $(document).ready(function(){

        $('.slugEdit').click(function(){
            var span = $(this).find('span');
            var isCustom = span.text().trim() === 'Auto Slug';
            span.html(isCustom ? 'Custom Slug <i class="fa fa-edit"></i>' : 'Auto Slug');
            var input = $('.slugEditData');
            if (isCustom) {
                input.attr('name', 'slug');
                input.attr('disabled', false);
            } else {
                input.removeAttr('name');
                input.attr('disabled', true);
            }
        });

        $(document).on("keyup", ".titleForSlug", function () {
            let title = $(this).val();
            let slug = title
                .toLowerCase()
                // .replace(/[^a-z0-9]+/g, '-')
                // .replace(/^-+|-+$/g, '');
                .replace(/[^a-z0-9\u0980-\u09FF]+/g, '-')
                .replace(/^-+|-+$/g, '');

            let $slugInput = $(".slugEditData");

            if ($slugInput.is(":disabled")) {
                $slugInput.val(slug);
            }
        });

        tinymce.init({
            selector: 'textarea.tinyEditor',
            height: 300,
            menubar: false,
            statusbar: false,
            plugins: 'lists advlist image link fullscreen advcode code',
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' + 
            'bullist numlist outdent advlist | link image | preview media fullscreen  | code |' +
            'forecolor backcolor emoticons | fontsize',
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function () {
                  var file = this.files[0];
                  var reader = new FileReader();
                  reader.onload = function () {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), { title: file.name });
                  };
                  reader.readAsDataURL(file);
                };
                input.click();
              },
            content_style: 'body{font-family:Helvetica,Arial,sans-serif; font-size:16px}',
            font_size_formats: '8px 10px 12px 14px 16px 18px 24px 36px 48px',
        });
          
        // $(".sortable").sortable({
        //   handle: ".dragable"
        // });
        // $( ".sortable" ).disableSelection();

        $('#PrintAction').on("click", function () {
            $('.PrintAreaContact').printThis();
          });

        $('#PrintAction2').on("click", function () {
            $('.PrintAreaContact2').printThis();
          });

          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          
          $(document).on('click','.showPassword',function(){
                $(this).toggleClass('active-show');
                if ($(this).hasClass('active-show')) {
                    $('input.password').prop('type','text');
                    $(this).empty().append('<i class="bx bx-show"></i>');
                } else {
                    $('input.password').prop('type','password');
                    $(this).empty().append('<i class="bx bx-hide"></i>');
                }

                alert('ok');
          });

          $("#division").on("change", function(){
                var id = $(this).val();
                  if(id==''){
                   $('#district').empty().append('<option value="">No District</option>');
                   $('#city').empty().append('<option value="">No City</option>');
                  }
                  var url ='{{url('geo/filter')}}' + '/'+id;
                  $.get(url,function(data){
                    $('#district').empty().append(data.geoData);
                    $('#city').empty().append('<option value="">No City</option>');
                  });   
            });

            $("#district").on("change", function(){
                var id = $(this).val();
                  if(id==''){
                   $('#city').empty().append('<option value="">No City</option>');
                  }
                  var url ='{{url('geo/filter')}}' + '/'+id;
                  $.get(url,function(data){
                    $('#city').empty().append(data.geoData);  
                  });   
            });

            $('.mediaDelete').click(function(e){
                e.preventDefault();

              var url =$(this).attr('href');

              if(confirm("Are you sure you want to delete this?")){
                
                $.ajax({
                  url : url,
                  type:'GET',
                  cache: false,
                  contentType: false,
                  dataType: 'json',
                  beforeSend: function()
                  {
                    
                  },
                  complete: function()
                  {
                      
                  },
                  }).done(function (data) {
                     
                     location.reload(true);
                    
                  }).fail(function () {
                      alert('fail');
                  });
                  
              }else{
                  return false;
              }

            });
          
      });
    </script>

    <script type="text/javascript">
      ///Check Box Select With Count show

          $(function() {
            $('.checkCounter').text('0');
            var generallen = $("input[name='checkid[]']:checked").length;
            if (generallen > 0) {
              $(".checkCounter").text('(' + generallen + ')');
            } else {
              $(".checkCounter").text(' ');
            }
            
          })
          
          function updateCounter() {
            var len = $("input[name='checkid[]']:checked").length;
            if (len > 0) {
              $(".checkCounter").text('(' + len + ')');
            } else {
              $(".checkCounter").text(' ');
            }
          }
          
          $("input:checkbox").on("change", function() {
            updateCounter();
          });

       
        $(document).ready(function(){
          $('#checkall').click(function() {
              var checked = $(this).prop('checked');
              $('input:checkbox').prop('checked', checked);
              updateCounter();
            });
        });
        
        ///Check Box Select With Count show
      </script>

      @stack('js')
   </body>
   <!-- END: Body-->
 </html>