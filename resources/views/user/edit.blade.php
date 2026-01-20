@extends('layouts.layout')

@section('title', 'Edit ' . $user->name)

@section('content')

@php
    $rolesArr = Auth::user()->roles;
@endphp
<?php
function get_city_name($id){
    $result=DB::table('cities')->where('id',$id)->first();
    return $result->name;
}
function get_state_name($id){
    $result=DB::table('states')->where('id',$id)->first();
    return $result->name;
}

function Get_Countries_List($data=null){
		$sql="SELECT ID,SORTNAME,NAME,PHONECODE FROM countries WHERE 1=1";
		if(is_array($data)){
			if(array_key_exists("ID",$data)){ $sql.=" AND ID='".$data['ID']."'";}
			if(array_key_exists("COUNTRYID",$data)){ $sql.=" AND ID='".$data['COUNTRYID']."'";}
			if(array_key_exists("COUNTRYNAME",$data)){ $sql.=" AND NAME='".$data['COUNTRYNAME']."'";}
			if(array_key_exists("NAME",$data)){ $sql.=" AND NAME='".$data['NAME']."'";}
		}
		$sql.=" ORDER BY NAME";
		$result=DB::select($sql);$return=array();
		if(count($result)>0){
			for($i=0;$i<count($result);$i++){
				$return[]=array(
							"COUNTRYID"=>$result[$i]->ID,
							"SHORTNAME"=>$result[$i]->SORTNAME,
							"COUNTRYNAME"=>$result[$i]->NAME,
							"PHONECODE"=>$result[$i]->PHONECODE,
						);
			}
		}
		return $return;
	}

function Get_State_List($data=null){
		$sql="SELECT ID,NAME FROM states WHERE 1=1";
		if(is_array($data)){
			if(array_key_exists("ID",$data)){ $sql.=" AND ID='".$data['ID']."'";}
			if(array_key_exists("STATEID",$data)){ $sql.=" AND ID='".$data['STATEID']."'";}
			if(array_key_exists("STATENAME",$data)){ $sql.=" AND NAME='".$data['STATENAME']."'";}
			if(array_key_exists("NAME",$data)){ $sql.=" AND NAME='".$data['NAME']."'";}
			if(array_key_exists("COUNTRYID",$data)){ $sql.=" AND COUNTRY_ID='".$data['COUNTRYID']."'";}
		}
		$sql.=" ORDER BY NAME";
		$result=DB::select($sql);$return=array();
		if(count($result)>0){
			for($i=0;$i<count($result);$i++){
				$return[]=array(
							"STATEID"=>$result[$i]->ID,
							"STATENAME"=>$result[$i]->NAME,
						);
			}
		}
		return $return;
	}
 function Get_City_List($data=null){
		$sql="SELECT ID,NAME FROM cities WHERE 1=1";
		if(is_array($data)){
			if(array_key_exists("ID",$data)){ $sql.=" AND ID='".$data['ID']."'";}
			if(array_key_exists("CITYID",$data)){ $sql.=" AND ID='".$data['CITYID']."'";}
			if(array_key_exists("CITYNAME",$data)){ $sql.=" AND NAME='".$data['CITYNAME']."'";}
			if(array_key_exists("NAME",$data)){ $sql.=" AND NAME='".$data['NAME']."'";}
			if(array_key_exists("STATEID",$data)){ $sql.=" AND STATE_ID='".$data['STATEID']."'";}
		}
		$result=DB::select($sql);$return=array();
		if(count($result)>0){
			for($i=0;$i<count($result);$i++){
				$return[]=array(
							"CITYID"=>$result[$i]->ID,
							"CITYNAME"=>$result[$i]->NAME,
						);
			}
		}
		return $return;
	}
	
$states = Get_State_List(array("COUNTRYID"=>101));;
$cities = Get_City_List(array("COUNTRYID"=>101));
        
