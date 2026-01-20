<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - Fool2Fool</title>
    <link rel="stylesheet" href="{{url('/')}}/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{url('/')}}/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.png" />   
</head>

    <body>

        @yield('content')

    <!-- BEGIN: Theme JS-->
    <script src="{{ url('/') }}/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ url('/') }}/assets/js/off-canvas.js"></script>
    <script src="{{ url('/') }}/assets/js/hoverable-collapse.js"></script>
    <script src="{{ url('/') }}/assets/js/misc.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script type="text/javascript">
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

    swal({
        title: "Warning!",
        text: 'Only Numbers Allowed!',
        icon: "warning",
        button: "Ok",
     });

          }

    });
    </script>

  <style>
   
    @media (max-width:640px){
    .frontleft{
        display:none;
    }
}

    
</style> 

  </body>

  <!-- END : Body-->

</html>