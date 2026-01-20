<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Admin\BatchAllocateModel;
class validBatchAllocateStudents implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	 private $Status=0;
	 private $filter;
	 private $BatchAllocateModel;
	 private $FormData;
	 private $attribute;
    public function __construct($FormData,$filter=null)
    {
		$this->filter=$filter;
		$this->FormData=$FormData;
		$this->BatchAllocateModel=new BatchAllocateModel();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
		$response=true;
		$this->attribute=$attribute;
		if($attribute=="STUDENTID"){
			$result=$this->BatchAllocateModel->get_Students($this->FormData);
			$tdata=array();
			if($result['success']==true ){
				for($i=0;$i<count($result['data']);$i++){
					$tdata[]=$result['data'][$i]['STUDENTID'];
				}
			}
			if(in_array($value,$tdata)){
				$response=true;
			}else{
				$response=false;
			}
		}
		elseif($attribute=="BATCHID"){
			$result=$this->BatchAllocateModel->get_Batch($this->FormData);
			$tdata=array();
			for($i=0;$i<count($result);$i++){
				$tdata[]=$result[$i]['BATCHID'];
			}
			if(in_array($value,$tdata)){
				$response=true;
			}else{
				$response=false;
			}
		}elseif($attribute=="BATCHTYPE"){
			if(($value!=0)&&($value!=1)){
				$response=false;
			}
		}
		return $response;
		/*$error=0;
		if(is_array($value)){
			for($i=0;$i<count($value);$i++){
				if($error==0){
					$tmp=str_replace("chkID__","",$value[$i]);
					$tmp=explode("__",$tmp);
					$data=array("STUDENTID"=>$tmp[1],"COURSEID"=>$tmp[2],"BATCHID"=>$this->AllData['BATCHID']);
					if($this->BatchAllocateModel->validate_Student_ID($data)==false){
						$error=1;$this->status=2;
					}
				}
			}
		}else{
			$this->status=1;
		}
		if($error==0){
			$result=$this->BatchAllocateModel->get_Batch($this->AllData);
			if(count($result)>0){
				$TotalMembers=$result[0]['MEMBERS'];
				$ExistsMembers=$result[0]['EXISTSMEMBERS'];
				$AddTo=count($this->AllData['STUDENTDETAILS']);
				$total=intval($TotalMembers)-intval(intval($ExistsMembers)+intval($AddTo));
				if($total<0){
					$error=1;$this->status=3;
				}
			}else{
				$error=1;$this->status=3;
			}
		}
		if($error==0){
			return true;
		}else{
			return false;
		}*/
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
		if($this->attribute=="BATCHTYPE"){
			return 'The Regular Batch Field value not valid';
		}
		elseif($this->attribute=="STUDENTID"){
			return 'The Student Name Field value not valid';
		}
		elseif($this->attribute=="BATCHID"){
			return 'The Batch Name Field value not valid';
		}
		else{
			return 'The Regular Batch Field value not valid';
		}
    }
}
