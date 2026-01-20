@extends('layouts.layout')
@section('title', 'Total Employees')
@section('content')
<?php $role= Auth::user()->roles->implode('name', ', '); ?>
 <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Employee Management </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Employee Management </li>
                </ol>
              </nav>
            </div>
            <div class="row">
              
             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    

                    <div class="row">
                   <div class="col-lg-9">
                     <h3 class="card-title mb-4">Users</h3>
                </div>
                <div class="col-sm-3">
                    <p class="float-right mb-3">                       
                       
                        <a style="width: 185px;" href="{{ route('users.create') }}" class="btn btn-primary">
                           + Add Employee
                          </a>
                         
                </p>
                </div>
            </div>


            <div class="table-responsive"> 
             <table id="order-listing" class="table table-striped table-bordered responsive nowrap">
               
                            <thead>
                                <tr>
                                        <th>SNo</th>
                                        <th>Name</th>
                                        <th>Online Count</th> 
                                        <th>Joining Date</th>
                                        @canany(['users-edit', 'users-delete'])<th>Action</th>@endcanany                                   
                                </tr>
                            </thead>
                                     
                            <tbody>
                                @php $i = 1; @endphp

                            @foreach($result as $key=>$item)

                                     <tr class="tabletranslate">   
                                    <td><div class="d-flex align-items-center">
                                                <div class="avatar avatar-blue mr-3">{{ $i }}</div></div></td>                                   
                                        <td> <div class="d-flex align-items-center">                                           
                                                <div class="">
                                                    <p class="font-weight-bold mb-0"><a href="{{url('/')}}/downline_list/{{$item->userid}}">{{ ucwords($item->name) }}</a></p>
                                                    
                                                    <p class="mb-0">{{ $item->email }}</p>
                                                </div>
                                            </div></td>
                                             <td>{{ $item->getRoleNames()->first();}}</td>
                                           
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
          </div>



<script>

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

                  window.location="{{ url('/') }}/users"; 

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

                  window.location="{{ url('/') }}/users"; 

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

