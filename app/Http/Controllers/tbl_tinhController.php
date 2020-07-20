<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_tinhRequest;
use App\tbl_tinh;

class tbl_tinhController extends Controller
{


    public function show($id)
    {
        $tbl_tinh = tbl_tinh::findOrFail($id);

        return response([$tbl_tinh], 200);
    }
}
