<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_donvitinh extends Model
{
    protected $table = 'tbl_donvitinh';

    public function tbl_chitieu()
    {
        return $this->belongsTo('App\tbl_chitieu', 'donvi');
    }
	public function tbl_chitieus()
    {
        // return $this->belongsTo('App\tbl_chitieu', 'donvi');
        return $this->hasMany('App\tbl_chitieu', 'donvi');
    }
}
