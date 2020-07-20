<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class xa_donvi extends Model{

    protected $table = "xa_donvi";
    public function admin_users(){
        return $this->belongsTo('App\admin_users', 'user_id', 'id');
    }
   

}
