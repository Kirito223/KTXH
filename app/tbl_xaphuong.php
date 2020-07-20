<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_xaphuong extends Model
{
    protected $table = 'tbl_xaphuong';
    public $timestamps = false;

    public function diabancha()
    {
        return $this->belongsTo('App\tbl_quanhuyen', '_district_id', 'id');
    }

}
