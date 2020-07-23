<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\quanlybieumau\NhaplieusolieuController;
use App\Http\Controllers\Ultils\ChitieuUltils;
use App\tbl_chitietbieumau;
use App\tbl_chitieu;
use App\tbl_donvihanhchinh;
use App\tbl_solieutheobieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class SummaryIndicatorReportController extends Controller
{
    public function viewReport()
    {
        return view('report\summaryindicatorsreport');
    }

    public function viewDaksong(){
        return view('baocaodaksong\index');
    }

    public function Summary(Request $request)
    {
        $currentYear = $request->year;
        $previuosYear = $currentYear - 1;
        $otherYear = $previuosYear - 1;
        $form = $request->form;
        $listUnitOfLocation = $this->getUnitOfLocation($request->location);
        $FormController = new NhaplieusolieuController();
        $arrChitieu = $FormController->showDeltalBieumauTH($request->form);
        // Make Tree chi tieu
        $dulieu = new stdClass();
        $Ultil = new ChitieuUltils();
        $TreeChitieu = $arrChitieu; //$Ultil->getTreeChitieu($arrChitieu);
        $datacha = tbl_chitietbieumau::where('bieumau', '=', $form)
            ->where('tbl_chitieu.idcha', null)
            ->where('tbl_chitietbieumau.isDelete', 0)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select(DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as id'), 'tbl_chitieu.tenchitieu', DB::raw('CAST(tbl_chitieu.idcha AS varchar(10)) as idcha'), 'tbl_donvitinh.tendonvi', DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as strid'))
            ->get();
        $datacha = $this->LocBieumau($TreeChitieu, $datacha);
        $datacha = $this->unique_array($datacha); //array_unique($datacha);

        $data = tbl_chitieu::with('childrenAll')->where('tbl_chitieu.IsDelete', 0)
            ->where('tbl_chitietbieumau.bieumau', '=', $form)
            ->whereNotNull('tbl_chitieu.idcha')
            ->join('tbl_chitietbieumau', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select(DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as id'), 'tbl_chitieu.tenchitieu', DB::raw('CAST(tbl_chitieu.idcha AS varchar(10)) as idcha'), 'tbl_donvitinh.tendonvi')
            ->get();

        $data = $this->LocBieumau($TreeChitieu, $data);

        $data = $this->unique_array($data);
        // Summary Report
        $Result = array();
        foreach ($TreeChitieu as $Chitieu) {
            $Item = new stdClass();
            $Item->id = $Chitieu->id;
            $Item->ten = $Chitieu->tenchitieu;
            $Item->idcha = $Chitieu->idcha;
            $Item->strid = strval($Chitieu->id);
            $Item->donvi = $Chitieu->tendonvi;
            $firstYear = $this->processForYear($Chitieu->id, $listUnitOfLocation, $otherYear, $form);
            if ($firstYear == null) {
                $Item->firstYear = 0;
            } else {
                $Item->firstYear = round($firstYear);
            }
            $twoYear = $this->processForYear($Chitieu->id, $listUnitOfLocation, $previuosYear, $form);
            if ($twoYear == null) {
                $Item->twoYear = 0;
            } else {
                $Item->twoyear = round($twoYear);
            }
            $threeYear = $this->processForYear($Chitieu->id, $listUnitOfLocation, $currentYear, $form);
            if ($threeYear == null) {
                $Item->threeYear = 0;
            } else {
                $Item->threeYear = round($threeYear);
            }
            array_push($Result, $Item);
        }

        $Result = $this->Loctrung($Result);
        $dulieu->nutcha = $datacha;
        $dulieu->nutcon = $data;
        $dulieu->chitiet = $Result;
        $dulieu->chitiet1 = $Result;
        $dulieu->chitiet2 = $Result;
        $dulieu->chitiet3 = $Result;
        return response()->json($dulieu);
    }

    public function unique_array($array)
    {
        $i = 0;
        while ($i < count($array) - 1) {
            $del = false;
            for ($y = $i + 1; $y < count($array); $y++) {
                if ($array[$i]->id == $array[$y]->id) {
                    $key = array_search($array[$y], $array);
                    array_splice($array, $y, 1);
                    $del = true;
                    break;
                }
            }
            $del ? $i = 0 : $i++;
        }
        return $array;
    }

    private function processForYear($Chitieu, $arrUnit, $Time, $form)
    {
        $Total = 0;
        foreach ($arrUnit as $Unit) {
            $Form = $this->getListTempalteOfUnit($Unit->id, $Time, $Time, $form);
            foreach ($Form as $formItem) {
                $sum = DB::table('tbl_chitietsolieutheobieu')
                    ->where('tbl_chitietsolieutheobieu.mabieusolieu', $formItem->id)
                    ->where('tbl_chitietsolieutheobieu.chitieu', $Chitieu)
                    ->where('tbl_chitietsolieutheobieu.isDelete', 0)
                // ->whereBetween('tbl_chitietsolieutheobieu.created_at', [$Time . '-01-01', $Time . '-12-31'])
                    ->sum('sanluong');
                $Total += $sum;
            }
        }
        return $Total;
    }
    # Lay danh sach cac don vi thuoc dia ban
    private function getUnitOfLocation($location)
    {
        $data = tbl_donvihanhchinh::where('tbl_donvihanhchinh.isDelete', 0)
            ->where('madonvi', $location)
            ->select('tbl_donvihanhchinh.id', 'tbl_donvihanhchinh.tendonvi')
            ->get();
        return $data;
    }

    private function getListTempalteOfUnit($unit, $startYear, $endYear, $form)
    {
        $data = tbl_solieutheobieu::where('tbl_solieutheobieu.donvinhap', $unit)
            ->where('tbl_solieutheobieu.isDelete', 0)
            ->where('bieumau', $form)
            ->where('tbl_solieutheobieu.namnhap', $startYear)
            ->get();
        return $data;
    }
    #Kiem tra xem chi tieu nao co ton tai thi se dua vao mang con khong thi thoi
    private function LocBieumau($arrMangchinh, $arrMangcon)
    {
        $Result = array();
        foreach ($arrMangcon as $con) {
            if ($this->checkExist($con, $arrMangchinh)) {
                array_push($Result, $con);
            }
        }
        return $Result;
    }

    private function Loctrung($mang)
    {
        for ($index = 0; $index < count($mang) - 1; $index++) {
            $item = $mang[$index];
            for ($itemy = $index + 1; $itemy < count($mang); $itemy++) {
                $itemss = $mang[$itemy];
                if ($itemss->id == $item->id) {
                    array_splice($mang, $itemy);
                    $index = 0;
                }
            }
        }
        return $mang;
    }
    private function checkExist($obj, $arr)
    {
        $Result = false;
        foreach ($arr as $value) {
            if ($value->id == $obj->id) {
                $Result = true;
                break;
            }
        }
        return $Result;
    }

    # Tong hop kinh te xa hoi huyen daksong

    public function BaocaoDaksong(Request $request)
    {
        $currentYear = $request->year;
        $previuosYear = $currentYear - 1;
        $form = $request->form;
        $listUnitOfLocation = $this->getUnitOfLocation($request->location);
        $FormController = new NhaplieusolieuController();
        $arrChitieu = $FormController->showDeltalBieumauTH($request->form);
        // Make Tree chi tieu
        $dulieu = new stdClass();
        $TreeChitieu = $arrChitieu; //$Ultil->getTreeChitieu($arrChitieu);
        $datacha = tbl_chitietbieumau::where('bieumau', '=', $form)
            ->where('tbl_chitieu.idcha', null)
            ->where('tbl_chitietbieumau.isDelete', 0)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select(DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as id'), 'tbl_chitieu.tenchitieu', DB::raw('CAST(tbl_chitieu.idcha AS varchar(10)) as idcha'), 'tbl_donvitinh.tendonvi', DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as strid'))
            ->get();
        $datacha = $this->LocBieumau($TreeChitieu, $datacha);
        $datacha = $this->unique_array($datacha);

        $data = tbl_chitieu::with('childrenAll')->where('tbl_chitieu.IsDelete', 0)
            ->where('tbl_chitietbieumau.bieumau', '=', $form)
            ->whereNotNull('tbl_chitieu.idcha')
            ->join('tbl_chitietbieumau', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select(DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as id'), 'tbl_chitieu.tenchitieu', DB::raw('CAST(tbl_chitieu.idcha AS varchar(10)) as idcha'), 'tbl_donvitinh.tendonvi')
            ->get();
        $data = $this->LocBieumau($TreeChitieu, $data);
        $data = $this->unique_array($data);
        // Summary Report
        $Result = array();
        foreach ($TreeChitieu as $Chitieu) {
            $Item = new stdClass();
            $Item->id = $Chitieu->id;
            $Item->ten = $Chitieu->tenchitieu;
            $Item->idcha = $Chitieu->idcha;
            $Item->strid = strval($Chitieu->id);
            $Item->donvi = $Chitieu->tendonvi;
            $firstYear = $this->processForYear($Chitieu->id, $listUnitOfLocation, $currentYear, $form);
            if ($firstYear == null) {
                $Item->firstYear = 0;
            } else {
                $Item->firstYear = round($firstYear);
            }
            $twoYear = $this->processForYear($Chitieu->id, $listUnitOfLocation, $previuosYear, $form);
            if ($twoYear == null) {
                $Item->twoYear = 0;
            } else {
                $Item->twoyear = round($twoYear);
            }
            
            array_push($Result, $Item);
        }

        $Result = $this->Loctrung($Result);
        $dulieu->nutcha = $datacha;
        $dulieu->nutcon = $data;
        $dulieu->chitiet = $Result;
        $dulieu->chitiet1 = $Result;
        $dulieu->chitiet2 = $Result;
        $dulieu->chitiet3 = $Result;
        return response()->json($dulieu);
    }
}
