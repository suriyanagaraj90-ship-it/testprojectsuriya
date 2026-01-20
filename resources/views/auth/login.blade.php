@extends('layouts.default')

@section('title', 'Login')

@section('content')

<?php if(Auth::check()) { header("Location:".url('/')."/dashboard");exit; } ?>


 <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
              
              <div class="col-lg-6 frontleft">
                  <img height="535" src="{{url('/')}}/assets/images/img4.jpg">
                  </div>
                  
                  
            <div class="col-lg-6 mx-auto">
              <div class="auth-form-light text-center p-5">
                <div class="brand-logo">
                 <a href="{{url('/')}}"> <img src="{{ url('/') }}/assets/images/logo.png"> </a>
                  <!--<h5 class="mb-3" style="color: #FF7F00;margin-top: -19px;">Connect with us to reach high!</h5>-->
                </div>
                <h4><strong>Login</strong></h4>
                <form class="pt-3" method="POST" action="{{ route('login') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                  <div class="form-group">
                     <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                        
                   @error('email')
                      <div id="login-username-error" class="invalid-feedback animated fadeIn">
                      <strong>{{ $message }}</strong>
                      </div>
                     @enderror
                  </div>
                  <div class="form-group">

                     <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                               
                  
                  <table><tr><td><input type="checkbox" onclick="showPwd1()" style="inline-size: auto;"></td><td>Show Password</td></tr></table>
                  
                  
                     @error('password')
                      <div id="login-username-error" class="invalid-feedback animated fadeIn">
                      <strong>{{ $message }}</strong>
                      </div>
                     @enderror
                  </div>
                  <div class="mt-3">

                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Login</button>
                    
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <!--<div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                    </div>-->
                    <?php 
                  $user_details=DB::table('users')->where('id',1)->first(); ?>
                  <div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="{{url('/')}}/register" class="text-primary">Create</a>
                  </div>
                    <a href="{{ url('/') }}/forgot_password" class="auth-link text-black mt-3">Forgot password?</a>
                  </div>
                 <!--  <div class="mb-2">
                    <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="mdi mdi-facebook me-2"></i>Connect using facebook </button>
                  </div> -->
                  
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

<style type="text/css">
  .auth .brand-logo {
    margin-bottom: 0px;
    margin-top: -41px;
}
.auth form .auth-form-btn { line-height: 0.5!important; }

.instagram-bg {
    background: radial-gradient(circle farthest-corner at 35% 90%, #fec564, transparent 50%), radial-gradient(circle farthest-corner at 0 140%, #fec564, transparent 50%), radial-gradient(ellipse farthest-corner at 0 -25%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 20% -50%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 0, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 60% -20%, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 100%, #d9317a, transparent), linear-gradient(#6559ca, #bc318f 30%, #e33f5f 50%, #f77638 70%, #fec66d 100%);
}
.help-block { float: left;margin-left: 46px; }
</style>
<script>
    function showPwd1() {
    
        var x = document.getElementById("password");

        if (x.type === "password") {

            x.type = "text";

        } else {

            x.type = "password";

        }

    }
</script>
 @endsection  

