<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_phanquyen extends Model
{
    protected $table = 'tbl_phanquyen';

    public function tbl_phongban()
    {
        return $this->belongsTo('App\tbl_phongban', 'phongban');
    }
}
