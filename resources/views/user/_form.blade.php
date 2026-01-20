
@php

    $rolesArr = Auth::user()->roles;

@endphp

<!--Form With Basic Form Elements Start-->
<div class="row">
                <div class="col-12">
<!--Form With Basic Form Elements Start-->
<fieldset>
 <div class="row push">
<div class="col-lg-12">
          <p class="font-size-sm text-field-header pt-1 pb-1">
              Employee Details
          </p>
      </div>


<div class="col-lg-6 col-md-12">

  <div class="form-group">
                                        <div class="controls">
<label>Name <span class="text-danger">*</span></label>
     <input type="text" class="form-control" name="name" id="name"  placeholder=" " value="{{isset($user->name)?$user->name:''}}" required > 
         @if ($errors->has('name')) <p class="help-block text-danger mb-25">{{ $errors->first('name') }}</p> @endif

    </div>

  </div>

  <div class="form-group">
                                        <div class="controls">
 <label>Email</label>
<input type="email" class="form-control" name="email" id="email" placeholder=" " value="{{isset($user->email)?$user->email:''}}" required > 
         @if ($errors->has('email')) <p class="help-block text-danger mb-25">{{ $errors->first('email') }}</p> @endif
   </div>

  </div>

<div class="form-group mt-5">
      <label>Status <span class="text-danger">*</span></label>
         <select class="form-control" name="status" id="status" >

                    @php

                        $select_active = ''; $select_inactive = '';

                        $status = isset($user->status) ? $user->status : old('status');

                        if($status=='Active') {

                            $select_active = 'selected';

                        }

                        if($status=='Inactive') {

                            $select_inactive = 'selected';

                        }

                    @endphp
                    <option value="Active" {{$select_active}}>Active</option>

                    <option value="Inactive" {{$select_inactive}}>Inactive</option>

                </select> 
                 @if ($errors->has('status')) <p class="help-block text-danger mb-25">{{ $errors->first('status') }}</p> @endif 

    

  </div>
  

 
      

  </div>

<div class="col-lg-6 col-md-12">

  <div class="form-group">
   <div class="controls">
<label>Mobile Number <span class="text-danger">*</span></label>   
      <input type="text" class="form-control numbersOnly" maxlength="10" name="phone_no" id="phone_no" placeholder=" " value="{{isset($user->phone_no)?$user->phone_no:''}}" <?php if(Auth::user()->is_member==1){?> readonly <?php }?> required > 
         @if ($errors->has('phone_no')) <p class="help-block text-danger mb-25">{{ $errors->first('phone_no') }}</p> @endif

    </div>

  </div>



   

  <div class="form-group">
     <div class="controls">
 <label>Password <span class="text-danger">*</span></label>
    <input type="password" class="form-control" name="password" id="password" placeholder=" " value="{{isset($user->password1)?$user->password1:''}}" required > 
         @if ($errors->has('password')) <p class="help-block text-danger mb-25">{{ $errors->first('password') }}</p> @endif

   </div>

      <table><tr><td><input type="checkbox" onclick="showPwd()" style="inline-size: auto;"></td><td>Show Password</td></tr></table>

    </div>


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
     
  
   <div class="col-lg-12 col-md-12 col-sm-12 text-center form-group " style="display:none;">

    <h5>Roles <span class="text-danger">*</span></h5>

    <div class="controls mb-1">

      {{ html()->select('roles', $roles, ['class' => 'form-control select2-selection__rendered roleselect']) }}

      @if ($errors->has('roles')) <p class="help-block text-danger">{{ $errors->first('roles') }}</p> @endif  

    </div>

  </div>
  

  </div>

<!--<div class="col-lg-12">
          <p class="font-size-sm text-field-header pt-1 pb-1">
             Bank Details
          </p>
      </div>
      
      <div class="col-lg-4">

  <div class="form-group">
                                        <div class="controls">
<label>Bank Name <span class="text-danger">*</span></label>
     <input type="text" class="form-control" name="bank" id="bank"  placeholder=" " value="{{isset($user->bank)?$user->bank:''}}" readonly > 
         @if ($errors->has('bank')) <p class="help-block text-danger mb-25">{{ $errors->first('bank') }}</p> @endif

    </div>

  </div>
  </div>
   
         <div class="col-lg-4">

  <div class="form-group">
                                        <div class="controls">
