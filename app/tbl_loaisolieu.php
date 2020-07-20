<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_loaisolieu extends Model
{
    protected $table = 'tbl_loaisolieu';

    public function tbl_baocaodinhky()
    {
        return $this->belongsTo('App\tbl_baocaodinhky', 'loaisolieu');
    }
}
