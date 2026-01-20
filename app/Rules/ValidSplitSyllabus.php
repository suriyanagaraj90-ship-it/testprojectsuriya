<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidSplitSyllabus implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	private $CourseSplitModel;
	private $FilterData;
	private $ErrorStatus;
    public function __construct($CourseSplitModel,$FilterData)
    {
		$this->FilterData=$FilterData;
		$this->CourseSplitModel=$CourseSplitModel;
		$this->ErrorStatus=0;
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
		/*
			ERROR STATUS and Codes
			1 -> required
			2 -> Syllabus not exists
			3 -> Course not exists
			4 -> Syllabus not mapping This course
			5 -> Duration not valid
			6 -> Duration Exceeded
		*/
		$Duration=0;$TotalDuration=0;
		$result1=$this->CourseSplitModel->get_syllabus_mapping_course(array("COURSEID"=>$this->FilterData['COURSEID'],"VALIDATION"=>1));
		
		if(count($value)>0){
			for($i=0;$i<count($value);$i++){
				$result=$this->CourseSplitModel->get_Syllabus(array("SLNO"=>$value[$i]['SYLLABUSID']));
				if(count($result)<=0){
					$this->ErrorStatus=2;return false;
				}elseif(count($result1)<=0){
					$this->ErrorStatus=3;return false;
				}else{
					$TotalDuration=$result1['DURATION'];
					if(in_array($value[$i]['SYLLABUSID'],$result1['SYLLABUS'])){
						if(is_numeric($value[$i]['DURATION'])==false){
							$this->ErrorStatus=5;return false;
						}else{
							$Duration=floatval($Duration)+floatval($value[$i]['DURATION']);
						}
					}else{
						$this->ErrorStatus=4;return false;
					}
				}
			}
			if($Duration<=$TotalDuration){
				return true;
			}else{
				$this->ErrorStatus=6;return false;
			}
		}else{
			$this->ErrorStatus=1;return false;
		}
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {/*
			ERROR STATUS and Codes
			1 -> required
			2 -> Syllabus not exists
			3 -> Course not exists
			4 -> Syllabus not mapping This course
			5 -> Duration not valid
			6 -> Duration Exceeded
		*/
		$msg="";
		if($this->ErrorStatus==1){
			return "The Syllabus Duration Split fields required.";
		}elseif($this->ErrorStatus==2){
			return "The Syllabus Field is required.";
		}elseif($this->ErrorStatus==3){
			return "The Course Field is required.";
		}elseif($this->ErrorStatus==4){
			return "The Syllabus Value not mapping to this course.";
		}elseif($this->ErrorStatus==5){
			return "The Syllabus Duration field value is not value.";
		}elseif($this->ErrorStatus==6){
			return "The Split Time Duration Exceeded.";
		}
    }
}
