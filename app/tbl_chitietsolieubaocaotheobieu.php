<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_chitietsolieubaocaotheobieu extends Model
{
    protected $table = "tbl_chitietsolieubaocaotheobieu";

    public function tbl_solieubaocaotheobieu()
    {
        return $this->belongsTo('App\tbl_solieubaocaotheobieu', 'mabieusolieu');
    }
}