<label>Beneficiary Name <span class="text-danger">*</span></label>
     <input type="text" class="form-control" name="beneficiary" id="beneficiary"  placeholder=" " value="{{isset($user->beneficiary)?$user->beneficiary:''}}" readonly > 
         @if ($errors->has('beneficiary')) <p class="help-block text-danger mb-25">{{ $errors->first('beneficiary') }}</p> @endif

    </div>

  </div>
  </div>
      
<div class="col-lg-4">

  <div class="form-group">
                                        <div class="controls">
<label>Account Number <span class="text-danger">*</span></label>
     <input type="number" class="form-control" name="account_number" id="account_number"  placeholder=" " value="{{isset($user->account_number)?$user->account_number:''}}" readonly > 
         @if ($errors->has('account_number')) <p class="help-block text-danger mb-25">{{ $errors->first('account_number') }}</p> @endif

    </div>

  </div>
  </div>
  
  <div class="col-lg-4">

  <div class="form-group">
                                        <div class="controls">
<label>Confirm Account Number <span class="text-danger">*</span></label>
     <input type="number" class="form-control" name="confirm_account_number" id="confirm_account_number"  placeholder=" " value="{{isset($user->account_number)?$user->account_number:''}}" readonly > 
         @if ($errors->has('confirm_account_number')) <p class="help-block text-danger mb-25">{{ $errors->first('confirm_account_number') }}</p> @endif

    </div>

  </div>
  </div>
  
<div class="col-lg-4">
    <div class="form-group">
                                        <div class="controls">
<label>IFSC code <span class="text-danger">*</span></label>
     <input type="text" class="form-control" name="IFSC" id="IFSC" minlength="11" maxlength="11"  placeholder=" " value="{{isset($user->IFSC)?$user->IFSC:''}}" readonly > 
         @if ($errors->has('IFSC')) <p class="help-block text-danger mb-25">{{ $errors->first('IFSC') }}</p> @endif

    </div>

  </div>

</div>

<div class="col-lg-4">
                  <div class="form-group controls" style="text-align: left;">
<label>Bank Document Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" minlength="12" maxlength="12" name="aadhaar" id="aadhaar"  placeholder=" " value="{{isset($user->bank_doc_name)?$user->bank_doc_name:''}}" readonly> 

  </div>
</div>

<div class="col-lg-4">
    <div class="form-group">
                                        <div class="controls">
                                          <label>Bank Document <span class="text-danger">*</span></label>
                                         <input type="file" class="form-control" name="bank_doc" id="bank_doc" value="{{isset($user->bank_doc)?$user->bank_doc:old('bank_doc')}}" >
                                          <input type="hidden" class="form-control" name="old_bank_doc" id="old_bank_doc"  placeholder=" " value="{{isset($user->bank_doc)?$user->bank_doc:''}}" readonly> 
    </div>

         <?php

      if(isset($user->bank_doc)) { ?>
      <div id="aniimated-thumbnials" class="list-unstyled row">

            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">

                  <a href="{{url('/')}}/uploads/members/{{$user->bank_doc}}" >

                        <img class="img-fluid thumbnail" src="{{url('/')}}/uploads/members/{{$user->bank_doc}}" alt="">

                  </a>

            </div>

</div>

      <?php } ?>


  </div>
  </div>
  
  <div class="col-lg-12">
          <p class="font-size-sm text-field-header pt-1 pb-1">
             KYC Details
          </p>
      </div>
      
<div class="col-lg-4">

  <div class="form-group">
                                        <div class="controls">
<label>Aadhaar Number <span class="text-danger">*</span></label>
     <input type="text" class="form-control numbersOnly" minlength="12" maxlength="12" name="aadhaar" id="aadhaar"  placeholder=" " value="{{isset($user->aadhaar)?$user->aadhaar:''}}" readonly> 
         @if ($errors->has('aadhaar')) <p class="help-block text-danger mb-25">{{ $errors->first('aadhaar') }}</p> @endif

    </div>

  </div>

</div>

