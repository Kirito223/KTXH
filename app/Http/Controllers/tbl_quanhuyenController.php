<?php

namespace App\Http\Controllers;

use App\devvn_quanhuyen;
use App\devvn_xaphuongthitran;
use App\Http\Controllers\Ultils\Ultils;
use App\Http\Requests\tbl_quanhuyenRequest;
use App\tbl_quanhuyen;
use DB;

class tbl_quanhuyenController extends Controller
{
    public function listHuyen()
    {
        $idProvince = session('idprovince');
        $list = tbl_quanhuyen::where('_province_id', $idProvince)
            ->where('tbl_quanhuyen.isDelete', 0)
            ->get();
        return response()->json($list);
    }

    public function listDistrictWithProvince($id)
    {
        $list = tbl_quanhuyen::where('_province_id', $id)
            ->where('tbl_quanhuyen.isDelete', 0)
            ->get();
        return response()->json($list);
    }
}
