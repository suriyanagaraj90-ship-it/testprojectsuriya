@extends('layouts.layout')
@section('title', 'Show Role')
@section('content')

<div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">Roles Management</h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Roles Management</li>
                </ol>
              </nav>
            </div>


<div class="row">
    <div class="col-lg-12 grid-margin">

        <div class="card">
                <div class="card-body">

                    <div class="row">
        <div class="col-lg-10">
            <h4 class="card-title">Show Role</h4>
        </div>
        <div class="col-lg-2">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>

   
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $role->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Permissions:</strong>
            @if(!empty($rolePermissions))
                @foreach($rolePermissions as $v)
                    <label class="label label-success">{{ $v->name }},</label>
                @endforeach
            @endif
        </div>
    </div>
</div>

</div>
</div>
</div>
</div>
</div>

@endsection
