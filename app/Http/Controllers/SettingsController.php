<?php

namespace App\Http\Controllers;
use App\User;
use App\Setting;
use App\Authorizable;
use App\GeneralModel;
use App\Rules\ValidUnique;
use DB;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{


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
     
        $settings = Setting::where('user_id', 1)->get();
       
        $states = $this->GeneralModel->Get_State_List(array("COUNTRYID"=>101));
        $cities = $this->GeneralModel->Get_City_List(array("COUNTRYID"=>101));

        return view('settings.index', compact('settings','states','cities'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'address' => 'required',
            'address1' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ];

          $messages = [
            'required' => 'This field is required.'
         ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails())
        {
             return 'error'; 
        } else {

if($request->get('gst')!=''){
$gst = $request->get('gst');
}
else{
    $gst=0;
}



        $update_settings = Setting::where('id', '1')->update(array("address"=>$request->get('address'),"address1"=>$request->get('address1'),"phone1"=>$request->get('phone1'),"email"=>$request->get('email'),"phone"=>$request->get('phone'),"facebook_link"=>$request->get('facebook_link'),"twitter_link"=>$request->get('twitter_link'),"gplus_link"=>$request->get('gplus_link'),"pintrest_link"=>$request->get('pintrest_link'),"youtube_link"=>$request->get('youtube_link'),"updated_at"=>now()));

       
       /*$settings_old_data = Setting::where('user_id', Auth::user()->id)->first();       
    
        $settings_new_data[]=array(
                            'user_id' => Auth::user()->id,
                            'address'=>$request->get('address'),
                            'email'=>$request->get('email'),                           
                            'updated_by'=>Auth::user()->id,
                            "date"=>date('Y-m-d H:i:s'),
                        );

    $LogResult=$this->GeneralModel->save_log("Settings Added by ".Auth::user()->name."","Settings","update",serialize($settings_new_data),serialize($settings_old_data),Auth::user()->id);*/

   return redirect('settings')->with('success','Details updated successfully.');

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
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
       
    }


}
