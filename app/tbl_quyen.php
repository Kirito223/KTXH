<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_quyen extends Model
{
    protected $table = 'tbl_quyen';

    public function nhomquyens() {
        return $this->belongsToMany('App\tbl_nhomquyen', 'tbl_nhomquyen_quyen', 'maquyen', 'manhomquyen');        
    }

    public function isQuyen(string $quyen) {
        return $this->tenquyen == $quyen;
    }

    public function routes() {
        return $this->belongsToMany('App\tbl_route', 'tbl_quyen_route', 'maquyen', 'maroute');        
    }

    public function hasRoute($route) {
        foreach($this->routes as $routeItem) {
            if($routeItem->route == $route) {
                return true;
            }
        }
        return false;
    }     
}
