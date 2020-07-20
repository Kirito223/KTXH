<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_thongbao extends Model
{
    protected $table = 'tbl_thongbao';
	public function taikhoans() {
        return $this->belongsToMany('App\tbl_taikhoan', 'tbl_taikhoan_thongbao', 'mathongbao', 'mataikhoan')->withPivot('isSeen');
    }
}
