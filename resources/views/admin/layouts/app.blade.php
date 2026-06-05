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
      
    </style>

     @stack('css')
   </head>
   <!-- END: Head-->

   <!-- BEGIN: Body-->
   <body class="default-skin pace-done">
      <!--wrapper-->
	    <div class="wrapper">
    
        @include(adminTheme().'layouts.sidebar')

        @include(adminTheme().'layouts.header')

        
        <div class="page-wrapper">
			    <div class="page-content">
              @yield('contents')
          </div>
        </div>

          @include(adminTheme().'layouts.footer')
        </div>

   <!-- Bootstrap JS -->
	 <script src="{{asset(assetLinkAdmin().'/assets/js/bootstrap.bundle.min.js')}}"></script>
	 <!--plugins-->
	 <script src="{{asset(assetLinkAdmin().'/assets/js/jquery.min.js')}}"></script>
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
          
        $(".sortable").sortable({
          handle: ".dragable"
        });
        $( ".sortable" ).disableSelection();

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
                    $(this).empty().append('<i class="fa fa-eye"></i>');
                } else {
                    $('input.password').prop('type','password');
                    $(this).empty().append('<i class="fa fa-eye-slash"></i>');
                }
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