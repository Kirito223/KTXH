<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class tbl_chitieu extends Model
{
    protected $table = "tbl_chitieu";

    protected $guarded = [];

    use NodeTrait;

    public function tbl_donvitinh()
    {
        // return $this->hasOne('App\tbl_donvitinh', 'donvi', 'id');
        return $this->belongsTo('App\tbl_donvitinh', 'donvi');
    }

    public function chitieucon()
    {
        return $this->hasMany('App\tbl_chitieu', 'idcha');
    }

    public function chitieucha()
    {
        return $this->belongsTo('App\tbl_chitieu', 'idcha');
    }

    public function donvihanhchinhs()
    {
        return $this->belongsToMany('App\tbl_donvihanhchinh', 'tbl_donvi_tieuchi', 'matieuchi', 'madonvi');
    }
    // public function tbl_chitietsolieutheobieu()
    // {
    //     return $this->hasMany('App\tbl_chitietsolieutheobieu', 'chitieu');
    // }



    public function children()
    {
        return $this->hasMany('App\tbl_chitieu', 'idcha', 'id');
    }
    public function parent()
    {
        return $this->belongsTo('App\tbl_chitieu', 'id');
    }
	
	public function childrenAll()
	{
		return $this->children()->with('childrenAll');
	}	
}
