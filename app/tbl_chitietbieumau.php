<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_chitietbieumau extends Model
{
    protected $table = 'tbl_chitietbieumau';
    public function tbl_bieumau()
    {
        return $this->belongsTo('App\tbl_bieumau', 'bieumau');
    }


    public function tbl_chitieu()
    {
        return $this->belongsTo('App\tbl_chitieu', 'chitieu', 'id');
    }
}
