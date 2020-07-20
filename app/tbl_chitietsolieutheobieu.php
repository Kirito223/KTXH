<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_chitietsolieutheobieu extends Model
{
    protected $table = 'tbl_chitietsolieutheobieu';

    public function tbl_solieutheobieu()
    {
        return $this->belongsTo('App\User', 'mabieusolieu');
    }
    public function tbl_chitieu()
    {
        return $this->hasMany('App\tbl_chitieu', 'chitieu', 'id');
    }
        public function tbl_donvihanhchinh()
    {
        return $this->hasMany('App\tbl_donvihanhchinh', 'madonvi','madonvi');
    }

    public function tbl_chitieuses()
    {
        return $this->hasMany('App\tbl_chitieuses', 'id','chitieu');
    }
}
