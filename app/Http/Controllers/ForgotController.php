<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Authorizable;
use App\GeneralModel;
use App\Setting;
use Gate;
use DB;
use PDF;
use File;
use Illuminate\Http\Request;
use Auth;

class ForgotController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function index()
    {
       
        return view('auth.forgotpassword');
    }

    public function send_forgot_password(Request $request)

    {



        $email=$request->get('email');
        $user = DB::table('users')->where('email', $email)->first();        

        if(isset($user->id) && $user->id!="") {

            $sent=$user->email; 

           $setting=Setting::where('id','=','1')->first();

           $cc=$setting->email;     

   

$from_name='Forgot Password - Fool2Fool'; 

$website_name = 'Fool2Fool';

$from_email = $setting->email;

$url = url('/login');



        $body  ='<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr>



            <td align="center" valign="top">



                        <div id="template_header_image">



                                    </div>



                        <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #ffffff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0,0,0,0.1); border-radius: 3px;">



            <tr>



            <td align="center" valign="top">



                            <!-- Header -->



                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style="background-color: #1a5f35; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; border-radius: 3px 3px 0 0;"><tr>



            <td id="header_wrapper" style="padding: 36px 48px; display: block;background-color: #0080ff;color: white;text-align: center;">



                                    <h1 style="color: #ffffff; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1;">Forgot Password</h1>



                                </td>



                                </tr></table>



            <!-- End Header -->



            </td>



                        </tr>



            <tr>



            <td align="center" valign="top">



                            <!-- Body -->



                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body"><tr>



            <td valign="top" id="body_content" style="background-color: #ffffff;">



                                    <!-- Content -->



                                    <table border="0" cellpadding="20" cellspacing="0" width="100%"><tr>



            <td valign="top" style="padding: 48px 48px 0;">



                              <div id="body_content_inner" style="color: #636363; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;margin: 0 0 16px;"">                              <p>Hi  '.$user->name.',</p>



            



            <p>We have received a request to visit your password. If you did not make the request, just ignore this email. We recommend that you keep your password secure and not share it with anyone.<br><br></p>



            <p></p>';



             $body.='<p><b>Username : </b>'.$user->phone_no.'</p>';  

             $body.='<p><b>Password : </b>'.$user->password1.'</p>';

             $body.='<p>Please click here to navigate login page: </b><a href="'.$url.'">'.$url.'</a></p>'; 

             

             $body.='<br><br><p style="font-family:Verdana; font-size:10.5;">Regards by</p>



            <p style="font-family:Verdana; font-size:10.5;">Fool2Fool</p><br><br>



                              </div>



                            </td>



                          </tr></table>



        <!-- End Content -->



        </td>



                            </tr></table>



        <!-- End Body -->



        </td>



                    </tr>



        <tr>



        <td align="center" valign="top">



                  <!-- Footer -->



                  <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer"><tr>



        <td valign="top" style="padding: 0; -webkit-border-radius: 6px;">



                                <table border="0" cellpadding="10" cellspacing="0" width="100%"><tr>



        <td colspan="2" valign="right" id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #c09bb9; font-family: Arial; font-size: 12px; line-height: 125%; text-align: left;"></td><td colspan="2" valign="right" id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #c09bb9; font-family: Arial; font-size: 12px; line-height: 125%; text-align: right;">



                              <p>Fool2Fool</p>



                            </td>



                          </tr></table>



        </td>



                            </tr></table>



        <!-- End Footer -->



        </td>



                    </tr>



        </table>



        </td></tr></table>';

        

        $headers = "MIME-Version: 1.0" . "\r\n";



        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";// More headers



        $headers .= 'From: <'.$cc.'>' . "\r\n";



        $headers .= 'Cc: '.$cc.'' . "\r\n";



        //mail($sent,$from_name,$body,$headers);



       return redirect()->back()->with('success','Login info sent to your registered email'); 

   } else {



    return redirect()->back()->with('error','Email is not exist'); 



   }



    }


}
