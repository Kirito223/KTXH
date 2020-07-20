<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_thonbieuii1 extends Model
{
    protected $table = "tbl_thonbieuii1";
    protected $fillable = ['tenthon'];
    public function thanhviens() {
        return $this->morphMany('App\tbl_thanhvienbieuii1', 'thuocdonvi', 'tenloaidonvi', 'iddonvi');
    }
}
