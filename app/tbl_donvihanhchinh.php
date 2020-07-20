<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_donvihanhchinh extends Model
{
    protected $table = 'tbl_donvihanhchinh';

    public function tbl_taikhoan()
    {
        return $this->hasMany('App\tbl_taikhoan', 'donvi', 'id');
    }
    public function tbl_donvihanhchinh()
    {
        return $this->belongsTo('App\tbl_donvihanhchinh', 'donvi');
    }
    public function donvihanhchinhcon()
    {
        return $this->hasMany('App\tbl_donvihanhchinh', 'madonvi');
    }
    public function donvihanhchinhcha()
    {
        return $this->belongsTo('App\tbl_donvihanhchinh', 'madonvi');
    }

    public function phongbans()
    {
        return $this->hasMany('App\tbl_phongban', 'madonvi');
    }

    public function chitieus()
    {
        return $this->belongsToMany('App\tbl_chitieu', 'tbl_donvi_tieuchi', 'madonvi', 'matieuchi');
    }
    public function tbl_bieumau()
    {
        return $this->hasMany('App\tbl_bieumau', 'madonvi', 'id');
    }
    public function tbl_guibaocao()
    {
        return $this->hasMany('App\tbl_guibaocao', 'donvinhan', 'id');
    }
}
