<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    //

    public function logo()
     {
         if($this->logo)
         {
            return $this->logo;
         }else{
            return 'medies/no-logo.png';
         }
     }
 
     public function favicon()
     {
         if($this->favicon)
         {
            return $this->favicon;
         }else{
            return 'medies/no-favicon.png';
         }
     }
}
