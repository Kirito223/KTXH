<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_thanhvienbieuii1 extends Model
{
    protected $table = "tbl_thanhvienbieuii1";

    public function thuocdonvi() {
        return $this->morphTo('thuocdonvi', 'tenloaidonvi', 'iddonvi');
    }
}
