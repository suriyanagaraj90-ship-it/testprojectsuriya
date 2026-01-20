<?php

date_default_timezone_set('Asia/Kolkata');
$role= Auth::user()->roles;
$setting = DB::table('settings')->where('id',1)->first();

?>



<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">



<head>

  <!-- Required meta tags -->

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') - Fools2Fools</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{url('/')}}/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/vendors/css/tables/datatable/extensions/dataTables.checkboxes.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/dataTable.Bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.Datatable.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.1/css/buttons.dataTables.min.css">
    
    
    
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{url('/')}}/assets/css/dataTables.bootstrap4.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
  <link rel="stylesheet" href="{{url('/')}}/assets/css/style.css">
  <link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.png" />  
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-J8QJ6B1D39"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-J8QJ6B1D39');
</script>
@vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body <?php if(Request::segment(1) =='downline') { ?> class="sidebar-icon-only" <?php } ?>>
    <div class="container-scroller">
     
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="{{url('/')}}/">
            <img src="{{url('/')}}/assets/images/logo.png" alt="logo" style="height:91px;width:115px;" /></a>
          <a class="navbar-brand brand-logo-mini" href="{{url('/')}}/"><img src="{{url('/')}}/assets/images/logo.png" alt="logo" style="height:50px;width:80px;" /> <!-- <span style="font-size: 13px;font-weight: bold;">{{ucwords(Auth::user()->name)}} ({{Auth::user()->userid}}) </span> --></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
 

          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <?php if(Auth::user()->profile_picture) { ?> 
                  <img src="{{ url('/') }}/uploads/members/{{Auth::user()->profile_picture}}" width="100" alt="image">
                   <?php } else { ?>

                        <img src="{{ url('/') }}/assets/images/default_member.png" alt="image" height="40" width="40">

                        <?php } ?>

                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1" style="color:#fff;font-weight: bold;">
                      
                      <?php if(Auth::user()->id==1 || $role=="Sub Admin") { ?>
                       {{ucwords(Auth::user()->name)}}
                      <?php } else {?>
                      {{ucwords(substr(Auth::user()->name, 0,10))}}...
                      <?php } ?></p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">

                <?php if(Auth::user()->is_member=='0') { ?>
                <a class="dropdown-item" href="{{ url('/') }}/settings">
                  <i class="mdi mdi-cached me-2 text-success"></i> Settings </a>
                  <?php } ?>
                   
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form1').submit();">
                  <i class="mdi mdi-logout me-2 text-primary"></i> Logout </a>

                  <form id="logout-form1" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf </form>
              </div>
            </li>
            <li class="nav-item d-none d-lg-block full-screen-link">
              <a class="nav-link">
                <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
              </a>
            </li>
            
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->

         @include('layouts.sidebar')

        <!-- partial -->
        <div class="main-panel">

          <?php if($setting->address!=''){ ?>


         <marquee style="color:#fff;background: linear-gradient(to right, #1212d8, #1212d8) !important;padding:14px 0;font-weight:bold;margin-top: 20px;" scrollamount="3"> <h4 class="font-weight-bold mb-0 d-md-block mt-1" style= "color: white;
  text-shadow: 2px 2px 4px #000000;"><?php echo $setting->address; ?> </h4></marquee>



           <?php } ?> 


          @yield('content')



          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
              <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © biggwing.com {{date('Y')}}</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{url('/')}}/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
   
    <!--<script src="{{url('/')}}/assets/vendors/chart.js/Chart.min.js"></script>-->
    <script src="{{url('/')}}/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{url('/')}}/assets/js/off-canvas.js"></script>
    <script src="{{url('/')}}/assets/js/hoverable-collapse.js"></script>
    <script src="{{url('/')}}/assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{url('/')}}/assets/js/dashboard.js"></script>
     <script src="{{url('/')}}/assets/js/settings.js"></script>
     <script src="{{url('/')}}/assets/js/jquery.cookie.js"></script>
    <script src="{{url('/')}}/assets/js/todolist.js"></script>
     <!-- Custom js for this page -->
    <script src="{{url('/')}}/assets/js/modal-demo.js"></script>
    <script src="{{url('/')}}/assets/js/data-table.js"></script>
    <!-- End custom js for this page -->
    <!-- End custom js for this page -->
    <!--  <script src="{{url('/')}}/assets/js/jquery.dataTables.js"></script>
    <script src="{{url('/')}}/assets/js/dataTables.bootstrap4.js"></script> -->
    <script src="{{url('/')}}/assets/js/jquery.dataTables.min.js"></script>
    <script src="{{url('/')}}/assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{url('/')}}/assets/js/dataTables.responsive.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    
  
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript">

function myFunction1() {

  var copyText = document.getElementById("link1");

  copyText.select();

  copyText.setSelectionRange(0, 99999)

  document.execCommand("copy");

}


function btn_Loading($this) {

    

var  loadingText  =  '<i class="fa fa-spinner" aria-hidden="true"></i> Processing...';



  if ($($this).html() !==  loadingText) {



  $this.data('original-text', $($this).html());



  $this.html(loadingText);



 }



}

function btn_reset($this) {



  $('.waves-ripple').remove();



  $this.html($this.data('original-text'));



  $this.removeAttr('disabled');



}



 @if(session()->has('success'))

   swal({

        title: "Success!",

        text: '<?php echo session()->get('success')?>',

        icon: "success",

        button: "Ok",

     });

  @endif



  @if(session()->has('error'))

   swal({

        title: "Warning!",

        text: '<?php echo session()->get('error')?>',

        icon: "warning",

        button: "Ok",

     });

  @endif

$('.numbersOnly').keyup(function(e) {

    

    if (/\D/g.test(this.value))

  {

    // Filter non-digits from input value.

    this.value = this.value.replace(/\D/g, '');

  }



    });




</script>
<style>
  select.form-control { height: 46px !important; }  
 .mdi-menu:before {
 color: #ff7f00;
 font-weight:bold;
}
@media only screen and (max-width: 500px) {
.mobileview{
      display: block;
  }
    
}
</style>

</body>
</html>