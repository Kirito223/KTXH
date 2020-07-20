<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_tinh extends Model
{
    protected $table = 'tbl_tinh';
    public $timestamps = false;

    public function diabancon()
    {
        return $this->hasMany('App\tbl_quanhuyen', '_province_id', 'id');
    }
}
