<div class="card">


    <a role="button" data-bs-toggle="collapse" href="#dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}" aria-expanded="{{ isset($closed) ? 'true' : 'false' }}" aria-controls="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">
       
        <div class="card-header roles-div" style="padding: 7px 15px; background: gainsboro;" role="tab" id="heading-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">

        <h4 class="mb-0"> 

            {{ $title ?? 'Override Permissions' }} {!! isset($user) ? '<span class="text-danger">(' . $user->getDirectPermissions()->count() . ')</span>' : '' !!}            

        </h4></a>

    </div>
    
    
     <div id="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}" class="card-collapse collapse {{ $closed ?? 'in' }}" role="tabcard" aria-labelledby="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">

        

        <div class="card-body" style="background: #c7deec52;padding: 7px 15px;">
            
            <div class="row">

                <div class="col-md-12"><h5 class="text-primary" style="font-weight: 600">User</h5></div>

                @foreach($permissions as $perm)

                    <?php  

                        $per_found = null;



                        if( isset($role) ) {

                            $per_found = $role->hasPermissionTo($perm->name);

                        }



                        if( isset($user)) {

                            $per_found = $user->hasDirectPermission($perm->name);

                        }

                    ?> 



                    @if (strpos($perm->name, 'users') !== false)

                    <div class="col-md-3">

                        <div class="checkbox adomx-checkbox-radio-group inline">

                            <label class="adomx-checkbox success {{ str_contains($perm->name, 'delete') ? 'text-danger' : '' }}">

                                {!! Form::checkbox("permissions[]", $perm->name, $per_found, isset($options) ? $options : []) !!} {{ ucwords(str_replace("_"," ",$perm->name)) }} <i class="icon"></i>

                            </label>

                        </div>

                    </div>

                    @endif

                @endforeach

            </div>
            
            <div class="col-md-12"><hr></div>
            
            <div class="row mt-20">

                <div class="col-md-12"><h5 class="text-primary" style="font-weight: 600">Roles</h5></div>

                @foreach($permissions as $perm)

                    <?php

                        $per_found = null;



                        if( isset($role) ) {

                            $per_found = $role->hasPermissionTo($perm->name);

                        }



                        if( isset($user)) {

                            $per_found = $user->hasDirectPermission($perm->name);

                        }

                    ?>



                    @if (strpos($perm->name, 'roles') !== false)

                    <div class="col-md-3">

                        <div class="checkbox adomx-checkbox-radio-group inline">

                            <label class="adomx-checkbox success {{ str_contains($perm->name, 'delete') ? 'text-danger' : '' }}">

                                {!! Form::checkbox("permissions[]", $perm->name, $per_found, isset($options) ? $options : []) !!} {{ ucwords(str_replace("_"," ",$perm->name)) }} <i class="icon"></i>

                            </label>

                        </div>

                    </div>

                    @endif

                @endforeach

            </div>
            
            <!--<div class="col-md-12"><hr></div>
            
            <div class="row mt-20">

                <div class="col-md-12"><h5 class="text-primary" style="font-weight: 600">Team</h5></div>

                @foreach($permissions as $perm)

                    <?php

                        $per_found = null;



                        if( isset($role) ) {

                            $per_found = $role->hasPermissionTo($perm->name);

                        }



                        if( isset($user)) {

                            $per_found = $user->hasDirectPermission($perm->name);

                        }

                    ?>



                    @if (strpos($perm->name, 'team') !== false)

                    <div class="col-md-3">

                        <div class="checkbox adomx-checkbox-radio-group inline">

                            <label class="adomx-checkbox success {{ str_contains($perm->name, 'delete') ? 'text-danger' : '' }}">

                                {!! Form::checkbox("permissions[]", $perm->name, $per_found, isset($options) ? $options : []) !!} {{ ucwords(str_replace("_"," ",$perm->name)) }} <i class="icon"></i>

                            </label>

                        </div>

                    </div>

                    @endif

                @endforeach

            </div>-->
            
            <div class="col-md-12"><hr></div>
            
            <div class="row mt-20">

                <div class="col-md-12"><h5 class="text-primary" style="font-weight: 600">Downline</h5></div>

                @foreach($permissions as $perm)

                    <?php

                        $per_found = null;



                        if( isset($role) ) {

                            $per_found = $role->hasPermissionTo($perm->name);

                        }



                        if( isset($user)) {

                            $per_found = $user->hasDirectPermission($perm->name);

                        }

                    ?>



                    @if (strpos($perm->name, 'downline') !== false)

                    <div class="col-md-3">

                        <div class="checkbox adomx-checkbox-radio-group inline">

                            <label class="adomx-checkbox success {{ str_contains($perm->name, 'delete') ? 'text-danger' : '' }}">

                                {!! Form::checkbox("permissions[]", $perm->name, $per_found, isset($options) ? $options : []) !!} {{ ucwords(str_replace("_"," ",$perm->name)) }} <i class="icon"></i>

                            </label>

                        </div>

                    </div>

                    @endif

                @endforeach

            </div>
            
            

  @php $title = isset($title) ? $title : ''; @endphp


@if(isset($title) && $title=='Admin Permissions')

            <!-- <div class="col-md-12"><hr></div> -->



            <!-- <div class="row mt-20">

                <div class="col-md-12"><h5 class="text-primary" style="font-weight: 600"> Global User</h5></div>

                    <div class="col-md-3">

                        <div class="checkbox adomx-checkbox-radio-group inline">

                            <label class="adomx-checkbox success ">

                                <input name="permissions[]" type="checkbox" checked="" value="global_user" disabled=""> Global User <i class="icon"></i>

                            </label>

                        </div>

                    </div>

            </div> -->

            @endif


       </div>

    </div>

</div>