<div class="col-lg-4">
    <div class="form-group">
                                        <div class="controls">
                                          <label>Aadhaar Front <span class="text-danger">*</span></label>
                                          <input type="hidden" class="form-control" name="old_aadhaar_front" id="old_aadhaar_front"  placeholder=" " value="{{isset($user->aadhaar_front)?$user->aadhaar_front:''}}" readonly> 
    </div>

         <?php

      if(isset($user->aadhaar_front)) { ?>
      <div id="aniimated-thumbnials1" class="list-unstyled row">

            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">

                  <a href="{{url('/')}}/uploads/members/{{$user->aadhaar_front}}" >

                        <img class="img-fluid thumbnail" src="{{url('/')}}/uploads/members/{{$user->aadhaar_front}}" alt="">

                  </a>

            </div>

</div>
       
      <?php } ?>


  </div>
  </div>
  
<div class="col-lg-4">
    <div class="form-group">
                                        <div class="controls">
                                          <label>Aadhaar Back <span class="text-danger">*</span></label>
                                          <input type="hidden" class="form-control" name="old_aadhaar_back" id="old_aadhaar_back"  placeholder=" " value="{{isset($user->aadhaar_back)?$user->aadhaar_back:''}}" readonly> 
    </div>

         <?php

      if(isset($user->aadhaar_back)) { ?>
      <div id="aniimated-thumbnials2" class="list-unstyled row">

            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">

                  <a href="{{url('/')}}/uploads/members/{{$user->aadhaar_back}}" >

                        <img class="img-fluid thumbnail" src="{{url('/')}}/uploads/members/{{$user->aadhaar_back}}" alt="">

                  </a>

            </div>

</div>
        
      <?php } ?>


  </div>
  </div>

<div class="col-lg-4">

  <div class="form-group">
                                        <div class="controls">
<label>PAN Number <span class="text-danger">*</span></label>
     <input type="text" class="form-control" name="PAN" maxlength="10" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" id="PAN"  placeholder=" " value="{{isset($user->PAN)?$user->PAN:''}}" readonly > 
         @if ($errors->has('PAN')) <p class="help-block text-danger mb-25">{{ $errors->first('PAN') }}</p> @endif

    </div>

  </div>

</div>

<div class="col-lg-4">
    <div class="form-group">
                                        <div class="controls">
                                          <label>PAN Document <span class="text-danger">*</span></label>
                                          <input type="hidden" class="form-control" name="old_PAN_doc" id="old_PAN_doc"  placeholder=" " value="{{isset($user->PAN_doc)?$user->PAN_doc:''}}" readonly> 
    </div>

         <?php

      if(isset($user->PAN_doc)) { ?>
      <div id="aniimated-thumbnials3" class="list-unstyled row">

            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">

                  <a href="{{url('/')}}/uploads/members/{{$user->PAN_doc}}" >

                        <img class="img-fluid thumbnail" src="{{url('/')}}/uploads/members/{{$user->PAN_doc}}" alt="">

                  </a>

            </div>

</div>

      <?php } ?>


  </div>
  </div>-->


       <!--<div class="form-group controls col-md-6">
      <label>Confirmation <span class="text-danger">*</span></label>
         <select class="form-control" name="confirm" id="confirm" >

                    @php

                        $select_confirm = ''; $unselect_confirm = '';

                        $confirm = isset($user->confirm) ? $user->confirm : old('confirm');

                        if($confirm=='1') {

                            $select_confirm = 'selected';

                        }

                        if($confirm=='0') {

                            $unselect_confirm = 'selected';

                        }

                    @endphp
                    <option value="">--Select--</option>
                    
                    <option value="1" {{$select_confirm}}>Confirm</option>

                    <option value="0" {{$unselect_confirm}}>Disconfirm</option>

                </select> 
                 @if ($errors->has('status')) <p class="help-block text-danger mb-25">{{ $errors->first('status') }}</p> @endif 

    

  </div>-->
  
   </div>

    <!--Form With Basic Form Elements End-->

<script>
        function showPwd() {

        var x = document.getElementById("password");

        if (x.type === "password") {

            x.type = "text";

        } else {

            x.type = "password";

        }

    }
</script>