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
        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/Excel') . '/' . 'giaidoan_tinh.xlsx');
        $sheet->setActiveSheetIndex(0);
        $sheetSelect = $sheet->getActiveSheet();
        $rowstart = 453;
        $rowend = 484;
        $arr = array();
        for ($row = $rowstart; $row <= $rowend; $row++) {
            $chitieu = $sheetSelect->getCellByColumnAndRow(2, $row)->getValue();
            if (strlen($chitieu) > 0) {
                array_push($arr, $chitieu);
            }
        }
        return response()->json($arr);
    }
}
