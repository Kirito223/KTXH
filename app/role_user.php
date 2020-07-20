<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role_user extends Model
{
      protected $table ="role_user";
	  public function role(){
        return $this->belongsTo('App\roles','role_id', 'id');
     }
  
}
