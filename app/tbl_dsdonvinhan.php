<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_dsdonvinhan extends Model
{
    protected $table = 'tbl_dsdonvinhan';
    public function tbl_donvihanhchinh()
    {
        return $this->hasMany('App\tbl_donvihanhchinh', 'donvi', 'id');
    }
}
