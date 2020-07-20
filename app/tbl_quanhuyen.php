<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_quanhuyen extends Model
{
    protected $table = 'tbl_quanhuyen';
    public $timestamps = false;

    public function diabancon()
    {
        return $this->hasMany('App\tbl_xaphuong', '_district_id', 'id');
    }
    public function diabancha()
    {
        return $this->belongsTo('App\tbl_tinh', '_province_id', 'id');
    }
}
