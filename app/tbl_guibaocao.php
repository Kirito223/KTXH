<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_guibaocao extends Model
{
    protected $table = 'tbl_guibaocao';
    public function tbl_baocao()
    {
        return $this->belongsTo('App\tbl_baocao', 'baocao');
    }
    public function tbl_donvihanhchinh()
    {
        return $this->belongsTo('App\tbl_donvihanhchinh', 'donvinhan');
    }

    public function tbl_taikhoan()
    {
        return $this->belongsTo('App\tbl_taikhoan', 'nguoigui');
    }
}
