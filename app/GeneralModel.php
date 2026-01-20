<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Response;
use DB;
use App\Setting;
use Illuminate\Support\Facades\Auth;

class GeneralModel extends Model
{
	public function Random_String($len)
    {
        $validCharacters = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuXxYyVvWwZz1234567890";
        $validCharNumber = strlen($validCharacters);
        $result =""; 

        for ($i = 0; $i < $len; $i++)
        {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        } 
        return $result;   
    } 

    public function forgot_send_sms($mobile_number,$message) {
        $ch = curl_init(); 
        curl_setopt($ch,CURLOPT_URL, config('app.SMS_HOST'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "apikey=".config('app.SMS_API_KEY')."&clientId=".config('app.SMS_CLIENT_ID')."&msisdn=".$mobile_number."&sid=".config('app.SMS_SENDOR_ID')."&msg=".$message."&fl=".config('app.SMS_FL')."&gwid=".config('app.SMS_GWID'));
        $response = json_decode(curl_exec($ch),true);
        curl_close($ch); 

        $mobile_number_count=explode(',', $mobile_number);
        $sms_count=count($mobile_number_count); 

        $user = User::where('phone_no',$mobile_number)->first();     

        $auth_user_sms_count=$user->sms_count-$sms_count;

        $update_user=DB::table('users')->where('id', $user->id)->update(['sms_count' => $auth_user_sms_count]);  

        $Result=DB::table('smslog')->insert(array("mobile_number"=>$mobile_number,"message"=>$message,"smslog"=>serialize($response), "created_by"=>$user->id, "created_at"=>date("Y-m-d H:i:s")));
        if($Result){$status=true;}else{$status=false;}  
        return $status;
    }
    
    public function send_sms($mobile_number,$message) {

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, config('app.SMS_HOST'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "apikey=".config('app.SMS_API_KEY')."&clientId=".config('app.SMS_CLIENT_ID')."&msisdn=".$mobile_number."&sid=".config('app.SMS_SENDOR_ID')."&msg=".$message."&fl=".config('app.SMS_FL')."&gwid=".config('app.SMS_GWID'));
        $response = json_decode(curl_exec($ch),true);   
        curl_close($ch);

        $mobile_number_count=explode(',', $mobile_number);
        $sms_count=count($mobile_number_count);  

        $user = User::where('phone_no',Auth::user()->phone_no)->first();     

        $auth_user_sms_count=$user->sms_count-$sms_count;

        $update_user=DB::table('users')->where('id', $user->id)->update(['sms_count' => $auth_user_sms_count]);    

        $Result=DB::table('smslog')->insert(array("mobile_number"=>$mobile_number,"message"=>$message,"smslog"=>serialize($response),"created_by"=>Auth::user()->id, "created_at"=>date("Y-m-d H:i:s")));
        if($Result){$status=true;}else{$status=false;}
        return $status;
    }

    public function generateMemberId() 
    {
        $memberObj = User::select('userid')->where('is_member','1')->where('created_by', Auth::user()->id)->orderBy('id', 'DESC')->first();
        $current_year = date('Y');
        //echo "<pre>"; print_r($memberObj);  exit;
        if ($memberObj) {
            $memberNo = $memberObj->userid; 
            $removed4char = substr($memberNo, 5);
            $year = substr($memberNo, 1,4);
            $generateMember_no = $memberNo+1;            
        } else {
            $generateMember_no = '1';//str_pad(1, 2, "0", STR_PAD_LEFT);
        }
        return $generateMember_no;     
    }

    public function generateStaffId()
    {
        $memberObj = User::select('userid')->where('is_member','2')->where('created_by', Auth::user()->id)->orderBy('id', 'DESC')->first();
        $current_year = date('Y');
        //echo "<pre>"; print_r($memberObj); exit;

        $settings = Setting::where('user_id', Auth::user()->id)->latest()->paginate();

        $prefix = isset($settings[0]->staff_prefix) ? $settings[0]->staff_prefix : '';

        if ($memberObj) {
            $memberNo = $memberObj->userid; 
            $removed4char = str_replace($prefix,"",$memberNo); 
            $generateMember_no = str_pad($removed4char+1, 2, "0", STR_PAD_LEFT);          
        } else {
            $generateMember_no = str_pad(1, 2, "0", STR_PAD_LEFT);
        }
        return $prefix.$generateMember_no;    
    }

    public function generateEnqNo() {
        $enquiryObj = Enquiry::select('enquiry_id')->where('created_by', Auth::user()->id)->orderBy('id', 'DESC')->first();
        $current_year = date('Y');
        
        $settings = Setting::where('user_id', Auth::user()->id)->latest()->paginate();

        $prefix = isset($settings[0]->invoice_prefix) ? $settings[0]->enquiry_prefix : '';

        if ($enquiryObj) {
            $enquiryNo = $enquiryObj->enquiry_id;
            $removed4char = substr($enquiryNo, strlen($prefix));
            $year = substr($enquiryNo, 1,4);
            //if($year<$current_year) {
                $generateEnquiry_no = $prefix. str_pad($removed4char + 1, 3, "0", STR_PAD_LEFT);
            //}
            //else {
                //$generateEnquiry_no = $stpad = $prefix. str_pad($removed4char + 1, 3, "0", STR_PAD_LEFT);
            //}
            
        } else {
            $generateEnquiry_no = $prefix . $current_year. str_pad(1, 3, "0", STR_PAD_LEFT);
        }
        return $generateEnquiry_no; 
    }

    public function generateWithoutGstInvoice() {
        $paymentObj = DB::table('membership_payment AS mp') 
        	->select('mp.invoice_no')
            ->leftJoin('users AS u', 'u.id', '=', 'mp.member_id')
        	->whereNull('mp.gst_no')
            ->where('u.created_by', Auth::user()->id)
            ->orderBy('mp.id', 'DESC')->first();
        $current_year = (date('m')<'04') ? date('Y',strtotime('-1 year')) : date('Y'); //date('Y');
        $settings = Setting::where('user_id', Auth::user()->id)->latest()->paginate();

        $prefix = isset($settings[0]->invoice_prefix) ? $settings[0]->invoice_prefix : '';
        
        if ($paymentObj) {
            $invoiceNo = $paymentObj->invoice_no;
            $removed4char = substr($invoiceNo, 7);
            $year = substr($invoiceNo, strlen($prefix),4);
            if($year<$current_year) {
                $generateInvno = $prefix.$current_year. str_pad($removed4char + 1, 5, "0", STR_PAD_LEFT);
            }
            else {
                $generateInvno = $prefix.$year. str_pad($removed4char + 1, 5, "0", STR_PAD_LEFT);
            }
            
        } else {
            $generateInvno = $prefix.$current_year. str_pad(1, 5, "0", STR_PAD_LEFT);
        }

       
        return $generateInvno; 
    }


    public function generateWithGstInvoice() {
        $paymentObj = DB::table('membership_payment AS mp') 
        	->select('mp.invoice_no')
            ->leftJoin('users AS u', 'u.id', '=', 'mp.member_id')
        	->where('mp.gst_no', '!=', '')
            ->where('u.created_by', Auth::user()->id)
            ->orderBy('mp.id', 'DESC')->first();
        $current_year = (date('m')<'04') ? date('Y',strtotime('-1 year')) : date('Y'); //date('Y');
        $settings = Setting::where('user_id', Auth::user()->id)->latest()->paginate();

        $prefix = isset($settings[0]->invoice_prefix) ? $settings[0]->invoice_prefix : '';
        //print_r($paymentObj);
        if ($paymentObj) {
            $invoiceNo = $paymentObj->invoice_no; 
            $dbval = str_replace($prefix,"",$invoiceNo); 
            $removed4char = substr($dbval, 4);
            $year = substr($invoiceNo, strlen($prefix),4);
            if($year<$current_year) {
                $generateInvno = $prefix.$current_year. str_pad($removed4char + 1, 3, "0", STR_PAD_LEFT);
            }
            else {
                //echo $removed4char; exit;
                $generateInvno = $prefix.$year. str_pad($removed4char + 1, 3, "0", STR_PAD_LEFT);
            }
            
        } else {
            $generateInvno = $prefix.$current_year. str_pad(1, 3, "0", STR_PAD_LEFT);
        }

        return $generateInvno; 
    }

    public function Get_Countries_List($data=null){
		$sql="SELECT ID,SORTNAME,NAME,PHONECODE FROM countries WHERE 1=1";
		if(is_array($data)){
			if(array_key_exists("ID",$data)){ $sql.=" AND ID='".$data['ID']."'";}
			if(array_key_exists("COUNTRYID",$data)){ $sql.=" AND ID='".$data['COUNTRYID']."'";}
			if(array_key_exists("COUNTRYNAME",$data)){ $sql.=" AND NAME='".$data['COUNTRYNAME']."'";}
			if(array_key_exists("NAME",$data)){ $sql.=" AND NAME='".$data['NAME']."'";}
		}
		$sql.=" ORDER BY NAME";
		$result=DB::select($sql);$return=array();
		if(count($result)>0){
			for($i=0;$i<count($result);$i++){
				$return[]=array(
							"COUNTRYID"=>$result[$i]->ID,
							"SHORTNAME"=>$result[$i]->SORTNAME,
							"COUNTRYNAME"=>$result[$i]->NAME,
							"PHONECODE"=>$result[$i]->PHONECODE,
						);
			}
		}
		return $return;
	}
	public function Get_State_List($data=null){
		$sql="SELECT ID,NAME FROM states WHERE 1=1";
		if(is_array($data)){
			if(array_key_exists("ID",$data)){ $sql.=" AND ID='".$data['ID']."'";}
			if(array_key_exists("STATEID",$data)){ $sql.=" AND ID='".$data['STATEID']."'";}
			if(array_key_exists("STATENAME",$data)){ $sql.=" AND NAME='".$data['STATENAME']."'";}
			if(array_key_exists("NAME",$data)){ $sql.=" AND NAME='".$data['NAME']."'";}
			if(array_key_exists("COUNTRYID",$data)){ $sql.=" AND COUNTRY_ID='".$data['COUNTRYID']."'";}
		}
		$sql.=" ORDER BY NAME";
		$result=DB::select($sql);$return=array();
		if(count($result)>0){
			for($i=0;$i<count($result);$i++){
				$return[]=array(
							"STATEID"=>$result[$i]->ID,
							"STATENAME"=>$result[$i]->NAME,
						);
			}
		}
		return $return;
	}
	public function Get_City_List($data=null){
		$sql="SELECT ID,NAME FROM cities WHERE 1=1";
		if(is_array($data)){
			if(array_key_exists("ID",$data)){ $sql.=" AND ID='".$data['ID']."'";}
			if(array_key_exists("CITYID",$data)){ $sql.=" AND ID='".$data['CITYID']."'";}
			if(array_key_exists("CITYNAME",$data)){ $sql.=" AND NAME='".$data['CITYNAME']."'";}
			if(array_key_exists("NAME",$data)){ $sql.=" AND NAME='".$data['NAME']."'";}
			if(array_key_exists("STATEID",$data)){ $sql.=" AND STATE_ID='".$data['STATEID']."'";}
		}
		$result=DB::select($sql);$return=array();
		if(count($result)>0){
			for($i=0;$i<count($result);$i++){
				$return[]=array(
							"CITYID"=>$result[$i]->ID,
							"CITYNAME"=>$result[$i]->NAME,
						);
			}
		}
		return $return;
	}
    public function Get_education_details($data=null){
        $sql="SELECT id,name FROM education_details WHERE 1=1 ORDER BY name";

        $result=DB::select($sql);$return=array();
        if(count($result)>0){
            for($i=0;$i<count($result);$i++){
                $return[]=array(
                            "id"=>$result[$i]->id,
                            "name"=>$result[$i]->name,
                        );
            }
        }
        return $return;
    }

    public function Get_occupation_details($data=null){
        $sql="SELECT id,name FROM occupation WHERE 1=1 ORDER BY name";

        $result=DB::select($sql);$return=array();
        if(count($result)>0){
            for($i=0;$i<count($result);$i++){
                $return[]=array(
                            "id"=>$result[$i]->id,
                            "name"=>$result[$i]->name,
                        );
            }
        }
        return $return;
    }

    public function Get_company_category($data=null){
        $sql="SELECT id,name FROM company_category WHERE 1=1 ORDER BY name";

        $result=DB::select($sql);$return=array();
        if(count($result)>0){
            for($i=0;$i<count($result);$i++){
                $return[]=array(
                            "id"=>$result[$i]->id,
                            "name"=>$result[$i]->name,
                        );
            }
        }
        return $return;
    }

     public function Get_all_projects($data=null){
        $sql="SELECT id,project_name,address FROM projects WHERE 1=1 ORDER BY project_name";

        $result=DB::select($sql);$return=array();
        if(count($result)>0){
            for($i=0;$i<count($result);$i++){
                $return[]=array(
                            "id"=>$result[$i]->id,
                            "project_name"=>$result[$i]->project_name,
                            "address"=>$result[$i]->address,
                        );
            }
        }
        return $return;
    }

	public function get_plan_details($id){
		$sql="SELECT * FROM membership_package WHERE id='".$id."'";
		$result=DB::select($sql);$return=array();
		return $result;
	}
	public function getTax($data=null){

		$return=array();

		$sql="SELECT AUTOID,TAX_NAME,TAX_PERCENTAGE FROM tax_per WHERE 1=1";

		
		$result=DB::select($sql);

		if(count($result)>0){

			for($i=0;$i<count($result);$i++){

				$return[]=array("ID"=>$result[$i]->TAX_PERCENTAGE,"TAXNAME"=>$result[$i]->TAX_NAME);

			}

		}

		return $return;

	}
public function get_state($id){
 $sql="SELECT * FROM states WHERE id='".$id."'";
 $result=DB::select($sql);$return=array();
 return $result[0]->name;
}
public function get_city($id){
 $sql="SELECT * FROM cities WHERE id='".$id."'";
 $result=DB::select($sql);$return=array();
 return $result[0]->name;
}
function get_customer_info($id){
 $sql="SELECT * FROM users WHERE fb_id='".$id."'";
 $result=DB::select($sql);$return=array();
 return $result;
}
function get_member_details($id){
 $sql="SELECT * FROM users WHERE id='".$id."'";
 $result=DB::select($sql);$return=array();
 return $result;
}
function get_member_payment_details($id){
 $sql="SELECT * FROM membership_payment WHERE member_id='".$id."'";
 $result=DB::select($sql);$return=array();
 return $result;
}
function settings(){
$sql="SELECT * FROM settings WHERE id=1 ";
 $result=DB::select($sql);$return=array();
 return $result;
}
function save_log($description,$module,
$action,$newdata,$olddata,$userid){
DB::table('log_activity')->insert(array("description"=>$description,"modulename"=>$module,"action"=>$action,"olddata"=>$olddata,"newdata"=>$newdata,"userid"=>$userid,"logtime"=>date("Y-m-d H:i:s")));
}
}
