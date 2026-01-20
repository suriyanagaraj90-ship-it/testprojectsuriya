<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Admin\Students;
class ValidEMail implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	private $error;
	private $Students;
	private $ID;
    public function __construct($ID=NULL)
    {
        //
		$this->ID=$ID;
		$this->Students=new Students();
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
		if($value==""){
			$error=1;$this->error=1;
		}elseif(!filter_var($value, FILTER_VALIDATE_EMAIL)){
			$error=1;$this->error=2;
		}
		elseif($this->Students->validate_email($value,$this->ID)==false){
			$error=1;$this->error=3;
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
			$return='The EMail field is required.';
		}
		elseif($this->error==2){
			$return='The EMail  field must be a valid email address.';
		}
		elseif($this->error==3){
			$return='The EMail has already been taken.';
		}
		return $return;
    }
}
