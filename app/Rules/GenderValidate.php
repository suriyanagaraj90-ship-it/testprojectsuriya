<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GenderValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	 private $error;
    public function __construct()
    {
        //
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
		$error=0;$Gender=strtolower($value);
		if($Gender==""){
			$error=1;$this->error=1;
		}elseif(($Gender!="male")&&($Gender!="female")&&($Gender!="others")){
			$error=1;$this->error=2;
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
		$return ="";
		if($this->error==1){
			$return='The Gender field is required.';
		}
		elseif($this->error==2){
			$return='The Gender field value is not valid';
		}
		return $return;
    }
}
