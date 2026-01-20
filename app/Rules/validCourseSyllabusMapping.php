<?php

namespace App\Rules;
use App\Admin\TopicsModel;
use Illuminate\Contracts\Validation\Rule;

class validCourseSyllabusMapping implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	private $CourseID;
    public function __construct($CourseID)
    {
        $this->CourseID=$CourseID;
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
        $TopicsModel=new TopicsModel();
		$result=$TopicsModel->Get_Syllabus(array("COURSEID"=>$this->CourseID));$SyllabusID=array();
		if(count($result)>0){
			for($i=0;$i<count($result);$i++){
				$SyllabusID[]=$result[$i]['SLNO'];
			}
		}
		if(in_array($value,$SyllabusID)){
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
        return "This Syllabus not valid under this course";
    }
}
