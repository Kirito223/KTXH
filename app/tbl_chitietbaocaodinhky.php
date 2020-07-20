<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_chitietbaocaodinhky extends Model
{
    protected $table = 'tbl_chitietbaocaodinhky';

    public function tbl_baocaodinhky()
    {
        return $this->belongsTo('App\tbl_baocaodinhky', 'mabaocaodinhky');
    }
}
