@extends('layouts.default')
@section('title', 'Register')
@section('content')

<div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
              
              <div class="col-lg-5 frontleft">
                  <img height="535" src="{{url('/')}}/assets/images/register.png">
                  </div>
                  
                  
            <div class="col-lg-7 mx-auto">
              <div class="auth-form-light text-center p-5">
                <div class="brand-logo">
                 <a href="{{url('/')}}"> <img src="{{ url('/') }}/assets/images/logo.png"> </a>
                  <!--<h5 class="mb-3" style="color: #FF7F00;margin-top: -19px;">Connect with us to reach high!</h5>-->
                </div>
                <h4><strong>Login</strong></h4>
                <form class="pt-3"  method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                           
                                <input placeholder="Name" id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                          
                        </div>

                        <div class="form-group">
                           
                                <input placeholder="Email"  id="email" type="email" class="form-control form-control-lg  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>

                        <div class="form-group">
                           
                                <input placeholder="Password" id="password" type="password" class="form-control form-control-lg  @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                        </div>

                        <div class="form-group">
                            
                                <input placeholder="Confirm Password"  id="password-confirm" type="password" class="form-control form-control-lg " name="password_confirmation" required autocomplete="new-password">
                            
                        </div>

                       
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                           
                        </div>
                        <div class="text-center mt-4 font-weight-light">Already have an account? <a href="{{url('/')}}/login" class="text-primary">Login</a>
                  </div>
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
