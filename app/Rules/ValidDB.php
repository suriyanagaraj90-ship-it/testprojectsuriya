<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\ValidUniqueModel;
class ValidDB implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $attribute;
    private $ValidUniqueModel;
    private $Filter;
    private $status;
    public function __construct($filter=null)
    {
        //
		$this->Filter=$filter;
        $this->ValidUniqueModel=new ValidUniqueModel();
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
        //
        $error=0;
        $this->attribute=$attribute;
        if($attribute=="STUDENTID"){
            $data=array("TABLE"=>"tbl_students");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"StudentID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
        }elseif($attribute=="STENROLLMENTNO"){
            $data=array("TABLE"=>"tbl_students");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"ENROLLMENTNO","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
        }elseif($attribute=="BATCHID"){
            $data=array("TABLE"=>"tbl_batch");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"BATCHID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
        }elseif($attribute=="COURSEID"){
            $data=array("TABLE"=>"tbl_courses");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"COURSEID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
        }elseif($attribute=="COURSESPLITID"){
            $data=array("TABLE"=>"tbl_course_split");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"COURSESPLITID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
        }elseif($attribute=="MULTISYLLABUS"){
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
					$data['WHERE'][]=array("COLUMN"=>"SLNO","CONDITION"=>"=","VALUE"=>$KeyValue);
					if($this->ValidUniqueModel->check_data($data)==false){
						$error=1;$this->status=1;return false;
					}
				}
			}else{
				$error=1;$this->status=2;
			}
			if($error==0){
				return true;
			}else{
				return false;
			}
		}elseif($attribute=="COURSESYLLABUSID"){
            $data=array("TABLE"=>"tbl_course_syllabus_mapping");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"MAPPINGID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
		}elseif($attribute=="STAFFID"){
            $data=array("TABLE"=>"tbl_staffs");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"STAFFID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
        }elseif($attribute=="MULTICOURSES"){
			if(count($value)>0){
				foreach($value as $KeyName=>$KeyValue){
					$data=array("TABLE"=>"tbl_courses");
					if($this->Filter!=null){
						if (is_array($data)){
							foreach($this->Filter as $KeyName1=>$KeyValue1){
								$data[$KeyName1]=$KeyValue1;
							}
						}
					}
					$data['WHERE'][]=array("COLUMN"=>"COURSEID","CONDITION"=>"=","VALUE"=>$KeyValue);
					if($this->ValidUniqueModel->check_data($data)==false){
						$error=1;$this->status=1;return false;
					}
				}
			}else{
				$error=1;$this->status=2;
			}
			if($error==0){
				return true;
			}else{
				return false;
			}
		}elseif($attribute=="COURSESTAFFMAPID"){
            $data=array("TABLE"=>"tbl_course_staff_mapping");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"MAPPINGID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
		}elseif($attribute=="SYLLABUSSTAFFMAPID"){
            $data=array("TABLE"=>"tbl_syllabus_staff_mapping");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"MAPPINGID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
		}elseif($attribute=="STUDENTCOURSEMAPID"){
            $data=array("TABLE"=>"tbl_student_course_mapping");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"MAPPINGID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
		}elseif($attribute=="COURSEBATCHMAPPINGID"){
            $data=array("TABLE"=>"tbl_batch_course_mapping");
            if($this->Filter!=null){
                if (is_array($data)){
                    foreach($this->Filter as $KeyName=>$KeyValue){
                        $data[$KeyName]=$KeyValue;
                    }
                }
            }
            $data['WHERE'][]=array("COLUMN"=>"MAPPINGID","CONDITION"=>"=","VALUE"=>$value);
            if($this->ValidUniqueModel->check_data($data)==false){
                $error=1;$this->status=1;
            }
            if($error==0){
                return true;
            }else{
                return false;
            }
		}
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->attribute=="STUDENTID"){
            if($this->status==1){
                return 'The Student Name field value Not Exists.';
            }
        }elseif($this->attribute=="COURSEID"){
            if($this->status==1){
                return 'The Course Name field value Not Exists.';
            }
        }elseif($this->attribute=="STENROLLMENTNO"){
			if($this->status==1){
                return 'The Enrollment Number field value Not Exists.';
            }
		}elseif($this->attribute=="MULTISYLLABUS"){
            if($this->status==1){
                return 'The Syllabus field value Not Exists.';
            }
			elseif($this->status==2){
                return 'The Syllabus field is required';
            }else{
				return 'The Syllabus field is required';
			}
        }elseif($this->attribute=="COURSESYLLABUSID"){
			return 'The Course Syllabus Mapping ID not exists.';
		}elseif($this->attribute=="STAFFID"){
            if($this->status==1){
                return 'The Staff Name field value Not Exists.';
            }
        }elseif($this->attribute=="MULTICOURSES"){
            if($this->status==1){
                return 'The Course field value Not Exists.';
            }
			elseif($this->status==2){
                return 'The Course field is required';
            }else{
				return 'The Course field is required';
			}
        }elseif($this->attribute=="COURSESTAFFMAPID"){
			return 'The Course Staff Mapping ID not exists.';
		}elseif($this->attribute=="SYLLABUSSTAFFMAPID"){
			return 'The Course Staff Mapping ID not exists.';
		}elseif($this->attribute=="COURSESPLITID"){
			return 'The Course Split ID not exists.';
		}elseif($this->attribute=="BATCHID"){
			return 'The Batch Name field value Not Exists.';
		}elseif($this->attribute=="COURSEBATCHMAPPINGID"){
			return 'The Batch Course Mapping Id Not Exists.';
		}else{
            return 'The validation error message.';
        }
		
    }
}
