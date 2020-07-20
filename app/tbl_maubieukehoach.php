<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_maubieukehoach extends Model
{
    protected $table = 'tbl_maubieukehoach';

    public function thons()
    {
        return $this->hasMany('App\tbl_thonbieuii1', 'maubieuii1');
    }

    public function thanhviens() {
        return $this->morphMany('App\tbl_thanhvienbieuii1', 'thuocdonvi', 'tenloaidonvi', 'iddonvi');
    }

    public function lichcongtacs() {
        return $this->hasMany('App\tbl_lichcongtacbieuii2', 'maubieuii2' );
    }

    public function dexuats() {
        return $this->hasMany('App\tbl_kehoachbieuii4b', 'maubieuii4b' );
    }
}   
