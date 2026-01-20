@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')

<?php $settings=DB::table('settings')->where('id',1)->first(); ?>


          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
                </span> Dashboard
              </h3>
              <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                  </li>
                </ul>
              </nav>
            </div>
            
           
          <?php if(Auth::user()->is_member==0 && Auth::user()->is_member!=1){   ?>
 
               
             <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Employees</h4>
                    <div class="table-responsive">
                      <table id="order-listing" class="table table-striped table-bordered responsive nowrap">
                            <thead>
                                <tr>
                                    <th>SNo.</th>
                                        <th>Name</th>
                                        <th>Joining Date</th>
                                        @canany(['users-edit', 'users-delete']) <th>Action</th>@endcanany                                   
                                </tr>
                            </thead>
                                     
                            <tbody>
                                @php $i = 1; @endphp

                            @foreach($totaluser_list as $key=>$item)

                                     <tr class="tabletranslate">   
                                    <td><div class="d-flex align-items-center">
                                                <div class="avatar avatar-blue mr-3">{{ $i }}</div></div></td>                                   
                                        <td> <div class="d-flex align-items-center">                                           
                                                <div class="">
                                                    <p class="font-weight-bold mb-0">{{ ucwords($item->name) }}</p>
                                                    <p class="mb-0">{{ $item->email }}</p>
                                                    <!--<p class="mb-0">{{ $item->email }}</p>-->
                                                </div>
                                            </div></td>
                                            <td> @php

                                  $created_date=$item->created_at;

                                  if($created_date!='') {

                                      echo date("d-m-Y h:i:s A",strtotime($created_date)); 

                                  }

                              @endphp</td>

 @canany(['users-edit', 'users-delete'])                               

                                        <td>

                                             <div class="dropup">
                                                @canany(['users-edit', 'users-delete'])

                                                @include('shared._actions', [

                                                    'entity' => 'users',

                                                    'id' => $item->id

                                                ])

                                            
                                            <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal" data-toggle="tooltip" data-placement="top"
                                      title="Actions"></i>
                                  </button>
                                  @endcanany 
                                            
                                        </div> 

                                        </td> 
     

                                        @endcanany 
                                        
                                        
            <div class="modal fade" id="slips<?php echo $i; ?>" role="dialog">

       <div class="modal-dialog">

     <div class="modal-content" style="font-family: sans-serif">

         <div class="modal-header" >

            <h5>Payment Slip</h5>

       <button type="button" class="btn btn-danger close" data-bs-dismiss="modal" aria-label="Close">

                <span aria-hidden="true">&times;</span></button>

         </div>

         <div class="modal-body" style="padding: 20px;">

          <form method="get" enctype="multipart/form-data">

            @csrf

            @if(isset($payment_details->payment_slip) && $payment_details->payment_slip)

            <img src="{{url('/')}}/uploads/user_members/KYC/{{$payment_details->payment_slip}}" style="max-width: -moz-available;max-width: -webkit-fill-available;">

            @else

            <h6 class="text-center">No Slip uploaded</h6>

            @endif

          <div class="modal-footer">
              
             
             @if(isset($payment_details->status) && $payment_details->status=='0')

            <button type="button" id="confirm_payment_btn_<?php echo $payment_details->id; ?>" class="btn btn-success waves-effect waves-light" onclick="confirm('<?php echo $payment_details->id; ?>')">Confirm Payment</button>
            
            @else
            <button type="button" class="btn btn-success waves-effect waves-light">Confirmed</button>
            
            @endif

           

          </div> 

          </form>       

     </div>

       </div>

   </div>

</div>

                                 
                                    @php $i++; @endphp 


                                    </tr>
                            @endforeach 
                            </tbody>
                        </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <?php } else { 
        $udownlines = DB::table('users')->where('sponserid',Auth::user()->userid)->where('is_member','1')->where('status','Active')->count();
        $upaid_downlines = DB::table('users')->where('sponserid',Auth::user()->userid)->where('confirm','1')->where('is_member','1')->where('status','Active')->count();
         ?>
       
                    
                    <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                      
                       <div class="row">
              <div class="col-md-2 stretch-card grid-margin">
           <?php if(Auth::user()->profile_picture) { ?> 
                  <img src="{{ url('/') }}/uploads/members/{{Auth::user()->profile_picture}}" width="100" alt="image">
                   <?php } else { ?>

                        <img src="{{ url('/') }}/assets/images/default_member.png" alt="image"  height="100" width="100">

                        <?php } ?>
                    </div>
                     <div class="col-lg-4 col-md-12">

                                  <div class="form-group">
                                        <div class="controls">
                                         <label>NAME: {{ucwords(Auth::user()->name)}}</label>
                                        </div>

                                   </div>
                                    <div class="form-group">
                                        <div class="controls">
                                         <label>PHONE NO: {{Auth::user()->phone_no}}</label>
                                        </div>
                                   </div>
                                   <div class="form-group">
                                        <div class="controls">
                                         <label>EMAIL ID: {{Auth::user()->email}}</label>
                                        </div>
                                    </div>
  
                        </div>
  
                     <div class="col-lg-6 col-md-12">

                                    <div class="form-group">
                                        <div class="controls">
                                         <label>BOOKING STATUS: <span style="padding:8px;" class="btn @if(Auth::user()->confirm==1) btn-success @else btn-danger @endif">@if(Auth::user()->confirm==1) Confirmed @else <a href="{{url('/')}}/send_help" style="text-decoration: none;color: #fff;">Waiting for Payment </a>@endif</span></label>
                                        </div>
                                    </div>
  
                                   <div class="form-group">
                                        <div class="controls">
                                       <label>NO OF REFERALS: <?php echo $upaid_downlines. " / " .$udownlines; ?> </label>
                                        </div>

                                    </div>
  
                                    <div class="form-group">
                                        <div class="controls">
                                        <label>FREE TRIP ALLOTED: {{Auth::user()->trip_allotment}}
                                             
                                              <?php
                                              $utrip_count=0;
                                             if($upaid_downlines>0) {
                                                 for($i=1;$i<=$upaid_downlines;$i++) {
                                                     if($i % 4 == 0) {
                                                 $utrip_count=$utrip_count+1;
                                                     }
                                             }
                                             }
                                             echo " / " .$utrip_count;
                                             $user_update = DB::table('users')->where('id',Auth::user()->id)->update(array('total_trip'=>$trip_count));
                                             ?>
                                             
                                             </label>
                                       </div>

                                    </div>
                                    
                                     <div class="form-group">
                                        <div class="controls">
                                       <input <?php if(Auth::user()->trip=="Own Referals") { ?> checked <?php } ?> type="radio" name="treferals" id="oreferals" value="Own Referals" onclick="update_trip('Own Referals')"> Own Referals
                                       <input <?php if(Auth::user()->trip=="Aided Referals") { ?> checked <?php } ?> type="radio" name="treferals" id="areferals" value="Aided Referals" onclick="update_trip('Aided Referals')"> Aided Referals
                                       </div>

                                    </div>
                       </div>
                    </div>
                    
             <div class="input-group">

                <span style="font-family:'ubuntu-bold', sans-serif; font-size:14px;background-color: #ff7f00b0;padding: 12px;color: white;float:left;font-weight: bold;">My Referral Link</span><div class="row">
                  <input type="text" name="link1" id="link1" class="form-control col-md-10" readonly style="font-weight: bold;padding: 4px;cursor:pointer;width:530px;font-family:'ubuntu-bold',sans-serif;" value="{{url('/')}}/register/sponser/{{Auth::user()->id}}/{{Auth::user()->userid}}">

                <button onclick="myFunction1()" title="Copy" style="background-color:#e6210deb;width:65px;color: #fff!important;" class="btn d-block"><i class="mdi mdi-content-copy menu-icon"></i></button> &nbsp; &nbsp; 
                
                <button onclick="window.open('https://api.whatsapp.com/send?text={{url('/')}}/register/sponser/{{Auth::user()->id}}/{{Auth::user()->userid}}', '_blank')" title="Copy" style="background-color:#00800087;width:40px;color: #fff!important;" class="btn d-block"><i class="mdi mdi-whatsapp menu-icon"></i></button>

              </div>

            </div>

          </div>
          </div>
          </div>
          </div>

             
           
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">My Downlines</h4>
                    <div class="table-responsive">
                     
                      <table id="order-listing" class="table table-striped table-bordered responsive nowrap">
                        <thead>
                          <tr>
                              <th>SNo.</th>
                              <th>Name</th>
                              <th>No Of Referals</th>
                              <th>Trips Alloted</th>
                              <th>Booking Status</th>
                              <th>Trip</th>
                              <th>Joining Date</th>
                              
                              
                             
                          </tr>
                        </thead>
                        <tbody>
                           @php $i = 1; @endphp

                            @foreach($downline_list as $key=>$item)

                                    <tr class="tabletranslate">   
                                    <td><div class="d-flex align-items-center">
                                                <div class="avatar avatar-blue mr-3">{{ $i }}</div></div></td>                                   
                                        <td> <div class="d-flex align-items-center">                                           
                                                <div class="">
                                                    <a href="{{url('/')}}/downline_list/{{$item->userid}}"><p class="font-weight-bold mb-0">{{ ucwords($item->name) }}</p></a>
                                                      <p class="mb-0">{{ $item->phone_no }}</p>
                                                      <!--<p class="mb-0">{{ $item->email }}</p>-->
                                                </div>
                                            </div></td>
                                            <td><?php 
                                            $downlines = DB::table('users')->where('sponserid',$item->userid)->where('is_member','1')->where('status','Active')->count();
                                            $paid_downlines = DB::table('users')->where('sponserid',$item->userid)->where('confirm','1')->where('is_member','1')->where('status','Active')->count();
         
         
                                               echo $paid_downlines. " / " .$downlines;?></td>
                                             <td>
                                                {{$item->trip_allotment}}
                                             
                                             <?php
                                             $trip_count=0;
                                             if($paid_downlines>0) {
                                                 for($i=1;$i<=$paid_downlines;$i++) {
                                                     if($i % 4 == 0) {
                                                 $trip_count=$trip_count+1;
                                                     }
                                             }
                                             }
                                             echo " / " .$trip_count;
                                             
                                             $user_update = DB::table('users')->where('id',$item->id)->update(array('total_trip'=>$trip_count));
                                             
                                             ?>
                                              
                                             </td>
                                             
                                              <td><span style="padding:8px;" class="btn @if($item->confirm==1) btn-success @else btn-danger @endif">@if($item->confirm==1) Confirmed @else Waiting @endif</span></td>
                                                <td>{{$item->trip}}</td>
                                           
                                            <td> @php

                                  $created_date=$item->created_at;

                                  if($created_date!='') {

                                      echo date("d-m-Y h:i:s A",strtotime($created_date)); 

                                  }

                              @endphp
                            </td>
                                   
                                    @php $i++; @endphp 

                                  </tr>

                            @endforeach 
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <?php } ?>
           
           
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
         
          <!-- partial -->
          
       
          <!-- The Modal -->
          

