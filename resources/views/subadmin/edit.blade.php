@extends('layouts.layout')

@section('title', 'Edit ' . $user->name)

@section('content')

@php
    $rolesArr = Auth::user()->roles;
@endphp

   <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">Admin Management</h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Admin Management</li>
                </ol>
              </nav>
            </div>
          <div class="row">
           <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Profile</h4>

 {{ html()->modelForm($user,'PUT')->acceptsFiles()->route('subadmin.update', $user->id )->id('msform')->open() }}


<!--Form With Basic Form Elements Start-->
<div class="row">
                <div class="col-12">
<!--Form With Basic Form Elements Start-->
<fieldset>
 <div class="row push">
<div class="col-lg-12">
          <h4 class="text-field-header pt-1 pb-1">
             Personal Details
          </h4>
      </div>

 <hr>
<div class="col-lg-4">

  <div class="form-group">
                                        <div class="controls">
<label>Name <span class="text-danger">*</span></label>
     <input type="text" class="form-control" name="name" id="name" maxlength="35" placeholder=" " value="{{isset($user->name)?$user->name:old('name')}}" required > 
         @if ($errors->has('name')) <p class="help-block text-danger mb-25">{{ $errors->first('name') }}</p> @endif

    </div>

  </div>

</div>
<div class="col-lg-4">
  <div class="form-group">
                                        <div class="controls">
 <label>Email</label>
<input type="email" class="form-control" name="email" id="email" placeholder=" " value="{{isset($user->email)?$user->email:old('email')}}" required > 
         @if ($errors->has('email')) <p class="help-block text-danger mb-25">{{ $errors->first('email') }}</p> @endif
   </div>

  </div>

</div>

<div class="col-lg-4">
    <div class="form-group">
                                        <div class="controls">
                                          <label>Profile Picture <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="photo" id="photo" value="{{isset($user->profile_picture)?$user->profile_picture:old('photo')}}">
            <input type="hidden" name="old_photo" id="old_photo" placeholder=" " value="{{isset($user->profile_picture)?$user->profile_picture:old('photo')}}"> 
              @if ($errors->has('photo')) <p class="help-block text-danger mb-25">{{ $errors->first('photo') }}</p> @endif
    </div>

    <br><br>

         <?php

      if(isset($user->profile_picture)) { ?>

        <img src="{{url('/')}}/uploads/members/{{$user->profile_picture}}" width="100">

      <?php } else {?>
<img class="img-avatar img-avatar32" src="{{ url('/') }}/assets/admin/media/avatars/avatar10.jpg" alt="">
<?php } ?>


  </div>
  </div>
<div class="col-lg-4">
    <div class="form-group">
                                        <div class="controls">
<label>Mobile Number <span class="text-danger">*</span></label>   
      <input type="text" class="form-control numbersOnly" maxlength="10" name="phone_no" id="phone_no" placeholder=" " value="{{isset($user->phone_no)?$user->phone_no:old('phone_no')}}" required <?php if(isset($user->phone_no)){?> readonly <?php }?>> 
         @if ($errors->has('phone_no')) <p class="help-block text-danger mb-25">{{ $errors->first('phone_no') }}</p> @endif

    </div>

  </div>
  </div>
<div class="col-lg-4">
  <div class="form-group">
                                        <div class="controls">
 <label>Password <span class="text-danger">*</span></label>
    <input type="password" class="form-control" name="password" id="password" placeholder=" " value="{{isset($user->password1)?$user->password1:''}}" required > 
         @if ($errors->has('password')) <p class="help-block text-danger mb-25">{{ $errors->first('password') }}</p> @endif

   </div>

      <table><tr><td><input type="checkbox" onclick="showPwd()" style="inline-size: auto;"></td><td>Show Password</td></tr></table>

    </div>
    </div>

<div class="col-lg-4">
    <div class="form-group">
                                        <div class="controls">
 <label>Roles <span class="text-danger">*</span></label>

 {{ html()->select('roles', $roles, isset($user) ? $user->roles->pluck('id')->toArray() : null)->class('form-control select2-selection__rendered roleselect') }}


      @if ($errors->has('roles')) <p class="help-block text-danger">{{ $errors->first('roles') }}</p> @endif  

    </div>

  </div>
 </div>



</div>


  
   </div>

    <div class="row">
        <div class="col-9 pull-left">
            <div class="buttons-group pull-left">
               <button type="submit" class="btn btn-primary">Submit <i class="fa fa-fw fa-thumbs-up position-right"></i></button>
              
        </div>
        <div class="col-3 pull-right">
            <div class="buttons-group pull-right">
               
            </div>
        </div>
    </div>


    <!--Form With Basic Form Elements End-->

    </fieldset>
  </div>
</div>




{{ html()->closeModelForm() }}


<script type="text/javascript">

   

    function showPwd() {

        var x = document.getElementById("password");

        if (x.type === "password") {

            x.type = "text";

        } else {

            x.type = "password";

        }

    }

</script>


</div>
</div>
</div>
</div>

@endsection