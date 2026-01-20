@extends('layouts.layout')
@section('title', 'Total SubAdmin')
@section('content')

 <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">SubAdmin Management </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">SubAdmin Management </li>
                </ol>
              </nav>
            </div>
            <div class="row">
              
             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    

                    <div class="row">
                   <div class="col-lg-9">
                     <h3 class="card-title mb-4">SubAdmin</h3>
                </div>
                 <div class="col-sm-3">
                    <p class="float-right mb-3">                       
                       
                        <a style="width: 185px;" href="{{ route('subadmin.create') }}" class="btn btn-primary">
                           + Add SubAdmin
                          </a>
                         
                </p>
                </div>
            </div>


            <div class="table-responsive"> 
             <table id="order-listing" class="table table-striped table-bordered responsive nowrap">
               
                            <thead>
                                <tr>
                                    <th>SNo.</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        @canany(['subadmin-edit', 'subadmin-delete'])<th>Action</th>@endcanany                                   
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
                                                    <p class="font-weight-bold mb-0">{{ ucwords($item->name) }}</p>
                                                    <p class="font-weight-bold mb-0">{{ $item->email }}</p>
                                                   
                                                </div>
                                            </div></td>
                                           
                                        <td><span style="padding:8px;" class="btn @if($item->status="Active") btn-success @else btn-danger @endif">{{ $item->status }}</span></td>
                                         
                                            
                                              <td> @php

                                  $created_date=$item->created_at;

                                  if($created_date!='') {

                                      echo date("d-m-Y h:i:s A",strtotime($created_date)); 

                                  }

                              @endphp</td>

 @canany(['subadmin-edit', 'subadmin-delete'])                               

                                        <td>

                                             <div class="dropup">
                                               @canany(['subadmin-edit', 'subadmin-delete'])

                                                @include('shared._actions', [

                                                    'entity' => 'subadmin',

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

