<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_solieutheobieu extends Model
{
    protected $table = 'tbl_solieutheobieu';
    public function tbl_chitietsolieutheobieu()
    {
        return $this->hasMany('App\Comment', 'id', 'mabieusolieu');
    }
}