?>

   <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">Employee Management</h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Employee Management</li>
                </ol>
              </nav>
            </div>
          <div class="row">
           <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Profile</h4>


 {{ html()->modelForm($user,'PUT')->acceptsFiles()->route('users.update', $user->id )->id('msform')->open() }}


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
      <input type="text" class="form-control numbersOnly" maxlength="10" name="phone_no" id="phone_no" placeholder=" " value="{{isset($user->phone_no)?$user->phone_no:old('phone_no')}}" required <?php if(Auth::user()->is_member==1){?> readonly <?php }?> > 
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
    
  
   </div>

    <div class="row">
        <div class="col-9 pull-left">
            <div class="buttons-group pull-left">
               <button id="btnsubmit" type="submit" onclick="formSub()" class="btn btn-primary">Submit <i class="fa fa-fw fa-thumbs-up position-right"></i></button>
               <button type="button" id="hide" class="btn btn-success" style="margin-bottom:-20px;display:none;"><i class="fa fa-circle-o-notch fa-spin"></i> loading...</button>
            <!-- <a href="{{url('/')}}/profile" class="btn btn-dark"> Back</a> -->
        </div>
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


    
<script>
    function formSub(){
       // $('#btnsubmit').hide();
       // $('#hide').show();
        
        var file = $("#bank_doc").val();
var fileType = file["type"];
var validImageTypes = ["image/jpg", "image/jpeg", "image/png"];
if ($.inArray(fileType, validImageTypes) < 0) {
     // invalid file type code goes here.
    //  alert('invalid file type in bank document');
    //  $('#btnsubmit').show();
    //     $('#hide').hide();

}

        $('form').submit();

    }
</script>
<script type="text/javascript">

    function get_City(val){

        var sid=val;

        $('#city_div').html('');

        var select = document.createElement("select");

            select.id = "city";

            select.className = "form-control selectpicker required";

            select.name = "city";

            $('#city_div').html(select);

        $.ajax({

            type:"GET",

            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },

            url:"{{ url('/') }}/members/GET-CITY",

            data:{SID:sid,_token:$('meta[name=_token]').attr('content')},

            async:false,

            dataType:"JSON",

            completed:function(e, x, settings, exception){ajax_errors(e, x, settings, exception)},

            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception)},

            success:function(response){

                $('#city').find('option').remove().end();

                $('#city').append('<option value="" disabled selected>Select a City</option>');

                for(var i=0;i<response.length;i++){

                    var option = document.createElement("option");

                        option.value = response[i]['CITYID'];

                        option.text = response[i]['CITYNAME'];

                        select.appendChild(option);

                }

            }

        });

        $('#city').attr('data-live-search',true);

        $('#city').selectpicker(); 

    }

    

    function showPwd() {

        var x = document.getElementById("password");

        if (x.type === "password") {

            x.type = "text";

        } else {

            x.type = "password";

        }

    }

</script>




<!-- Content Body End -->

<script type="text/javascript">
function validateAadhaar(){ 
var regexp = /^[2-9]{1}[0-9]{3}\s[0-9]{4}\s[0-9]{4}$/; 
var ano = document.getElementById("aadhaar").value; 

if(regexp.test(ano)) { 
alert("Valid Aadhaar Number"); 
}else{ 
alert("Invalid Aadhaar Number"); 
} 
    
} 



function submit_form() {
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

if($("#name").val()=='') {
   swal({
        title: "Warning!",
        text: "Enter your name",
        icon: "warning",
        button: "Ok",
     });
    document.getElementById("name").focus();
  }
  else if($("#email").val()=='') {
     swal({
        title: "Warning!",
        text: "Enter your email",
        icon: "warning",
        button: "Ok",
     });  
      document.getElementById("email").focus();
  } else if($("#phone_no").val()=='') {
      swal({
        title: "Warning!",
        text: "Enter your phone number",
        icon: "warning",
        button: "Ok",
     });
       document.getElementById("phone_no").focus();
  }
    else if($("#email").val()!="") {
    if($("#email").val().match(mailformat)) {

      var form_data = new FormData();

      form_data.append('_token', '{{csrf_token()}}');

      form_data.append("name", $("#name").val());
      form_data.append("email", $("#email").val());
      form_data.append("phone_no", $("#phone_no").val());
      form_data.append("password1", $("#password1").val());
      //form_data.append("profile_picture", $('#profile_picture')[0].files[0]);

      $.ajax({   
              type: 'POST', 
              url: '{{url('/')}}/adminprofile/update',
              headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }, 
               data: form_data,
               dataType:"json",
               contentType: false,
               cache: false,
               processData: false, 
                beforeSend: function(){
                  btn_Loading($('#btnsubmit'));
               },
                success: function (response) {
           if(response==1) {                            
                    swal({
  title: "Success!",
  text: "Succesfully Updated.",
  icon: "success",
  buttons: {
            confirm: {
                text: "Ok",
                value: true,
                visible: true,
                className: "button button-danger",
                closeModal: true
            }
        },
}).then((willDelete) => {
                if (willDelete) {           
                  window.location='{{url('/')}}/adminprofile'; 
                }
            });
} 
btn_reset($('#btnsubmit'));
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

</div>
</div>
</div>
</div>

@endsection