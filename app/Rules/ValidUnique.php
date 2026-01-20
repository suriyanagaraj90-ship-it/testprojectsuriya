<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\ValidUniqueModel;

use Illuminate\Support\Facades\Validator;
class ValidUnique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	 private $Filter;
	 private $status;
     private $Valid_Unique_Model;
     private $error;
    public function __construct($filter)
    {
        //
		$this->Filter=$filter;
		$this->Valid_Unique_Model=new ValidUniqueModel();
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
		if($this->Valid_Unique_Model->Validate_Unique($this->Filter)==false){
			$error=1;$this->error=1;
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
		if($this->error==1){
			return 'This value has already been taken.';
		}
    }
}
