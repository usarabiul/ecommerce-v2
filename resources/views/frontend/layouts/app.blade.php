<!DOCTYPE html>
<html lang="en-US">
    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{csrf_token()}}" />
        @yield('title')
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{asset(general()->favicon())}}" />
        @yield('SEO')
        
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/vendors/normalize.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/vendors/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/vendors/evara-font.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/vendors/uicons-regular-straight.css')}}" />
        
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/plugins/jquery-ui.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/plugins/magnific-popup.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/plugins/select2.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/plugins/slick.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/plugins/perfect-scrollbar.css')}}" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.0/css/all.min.css" integrity="sha512-gRH0EcIcYBFkQTnbpO8k0WlsD20x5VzjhOA1Og8+ZUAhcMUCvd+APD35FJw3GzHAP3e+mP28YcDJxVr745loHw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" type="text/css" href="{{asset(assetLink().'/css/main.css')}}" />
        
        @stack('css')
    </head>
    
    <body>
        
        <!--Header Part Include Start-->
        @include(welcomeTheme().'layouts.header')

        <!--Main Content Section Start-->
        <main class="main">
        @yield('contents')
        </main>
        <!--Main Content Section End-->
        
        <!--Footer Part Include Start-->
        @include(welcomeTheme().'layouts.footer')

        <!-- Preloader Start -->
        <div id="preloader-active">
            <div class="preloader d-flex align-items-center justify-content-center">
                <div class="preloader-inner position-relative">
                    <div class="text-center">
                        <h5 class="mb-5">Now Loading</h5>
                        <div class="loader">
                            <div class="bar bar1"></div>
                            <div class="bar bar2"></div>
                            <div class="bar bar3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Vendor JS-->
    <script src="{{asset(assetLink().'/js/vendor/modernizr-3.6.0.min.js')}}"></script>
    <script src="{{asset(assetLink().'/js/vendor/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset(assetLink().'/js/vendor/jquery-migrate-3.3.0.min.js')}}"></script>
    <script src="{{asset(assetLink().'/js/vendor/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/slick.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/jquery.syotimer.min.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/wow.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/jquery-ui.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/perfect-scrollbar.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/magnific-popup.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/select2.min.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/waypoints.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/counterup.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/jquery.countdown.min.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/images-loaded.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/isotope.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/scrollup.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/jquery.vticker-min.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/jquery.theia.sticky.js')}}"></script>
    <script src="{{asset(assetLink().'/js/plugins/jquery.elevatezoom.js')}}"></script>
    <!-- Template  JS -->
    <script src="{{asset(assetLink().'/js/main.js')}}"></script>
    <script src="{{asset(assetLink().'/js/shop.js')}}"></script>
     

      <script>
          $(document).ready(function(){

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
          
      });
      </script>
      
      <script>

          $(document).on("click", ".cartUpdate", function () {
                var that =$(this);
                  var url = that.attr("data-url");
                  $.ajax({
                      url: url,
                      method: "GET",
                  })
                  .done(function (data) {
                      $(".cart-count").empty().append(data.cartCount);
                      $('.cartItemsAll').empty().append(data.cartItems);
                  })
                  .fail(function () {
                      // location.reload(true);
                  });
            });

          $(document).on('click','.subsriberbtm',function(e){
            e.preventDefault();
              var url = $('#subscirbeForm').data('url');
              var subscribeEmail =$('#subscribeEmail').val();
                  $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                      data: {email : subscribeEmail},
                    cache: false,
      
                  })
                  .done(function(data) {
                      if(data.success)
                        {
                          $("#subscribeemailMsg").html("<p style='color: #f6f6f6;background: #009688;margin: 5px 0;padding: 4px 5px;border-radius: 4px;font-weight: bold;line-height: 14px;font-size: 12px;'>"+ data.message +"</p>");
                          $("#subscribeEmail").css("border","");
                          $("#subscirbeForm")[0].reset();
                        }else{
                          $("#subscribeemailMsg").html("<p style='color: #f6f6f6;background: #ff9800;margin: 5px 0;padding: 4px 5px;border-radius: 4px;font-weight: bold;line-height: 14px;font-size: 12px;'>"+ data.message +"</p>");
                        }
                  })
                  .fail(function() {
                    // alert("error");
                  });
      
          });
      
          $("#subscribeEmail").keyup(function(){
                if(validateEmail()){
                    $("#subscribeEmail").css("border","2px solid green");
                    $("#subscribeemailMsg").html("<p style='color: #f6f6f6;background: #009688;margin: 5px 0;padding: 4px 5px;border-radius: 4px;font-weight: bold;line-height: 14px;font-size: 12px;'>Validated Email</p>");
                }else{
                      var subscribeEmail=$("#subscribeEmail").val();
                      if(subscribeEmail==''||subscribeEmail==null || subscribeEmail=='undefined'){
                          $("#subscribeemailMsg").html("<p style='color: white;background: red;margin: 5px 0;padding: 4px 5px;border-radius: 4px;font-weight: bold;line-height: 14px;font-size: 12px;'>Please Get a Verified Email</p>");
                      }else{
                        $("#subscribeEmail").css("border","2px solid red");
                        $("#subscribeemailMsg").html("");
                      }
                }
            });
      
          function validateEmail(){
                var subscribeEmail=$("#subscribeEmail").val();
      
                  var reg =/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                  if(reg.test(subscribeEmail)){
                    return true;
                  }else{
                    return false;
            }
          }
          
            $(document).on('click','.showPassword',function(){
                $(this).toggleClass('active-show');
                if ($(this).hasClass('active-show')) {
                    $('input.password').prop('type','text');
                    $(this).empty().append('<i class="fa fa-eye"></i>');
                } else {
                    $('input.password').prop('type','password');
                    $(this).empty().append('<i class="fa fa-eye-slash"></i>');
                }
            });


      </script>
      
      @stack('js')
        
        
    </body>
</html>
