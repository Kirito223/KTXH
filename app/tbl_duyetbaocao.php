<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_duyetbaocao extends Model
{
    protected $table = 'tbl_duyetbaocao';

    public function tbl_taikhoan()
    {
        return $this->hasOne('App\tbl_taikhoan', 'nguoipheduyet', 'id');
    }
    public function tbl_baocaodinhky()
    {
        return $this->hasOne('App\tbl_baocaodinhky', 'mabaocao', 'id');
    }
}
