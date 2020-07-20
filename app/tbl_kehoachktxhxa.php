<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_kehoachktxhxa extends Model
{
    protected $table = 'tbl_kehoachktxhxa';

    public function maubieus()
    {
        return $this->hasMany('App\tbl_maubieukehoach', 'kehoach');
    }
}
