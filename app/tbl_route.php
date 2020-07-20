<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_route extends Model
{
    protected $table = 'tbl_route';

    public function quyens() {
        return $this->belongsToMany('App\tbl_quyen', 'tbl_quyen_route', 'maroute', 'maquyen');        
    }
}
