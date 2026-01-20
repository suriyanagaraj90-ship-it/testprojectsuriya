@extends('layouts.default')
@section('title', 'Forgot Password')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <a href="{{ route('password.request') }}" class="auth-link text-black mt-3">Forgot password?</a>
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

    

@endsection
