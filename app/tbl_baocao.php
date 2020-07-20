<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_baocao extends Model
{
    protected $table = "tbl_baocao";

    public function tbl_taikhoan()
    {
        return $this->belongsTo('App\tbl_taikhoan', 'nguoicapnhat');
    }

    public function tbl_chitietbaocao()
    {
        return $this->hasMany('App\tbl_chitietbaocao', 'baocao', 'id');
    }

    public function tbl_guibaocao()
    {
        return $this->hasMany('App\tbl_guibaocao', 'id', 'baocao');
    }
}
