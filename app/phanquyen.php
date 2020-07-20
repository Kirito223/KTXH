<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class phanquyen extends Model{
    protected $table ="phanquyen";
    public function admin_users(){
        return $this->belongsTo('App\admin_users', 'user_id', 'id');
    }
}