<script>



 function update_trip(value){

                $.ajax("{{url('/')}}/update_trip", {

                type: 'GET',  

                data: {val:value}, 

                success: function (response) {

                 

                  if(response=='success') { 

                    swal({

  title: "Success!",

  text: "Updated successfully.",

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

                  window.location="{{ url('/') }}/dashboard"; 

                }

            });

} 

         }      

            });

                

 }
 
 function update_trip_allotment(id,value){

                $.ajax("{{url('/')}}/update_trip_allotment", {

                type: 'GET',  

                data: {id:id,val:value}, 

                success: function (response) {

                 

                  if(response=='success') { 

                    swal({

  title: "Success!",

  text: "Updated successfully.",

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



});
} 

         }      

            });

                

 }
            
            
            function confirm(id,payment_status,value){

                $.ajax("{{url('/')}}/store_confirm_payment", {

                type: 'GET',  

                data: {id:id}, 

                 beforeSend: function(){

             btn_Loading($('#confirm_payment_btn_'+id));

             $('#confirm_payment_btn_'+id).attr('disabled','disabled');

             },

                success: function (response) {

                 

                  if(response=='success') { 

                    swal({

  title: "Success!",

  text: "Payment confirmed successfully.",

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

                  window.location="{{ url('/') }}/dashboard"; 

                }

            });

} else {



  swal({

       title: "Warning!",

        text: "Payment rejected notification send to user.",

        icon: "warning",

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

                  window.location="{{ url('/') }}/dashboard"; 

                }

            });



}



 $("#"+idval).modal('hide');

 btn_reset($('#confirm_payment_btn_'+id));

 $('#confirm_payment_btn_'+id).removeAttr('disabled'); 

                    

                }      

            });

                

            }
       
 </script>
        

@endsection           

