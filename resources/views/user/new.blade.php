@extends('layouts.layout')

@section('title', 'Create Employee')

@section('content')

<!-- Content Body Start -->

<div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">Employee Management</h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
                </ol>
              </nav>
            </div>
          <div class="row">
           <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Employee</h4>

                  {{ html()->form('POST')->acceptsFiles()->route('users.store')->id('msform')->open() }}

    @include('user._form')
<div class="row">
        <div class="col-9 pull-left">
            <div class="buttons-group pull-left">
            <a href="{{ route('users.index') }}" class="btn btn-dark"> Back</a>
        </div>
        </div>
        <div class="col-3 pull-right">
            <div class="buttons-group pull-right">
               <button id="btnsubmit" type="submit" class="btn btn-primary">Submit <i class="fa fa-fw fa-thumbs-up position-right"></i></button>
            </div>
        </div>
    </div>


    {{ html()->form()->close() }}
    
                                     </div>
              </div>
            </div>
         </div>
</div>
<!-- Content Body Start -->

    
@endsection