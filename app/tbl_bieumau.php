<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_bieumau extends Model
{
    protected $table = 'tbl_bieumau';

    public function tbl_chitietbieumau()
    {
        return $this->hasMany('App\tbl_chitietbieumau', 'bieumau', 'id');
    }
	public function tbl_donvihanhchinh()
	{
		return $this->belongsTo('App\tbl_donvihanhchinh', 'madonvi');
	}
}
