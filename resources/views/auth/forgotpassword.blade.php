@extends('layouts.default')

@section('title', 'Login')

@section('content')

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
                <h4><strong>Forgot Password</strong></h4>
                <form class="pt-3" method="POST" action="{{ url('/') }}/send/forgot_password">
                     @csrf
                  <div class="form-group">
                   
                    <input class="form-control form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" type="text" placeholder="Enter Your Email"  name="email" value="{{ old('email') }}" required autofocus >
                                                            
                    @if ($errors->has('email'))
                      <div id="login-username-error" class="invalid-feedback animated fadeIn">
                      <strong>{{ $errors->first('email') }}</strong>
                      </div>
                     @endif
                  </div>
                 
                  <div class="mt-3">
                      

                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Submit</button>
                    
                  </div>
                  
                  <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                  Don't have an account? <a href="{{url('/')}}/register" class="text-primary"> Register</a>
                  </div>
                  <div class="text-center font-weight-light">
                  Already have an account? <a href="{{url('/')}}/login" class="text-primary">Login</a>
                </div>
                  
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

 @endsection  

