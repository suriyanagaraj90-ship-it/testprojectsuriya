<?php
date_default_timezone_set('Asia/Kolkata');
?> 

 <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
               <!--<div class="nav-profile-image">
                  <?php if(Auth::user()->profile_picture) { ?> 
                  <img src="{{ url('/') }}/uploads/members/{{Auth::user()->profile_picture}}" width="100" alt="profile">
                   <?php } else { ?>

                        <img src="{{ url('/') }}/app-assets/images/portrait/small/avatar-s-11.jpg" alt="profile" height="40" width="40">

                        <?php } ?>
                  <span class="login-status online"></span>
                </div>-->
                <div class="nav-profile-text d-flex flex-column mt-3">
                  <span class="font-weight-bold mb-0" style="font-size:15px;margin-left: -42px;">{{ucwords(Auth::user()->name)}}</span>
                  
                </div>

                <i class="mdi mdi-bookmark-check text-success nav-profile-badge mt-3"></i>
              </a>
            </li>
            <li class="nav-item mt-3<?php if(Request::segment(1) =='dashboard') { echo 'active'; } ?>">
              <a class="nav-link" href="{{url('/')}}/home">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <?php if(Auth::user()->is_member!=1){ ?>
            
             <li class="nav-item {{ Request::segment(1) === 'subadmin' ? 'active' : null }}">
              <a class="nav-link" href="{{ url('/') }}/subadmin/{{Auth::user()->id}}/edit">
                <span class="menu-title">Profile</span>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
            </li>
             
             <?php } else { ?>

            <li class="nav-item {{ Request::segment(1) === 'profileupdate' ? 'active' : null }}">
              <a class="nav-link" href="{{ url('/') }}/profileupdate">
                <span class="menu-title">Profile</span>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
            </li>
            
            <?php } ?>
            
             <!--<li class="nav-item {{ Request::segment(1) === 'add_amount' ? 'active' : null }}">
              <a class="nav-link" href="{{ url('/') }}/add_amount">
                <span class="menu-title">Add amount</span>
                <i class="mdi mdi-account-plus menu-icon"></i>
              </a>
            </li>-->
            
            
            
             @if(Auth::user()->is_member==0 && Auth::user()->is_member!=1)

            <!--<li class="nav-item {{ Request::segment(1) === 'team' ? 'active' : null }}">
              <a class="nav-link" href="{{ url('/') }}/team">
                <span class="menu-title">Team</span>
                <i class="mdi mdi-account menu-icon"></i>
              </a>
            </li>-->
            
            
            <li class="nav-item {{ Request::segment(1) === 'users' ? 'active' : null }}">
              <a class="nav-link" href="{{ url('/') }}/users">
                <span class="menu-title">Employees</span>
                <i class="mdi mdi-account menu-icon"></i>
              </a>
            </li>
           
            
             
             <li class="nav-item {{ Request::segment(1) === 'subadmin' ? 'active' : null }}">
              <a class="nav-link" href="{{ url('/') }}/subadmin">
                <span class="menu-title">Sub admin</span>
                <i class="mdi mdi-account menu-icon"></i>
              </a>
            </li>

           
             <li class="nav-item {{ Request::segment(1) === 'roles' ? 'active' : null }}">
              <a class="nav-link" href="{{ url('/') }}/roles">
                <span class="menu-title">Roles & Permissions</span>
                <i class="mdi mdi-settings menu-icon"></i>
              </a>
            </li>

         @endif

            
            <li class="nav-item">
              <a class="nav-link"  href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="menu-title">Logout</span>
                <i class="mdi mdi-power menu-icon"></i>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                           @csrf
                        </form>
            </li>
            
            
          </ul>
        </nav>
 
<style type="text/css">
  .sidebar .nav .nav-item .nav-link i.menu-arrow1:before {
    color: #b66dff; 
    content: "\f140";
    font-size: inherit;
  }
</style>
            







   



