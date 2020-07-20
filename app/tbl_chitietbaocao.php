<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_chitietbaocao extends Model
{
    protected $table = "tbl_chitietbaocao";
    public function tbl_baocao()
    {
        return $this->belongsTo('App\tbl_baocao', 'baocao');
    }
}
