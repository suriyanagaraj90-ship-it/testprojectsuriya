<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\ValidUniqueModel;
class validCourseSyllabusMapEdit implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $ValidUniqueModel;
	private $Filter;
	private $status;
	private $MAPID;
	private $COURSEID;
	private $Id;
	private $Exists;
    public function __construct($MAPID,$COURSEID,$Filter=null)
    {
        //
        $this->ValidUniqueModel=new ValidUniqueModel();
		$this->Filter=$Filter;
		$this->MAPID=$MAPID;
		$this->COURSEID=$COURSEID;
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
		$error=0;$this->status=0;
		if(count($value)>0){
			foreach($value as $KeyName=>$KeyValue){
				$data=array("TABLE"=>"tbl_syllabus");
				if($this->Filter!=null){
					if (is_array($data)){
						foreach($this->Filter as $KeyName1=>$KeyValue1){
							$data[$KeyName1]=$KeyValue1;
						}
					}
				}
				$this->Id=$KeyValue;
				$data['WHERE'][]=array("COLUMN"=>"SLNO","CONDITION"=>"=","VALUE"=>$KeyValue);
				if($this->ValidUniqueModel->check_data($data)==false){
					$error=1;$this->status=1;return false;
				}
			}
		}else{
			$error=1;$this->status=2;
		}
		$checkdata=$this->ValidUniqueModel->validate_syllabus_id_in_Course_Split(array("NEWDATA"=>$value,"COURSEID"=>$this->COURSEID,"MAPPINGID"=>$this->MAPID));
		if(count($checkdata)>0){
			$tval="";
			foreach($checkdata as $keyname=>$keyvalue){
				if($tval==""){
					$tval.=$keyvalue;
				}else{
					$tval.=", ".$keyvalue;
				}
			}
			$error=1;$this->status=3;
			$this->Exists=$tval;
		}
		if($error==0){
			return true;
		}else{
			return false;
		}
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
		if($this->status==1){
			return 'The Syllabus field value Not Exists.';
        }
		elseif($this->status==2){
            return 'The Syllabus field is required';
        }
		elseif($this->status==3){
			return "You've to remove ".$this->Exists." syllabus from course split under ".$this->ValidUniqueModel->get_course_details($this->COURSEID)." Course first";
        }else{
			return 'The Syllabus field is required';
		}
    }
}
