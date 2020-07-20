<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_baocaodinhky extends Model
{
    protected $table = 'tbl_baocaodinhky';

    public function tbl_chitietbaocaodinhky()
    {
        return $this->hasMany('App\tbl_chitietbaocaodinhky', 'mabaocaodinhky', 'id');
    }

    public function tbl_loaisolieu()
    {
        return $this->hasOne('App\tbl_loaisolieu', 'loaisolieu', 'id');
    }
}
