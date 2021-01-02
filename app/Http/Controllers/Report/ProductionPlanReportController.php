<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\quanlybieumau\NhaplieusolieuController;
use App\Http\Controllers\Ultils\ChitieuUltils;
use App\tbl_chitietbieumau;
use App\tbl_chitieu;
use App\tbl_loaisolieu;
use App\tbl_bieumau;
use App\tbl_chitietsolieutheobieu;
use App\tbl_donvihanhchinh;
use App\tbl_solieutheobieu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;



use stdClass;
use Session;

class ProductionPlanReportController extends Controller
{
    public function viewProductionPlan()
    {
        return view('report\productionplanreport');
    }
    public function viewdubaosolieu()
    {
        return view('report\dubaosolieu');
    }
    public function viewsanxuat()
    {
        return view('report\sanxuat');
    }

    public function getDetailTemplate($id, $tblChitietbieumau)
    {
        $result = array();
        foreach ($tblChitietbieumau as $item) {
            if ($item->bieumau == $id) {
                array_push($result, $item);
            }
        }

        return $result;
    }

    public function checkvalueExist($arr, $id)
    {
        foreach ($arr as $value) {
            if ($value->id == $id) {
                return true;
            }
        }
        return false;
    }

    private function getInfochitieu($id, $tblchitieu)
    {
        $result = null;
        foreach ($tblchitieu as $item) {
            if ($item->id == $id) {
                $result = $item;
                break;
            }
        }
        return $result;
    }

    // Tao cay chi tieu
    public function getTreeChitieu($arrChitieu, $tblchitieu)
    {
        $result = array();
        foreach ($arrChitieu as $item) {
            $ParentId = $item->idcha;
            $obj = new stdClass();
            $obj->id = $item->id; // Id of chi tieu
            $obj->ten = $item->tenchitieu;
            $obj->donvi = $item->tendonvi;
            $obj->idcha = $ParentId;
            array_push($result, $obj);
            $idFind = $ParentId;
            if ($ParentId != null) {
                // Find Parent node of this child node
                while ($idFind != null) {
                    $valueExist = $this->checkvalueExist($result, $idFind);
                    if ($valueExist == false) {
                        $chitieuf = $this->getInfochitieu($idFind, $tblchitieu);
                        $obj = new stdClass();
                        $obj->id = $chitieuf->id; // Id cua chi tieu;
                        $obj->ten = $chitieuf->tenchitieu;
                        $obj->donvi = $chitieuf->tendonvi;
                        $obj->idcha = $chitieuf->idcha;
                        array_push($result, $obj);
                        $idFind = $chitieuf->idcha;
                    } else {
                        $idFind = null;
                    }
                }
            }
        }
        return $result;
    }

