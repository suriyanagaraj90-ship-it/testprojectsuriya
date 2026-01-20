@extends('layouts.layout')
@section('title', 'Downline List')
@section('content')

 <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Downlines List </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Downlines </li>
                </ol>
              </nav>
            </div>
            
           
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Downlines</h4>
                    
                    
                    <div class="table-responsive">
                      <table id="order-listing" class="table table-striped table-bordered responsive nowrap" style="width:100%">
                        <thead>
                          <tr>
                              <th>SNo.</th>
                              <th>Name</th>
                              <th>No Of Referals</th>
                              <th>Trips Alloted</th>
                              <th>Booking Status</th>
                              <th>Trip</th>
                              <th>Joining Date</th>
                              @if(Auth::user()->id==1)<th>Action</th>@endif      
                             
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
                                                    <a href="{{url('/')}}/downline_list/{{$item->userid}}"><p class="font-weight-bold mb-0">{{ ucwords($item->name) }}</p></a>
                                                    	<p class="mb-0">{{ $item->phone_no }}</p>
                                                    	<!-- <p>{{ $item->email }}</p> -->
                                                    
                                                </div>
                                            </div></td>
                                            <td><?php 
                                            $downlines = DB::table('users')->where('sponserid',$item->userid)->where('is_member','1')->where('status','Active')->count();
                                            $paid_downlines = DB::table('users')->where('sponserid',$item->userid)->where('confirm','1')->where('is_member','1')->where('status','Active')->count();
         
         
                                             echo $paid_downlines. " / " .$downlines;?></td>
                                             <td>
                                                 @if(Auth::user()->is_member==0)
                                                 <input style="width: 95px;" onkeyup="update_trip_allotment('{{$item->id}}',this.value)" type="text" class="numbersOnly" name="trip_alloted" value="{{$item->trip_allotment}}">
                                                 @else
                                                 {{$item->trip_allotment}}
                                                 @endif
                                             
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
                                              <td>{{ $item->trip }}</td>
                                            <td> @php

                                  $created_date=$item->created_at;

                                  if($created_date!='') {

                                      echo date("d-m-Y h:i:s A",strtotime($created_date)); 

                                  }

                              @endphp
                            </td>
                                    

                                    @if(Auth::user()->id==1)                             

                                        <td>

                                             <div class="dropup">
                                                @canany(['edit_users', 'delete_users'])

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
     

                                        @endif
                                          
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
</script>

@endsection

