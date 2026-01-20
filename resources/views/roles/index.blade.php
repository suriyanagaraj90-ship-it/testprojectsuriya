@extends('layouts.layout')
@section('title', 'Roles')
@section('content')
<div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Roles Management </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Role sManagement </li>
                </ol>
              </nav>
            </div>
            <div class="row">
              
             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    

                    <div class="row">
                   <div class="col-lg-9">
                     <h3 class="card-title mb-4">Roles</h3>
                </div>
                <div class="col-sm-3">
                    <p class="float-right mb-3">                       
                       
                        <a href="{{ route('roles.create') }}" class="btn btn-primary">
                           + New Role
                          </a>
                         
                </p>
                </div>
            </div>

            @session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession


            <div class="table-responsive"> 
             <table id="order-listing" class="table table-striped table-bordered responsive nowrap">
               
                            <thead>
                                <tr>
                                        <th>SNo</th>
                                        <th>Name</th>
                                        <th>Action</th> 
                                </tr>
                            </thead>
                                     
                            <tbody>
                                @php $i = 0; @endphp

                             @foreach ($roles as $key => $role)

                                     <tr class="tabletranslate">   

                                        <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $role->name }}</td>
        <td>
            <!-- <a class="btn btn-info btn-sm" href="{{ route('roles.show',$role->id) }}"><i class="fa-solid fa-list"></i> Show</a> -->
            @can('role-edit')
                <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
            @endcan

            @can('role-delete')
            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
            </form>
            @endcan
        </td>
    </tr>
                                   

                                 
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
