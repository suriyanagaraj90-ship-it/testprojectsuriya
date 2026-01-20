@extends('layouts.layout')
@section('title', 'General Settings')
@section('content')

<?php

function get_city_name($id){

$result=DB::table('cities')->where('id',$id)->first();

return $result->name;

}

function get_state_name($id){

$result=DB::table('states')->where('id',$id)->first();

return $result->name;

}

?>

@php

$rolesArr = Auth::user()->roles;

@endphp

<div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">Settings</h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
              </nav>
            </div>
          <div class="row">
           <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Settings</h4>
                  <form method="POST" action="{{url('/')}}/settings" accept-charset="UTF-8" id="msform" enctype="multipart/form-data">
                      @csrf
                    <p class="card-description">
                      Update settings info
                    </p>
                    
                    <div class="row">
                 <div class="col-12 grid-margin">
                   <div class="form-group row">
                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Latest News <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                       <textarea name="address" id="address" class="form-control ckeditor" rows="6">{!! $settings[0]['address'] !!}</textarea>
            @if ($errors->has('address')) 
            <span class="help-block text-danger">{{ $errors->first('address') }}</span>@endif
                      </div>
                    </div>
                    
                
                    
                    <div class="form-group row">
                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Address <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                       <textarea name="address1" id="address1" class="form-control" rows="6">{{isset($settings[0]['address1'])?$settings[0]['address1']:''}}</textarea>

              @if ($errors->has('address1')) 
            <span class="help-block text-danger">{{ $errors->first('address1') }}</span>@endif
                      </div>
                    </div>
                    
              </div>
                    </div>
                    
                    
                    <div class="row">
                    <div class="col-6 grid-margin">
                        
                    <div class="form-group row">
                      <label for="exampleInputUsername2" class="col-sm-5 col-form-label">Ph No1 <span class="text-danger">*</span></label>
                      <div class="col-sm-7">
                      <input type="text" class="form-control" name="phone" id="phone" class="form-control mb-2" value="{{isset($settings[0]['phone'])?$settings[0]['phone']:''}}">
            @if ($errors->has('phone')) 
            <span class="help-block text-danger">{{ $errors->first('phone') }}</span>@endif
                      </div>
                    </div>
                  
                   
               
                    </div>
                    
                    
              
                    <div class="col-6 grid-margin">
                        
                       
                    <div class="form-group row">
                      <label for="exampleInputUsername2" class="col-sm-5 col-form-label">Email <span class="text-danger">*</span></label>
                      <div class="col-sm-7">
                       <input type="email" name="email" id="email"  class="form-control" value="{{isset($settings[0]['email'])?$settings[0]['email']:''}}">
                @if ($errors->has('email')) 
            <span class="help-block text-danger">{{ $errors->first('email') }}</span>@endif
                      </div>
                    </div>
                    
                   
                   </div>

                     <div class="form-group row">
                     
                      <div class="col-sm-9">

                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                </div>
            </div>

                 </form>
               
              </div>
            </div>
         </div>
       </div>
         


<!-- Content Body End -->
<script type="text/javascript" src="{{url('/')}}/assets/js/plugins/ckeditor/ckeditor.js"></script>


<script type="text/javascript">
 
function submit_form() {
    
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

if($("#address").val()=='') {
   swal({
        title: "Warning!",
        text: "Enter address",
        icon: "warning",
        button: "Ok",
     });
    document.getElementById("address").focus();
  }
  else if($("#email").val()=='') {
     swal({
        title: "Warning!",
        text: "Enter your email",
        icon: "warning",
        button: "Ok",
     });  
      document.getElementById("email").focus();
  } else if($("#phone").val()=='') {
      swal({
        title: "Warning!",
        text: "Enter your phone number",
        icon: "warning",
        button: "Ok",
     });
       document.getElementById("phone").focus();
  }

    else if($("#email").val()!="") {
    if($("#email").val().match(mailformat)) {

      $.ajax({   
              type: 'POST', 
              url: "{{url('/')}}/settings", 
              data: $('#msform').serialize(), 
                
                success: function (response) {

           if(response=='Success') {     
       swal({
        title: "Success!",
        text: "Succesfully Updated.",
        icon: "success",
            buttons: {
                confirm: {
                    text: "OK!",
                    value: true,
                    visible: true,
                    closeModal: true
                }
            },
        }).then((willDelete) => {
            if (willDelete) {
                 window.location.href = "{{ url('/') }}/settings";
            } 
        }); 

     
} else {
   swal({
        title: "Warning!",
        text: "Please fill all required fields.",
        icon: "warning",
        button: "Ok",
     });  
}

         }      
     });

    } else {
     swal({
        title: "Warning!",
        text: "You have entered an invalid email address!",
        icon: "warning",
        button: "Ok",
     });
     document.getElementById("email").focus();
 }
  }
}


   
</script> 

@endsection