<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validations extends Model
{
    use HasFactory;
    
    const VALIDATION_TYPE_EMAIL = 'email';
    const VALIDATION_TYPE_FORGOT_PWD = 'forgot_pwd';
    const IS_VALID = 0;
    const IS_EXPIRED = 1;
    
    public function getNewValidation($user_id, $landing_page = '/', $validation_type = self::VALIDATION_TYPE_EMAIL, $expire_at = null){
        $this->user_id = $user_id;
        $this->landing_page = $landing_page;
        $this->expire_at = $expire_at;
        $this->validation_string = $this->makeValidationString();
        $this->validation_type = $validation_type;
        $this->save();
        return $this;
    }
    
    private function makeValidationString($length = 15){
        $string = bin2hex(random_bytes($length)); 
        return $string; 
    }
    
    public function isExpired(){
        return $this->expired == self::IS_EXPIRED || (!is_null($this->expire_at) && strtotime($this->expire_at) < time());
    }
    
    public function expire(){
        $this->expired = self::IS_EXPIRED;
        $this->save();
    }
    
    public function extend($minutes = 10){
        $expire_at = \Carbon\Carbon::createFromTimeString($this->expire_at);
        $expire_at->addMinutes($minutes);
        $this->expire_at = $expire_at;
        $this->save();
    }
}
