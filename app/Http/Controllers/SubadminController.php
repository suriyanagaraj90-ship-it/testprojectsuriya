<?php

namespace App\Http\Controllers;

use App\Models\User;

use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;

use App\Authorizable;

use App\GeneralModel;

use App\Volunteers;

use App\Setting;

use Gate;

use DB;

use Twilio\Rest\Client;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;



class SubadminController extends Controller

{

    

    //use Authorizable;

    private $GeneralModel;



    public function __construct()

    {

        $this->GeneralModel=new GeneralModel();

    }



    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $result = User::where('is_member','0')->where('id','!=','1')->orderBy('id', 'DESC')->get();

        $roles = Role::where('name','User')->pluck('name', 'id');
       
        return view('subadmin.index', compact('result','roles'));

    }

    



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    

    public function create()

{

$rolesArr = Auth::user()->roles;

$user=DB::table('users')->get();

$roles = Role::pluck('name', 'id');

unset($roles[3]);

return view('subadmin.new',compact('user','roles'));

}



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

       

        $this->validate($request, [

            'name' => 'required',

            'phone_no' => 'required|max:12',

            'password' => 'required|min:6',

            'email' => 'required',

            'photo' => 'mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',

        ]);

    
$repeatid=User::where('phone_no',$request->get('phone_no'))->count();


    if($repeatid>=1){

   return redirect()->back()->with('error','Phone number already exist!');

    } else {

$created_by = Auth::user()->id;


$user=DB::table('users')->orderBy('id', 'DESC')->first();            


   if (request()->hasFile('photo')) 

        {

            

            $files = request()->file('photo');              

            $images = uniqid() . '_' . time() . '.' . $files->getClientOriginalExtension();

            $files->move('uploads/members/', $images);

            $image=$images; 

        }

        else

        {

            $image=null;

        }

       

        //$cour[]=array("name"=>$request->get('name'),"email"=>$request->get('email'),"phone_no"=>$request->get('phone_no'),"password1"=>$request->get('password'));

//$LogResult=$this->GeneralModel->save_log("User Added by ".Auth::user()->name."","USER","New",serialize($cour),'',Auth::user()->id);

        // Create the user

        if ( $user = User::create(["name"=>$request->get('name'),'password' => bcrypt($request->get('password')),'password1' => $request->get('password'),"phone_no"=>$request->get('phone_no'),"email"=>$request->get('email'),'is_member'=>'0','created_by'=>$created_by,'profile_picture'=>$image]) ) {

         $rolename=DB::table('roles')->where('id',$request->get('roles'))->first();  

        $role = Role::updateOrCreate(['name' => $rolename->name]);
         
        $permissions = Permission::pluck('id','id')->all();
       
        $role->syncPermissions($permissions);
         
        $user->assignRole([$role->id]);

            return redirect()->route('subadmin.index')->with('success','User has been created.');


        } else {

            return redirect()->route('subadmin.index')->with('error','Unable to create user.');

        }



    }

    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $user = User::find($id);

        $rolesArr = Auth::user()->roles;

        if(Auth::user()->id!=1) {

            $roles = Role::pluck('name', 'id');

            unset($roles[4]);

        } else {

            $roles = Role::where('id',1)->pluck('name', 'id');

        }
    

        return view('subadmin.edit', compact('user', 'roles'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {

        

           $this->validate($request, [

            'name' => 'required',

            'phone_no' => 'required|max:12|unique:users,phone_no,'.$id.',',

            'password' => 'required|min:6',

            'email' => 'required',

            'photo' => 'mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',


        ]);



        try

        {

      

        $user = User::findOrFail($id);

        $course_old=DB::table('users')->where('id',$id)->first();



        // Update user

        $user->fill($request->except('roles', 'permissions', 'password'));



        // merge values

        $request->merge(['updated_by' => Auth::user()->id]);

        

          if (request()->hasFile('photo')) 

         {

             

        $testy=DB::table('users')->where('id',$id)->first();

        $img=$testy->profile_picture;

        $image_path = "uploads/members/".$img;

        

        if (file_exists($image_path)) 

         {

            @unlink($image_path);

         }

             

            $files = request()->file('photo');              

            $images = uniqid() . '_' . time() . '.' . $files->getClientOriginalExtension();

            $files->move('uploads/members/', $images);

            $image=$images;

         } 

         

         else{

             

             $image=$request->get('old_photo');

         }

      
        // check for password change

        if($request->get('password')) {

            

            $user->password = bcrypt($request->get('password'));

            $user->password1 =$request->get('password');

            $user->profile_picture =$image;

            //$user->confirm = $request->get('confirm');

        }



        // Handle the user roles

        //$this->syncPermissions($request, $user);


        $rolename=DB::table('roles')->where('id',$request->get('roles'))->first();  

        $role = Role::updateOrCreate(['name' => $rolename->name]);
         
        $permissions = Permission::pluck('id','id')->all();
       
        $role->syncPermissions($permissions);

        $user->save();


        $rolesArr = Auth::user()->roles;


        //$course_new[]=array("name"=>$request->get('name'),"email"=>$request->get('email'),"phone_no"=>$request->get('phone_no'),"password1"=>$request->get('password'));

        

        //$LogResult=$this->GeneralModel->save_log("User updated by ".Auth::user()->name."","USER","Update",serialize($course_new),serialize($course_old),Auth::user()->id);

   
        return redirect()->back()->with('success','User has been updated.'); 

        }

         catch (\PDOException $e) {

            // print_r($e->getMessage()); exit;

            return redirect()->back()->with('error','Unable to create');

        }



    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int $id

     * @return \Illuminate\Http\Response

     * @internal param Request $request

     */

    public function destroy($id)

    {

       DB::table('users')->where('id',$id)->delete();

       return redirect()->back()->with('success','User has been deleted');


       /*$save=DB::table('users')->where('id',$id)->first();

  
       $check = DB::table('user_payment')->where('userid',$id)->where('amount',125)->count();

        if($check==3750)

        {

DB::table('users')->where('id',$id)->delete();
DB::table('user_payment')->where('user_id',$id)->delete();
DB::table('user_wallet')->where('userid',$id)->delete();


return redirect()->back()->with('success','User has been deleted');

    }

else

{

    return redirect()->back()->with('error','User is in progress. Level not completed.');

}  */  

    }





    /**

     * Sync roles and permissions

     *

     * @param Request $request

     * @param $user

     * @return string

     */

    private function syncPermissions(Request $request, $user)

    {

        // Get the submitted roles

        $roles = $request->get('roles', []);

        $permissions = $request->get('permissions', []);



        // Get the roles

        $roles = Role::find($roles);



        // check for current role changes

        if( ! $user->hasAllRoles( $roles ) ) {

            // reset all direct permissions for user

            $user->permissions()->sync([]);

        } else {

            // handle permissions

            $user->syncPermissions($permissions);

        }



        $user->syncRoles($roles);



        return $user;

    }

    public function filter_status($status)

    {

        if($status=='Active') {

            $result=User::where('is_member','4')->where('id','!=','1')->where('status','Active')->orderBy('id', 'DESC')->get();

          }

          

        else if($status=='Inactive') {

            $result=User::where('is_member','4')->where('id','!=','1')->where('status','Inactive')->orderBy('id', 'DESC')->get();   

            } 

              

        else {

           $result =User::where('is_member','4')->where('id','!=','1')->orderBy('id', 'DESC')->get();

         } 

        

        return view('user.index',compact('result'));

    }



}

