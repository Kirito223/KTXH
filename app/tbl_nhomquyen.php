<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_nhomquyen extends Model
{
    protected $table = 'tbl_nhomquyen';

    public function taikhoans() {
        return $this->belongsToMany('App\tbl_taikhoan', 'tbl_taikhoan_nhomquyen', 'manhomquyen', 'mataikhoan');        
    }

    public function quyens() {
        return $this->belongsToMany('App\tbl_quyen', 'tbl_nhomquyen_quyen', 'manhomquyen', 'maquyen');        
    }
	
	public function hasQuyen(string $permission) 
    {
        foreach($this->quyens as $quyen) {
            if($quyen->tenquyen == $permission) {
                return true;
            }
        }
        return false;
    }
	
	public function hasRoute(string $route){
        foreach($this->quyens as $quyen) {
            if($quyen->hasRoute($route)) {
                return true;
            }
        }
        return false;
    }
}
