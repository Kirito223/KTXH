<?php

namespace App\Http\Controllers;

use App\tbl_baocao;
use App\tbl_bieumau;
use App\tbl_chitietbaocao;
use App\tbl_chitietbieumau;
use App\tbl_chitietsolieutheobieu;
use App\tbl_solieutheobieu;
use DB;

class TestDataController extends Controller
{
    public function info()
    {
        
      DB::table('tbl_solieutheobieu')->delete();
       DB::table('tbl_chitietsolieutheobieu')->delete();
       DB::table('tbl_bieumau')->delete();
       DB::table('tbl_chitietbieumau')->delete();
       DB::table('tbl_baocao')->delete();
       DB::table('tbl_chitietbaocao')->delete();
        return json_encode('ok');
    }
}
