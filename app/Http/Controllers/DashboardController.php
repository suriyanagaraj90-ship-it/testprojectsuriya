<?php

namespace App\Http\Controllers;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Authorizable;
use App\GeneralModel;
use Gate;
use DB;
use PDF;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       $today = date('Y-m-d');
       $role= Auth::user()->roles->implode('name', ', ');

    if(Auth::user()->is_member==0 && Auth::user()->is_member!=1){  
            
      $total_active_user = DB::table('users')->where('is_member','1')->where('status','Active')->count();
      $total_inactive_user = DB::table('users')->where('is_member','1')->where('status','Inactive')->count();  
      $totaluser_list = DB::table('users')->where('is_member','1')->orderBy('id', 'DESC')->get(); 
     
      return view('home',compact('total_active_user','total_inactive_user','totaluser_list'));
        
    } else {
        $totaluser = DB::table('users')->where('is_member','1')->count();
    }
        return view('home',compact('totaluser'));
    }
}