    public function viewReportdubao(Request $request)
    {
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        $tblChitieu = tbl_chitieu::where('tbl_chitieu.isDelete', 0)->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha',  'tbl_donvitinh.tendonvi')->get();
        $tblChitietbieumau = tbl_chitietbieumau::where('tbl_chitietbieumau.isDelete', 0)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitietbieumau.id', 'tbl_chitietbieumau.bieumau', 'tbl_chitietbieumau.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->get();
        $tblsolieutheobieu = tbl_solieutheobieu::where('isDelete', 0)
            ->get();
        $tbl_chitietsolieutheobieu = tbl_chitietsolieutheobieu::where('isDelete', 0)->get();
        if ($donvicha == null) $donvicha = $madonvi;
        $currentYear = $request->year;
        $periviousYear = $currentYear - 1;
        // $otherYear = $periviousYear - 1;
        $loaisolieu = $request->loaisolieu;
        $Form = $request->bieumau;
        $mau = $request->mau;
        $FormController = new NhaplieusolieuController();

        $tenloaisolieu = tbl_loaisolieu::where('id', $loaisolieu)->first();
        $Ultil = new ChitieuUltils();

        $TreeChitieu = $Ultil->getTreeChitieunew($Form);
        if ($mau != null) {
            // Lấy chi tiết biểu mẫu

            $listChitieu = $this->getDetailTemplate($mau, $tblChitietbieumau);

            // Tạo cây chỉ tiêu từ danh sách chỉ tiêu
            $TreeChitieu = $this->getTreeChitieu($listChitieu, $tblChitieu); //$Ultil->getTreeChitieu($listChitieu);
        }
        $dulieu = new stdClass();
        $thongtin = new stdClass();
        $Result = array();
        $listXaofHuyen = null;

        if ($request->diaban == 1) {
            $listXaofHuyen = tbl_donvihanhchinh::where('madonvi', $request->location)
                ->where('isDelete', 0)
                ->get();
        } else {
            // Tong hop bao cao theo xa
            $listXaofHuyen = tbl_donvihanhchinh::where('id', $request->location)
                ->where('isDelete', 0)
                ->get();
        }
        $datacha = tbl_chitieu::where('tbl_chitieu.id', '=', $Form)
            ->where('tbl_chitieu.IsDelete', 0)
            ->where('madonvi', $donvicha)
            //->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            //->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->select(DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as id'), 'tbl_chitieu.tenchitieu', DB::raw('CAST(tbl_chitieu.idcha AS varchar(10)) as idcha'), 'tbl_donvitinh.tendonvi', DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as strid'))
            ->orderBy('tbl_chitieu.thutu', 'desc')
            ->orderBy('tbl_chitieu.id')
            ->groupBy('id', 'tbl_chitieu.tenchitieu', 'idcha', 'tbl_donvitinh.tendonvi', 'strid')
            ->get();

        $data = tbl_chitieu::with('childrenAll')->where('tbl_chitieu.IsDelete', 0)
            ->whereNotNull('tbl_chitieu.idcha')
            ->join('tbl_chitietbieumau', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->orderBy('tbl_chitieu.thutu', 'desc')
            ->orderBy('tbl_chitieu.id')
            ->get();

        if ($mau != null) {
            $Form = $mau;
            $datacha = tbl_chitietbieumau::where('bieumau', '=', $Form)
                ->where('tbl_chitieu.idcha', null)
                ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
                ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
                //->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
                ->select(DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as id'), 'tbl_chitieu.tenchitieu', DB::raw('CAST(tbl_chitieu.idcha AS varchar(10)) as idcha'), 'tbl_donvitinh.tendonvi', DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as strid'))
                ->orderBy('tbl_chitieu.thutu', 'desc')
                ->orderBy('tbl_chitieu.id')
                ->groupBy('id', 'tbl_chitieu.tenchitieu', 'idcha', 'tbl_donvitinh.tendonvi', 'strid')
                ->get();

            $data = tbl_chitieu::with('childrenAll')->where('tbl_chitieu.IsDelete', 0)
                ->where('tbl_chitietbieumau.bieumau', '=', $Form)
                ->whereNotNull('tbl_chitieu.idcha')
                ->join('tbl_chitietbieumau', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
                ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
                ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
                ->orderBy('tbl_chitieu.thutu', 'desc')
                ->orderBy('tbl_chitieu.id')
                ->get();
        }
        $tong_tu1 = 0;
        $tong_mau1 = 0;


        $tong_tyle1 = 0;
        $tong_tyle2 = 0;
        $tong_tyle3 = 0;
        $tong_tyle4 = 0;
        $tong_tyle5 = 0;
        $tong_tyle6 = 0;
        foreach ($TreeChitieu as $chitieu) {
            $Item = new stdClass();
            $Item->id = $chitieu->id;
            $Item->chitieu = $chitieu->ten;
            $Item->idcha = $chitieu->idcha;
            $Item->strid = strval($chitieu->id);
            $Item->donvi = $chitieu->donvi;
            $TotalofTHnam1 = $this->DataOfyearTH($currentYear - 5, $listXaofHuyen, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam2 = $this->DataOfyearTH($currentYear - 4, $listXaofHuyen, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam3 = $this->DataOfyearTH($currentYear - 3, $listXaofHuyen, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam4 = $this->DataOfyearTH($currentYear - 2, $listXaofHuyen, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam5 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $chitieu->id, $Form, $loaisolieu, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam6 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam7 = $this->DataOfyearTH($currentYear + 2, $listXaofHuyen, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam8 = $this->DataOfyearTH($currentYear + 3, $listXaofHuyen, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam9 = $this->DataOfyearTH($currentYear + 4, $listXaofHuyen, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofTHnam10 = $this->DataOfyearTH($currentYear + 5, $listXaofHuyen, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);


            foreach ($listXaofHuyen as $xa) {
                $ghichu1 = $this->ghichuDataOfyear($currentYear - 5, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu2 = $this->ghichuDataOfyear($currentYear - 4, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu3 = $this->ghichuDataOfyear($currentYear - 3, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu4 = $this->ghichuDataOfyear($currentYear - 2, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu5 = $this->ghichuDataOfyear($currentYear - 1, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu = $this->ghichuDataOfyear($currentYear, $xa->id, $chitieu->id, $Form, $loaisolieu, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu6 = $this->ghichuDataOfyear($currentYear + 1, $xa->id, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu7 = $this->ghichuDataOfyear($currentYear + 2, $xa->id, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu8 = $this->ghichuDataOfyear($currentYear + 3, $xa->id, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu9 = $this->ghichuDataOfyear($currentYear + 4, $xa->id, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $ghichu10 = $this->ghichuDataOfyear($currentYear + 5, $xa->id, $chitieu->id, $Form, 9, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            }
            //lay so lieu SS nam -10
            $GiaSS2010 = $this->SumdataXaTH($currentYear - 10, $donvicha, $chitieu->id, $Form, 33, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);

            //lay so lieu SS nam hien tại
            $GiaSS2020 = $this->SumdataXaTH($currentYear, $donvicha, $chitieu->id, $Form, 33, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            //lay so lieu TT nam 2019
            $GiaTH1 = $this->SumdataXaTH($currentYear - 5, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH2 = $this->SumdataXaTH($currentYear - 4, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH3 = $this->SumdataXaTH($currentYear - 3, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH4 = $this->SumdataXaTH($currentYear - 2, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH5 = $this->SumdataXaTH($currentYear - 1, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH = $this->SumdataXaTH($currentYear, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH6 = $this->SumdataXaTH($currentYear + 1, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH7 = $this->SumdataXaTH($currentYear + 2, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH8 = $this->SumdataXaTH($currentYear + 3, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH9 = $this->SumdataXaTH($currentYear + 4, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
            $GiaTH10 = $this->SumdataXaTH($currentYear + 5, $donvicha, $chitieu->id, $Form, 34, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);

            $Item->THnam1 = $TotalofTHnam1;
            $Item->THnam2 = $TotalofTHnam2;
            $Item->THnam3 = $TotalofTHnam3;
            $Item->THnam4 = $TotalofTHnam4;
            $Item->THnam5 = $TotalofTHnam5;
            $Item->THnam = $TotalofTHnam;
            $Item->KHnam = $TotalofKHnam;
            $Item->KHnam1 = $TotalofTHnam6;
            $Item->KHnam2 = $TotalofTHnam7;
            $Item->KHnam3 = $TotalofTHnam8;
            $Item->KHnam4 = $TotalofTHnam9;
            $Item->KHnam5 = $TotalofTHnam10;
            //gia ss
            $Item->GiaSS2010 = $GiaSS2010;
            $Item->GiaSS2020 = $GiaSS2020;
            //gia tt $GiaTH1
            $Item->GiaTH1 = $GiaTH1;
            $Item->GiaTH2 = $GiaTH2;
            $Item->GiaTH3 = $GiaTH3;
            $Item->GiaTH4 = $GiaTH4;
            $Item->GiaTH5 = $GiaTH5;
            $Item->GiaTH = $GiaTH;
            $Item->GiaTH6 = $GiaTH6;
            $Item->GiaTH7 = $GiaTH7;
            $Item->GiaTH8 = $GiaTH8;
            $Item->GiaTH9 = $GiaTH9;
            $Item->GiaTH10 = $GiaTH10;
            $Item->tyle = 0;

            $Item->dubao = 0;
            if ($TotalofTHnam > 0)
                $Item->tyle = ($TotalofTHnam1 / $TotalofTHnam) ** (1 / 5);
            if ($TotalofTHnam > 0 && $Item->tyle > 0)
                $Item->dubao = ($TotalofTHnam5 / ($TotalofTHnam * ($Item->tyle ** 5))) ** (1 / 5);
            # Detail KH and TH of xa
            //ty le moi
            //2015=$TotalofTHnam1*$GiaSS2010/1000000000
            //2020=$TotalofTHnam*$GiaSS2010/1000000000
            $Item->tyle1 = 0;
            if ($TotalofTHnam1 * $GiaSS2010 / 1000000000 > 0)
                $Item->tyle1 = (($TotalofTHnam / $TotalofTHnam1) ** (1 / 5)) * 100;
            $tong_tu1 += $TotalofTHnam;
            $tong_mau1 += $TotalofTHnam1;

            $Item->tyle2 = 0;
            if ($TotalofTHnam * $GiaSS2010 / 1000000000 > 0)
                $Item->tyle2 = (($TotalofTHnam10 / $TotalofTHnam) ** (1 / 5)) * 100;

            $Item->tyle3 = 0;
            if ($TotalofTHnam1 * $GiaTH1 > 0)
                $Item->tyle3 = (($TotalofTHnam * $GiaTH / $TotalofTHnam1 * $GiaTH1) ** (1 / 5)) * 100;
            $Item->tyle4 = 0;
            if ($GiaTH * $TotalofTHnam > 0)
                $Item->tyle4 = (($TotalofTHnam10 * $GiaTH10 / $GiaTH * $TotalofTHnam) ** (1 / 5)) * 100;


            //tyle 5, 6
            $Item->tyle5 = 0;
            if ($TotalofTHnam1 > 0)
                $Item->tyle5 = (($TotalofTHnam / $TotalofTHnam1) ** (1 / 5)) * 100;
            $Item->tyle6 = 0;
            if ($TotalofTHnam > 0)
                $Item->tyle6 = (($TotalofTHnam10 / $TotalofTHnam) ** (1 / 5)) * 100;
            //$tong_tyle1+=$Item->tyle1;
            $tong_tyle2 += $Item->tyle2;
            $tong_tyle3 += $Item->tyle3;
            $tong_tyle4 += $Item->tyle4;
            $tong_tyle5 += $Item->tyle5;
            $tong_tyle6 += $Item->tyle6;

            $DetailXa = array();
            $sttxa = 1;

            foreach ($listXaofHuyen as $xa) {
                $Itemxa = new stdClass();


                $THnam1 = $this->SumdataXaTH($currentYear - 5, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $THnam2 = $this->SumdataXaTH($currentYear - 4, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $THnam3 = $this->SumdataXaTH($currentYear - 3, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $THnam4 = $this->SumdataXaTH($currentYear - 2, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $THnam5 = $this->SumdataXaTH($currentYear - 1, $xa->id, $chitieu->id, $Form, 8, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);
                $THnam = $this->SumdataXaTH($currentYear, $xa->id, $chitieu->id, $Form, $loaisolieu, $tblsolieutheobieu, $tbl_chitietsolieutheobieu);



                $Itemxa->id = $chitieu->id;
                $Itemxa->tenxa = $xa->tendonvi;
                $Itemxa->THnam1 = $THnam1;
                $Itemxa->THnam2 = $THnam2;
                $Itemxa->THnam3 = $THnam3;
                $Itemxa->THnam4 = $THnam4;
                $Itemxa->THnam5 = $THnam5;
                $Itemxa->THnam = $THnam;
                //array_push($DetailXa, $Itemxa);
                //array_push($DetailXa, $TH);
                //array_push($DetailXa, $KHYear);
                $tenxa = 'Xa_' . $sttxa;
                $Itemxa->idxa = $tenxa;
                $sttxa++;
                $Item->{$xa->tendonvi} = $Itemxa;
                $Item->{$tenxa} = $Itemxa;
            }
            $Item->Detail = $DetailXa;
            if ($tong_mau1 > 0)
                $tong_tyle1 = (($tong_tu1 / $tong_mau1) ** (1 / 5)) * 100;
            $tong_tyle2 = ($tong_tyle1 ** (1 / 5)) * 100;
            $tong_tyle3 = ($tong_tyle1 ** (1 / 5)) * 100;
            $tong_tyle4 = ($tong_tyle1 ** (1 / 5)) * 100;
            $tong_tyle5 = ($tong_tyle1 ** (1 / 5)) * 100;
            $tong_tyle6 = ($tong_tyle1 ** (1 / 5)) * 100;
            $thongtin->diaban = $request->namelocation;
            $thongtin->nam = $request->year;
            $thongtin->tyle1 = $tong_tyle1;
            $thongtin->tyle2 = $tong_tyle2;
            $thongtin->tyle3 = $tong_tyle3;
            $thongtin->tyle4 = $tong_tyle4;
            $thongtin->tyle5 = $tong_tyle5;
            $thongtin->tyle6 = $tong_tyle6;
            $thongtin->bieumau = $Form;
            $thongtin->solieu = $tenloaisolieu['tenloaisolieu'];
            $dulieu->nutcha = $datacha;
            $dulieu->nutcon = $data;
            $dulieu->chitiet = $Result;
            $dulieu->chitiet1 = $Result;
            $dulieu->chitiet2 = $Result;
            $dulieu->chitiet3 = $Result;
            $dulieu->chitiet4 = $Result;
            $dulieu->thongtin = $thongtin;
            return response()->json($dulieu);
            array_push($Result, $Item);
        }

        return response()->json($Result);
    }

    public function viewReport(Request $request)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(20000);
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        //$madonvi = 94;
        //$donvicha = 94;
        if ($donvicha == null) $donvicha = $madonvi;
        $currentYear = $request->year;
        $periviousYear = $currentYear - 1;
        // $otherYear = $periviousYear - 1;
        $loaisolieu = $request->loaisolieu;
        $tenloaisolieu = tbl_loaisolieu::where('id', $loaisolieu)->first();
        $Form = $request->bieumau;
        $FormController = new NhaplieusolieuController();
        $listChitieu = $FormController->showDeltalBieumauTH($Form);
        $Ultil = new ChitieuUltils();
        $TreeChitieu = $Ultil->getTreeChitieu($listChitieu);
        $dulieu = new stdClass();
        $thongtin = new stdClass();
        $Result = array();
        $listXaofHuyen = null;
        if ($request->diaban == 1) {
            $listXaofHuyen = tbl_donvihanhchinh::where('madonvi', $request->location)
                ->get();
        } else {
            // Tong hop bao cao theo xa
            $listXaofHuyen = tbl_donvihanhchinh::where('id', $request->location)
                ->get();
        }
        $datacha = tbl_chitietbieumau::where('bieumau', '=', $Form)
            ->where('tbl_chitieu.idcha', null)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            //->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->select(DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as id'), 'tbl_chitieu.tenchitieu', DB::raw('CAST(tbl_chitieu.idcha AS varchar(10)) as idcha'), 'tbl_donvitinh.tendonvi', DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as strid'))
            ->orderBy('tbl_chitieu.thutu', 'desc')
            ->orderBy('tbl_chitieu.id')
            ->groupBy('id', 'tbl_chitieu.tenchitieu', 'idcha', 'tbl_donvitinh.tendonvi', 'strid')
            ->get();

        $data = tbl_chitieu::with('childrenAll')->where('tbl_chitieu.IsDelete', 0)
            ->where('tbl_chitietbieumau.bieumau', '=', $Form)
            ->whereNotNull('tbl_chitieu.idcha')
            ->join('tbl_chitietbieumau', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->orderBy('tbl_chitieu.thutu', 'desc')
            ->orderBy('tbl_chitieu.id')
            ->get();

        foreach ($TreeChitieu as $chitieu) {
            $Item = new stdClass();
            $Item->id = $chitieu->id;
            $Item->chitieu = $chitieu->ten;
            $Item->idcha = $chitieu->idcha;
            $Item->strid = strval($chitieu->id);
            $Item->donvi = $chitieu->donvi;
            $TotalofPerviousYear = $this->DataOfyear($periviousYear, $listXaofHuyen, $chitieu->id, $Form, 9);

            //dd($TotalofCurrentYear);
            //return 200;
            $TotalofTHPerviousYear = $this->DataOfyear($periviousYear, $listXaofHuyen, $chitieu->id, $Form, $loaisolieu);
            //chỉnh code ngược lại
            $TotalofTHYear = $this->DataOfyear($currentYear, $listXaofHuyen, $chitieu->id, $Form, $loaisolieu);
            $TotalofCurrentYear = $this->DataOfyear($currentYear, $listXaofHuyen, $chitieu->id, $Form, 9);
            $KHnew = $this->DataOfyear($currentYear + 1, $listXaofHuyen, $chitieu->id, $Form, 9);
            $Item->thYear = $TotalofTHYear;
            $Item->KHpreYear = $TotalofPerviousYear;
            $Item->KHcurrentYear = $TotalofCurrentYear;
            $Item->estimate = $TotalofTHPerviousYear;
            $Item->KHnamsau = $KHnew;
            $ghichu = $this->ghichuDataOfyear($currentYear, $listXaofHuyen, $chitieu->id, $Form, $loaisolieu);
            $Item->ghichu = $ghichu;
            //dd($ghichu);
            //return 200;
            $Item->tyle = 0;
            if ($TotalofPerviousYear > 0)
                $Item->tyle = $TotalofTHPerviousYear / $TotalofPerviousYear;
            # Detail KH and TH of xa

            $DetailXa = array();
            $sttxa = 1;
            foreach ($listXaofHuyen as $xa) {
                $Itemxa = new stdClass();
                $KH = $this->SumdataXa($periviousYear, $xa->id, $chitieu->id, $Form, 9);
                $TH = $this->SumdataXa($periviousYear, $xa->id, $chitieu->id, $Form, $loaisolieu);
                $KHYear = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form, 9);
                $THYear = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form, $loaisolieu);
                $Item->thYear = $TotalofTHYear;
                $Itemxa->id = $chitieu->id;
                $Itemxa->tenxa = $xa->tendonvi;
                $Itemxa->KH = $KH;
                $Itemxa->TH = $TH;
                if ($KHYear == null) $KHYear = 0;
                $Itemxa->KHYear = $KHYear;
                $Itemxa->THYear = $THYear;
                $Itemxa->tyle1 = 0;
                $Itemxa->tyle2 = 0;
                if ($TH > 0) $Itemxa->tyle1 = $THYear / $TH;
                if ($KHYear > 0) $Itemxa->tyle2 = $THYear / $KHYear;
                //array_push($DetailXa, $Itemxa);
                //array_push($DetailXa, $TH);
                //array_push($DetailXa, $KHYear);
                $tenxa = 'Xa_' . $sttxa;
                $Itemxa->idxa = $tenxa;
                $sttxa++;
                //$Item->{$xa->tendonvi}=$Itemxa;
                $Item->{$tenxa} = $Itemxa;
            }

            $Item->Detail = $DetailXa;
            array_push($Result, $Item);
        }

        $tbbieumau = tbl_bieumau::where('id', $request->bieumau)->first();
        $thongtin->diaban = $request->namelocation;
        $thongtin->nam = $request->year;
        $thongtin->bieumau = $tbbieumau->tenbieumau;
        $thongtin->solieu = $tenloaisolieu['tenloaisolieu'];
        $dulieu->nutcha = $datacha;
        $dulieu->nutcon = $data;
        $dulieu->chitiet = $Result;
        $dulieu->chitiet1 = $Result;
        $dulieu->chitiet2 = $Result;
        $dulieu->chitiet3 = $Result;
        $dulieu->chitiet4 = $Result;
        $dulieu->chitiet5 = $Result;
        $dulieu->thongtin = $thongtin;
        return response()->json($dulieu);
    }
    public function viewReportsanxuat(Request $request)
    {
        set_time_limit(20000);
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        //$madonvi = 94;
        //$donvicha = 94;
        if ($donvicha == null) $donvicha = $madonvi;
        $currentYear = $request->year;
        $periviousYear = $currentYear - 1;
        // $otherYear = $periviousYear - 1;
        $loaisolieu = $request->loaisolieu;
        $tenloaisolieu = tbl_loaisolieu::where('id', $loaisolieu)->first();
        $Form = $request->bieumau;
        $mau = $request->mau;
        $loaimau = $request->loaimau;
        $FormController = new NhaplieusolieuController();
        $listChitieu = $FormController->showDeltalBieumauTH($Form);
        $Ultil = new ChitieuUltils();
        $TreeChitieu = $Ultil->getTreeChitieu($listChitieu);
        $dulieu = new stdClass();
        $thongtin = new stdClass();
        $Result = array();
        $listXaofHuyen = null;
        $location = $request->location;
        if ($location == 105) $location = $madonvi;
        if ($request->diaban == 1 || $request->diaban == 3) {
            $listXaofHuyen = tbl_donvihanhchinh::where('madonvi', $location)
                ->get();
        } else {
            // Tong hop bao cao theo xa
            $listXaofHuyen = tbl_donvihanhchinh::where('id', $location)
                ->get();
        }




        $tbbieumau = tbl_bieumau::where('id', $request->bieumau)->first();
        $thongtin->diaban = $request->namelocation;
        $thongtin->nam = $request->year;
        $thongtin->bieumau = $tbbieumau->tenbieumau;
        $dulieu->chitiet = $Result;
        $dulieu->thongtin = $thongtin;

        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/Excel') . '/' . $mau);
        $sheet->setActiveSheetIndex(0);
        $sheetSelect = $sheet->getActiveSheet();
        //$chitieu=$sheetSelect->getCellByColumnAndRow(1, 42)->getValue();
        //$chitieu=trim(str_replace("'","","a. Trồng trọt"));
        //$dschitieu=$this->getChitieuString($chitieu);
        //$maid=$chitieu;
        //$TotalofTHnam = $this->DataOfyearTH($currentYear-10, $donvicha, '2980', '216',33);
        //$GiaSS2010 = $this->SumdataXaTH($currentYear-10, $donvicha, '2640', '216',33);
        //dd($GiaSS2010);
        //return 200;
        //tieu de
        $sheetSelect->setCellValueByColumnAndRow(1, 1, 'TỔNG HỢP GIÁ TRỊ SẢN XUẤT GIAI ĐOẠN ' . ($currentYear - 5) . '-' . ($currentYear) . ' VÀ KẾ HOẠCH GĐ ' . ($currentYear) . '-' . ($currentYear + 5));
        if ($loaimau == 1) {
            $sheetSelect->setCellValueByColumnAndRow(5, 2, 'ƯTH/ TH ' . ($periviousYear));
            $sheetSelect->setCellValueByColumnAndRow(6, 2, 'Năm ' . ($currentYear));
            $sheetSelect->setCellValueByColumnAndRow(6, 3, 'KH ' . ($currentYear));
            $sheetSelect->setCellValueByColumnAndRow(7, 3, 'ƯTH/ TH ' . ($currentYear));
            $sheetSelect->setCellValueByColumnAndRow(8, 2, 'KH ' . ($currentYear + 1));
            $sheetSelect->setCellValueByColumnAndRow(9, 3, 'ƯTH/ TH ' . ($currentYear) . '/  ƯTH/ TH ' . ($currentYear - 1));
            $sheetSelect->setCellValueByColumnAndRow(10, 3, 'ƯTH/ TH ' . ($currentYear) . '/  KH ' . ($currentYear));
            $sheetSelect->setCellValueByColumnAndRow(11, 3, 'KH ' . ($currentYear + 1) . '/  TH ' . ($currentYear));
        } else {

            $sheetSelect->setCellValueByColumnAndRow(6, 2, 'Năm ' . ($currentYear)); // 
            $sheetSelect->setCellValueByColumnAndRow(22, 2, 'Mục tiêu giai đoạn ' . ($currentYear - 4) . ' - ' . ($currentYear));
            $sheetSelect->setCellValueByColumnAndRow(23, 3, ($currentYear - 4));
            $sheetSelect->setCellValueByColumnAndRow(24, 3, ($currentYear - 3));
            $sheetSelect->setCellValueByColumnAndRow(25, 3, ($currentYear - 2));
            $sheetSelect->setCellValueByColumnAndRow(26, 3, ($currentYear - 1));
            $sheetSelect->setCellValueByColumnAndRow(27, 3, 'Ước thực hiện ' . ($currentYear));
            $sheetSelect->setCellValueByColumnAndRow(28, 2, 'Ước thực hiện ' . ($currentYear - 4) . ' - ' . ($currentYear));
            $sheetSelect->setCellValueByColumnAndRow(29, 2, 'Đánh giá thực hiện so với mục tiêu ' . ($currentYear - 4) . ' - ' . ($currentYear));
        }


        $Form = 223; //chi tieu tong hop kinh te
        $rowstart = 42;
        $rowend = 58;
        for ($row = $rowstart; $row <= $rowend; $row++) {
            $chitieu = $sheetSelect->getCellByColumnAndRow(1, $row)->getValue();
            //$chitieu=trim(str_replace("'","",$chitieu));
            //$dschitieu=$this->getChitieuString($chitieu);
            //GIÁ TRỊ SẢN XUẤT
            if (strlen($chitieu) > 0) {
                $maid = $chitieu;
                $TotalofTHnam5 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
                $TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
                $TotalofTHnam6 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
                $TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
                $sheetSelect->setCellValueByColumnAndRow(4, $row, $TotalofTHnam5);
                $sheetSelect->setCellValueByColumnAndRow(5, $row, $TotalofTHnam);
                $sheetSelect->setCellValueByColumnAndRow(6, $row, $TotalofKHnam);
                $sheetSelect->setCellValueByColumnAndRow(7, $row, $TotalofTHnam6);
            }
        }
        $Form = 233; //bieu mau van hoa xa hoi
        $rowstart = 60;
        $rowend = 85;
        for ($row = $rowstart; $row <= $rowend; $row++) {
            $chitieu = $sheetSelect->getCellByColumnAndRow(1, $row)->getValue();
            //$chitieu=trim(str_replace("'","",$chitieu));
            //$dschitieu=$this->getChitieuString($chitieu);
            //GIÁ TRỊ SẢN XUẤT
            if (strlen($chitieu) > 0) {
                $maid = $chitieu;
                $TotalofTHnam5 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
                $TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
                $TotalofTHnam6 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
                $TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
                $sheetSelect->setCellValueByColumnAndRow(4, $row, $TotalofTHnam5);
                $sheetSelect->setCellValueByColumnAndRow(5, $row, $TotalofTHnam);
                $sheetSelect->setCellValueByColumnAndRow(6, $row, $TotalofKHnam);
                $sheetSelect->setCellValueByColumnAndRow(7, $row, $TotalofTHnam6);
            }
        }
        $Form = 216; //bieu mau phong nong nghiep
        $rowstart = 89;
        $rowend = 427;
        for ($row = $rowstart; $row <= $rowend; $row++) {
            $chitieu = $sheetSelect->getCellByColumnAndRow(1, $row)->getValue();
            //$chitieu=trim(str_replace("'","",$chitieu));
            //$dschitieu=$this->getChitieuString($chitieu);
            //GIÁ TRỊ SẢN XUẤT
            if (strlen($chitieu) > 0) {
                $maid = $chitieu;
                $TotalofTHnam5 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
                $TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
                $TotalofTHnam6 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
                $TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
                $sheetSelect->setCellValueByColumnAndRow(4, $row, $TotalofTHnam5);
                $sheetSelect->setCellValueByColumnAndRow(5, $row, $TotalofTHnam);
                $sheetSelect->setCellValueByColumnAndRow(6, $row, $TotalofKHnam);
                $sheetSelect->setCellValueByColumnAndRow(7, $row, $TotalofTHnam6);
            }
        }
        $Form = 220; // mau phong kinh te ha tang
        $rowstart = 429;
        $rowend = 460;
        for ($row = $rowstart; $row <= $rowend; $row++) {
            $chitieu = $sheetSelect->getCellByColumnAndRow(1, $row)->getValue();
            //$chitieu=trim(str_replace("'","",$chitieu));
            //$dschitieu=$this->getChitieuString($chitieu);
            //GIÁ TRỊ SẢN XUẤT
            if (strlen($chitieu) > 0) {
                $maid = $chitieu;
                $TotalofTHnam5 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
                $TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
                $TotalofTHnam6 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
                $TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
                $sheetSelect->setCellValueByColumnAndRow(4, $row, $TotalofTHnam5);
                $sheetSelect->setCellValueByColumnAndRow(5, $row, $TotalofTHnam);
                $sheetSelect->setCellValueByColumnAndRow(6, $row, $TotalofKHnam);
                $sheetSelect->setCellValueByColumnAndRow(7, $row, $TotalofTHnam6);
            }
        }
        $Form = 236; // mau phong y te huyen
        $rowstart = 466;
        $rowend = 476;
        for ($row = $rowstart; $row <= $rowend; $row++) {
            $chitieu = $sheetSelect->getCellByColumnAndRow(1, $row)->getValue();
            //$chitieu=trim(str_replace("'","",$chitieu));
            //$dschitieu=$this->getChitieuString($chitieu);
            //GIÁ TRỊ SẢN XUẤT
            if (strlen($chitieu) > 0) {
                $maid = $chitieu;
                $TotalofTHnam5 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
                $TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
                $TotalofTHnam6 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
                $TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
                $sheetSelect->setCellValueByColumnAndRow(4, $row, $TotalofTHnam5);
                $sheetSelect->setCellValueByColumnAndRow(5, $row, $TotalofTHnam);
                $sheetSelect->setCellValueByColumnAndRow(6, $row, $TotalofKHnam);
                $sheetSelect->setCellValueByColumnAndRow(7, $row, $TotalofTHnam6);
            }
        }
        $Form = 238; // mau chi tieu tong hop ktxh
        $rowstart = 513;
        $rowend = 544;
        for ($row = $rowstart; $row <= $rowend; $row++) {
            $chitieu = $sheetSelect->getCellByColumnAndRow(1, $row)->getValue();
            //$chitieu=trim(str_replace("'","",$chitieu));
            //$dschitieu=$this->getChitieuString($chitieu);
            //GIÁ TRỊ SẢN XUẤT
            if (strlen($chitieu) > 0) {
                $maid = $chitieu;
                $TotalofTHnam5 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
                $TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
                $TotalofTHnam6 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
                $TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
                $sheetSelect->setCellValueByColumnAndRow(4, $row, $TotalofTHnam5);
                $sheetSelect->setCellValueByColumnAndRow(5, $row, $TotalofTHnam);
                $sheetSelect->setCellValueByColumnAndRow(6, $row, $TotalofKHnam);
                $sheetSelect->setCellValueByColumnAndRow(7, $row, $TotalofTHnam6);
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
        if (!file_exists(public_path('export'))) {
            mkdir(public_path('export'));
        }
        $writer->save(public_path('export') . "/" . $mau);

        return response()->json($mau);
    }
    public function Exportdata(Request $request)
    {
        set_time_limit(20000);
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        //$madonvi = 94;
        //$donvicha = 94;
        if ($donvicha == null) $donvicha = $madonvi;
        $currentYear = $request->year;
        $periviousYear = $currentYear - 1;
        // $otherYear = $periviousYear - 1;
        $loaisolieu = $request->loaisolieu;
        $tenloaisolieu = tbl_loaisolieu::where('id', $loaisolieu)->first();
        $Form = $request->bieumau;
        $FormController = new NhaplieusolieuController();
        $listChitieu = $FormController->showDeltalBieumauTH($Form);
        $Ultil = new ChitieuUltils();
        $TreeChitieu = $Ultil->getTreeChitieu($listChitieu);
        $dulieu = new stdClass();
        $thongtin = new stdClass();
        $Result = array();
        $listXaofHuyen = null;
        if ($request->diaban == 1) {
            $listXaofHuyen = tbl_donvihanhchinh::where('madonvi', $request->location)
                ->get();
        } else {
            // Tong hop bao cao theo xa
            $listXaofHuyen = tbl_donvihanhchinh::where('id', $request->location)
                ->get();
        }


        foreach ($TreeChitieu as $chitieu) {
            $Item = new stdClass();
            $Item->id = $chitieu->id;
            $Item->chitieu = $chitieu->ten;
            $Item->idcha = $chitieu->idcha;
            $Item->strid = strval($chitieu->id);
            $Item->donvi = $chitieu->donvi;
            $TotalofPerviousYear = $this->DataOfyear($periviousYear, $listXaofHuyen, $chitieu->id, $Form, 9);

            //dd($TotalofCurrentYear);
            //return 200;
            $TotalofTHPerviousYear = $this->DataOfyear($periviousYear, $listXaofHuyen, $chitieu->id, $Form, $loaisolieu);
            //chỉnh code ngược lại
            $TotalofTHYear = $this->DataOfyear($currentYear, $listXaofHuyen, $chitieu->id, $Form, $loaisolieu);
            $TotalofCurrentYear = $this->DataOfyear($currentYear, $listXaofHuyen, $chitieu->id, $Form, 9);
            $Item->thYear = $TotalofTHYear;
            $Item->KHpreYear = $TotalofPerviousYear;
            $Item->KHcurrentYear = $TotalofCurrentYear;
            $Item->estimate = $TotalofTHPerviousYear;

            $Item->tyle = 0;
            if ($TotalofPerviousYear > 0)
                $Item->tyle = $TotalofTHPerviousYear / $TotalofPerviousYear;
            # Detail KH and TH of xa

            $DetailXa = array();
            $sttxa = 1;
            foreach ($listXaofHuyen as $xa) {
                $Itemxa = new stdClass();
                $KH = $this->SumdataXa($periviousYear, $xa->id, $chitieu->id, $Form, 9);
                $TH = $this->SumdataXa($periviousYear, $xa->id, $chitieu->id, $Form, $loaisolieu);
                $KHYear = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form, 9);
                $THYear = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form, $loaisolieu);
                $Item->thYear = $TotalofTHYear;
                $Itemxa->id = $chitieu->id;
                $Itemxa->tenxa = $xa->tendonvi;
                $Itemxa->KH = $KH;
                $Itemxa->TH = $TH;
                $Itemxa->KHYear = $KHYear;
                $Itemxa->THYear = $THYear;
                $tenxa = 'Xa_' . $sttxa;
                $Itemxa->idxa = $tenxa;
                $sttxa++;
                $Item->{$tenxa} = $Itemxa;
            }

            $Item->Detail = $DetailXa;
            array_push($Result, $Item);
        }

        $tbbieumau = tbl_bieumau::where('id', $request->bieumau)->first();
        $thongtin->diaban = $request->namelocation;
        $thongtin->nam = $request->year;
        $thongtin->bieumau = $tbbieumau->tenbieumau;
        $dulieu->chitiet = $Result;
        $dulieu->thongtin = $thongtin;

        //  Export data

        $spreadsheet = new Spreadsheet();
        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "ChitieuNN");
        $spreadsheet->addSheet($sheet, 0);
        $spreadsheet->setActiveSheetIndex(0);
        $sheetSelect = $spreadsheet->getActiveSheet();


        $row = 4;
        $sheetSelect->mergeCellsByColumnAndRow(1, $row, 1, $row + 2);
        $sheetSelect->setCellValue('A4', "STT");

        $sheetSelect->mergeCellsByColumnAndRow(2, $row, 2, $row + 2);
        $sheetSelect->setCellValue('B4', "Chỉ tiêu");

        $sheetSelect->mergeCellsByColumnAndRow(3, $row, 3, $row + 2);
        $sheetSelect->setCellValue('C4', "ĐVT");
        $columnXa = 9;
        // Total
        $sheetSelect->mergeCells("D4:H4");
        $sheetSelect->setCellValue("D4", "Toàn huyện");

        $sheetSelect->mergeCells("D5:D7");
        $sheetSelect->setCellValue("D5", "TH " . $periviousYear);

        $sheetSelect->mergeCells("E5:E7");
        $sheetSelect->setCellValue("E5", "KH " . $currentYear);

        $sheetSelect->mergeCells("F5:F7");
        $sheetSelect->setCellValue("F5", "ƯTH " . $currentYear);

        $sheetSelect->mergeCells("G5:H5");
        $sheetSelect->setCellValue("G5", "So sánh%");

        $sheetSelect->mergeCells("G6:G7");
        $sheetSelect->setCellValue("G6", "ƯTH" . $currentYear . "/ƯTH" . $periviousYear);

        $sheetSelect->mergeCells("H6:H7");
        $sheetSelect->setCellValue("H6", "ƯKH" . $currentYear . "/ƯKh" . $periviousYear);

        // Render column Xa
        foreach ($listXaofHuyen as $xa) {
            $sheetSelect->mergeCellsByColumnAndRow($columnXa, $row, $columnXa + 4, $row);
            $sheetSelect->setCellValueByColumnAndRow($columnXa, $row, $xa->tendonvi);
            $columnXa += 5;
        }

        // Render colum TH, KH

        // Render xa
        $row++;
        $columnXa = 9;
        foreach ($listXaofHuyen as $xa) {

            $sheetSelect->mergeCellsByColumnAndRow($columnXa, $row, $columnXa, $row + 2);
            $sheetSelect->setCellValueByColumnAndRow($columnXa, $row, "TH" . $periviousYear);

            $columnXa++;
            $sheetSelect->mergeCellsByColumnAndRow($columnXa, $row, $columnXa, $row + 2);
            $sheetSelect->setCellValueByColumnAndRow($columnXa, $row, "KH" . $currentYear);

            $columnXa++;
            $sheetSelect->mergeCellsByColumnAndRow($columnXa, $row, $columnXa, $row + 2);
            $sheetSelect->setCellValueByColumnAndRow($columnXa, $row, "ƯTH" . $currentYear);

            $columnXa++;

            $sheetSelect->mergeCellsByColumnAndRow($columnXa, $row, $columnXa + 1, $row);
            $sheetSelect->setCellValueByColumnAndRow($columnXa, $row, "So sánh %");

            $sheetSelect->mergeCellsByColumnAndRow($columnXa, $row + 1, $columnXa, $row + 2);
            $sheetSelect->setCellValueByColumnAndRow($columnXa, $row + 1, "ƯTH" . $currentYear . "/ƯTH" . $periviousYear);

            $sheetSelect->mergeCellsByColumnAndRow($columnXa + 1, $row + 1, $columnXa + 1, $row + 2);
            $sheetSelect->setCellValueByColumnAndRow($columnXa + 1, $row + 1, "ƯKH" . $currentYear . "/KH" . $currentYear);
            $columnXa += 2;
        }

        // Render body

        $rowBody = 8;
        $lastColumn = 0;

        $detail = $dulieu->chitiet;
        $index = 1;
        foreach ($detail as $value) {
            $columnXa = 9;
            $idXa = 1;
            $sheetSelect->setCellValue('A' . $rowBody, $index);
            $sheetSelect->setCellValue('B' . $rowBody, $value->chitieu);
            $sheetSelect->setCellValue('C' . $rowBody, $value->donvi != null ? $value->donvi : "");
            // Render tong
            $sheetSelect->setCellValue("D" . $rowBody, $value->estimate);
            $sheetSelect->setCellValue("E" . $rowBody, $value->KHcurrentYear);
            $sheetSelect->setCellValue("F" . $rowBody, $value->thYear);
            $sheetSelect->setCellValue("G" . $rowBody, $value->estimate != 0 ? round($value->thYear / $value->estimate, 2)  : "");
            $sheetSelect->setCellValue("H" . $rowBody, $value->KHcurrentYear != 0 ? round($value->thYear / $value->KHcurrentYear, 2) : "");

            foreach ($listXaofHuyen as $xa) {
                $item = get_object_vars($value);
                $object = $item['Xa_' . $idXa];
                $sheetSelect->setCellValueByColumnAndRow($columnXa, $rowBody, $object->TH);
                $columnXa++;

                $sheetSelect->setCellValueByColumnAndRow($columnXa, $rowBody, $object->KHYear);
                $columnXa++;

                $sheetSelect->setCellValueByColumnAndRow($columnXa, $rowBody, $object->THYear);
                $columnXa++;

                if ($object->TH != 0) {
                    $sheetSelect->setCellValueByColumnAndRow($columnXa, $rowBody, $object->THYear / $object->TH);
                    $columnXa++;
                } else {
                    $sheetSelect->setCellValueByColumnAndRow($columnXa, $rowBody, "");
                    $columnXa++;
                }

                if ($object->KHYear != 0) {
                    $sheetSelect->setCellValueByColumnAndRow($columnXa, $rowBody, $object->THYear / $object->KHYear);
                } else {
                    $sheetSelect->setCellValueByColumnAndRow($columnXa, $rowBody, "");
                }
                $columnXa++;
                $lastColumn = $columnXa;
                $idXa++;
            }
            $rowBody++;
            $index++;
        }


        $cellSesion = $sheetSelect->getCellByColumnAndRow($lastColumn, $rowBody)->getCoordinate();

        $lastCellHeader = $sheetSelect->getCellByColumnAndRow($lastColumn, 7)->getCoordinate();

        $sheetSelect->mergeCells("A1:R1");
        $sheetSelect->setCellValue("A1", "BIỂU 07: TỔNG HỢP CHỈ TIÊU KINH TẾ - XÃ HỘI NĂM " . $currentYear . "  PHÂN THEO CÁC XÃ, THỊ TRẤN");

        $sheetSelect->mergeCells("A2:R2");
        $sheetSelect->setCellValue("A2", "(Kèm theo Báo cáo số: ...../BC-UBND, ngày......tháng...... năm 2020 của UBND " . $dulieu->thongtin->diaban . ")");

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                    'borderSize' => 1,
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'inside' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], 'borderSize' => 1,
                ),
            ],

            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $styleBold = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheetSelect->getStyle("A1:R1")->applyFromArray($styleBold);
        $sheetSelect->getStyle("A2:R2")->applyFromArray($styleBold);
        $sheetSelect->getStyle("A2:" . $lastCellHeader)->applyFromArray($styleBold);
        $sheetSelect->getStyle("A4" . ":" . $cellSesion)->applyFromArray($styleArray);

        // Auto-size columns for all worksheets
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            foreach ($worksheet->getColumnIterator() as $column) {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        if (!file_exists(public_path('export'))) {
            mkdir(public_path('export'));
        }
        $writer->save(public_path('export') . "/ChitieuNN.xlsx");

        return response()->json("ChitieuNN.xlsx");
    }


    private function writeLevel($level)
    {
        $str = "";
        for ($l = 0; $l < $level; $l++) {
            $str .= "   ";
        }
        return $str;
    }

    public function findLevel($data, $item, $lv)
    {
        $obj = $this->findParent($data, $item);
        if ($obj != null) {
            $lv++;
            return $this->findLevel($data, $obj, $lv);
        } else {
            return $lv;
        }
    }

    private function findParent($data, $obj)
    {
        foreach ($data as $values) {
            if ($values->id == $obj->idcha) {
                return $values;
            }
        }
        return null;
    }

    private function findChild($data, $itemParent)
    {
        $Result = array();
        foreach ($data as $value) {
            if ($value->idcha == $itemParent) {
                array_push($Result, $value);
            }
        }
        return $Result;
    }

    private function checkWrite($arr, $item)
    {
        foreach ($arr as $value) {
            if ($value->id == $item) {
                return true;
            }
        }
        return false;
    }

    public function DataOfyear($year, $listXa, $chitieu, $bieumau, $loaisolieu)
    {
        $total = 0;
        foreach ($listXa as $xa) {
            $listBieumau = $this->getBieumauOfUnit($xa->id, $bieumau, $year, $loaisolieu);
            //dd($listBieumau);
            //return 200;
            $sum = $this->totalDeltailBieumau($chitieu, $listBieumau, $year);
            $total += $sum;
        }
        return $total;
    }
    public function ghichuDataOfyear($year, $xa, $chitieu, $bieumau, $loaisolieu, $tbl_solieutheobieu, $tbl_chitietsolieutheobieu)
    {
        $total = '';
        // Loc cac bieu mau co lien quan
        $listBieumau = $this->getBieumauOfUnit($xa, $bieumau, $year, $loaisolieu, $tbl_solieutheobieu);

        $sum = $this->ghichuBieumau($chitieu, $listBieumau, $year, $tbl_chitietsolieutheobieu);
        $total = $sum;
        return $total;
    }
    public function DataOfyearTH($year, $listXa, $chitieu, $bieumau, $loaisolieu, $tblsolieutheobieu, $tbl_chitietsolieutheobieu)
    {
        $total = 0;
        foreach ($listXa as $xa) {
            $listBieumau = $this->filterDataOfTemplate($xa->id, $bieumau, $year, $loaisolieu, $tblsolieutheobieu);
            if (count($listBieumau) == 0) {
                $sum = 0;
            } else {
                $sum = $this->maxtotalDeltailBieumau($chitieu, $listBieumau, $year, $tbl_chitietsolieutheobieu);
                $total += $sum;
            }
        }
        return $total;
    }


    private function filterDataOfTemplate($idXa, $bieumau, $year, $loaisolieu, $tbl_solieutheobieu)
    {
        $result = array();
        foreach ($tbl_solieutheobieu as $value) {
            if ($value->donvinhap == $idXa) {
                if ($value->bieumau == $bieumau && $value->year == $year && $value->loaisolieu == $loaisolieu) {
                    array_push($result, $value);
                }
            }
        }
        return $result;
    }
    private function SumdataXaTH($year, $xa, $chitieu, $bieumau, $loaisolieu, $tbl_solieutheobieu, $tbl_chitietsolieutheobieu)
    {
        $total = 0;
        //  $listUnit = $this->getListUnitOfxa($xa);
        $listBieumau = $this->filterDataOfTemplate($xa, $bieumau, $year, $loaisolieu, $tbl_solieutheobieu);       //dd($listBieumau);
        //return 200;
        $sum = $this->maxtotalDeltailBieumau($chitieu, $listBieumau, $year, $tbl_chitietsolieutheobieu);
        $total += $sum;
        return $total;
    }
    private function SumdataXa($year, $xa, $chitieu, $bieumau, $loaisolieu)
    {
        $total = 0;
        //  $listUnit = $this->getListUnitOfxa($xa);
        $listBieumau = $this->getBieumauOfUnit($xa, $bieumau, $year, $loaisolieu);
        $sum = $this->totalDeltailBieumau($chitieu, $listBieumau, $year);
        $total += $sum;
        return $total;
    }

    private function totalDeltailBieumau($Chitieu, $arrBieumau, $Time)
    {
        $total = 0;
        foreach ($arrBieumau as $bieumau) {
            // get Deltal
            $sum = DB::table('tbl_chitietsolieutheobieu')
                ->where('mabieusolieu', $bieumau->id)
                ->where('tbl_chitietsolieutheobieu.chitieu', $Chitieu)
                ->where('tbl_chitietsolieutheobieu.isDelete', 0)
                ->sum('sanluong');
            $total += $sum;
        }
        return $total;
    }
    private function arrDeltailBieumau()
    {
        $data = DB::table('tbl_chitietsolieutheobieu')
            ->where('tbl_chitietsolieutheobieu.isDelete', 0)
            ->get();
        return $data;
    }
    private function getDeltailBieumau($Chitieu, $arrBieumau, $Time, $data)
    {
        $total = 0;
        foreach ($arrBieumau as $bieumau) {
            // get Deltal
            $sum = 0;
            foreach ($value as $data) {
                if ($data->mabieusolieu == $bieumau->id && $data->chitieu == $Chitieu) {
                    $sum = $data->sanluong;
                    break;
                }
            }
            $total += $sum;
        }
        return $total;
    }
    private function ghichuBieumau($Chitieu, $arrBieumau, $Time, $tbl_chitietsolieutheobieu)
    {
        $ghichu = '';
        if (count($arrBieumau) > 0) {
            foreach ($arrBieumau as $bieumau) {
                $sum = '';
                foreach ($tbl_chitietsolieutheobieu as $value) {
                    if ($value->mabieusolieu == $bieumau->id && $value->chitieu == $Chitieu) {
                        $sum = $value->ghichu;
                    }
                }
                $ghichu == $sum;
            }
        }

        return $ghichu;
    }
    private function maxtotalDeltailBieumau($Chitieu, $arrBieumau, $Time, $tbl_chitietsolieutheobieu)
    {
        $total = 0;
        foreach ($arrBieumau as $bieumau) {
            // get Deltal
            $sum = 0;

            foreach ($tbl_chitietsolieutheobieu as $value) {
                if ($value->mabieusolieu == $bieumau->id && $value->chitieu == $Chitieu) {
                    $sum += $value->sanluong;
                }
            }
            if ($sum > $total) $total = $sum;
        }
        return $total;
    }


    private function getBieumauOfUnit($unit, $form, $year, $loaisolieu, $tbl_solieutheobieu)
    {
        $data = array();
        foreach ($tbl_solieutheobieu as $value) {
            if ($value->donvinhap == $unit && $value->namnhap == $year) {
                if ($value->bieumau == $form && $value->loaisolieu == $loaisolieu) {
                    array_push($data, $value);
                }
            }
        }
        return $data;
    }
    private function totalDeltailBieumauwithKy($Chitieu, $arrBieumau, $Time)
    {
        $total = 0;
        foreach ($arrBieumau as $bieumau) {
            // get Deltail
            $sum = DB::table('tbl_chitietsolieutheobieu')
                ->where('mabieusolieu', $bieumau->id)
                ->where('tbl_chitietsolieutheobieu.chitieu', $Chitieu)
                ->where('tbl_chitietsolieutheobieu.isDelete', 0)
                ->where('tbl_solieutheobieu.namnhap', $Time)
                ->sum('sanluong');
            $total += $sum;
        }
        return $total;
    }

    private function getListUnitOfxa($maxa)
    {
        $listUnit = tbl_donvihanhchinh::where('tbl_donvihanhchinh.isDelete', 0)
            ->where('tbl_donvihanhchinh.madonvi', '=', $maxa)
            ->select('tbl_donvihanhchinh.id', 'tbl_donvihanhchinh.tendonvi')
            ->get();
        return $listUnit;
    }

    // Bao cao tong hop cap xa
    public function danhsachXa()
    {
        $madiaban = session('madiabandvch');
        if ($madiaban == null) {
            $madiaban = session('madonvi');

            $data = tbl_donvihanhchinh::where('isDelete', 0)
                ->where('madonvi',  $madiaban)
                ->get();
            return response()->json($data);
        } else {
            $madonvi = session('madonvi');
            $data = tbl_donvihanhchinh::where('isDelete', 0)
                ->where('id',  $madonvi)
                ->get();
            return response()->json($data);
        }
    }
    public function danhsachTinh()
    {
        $data = tbl_donvihanhchinh::where('isDelete', 0)
            ->where('id',  105)
            ->get();
        return response()->json($data);
    }
    // Bao cao tong hop cap huyen
    public function listDonvihanhchinParent()
    {
        $madonvi = session('madonvi');
        $donvihanhchinh = tbl_donvihanhchinh::where('isDelete', '=', 0)
            ->where('id', '=', $madonvi)
            ->get();
        return response()->json($donvihanhchinh);
    }
    public function getChitieuString($tenchitieu)
    {
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        if ($donvicha == null) $donvicha = $madonvi;
        $data = tbl_chitieu::select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_donvitinh.tendonvi', 'tbl_chitieu.idcha')
            ->where('tenchitieu', $tenchitieu)
            ->where('tbl_chitieu.isDelete', 0)
            ->where('madonvi', '=', $donvicha)
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->orderBy('tbl_chitieu.thutu', 'desc')
            ->orderBy('tbl_chitieu.id')
            ->first();
        return $data;
    }
}
