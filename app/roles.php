<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
	protected $table ="roles";

	public function admin_users()
	{
		return $this->belongsToMany(admin_users::class);
	}
	 public function roleUser(){
        return $this->hasMany('App\role_user','role_id', 'id');
     }
}
