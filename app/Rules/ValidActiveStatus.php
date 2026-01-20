<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidActiveStatus implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	 private $Status;
    public function __construct()
    {
        $this->Status=0;
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
		$error=0;
        if(($value!=0)&&($value!=1)){
			$error=1;$this->Status=1;
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
		if($this->Status==1){
			return 'The Active Status Field Value is not valid.';
		}
    }
}
