<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_phongban extends Model
{
    protected $table = 'tbl_phongban';

    public function tbl_phanquyen()
    {
        return $this->hasMany('App\tbl_phanquyen', 'id', 'phongban');
    }
	public function tbl_phongban()
    {
        return $this->hasMany('App\tbl_phongban', 'id', 'phongban');
    }
    public function donvihanhchinh() {
        return $this->belongsTo('App\tbl_donvihanhchinh', 'madonvi');
    }
	
	public function taikhoans() {
        return $this->hasMany('App\tbl_taikhoan', 'phongban');
    }
}
