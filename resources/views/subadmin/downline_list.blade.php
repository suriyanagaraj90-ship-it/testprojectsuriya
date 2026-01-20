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
                              <th>Downlines</th>
                              <th>Earnings</th>
                              <th>Withdraw</th>
                              <th>Joining Date</th>
                              <th>Status</th>
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
                                                    	<p>{{ $item->phone_no }}</p>
                                                    	<!-- <p>{{ $item->email }}</p> -->
                                                    
                                                </div>
                                            </div></td>
                                            <td><?php
                                             echo $downlines = DB::table('users')->where('sponserid',$item->userid)->where('is_member','1')->where('status','Active')->count();
                                             ?>
                                             	
                                             </td>
                                            <td>
                                            	<?php
                                            	echo $earnings = DB::table('user_wallet')->where('sponserid',$item->id)->sum('amount'); ?>
                                            </td>
                                            <td style="font-weight:bold;color:red;">
                                              <?php
                                              echo $withdraw = DB::table('withdraw')->where('sponserid',$item->id)->sum('withdraw'); ?>
                                            </td>
                                            <td> @php

                                  $created_date=$item->created_at;

                                  if($created_date!='') {

                                      echo date("d-m-Y h:i:s A",strtotime($created_date)); 

                                  }

                              @endphp
                            </td>
                                    <td><span style="padding:8px;" class="btn @if($item->status="Active") btn-success @else btn-danger @endif">{{ $item->status }}</span></td>

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



@endsection

