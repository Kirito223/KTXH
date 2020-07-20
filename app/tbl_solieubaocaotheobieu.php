<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_solieubaocaotheobieu extends Model
{
    protected $table = "tbl_solieubaocaotheobieu";

    public function tbl_chitietsolieubaocaotheobieu()
    {
        return $this->hasMany('App\tbl_chitietsolieubaocaotheobieu', 'mabieusolieu', 'id');
    }
}
