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
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use ConvertApi\ConvertApi;
use CURLFile;
use Illuminate\Support\Arr;
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
	public function viewReportdubao(Request $request)
	{
		$madonvi = Session::get('madonvi');
		$donvicha = Session::get('donvicha');
		if ($donvicha == null) $donvicha = $madonvi;
		if ($donvicha == 106) $donvicha = $madonvi;
		$currentYear = $request->year;
		$periviousYear = $currentYear - 1;
		// $otherYear = $periviousYear - 1;
		$loaisolieu = $request->loaisolieu;
		$Form = $request->bieumau;
		$mau = $request->mau;
		$FormController = new NhaplieusolieuController();
		$listChitieu = $FormController->showDeltalChiTieu($Form);
		$tenloaisolieu = tbl_loaisolieu::where('id', $loaisolieu)->first();
		$Ultil = new ChitieuUltils();
		$TreeChitieu = $Ultil->getTreeChitieunew($Form);
		if ($mau != null) {
			$listChitieu = $FormController->showDeltalBieumauTH($mau);
			$TreeChitieu = $Ultil->getTreeChitieu($listChitieu);
		}
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
		//dd($datacha);
		//return 200;
		$data = tbl_chitieu::with('childrenAll')->where('tbl_chitieu.IsDelete', 0)
			->whereNotNull('tbl_chitieu.idcha')
			->join('tbl_chitietbieumau', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
			->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
			->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->orderBy('tbl_chitieu.thutu', 'desc')
			->orderBy('tbl_chitieu.id')
			->get();
		//dd($TreeChitieu);
		//return 200;

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
			$TotalofTHnam1 = $this->DataOfyearTH($currentYear - 5, $listXaofHuyen, $chitieu->id, $Form, 8);
			$TotalofTHnam2 = $this->DataOfyearTH($currentYear - 4, $listXaofHuyen, $chitieu->id, $Form, 8);
			$TotalofTHnam3 = $this->DataOfyearTH($currentYear - 3, $listXaofHuyen, $chitieu->id, $Form, 8);
			$TotalofTHnam4 = $this->DataOfyearTH($currentYear - 2, $listXaofHuyen, $chitieu->id, $Form, 8);
			$TotalofTHnam5 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $chitieu->id, $Form, 8);
			$TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $chitieu->id, $Form, $loaisolieu);
			$TotalofTHnam6 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $chitieu->id, $Form, 9);
			$TotalofTHnam7 = $this->DataOfyearTH($currentYear + 2, $listXaofHuyen, $chitieu->id, $Form, 9);
			$TotalofTHnam8 = $this->DataOfyearTH($currentYear + 3, $listXaofHuyen, $chitieu->id, $Form, 9);
			$TotalofTHnam9 = $this->DataOfyearTH($currentYear + 4, $listXaofHuyen, $chitieu->id, $Form, 9);
			$TotalofTHnam10 = $this->DataOfyearTH($currentYear + 5, $listXaofHuyen, $chitieu->id, $Form, 9);
			$TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $chitieu->id, $Form, 9);

			$ghichu1 = $this->ghichuDataOfyear($currentYear - 5, $listXaofHuyen, $chitieu->id, $Form, 8);
			$ghichu2 = $this->ghichuDataOfyear($currentYear - 4, $listXaofHuyen, $chitieu->id, $Form, 8);
			$ghichu3 = $this->ghichuDataOfyear($currentYear - 3, $listXaofHuyen, $chitieu->id, $Form, 8);
			$ghichu4 = $this->ghichuDataOfyear($currentYear - 2, $listXaofHuyen, $chitieu->id, $Form, 8);
			$ghichu5 = $this->ghichuDataOfyear($currentYear - 1, $listXaofHuyen, $chitieu->id, $Form, 8);
			$ghichu = $this->ghichuDataOfyear($currentYear, $listXaofHuyen, $chitieu->id, $Form, $loaisolieu);
			$ghichu6 = $this->ghichuDataOfyear($currentYear + 1, $listXaofHuyen, $chitieu->id, $Form, 9);
			$ghichu7 = $this->ghichuDataOfyear($currentYear + 2, $listXaofHuyen, $chitieu->id, $Form, 9);
			$ghichu8 = $this->ghichuDataOfyear($currentYear + 3, $listXaofHuyen, $chitieu->id, $Form, 9);
			$ghichu9 = $this->ghichuDataOfyear($currentYear + 4, $listXaofHuyen, $chitieu->id, $Form, 9);
			$ghichu10 = $this->ghichuDataOfyear($currentYear + 5, $listXaofHuyen, $chitieu->id, $Form, 9);

			$Item->ghichu1 = $ghichu1;
			$Item->ghichu2 = $ghichu2;
			$Item->ghichu3 = $ghichu3;
			$Item->ghichu4 = $ghichu4;
			$Item->ghichu5 = $ghichu5;
			$Item->ghichu = $ghichu;
			$Item->ghichu6 = $ghichu6;
			$Item->ghichu7 = $ghichu7;
			$Item->ghichu8 = $ghichu8;
			$Item->ghichu9 = $ghichu9;
			$Item->ghichu10 = $ghichu10;
			//dd($TotalofTHnam1);
			//return 200;
			//lay so lieu SS nam -10
			$GiaSS2010 = $this->SumdataXaTH($currentYear - 10, $donvicha, $chitieu->id, $Form, 33);

			//lay so lieu SS nam hien tại
			$GiaSS2020 = $this->SumdataXaTH($currentYear, $donvicha, $chitieu->id, $Form, 33);
			//lay so lieu TT nam 2019
			$GiaTH1 = $this->SumdataXaTH($currentYear - 5, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH2 = $this->SumdataXaTH($currentYear - 4, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH3 = $this->SumdataXaTH($currentYear - 3, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH4 = $this->SumdataXaTH($currentYear - 2, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH5 = $this->SumdataXaTH($currentYear - 1, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH = $this->SumdataXaTH($currentYear, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH6 = $this->SumdataXaTH($currentYear + 1, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH7 = $this->SumdataXaTH($currentYear + 2, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH8 = $this->SumdataXaTH($currentYear + 3, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH9 = $this->SumdataXaTH($currentYear + 4, $donvicha, $chitieu->id, $Form, 34);
			$GiaTH10 = $this->SumdataXaTH($currentYear + 5, $donvicha, $chitieu->id, $Form, 34);

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


				$THnam1 = $this->SumdataXaTH($currentYear - 5, $xa->id, $chitieu->id, $Form, 8);
				$THnam2 = $this->SumdataXaTH($currentYear - 4, $xa->id, $chitieu->id, $Form, 8);
				$THnam3 = $this->SumdataXaTH($currentYear - 3, $xa->id, $chitieu->id, $Form, 8);
				$THnam4 = $this->SumdataXaTH($currentYear - 2, $xa->id, $chitieu->id, $Form, 8);
				$THnam5 = $this->SumdataXaTH($currentYear - 1, $xa->id, $chitieu->id, $Form, 8);
				$THnam = $this->SumdataXaTH($currentYear, $xa->id, $chitieu->id, $Form, $loaisolieu);



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
			array_push($Result, $Item);
		}
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

		$thongtin->solieu = $tenloaisolieu['tenloaisolieu'];
		$tbbieumau = tbl_bieumau::where('id', $request->bieumau)->first();

		$thongtin->bieumau = $tbbieumau->tenbieumau;
		$dulieu->nutcha = $datacha;
		$dulieu->nutcon = $data;
		$dulieu->chitiet = $Result;
		$dulieu->chitiet1 = $Result;
		$dulieu->chitiet2 = $Result;
		$dulieu->chitiet3 = $Result;
		$dulieu->chitiet4 = $Result;
		$dulieu->thongtin = $thongtin;
		return response()->json($dulieu);
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
		if ($donvicha == 106) $donvicha = $madonvi;
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
		$bieumau = $request->loaimau;
		//xuat du lieu chupuh
		if ($madonvi == 20 || $donvicha == 20) {
			if ($bieumau == 2) return $this->viewcayanqua_chupu($request);
			return $this->viewchupuh($request);
		}
		//xuat du lieu dahoaui
		if ($madonvi == 60 || $donvicha == 60) {
			return $this->viewdahuoai($request);
			//return 200;
		}
		//xuat du lieu bacai
		if ($madonvi == 107 || $donvicha == 107) {
			return $this->viewbacai($request);
			//return 200;
		}
	}
	public function viewbacai(Request $request)
	{
		set_time_limit(20000);
		$madonvi = Session::get('madonvi');
		$donvicha = Session::get('donvicha');
		//$madonvi = 94;
		//$donvicha = 94;
		if ($donvicha == null) $donvicha = $madonvi;
		if ($donvicha == 106) $donvicha = $madonvi;
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
		//$chitieu=$sheetSelect->getCellByColumnAndRow(2, 135)->getValue();
		//$chitieu=trim(str_replace("'","","a. Trồng trọt"));
		//$dschitieu=$this->getChitieuString($chitieu);
		//$maid=$chitieu;
		//$TotalofTHnam = $this->DataOfyearTH(2020, $listXaofHuyen, '4689', '271',8);
		//$GiaSS2010 = $this->SumdataXaTH(2010, $donvicha, '2194', '255',33);
		//dd($TotalofTHnam);
		//return 200;
		//tieu de

		//if($loaimau==1)
		{
			$sheetSelect->setCellValueByColumnAndRow(1, 1, 'TỔNG HỢP GIÁ TRỊ SẢN XUẤT GIAI ĐOẠN ' . ($currentYear - 4) . ' - ' . ($currentYear) . ' VÀ DỰ BÁO GIAI ĐOẠN ' . ($currentYear + 1) . ' - ' . ($currentYear + 5));
			$sheetSelect->setCellValueByColumnAndRow(76, 1, $currentYear);
		}



		$Form = 273; //nong lam thuy san
		$rowstart = 47;
		$rowend = 160;
		for ($row = $rowstart; $row <= $rowend; $row++) {
			$chitieu = $sheetSelect->getCellByColumnAndRow(2, $row)->getValue();
			//$chitieu=trim(str_replace("'","",$chitieu));
			//$dschitieu=$this->getChitieuString($chitieu);
			//GIÁ TRỊ SẢN XUẤT
			if (strlen($chitieu) > 0) {
				$maid = $chitieu;
				////fill sản lượng 2015-2020 - Thực hiện 8/ KH 9
				$TotalofTHnam5 = $this->DataOfyearTH($currentYear - 5, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam4 = $this->DataOfyearTH($currentYear - 4, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam3 = $this->DataOfyearTH($currentYear - 3, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam2 = $this->DataOfyearTH($currentYear - 2, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam1 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
				$TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
				// fill ke hoach 2021-2025
				$TotalofKHnam1 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam2 = $this->DataOfyearTH($currentYear + 2, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam3 = $this->DataOfyearTH($currentYear + 3, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam4 = $this->DataOfyearTH($currentYear + 4, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam5 = $this->DataOfyearTH($currentYear + 5, $listXaofHuyen, $maid, $Form, 9);
				//fill gia so sanh
				$GiaSS2010 = $this->SumdataXaTH($currentYear - 10, $donvicha, $maid, $Form, 33);
				//fill chi so gia
				$GiaTT1 = $this->SumdataXaTH($currentYear - 5, $donvicha, $maid, $Form, 34);
				$GiaTT2 = $this->SumdataXaTH($currentYear - 4, $donvicha, $maid, $Form, 34);
				$GiaTT3 = $this->SumdataXaTH($currentYear - 3, $donvicha, $maid, $Form, 34);
				$GiaTT4 = $this->SumdataXaTH($currentYear - 2, $donvicha, $maid, $Form, 34);
				$GiaTT5 = $this->SumdataXaTH($currentYear - 1, $donvicha, $maid, $Form, 34);
				$GiaTT6 = $this->SumdataXaTH($currentYear, $donvicha, $maid, $Form, 34);
				$GiaTT7 = $this->SumdataXaTH($currentYear + 1, $donvicha, $maid, $Form, 34);
				$GiaTT8 = $this->SumdataXaTH($currentYear + 2, $donvicha, $maid, $Form, 34);
				$GiaTT9 = $this->SumdataXaTH($currentYear + 3, $donvicha, $maid, $Form, 34);
				$GiaTT10 = $this->SumdataXaTH($currentYear + 4, $donvicha, $maid, $Form, 34);
				$GiaTT11 = $this->SumdataXaTH($currentYear + 5, $donvicha, $maid, $Form, 34);

				$sheetSelect->setCellValueByColumnAndRow(17, $row, $TotalofTHnam5);
				$sheetSelect->setCellValueByColumnAndRow(18, $row, $TotalofTHnam4);
				$sheetSelect->setCellValueByColumnAndRow(19, $row, $TotalofTHnam3);
				$sheetSelect->setCellValueByColumnAndRow(20, $row, $TotalofTHnam2);
				$sheetSelect->setCellValueByColumnAndRow(21, $row, $TotalofTHnam1);
				$sheetSelect->setCellValueByColumnAndRow(22, $row, $TotalofTHnam);
				$sheetSelect->setCellValueByColumnAndRow(23, $row, $TotalofKHnam);
				$sheetSelect->setCellValueByColumnAndRow(24, $row, $TotalofKHnam1);
				$sheetSelect->setCellValueByColumnAndRow(25, $row, $TotalofKHnam2);
				$sheetSelect->setCellValueByColumnAndRow(26, $row, $TotalofKHnam3);
				$sheetSelect->setCellValueByColumnAndRow(27, $row, $TotalofKHnam4);
				$sheetSelect->setCellValueByColumnAndRow(28, $row, $TotalofKHnam5);
				//giá ss
				$sheetSelect->setCellValueByColumnAndRow(29, $row, $GiaSS2010);
				//giá tt
				$sheetSelect->setCellValueByColumnAndRow(30, $row, $GiaTT1);
				$sheetSelect->setCellValueByColumnAndRow(31, $row, $GiaTT2);
				$sheetSelect->setCellValueByColumnAndRow(32, $row, $GiaTT3);
				$sheetSelect->setCellValueByColumnAndRow(33, $row, $GiaTT4);
				$sheetSelect->setCellValueByColumnAndRow(34, $row, $GiaTT5);
				$sheetSelect->setCellValueByColumnAndRow(35, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(36, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(37, $row, $GiaTT7);
				$sheetSelect->setCellValueByColumnAndRow(38, $row, $GiaTT8);
				$sheetSelect->setCellValueByColumnAndRow(39, $row, $GiaTT9);
				$sheetSelect->setCellValueByColumnAndRow(40, $row, $GiaTT10);
				$sheetSelect->setCellValueByColumnAndRow(41, $row, $GiaTT11);
			}
		}
		$Form = 262; //CÔNG NGHIỆP - TTCN
		$rowstart = 161;
		$rowend = 230;
		for ($row = $rowstart; $row <= $rowend; $row++) {
			$chitieu = $sheetSelect->getCellByColumnAndRow(2, $row)->getValue();
			//$chitieu=trim(str_replace("'","",$chitieu));
			//$dschitieu=$this->getChitieuString($chitieu);
			//GIÁ TRỊ SẢN XUẤT
			if (strlen($chitieu) > 0) {
				$maid = $chitieu;
				////fill sản lượng 2015-2020 - Thực hiện 8/ KH 9
				$TotalofTHnam5 = $this->DataOfyearTH($currentYear - 5, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam4 = $this->DataOfyearTH($currentYear - 4, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam3 = $this->DataOfyearTH($currentYear - 3, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam2 = $this->DataOfyearTH($currentYear - 2, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam1 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
				$TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
				// fill ke hoach 2021-2025
				$TotalofKHnam1 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam2 = $this->DataOfyearTH($currentYear + 2, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam3 = $this->DataOfyearTH($currentYear + 3, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam4 = $this->DataOfyearTH($currentYear + 4, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam5 = $this->DataOfyearTH($currentYear + 5, $listXaofHuyen, $maid, $Form, 9);
				//fill gia so sanh
				$GiaSS2010 = $this->SumdataXaTH($currentYear - 10, $donvicha, $maid, $Form, 33);
				//fill chi so gia
				$GiaTT1 = $this->SumdataXaTH($currentYear - 5, $donvicha, $maid, $Form, 34);
				$GiaTT2 = $this->SumdataXaTH($currentYear - 4, $donvicha, $maid, $Form, 34);
				$GiaTT3 = $this->SumdataXaTH($currentYear - 3, $donvicha, $maid, $Form, 34);
				$GiaTT4 = $this->SumdataXaTH($currentYear - 2, $donvicha, $maid, $Form, 34);
				$GiaTT5 = $this->SumdataXaTH($currentYear - 1, $donvicha, $maid, $Form, 34);
				$GiaTT6 = $this->SumdataXaTH($currentYear, $donvicha, $maid, $Form, 34);
				$GiaTT7 = $this->SumdataXaTH($currentYear + 1, $donvicha, $maid, $Form, 34);
				$GiaTT8 = $this->SumdataXaTH($currentYear + 2, $donvicha, $maid, $Form, 34);
				$GiaTT9 = $this->SumdataXaTH($currentYear + 3, $donvicha, $maid, $Form, 34);
				$GiaTT10 = $this->SumdataXaTH($currentYear + 4, $donvicha, $maid, $Form, 34);
				$GiaTT11 = $this->SumdataXaTH($currentYear + 5, $donvicha, $maid, $Form, 34);

				$sheetSelect->setCellValueByColumnAndRow(17, $row, $TotalofTHnam5);
				$sheetSelect->setCellValueByColumnAndRow(18, $row, $TotalofTHnam4);
				$sheetSelect->setCellValueByColumnAndRow(19, $row, $TotalofTHnam3);
				$sheetSelect->setCellValueByColumnAndRow(20, $row, $TotalofTHnam2);
				$sheetSelect->setCellValueByColumnAndRow(21, $row, $TotalofTHnam1);
				$sheetSelect->setCellValueByColumnAndRow(22, $row, $TotalofTHnam);
				$sheetSelect->setCellValueByColumnAndRow(23, $row, $TotalofKHnam);
				$sheetSelect->setCellValueByColumnAndRow(24, $row, $TotalofKHnam1);
				$sheetSelect->setCellValueByColumnAndRow(25, $row, $TotalofKHnam2);
				$sheetSelect->setCellValueByColumnAndRow(26, $row, $TotalofKHnam3);
				$sheetSelect->setCellValueByColumnAndRow(27, $row, $TotalofKHnam4);
				$sheetSelect->setCellValueByColumnAndRow(28, $row, $TotalofKHnam5);
				//giá ss
				$sheetSelect->setCellValueByColumnAndRow(29, $row, $GiaSS2010);
				//giá tt
				$sheetSelect->setCellValueByColumnAndRow(30, $row, $GiaTT1);
				$sheetSelect->setCellValueByColumnAndRow(31, $row, $GiaTT2);
				$sheetSelect->setCellValueByColumnAndRow(32, $row, $GiaTT3);
				$sheetSelect->setCellValueByColumnAndRow(33, $row, $GiaTT4);
				$sheetSelect->setCellValueByColumnAndRow(34, $row, $GiaTT5);
				$sheetSelect->setCellValueByColumnAndRow(35, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(36, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(37, $row, $GiaTT7);
				$sheetSelect->setCellValueByColumnAndRow(38, $row, $GiaTT8);
				$sheetSelect->setCellValueByColumnAndRow(39, $row, $GiaTT9);
				$sheetSelect->setCellValueByColumnAndRow(40, $row, $GiaTT10);
				$sheetSelect->setCellValueByColumnAndRow(41, $row, $GiaTT11);
			}
		}


		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
		if (!file_exists(public_path('export'))) {
			mkdir(public_path('export'));
		}
		$writer->save(public_path('export') . "/" . $mau);
		// chuyen file pdf
		$fileName = explode('.', $mau);

		//$this->convertPDF(public_path('export') . "/".$mau , $fileName[0].".pdf");
		//return response()->json($fileName[0].".pdf");
		return response()->json($mau);
	}

	public function loadDataReport(Request $request)
	{
		$madonvi = Session::get('madonvi');
		$donvicha = Session::get('donvicha');
		if ($donvicha == null) $donvicha = $madonvi;
		$currentYear = $request->year;
		$periviousYear = $currentYear - 5;
		$nextYear = $currentYear + 1;
		$loaisolieu = $request->loaisolieu;
		$danhSachBieumau = [223, 237, 277, 220, 237];
		$loaimau = $request->loaimau;
		$listXaofHuyen = null;
		$location = $request->location;
		if ($location == 105) $location = $madonvi;
		$listDonVi = array();
		if ($request->diaban == 1 || $request->diaban == 3) {
			$listXaofHuyen = tbl_donvihanhchinh::where('madonvi', $location)
				->get();
			foreach ($listXaofHuyen as  $value) {
				array_push($listDonVi, $value->id);
			}
		} else {
			// Tong hop bao cao theo xa
			$listXaofHuyen = tbl_donvihanhchinh::where('id', $location)
				->get();
			foreach ($listXaofHuyen as $value) {
				array_push($listDonVi, $value->id);
			}
		}

		$tblsolieutheobieu = tbl_solieutheobieu::where('isDelete', 0)
			->whereBetween('namnhap', [$periviousYear, $nextYear])
			->whereIn('donvinhap', $listDonVi)
			->whereIn('bieumau', $danhSachBieumau)
			->get();
		$bieuMau = array();
		foreach ($tblsolieutheobieu as $item) {
			array_push($bieuMau, $item->id);
		}
		$tblchitietsolieutheobieu = tbl_chitietsolieutheobieu::where('isDelete', 0)
			->whereIn('mabieusolieu', $bieuMau)
			->get();
		return response()->json([
			'solieutheobieu' => $tblsolieutheobieu, 'chitietsolieutheobieu' => $tblchitietsolieutheobieu,
			'donvihanhchinh' => $listXaofHuyen, 'donvicha' => $donvicha
		]);
	}

	public function viewchupuh(Request $request)
	{
		$currentYear = $request->year;
		$mau = $request->mau;
		$data = json_decode($request->data);
		$sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/Excel') . '/' . $mau);
		$sheet->setActiveSheetIndex(0);
		$sheetSelect = $sheet->getActiveSheet();
		$sheetSelect->setCellValueByColumnAndRow(76, 1, $currentYear);

		# Phan 1 PHÒNG TÀI CHÍNH TỪ DÒNG
		$rowstart = 41;
		$phan1 = $data->phan1;

		foreach ($phan1 as $item) {
			$clolumsTH = $item->clolumsTH;
			$sheetSelect->setCellValueByColumnAndRow(17, $rowstart, $clolumsTH[0]);
			$sheetSelect->setCellValueByColumnAndRow(18, $rowstart, $clolumsTH[1]);
			$sheetSelect->setCellValueByColumnAndRow(19, $rowstart, $clolumsTH[2]);
			$sheetSelect->setCellValueByColumnAndRow(20, $rowstart, $clolumsTH[3]);

			$sheetSelect->setCellValueByColumnAndRow(21, $rowstart, $clolumsTH[4]);
			$sheetSelect->setCellValueByColumnAndRow(22, $rowstart, $clolumsTH[5]);
			$sheetSelect->setCellValueByColumnAndRow(23, $rowstart, $clolumsTH[6]);
			$columKH = $item->columnTKH;
			$sheetSelect->setCellValueByColumnAndRow(24, $rowstart, $columKH[0]);
			$sheetSelect->setCellValueByColumnAndRow(25, $rowstart, $columKH[1]);
			$sheetSelect->setCellValueByColumnAndRow(26, $rowstart, $columKH[2]);
			$sheetSelect->setCellValueByColumnAndRow(27, $rowstart, $columKH[3]);
			$sheetSelect->setCellValueByColumnAndRow(28, $rowstart, $columKH[4]);
			//giá ss
			$sheetSelect->setCellValueByColumnAndRow(29, $rowstart, $item->giaSS2010);
			$columnsGiaTT = $item->columnsGiaTT;
			//giá tt
			$sheetSelect->setCellValueByColumnAndRow(30, $rowstart, $columnsGiaTT[0]);
			$sheetSelect->setCellValueByColumnAndRow(31, $rowstart, $columnsGiaTT[1]);
			$sheetSelect->setCellValueByColumnAndRow(32, $rowstart, $columnsGiaTT[2]);
			$sheetSelect->setCellValueByColumnAndRow(33, $rowstart, $columnsGiaTT[3]);
			$sheetSelect->setCellValueByColumnAndRow(34, $rowstart, $columnsGiaTT[4]);
			$sheetSelect->setCellValueByColumnAndRow(35, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(36, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(37, $rowstart, $columnsGiaTT[6]);
			$sheetSelect->setCellValueByColumnAndRow(38, $rowstart, $columnsGiaTT[7]);
			$sheetSelect->setCellValueByColumnAndRow(39, $rowstart, $columnsGiaTT[8]);
			$sheetSelect->setCellValueByColumnAndRow(40, $rowstart, $columnsGiaTT[9]);
			$sheetSelect->setCellValueByColumnAndRow(41, $rowstart, $columnsGiaTT[10]);
			$rowstart++;
		}

		# Phan 2 CHỈ TIÊU XÃ HỘI HUYỆN 
		$rowstart = 61;
		$phan2 = $data->phan2;
		foreach ($phan2 as $item) {
			$clolumsTH = $item->clolumsTH;
			$sheetSelect->setCellValueByColumnAndRow(17, $rowstart, $clolumsTH[0]);
			$sheetSelect->setCellValueByColumnAndRow(18, $rowstart, $clolumsTH[1]);
			$sheetSelect->setCellValueByColumnAndRow(19, $rowstart, $clolumsTH[2]);
			$sheetSelect->setCellValueByColumnAndRow(20, $rowstart, $clolumsTH[3]);
			$sheetSelect->setCellValueByColumnAndRow(21, $rowstart, $clolumsTH[4]);
			$sheetSelect->setCellValueByColumnAndRow(22, $rowstart, $clolumsTH[5]);
			$sheetSelect->setCellValueByColumnAndRow(23, $rowstart, $clolumsTH[6]);
			$columKH = $item->columnTKH;
			$sheetSelect->setCellValueByColumnAndRow(24, $rowstart, $columKH[0]);
			$sheetSelect->setCellValueByColumnAndRow(25, $rowstart, $columKH[1]);
			$sheetSelect->setCellValueByColumnAndRow(26, $rowstart, $columKH[2]);
			$sheetSelect->setCellValueByColumnAndRow(27, $rowstart, $columKH[3]);
			$sheetSelect->setCellValueByColumnAndRow(28, $rowstart, $columKH[4]);
			//giá ss
			$sheetSelect->setCellValueByColumnAndRow(29, $rowstart, $item->giaSS2010);
			$columnsGiaTT = $item->columnsGiaTT;
			//giá tt
			$sheetSelect->setCellValueByColumnAndRow(30, $rowstart, $columnsGiaTT[0]);
			$sheetSelect->setCellValueByColumnAndRow(31, $rowstart, $columnsGiaTT[1]);
			$sheetSelect->setCellValueByColumnAndRow(32, $rowstart, $columnsGiaTT[2]);
			$sheetSelect->setCellValueByColumnAndRow(33, $rowstart, $columnsGiaTT[3]);
			$sheetSelect->setCellValueByColumnAndRow(34, $rowstart, $columnsGiaTT[4]);
			$sheetSelect->setCellValueByColumnAndRow(35, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(36, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(37, $rowstart, $columnsGiaTT[6]);
			$sheetSelect->setCellValueByColumnAndRow(38, $rowstart, $columnsGiaTT[7]);
			$sheetSelect->setCellValueByColumnAndRow(39, $rowstart, $columnsGiaTT[8]);
			$sheetSelect->setCellValueByColumnAndRow(40, $rowstart, $columnsGiaTT[9]);
			$sheetSelect->setCellValueByColumnAndRow(41, $rowstart, $columnsGiaTT[10]);
			$rowstart++;
		}
		# Phan 3 NÔNG LÂM THỦY SẢN
		$rowstart = 91;
		$phan3 = $data->phan3;
		foreach ($phan3 as $item) {
			$clolumsTH = $item->clolumsTH;
			$sheetSelect->setCellValueByColumnAndRow(17, $rowstart, $clolumsTH[0]);
			$sheetSelect->setCellValueByColumnAndRow(18, $rowstart, $clolumsTH[1]);
			$sheetSelect->setCellValueByColumnAndRow(19, $rowstart, $clolumsTH[2]);
			$sheetSelect->setCellValueByColumnAndRow(20, $rowstart, $clolumsTH[3]);
			$sheetSelect->setCellValueByColumnAndRow(21, $rowstart, $clolumsTH[4]);
			$sheetSelect->setCellValueByColumnAndRow(22, $rowstart, $clolumsTH[5]);
			$sheetSelect->setCellValueByColumnAndRow(23, $rowstart, $clolumsTH[6]);
			$columKH = $item->columnTKH;
			$sheetSelect->setCellValueByColumnAndRow(24, $rowstart, $columKH[0]);
			$sheetSelect->setCellValueByColumnAndRow(25, $rowstart, $columKH[1]);
			$sheetSelect->setCellValueByColumnAndRow(26, $rowstart, $columKH[2]);
			$sheetSelect->setCellValueByColumnAndRow(27, $rowstart, $columKH[3]);
			$sheetSelect->setCellValueByColumnAndRow(28, $rowstart, $columKH[4]);
			//giá ss
			$sheetSelect->setCellValueByColumnAndRow(29, $rowstart, $item->giaSS2010);
			$columnsGiaTT = $item->columnsGiaTT;
			//giá tt
			$sheetSelect->setCellValueByColumnAndRow(30, $rowstart, $columnsGiaTT[0]);
			$sheetSelect->setCellValueByColumnAndRow(31, $rowstart, $columnsGiaTT[1]);
			$sheetSelect->setCellValueByColumnAndRow(32, $rowstart, $columnsGiaTT[2]);
			$sheetSelect->setCellValueByColumnAndRow(33, $rowstart, $columnsGiaTT[3]);
			$sheetSelect->setCellValueByColumnAndRow(34, $rowstart, $columnsGiaTT[4]);
			$sheetSelect->setCellValueByColumnAndRow(35, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(36, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(37, $rowstart, $columnsGiaTT[6]);
			$sheetSelect->setCellValueByColumnAndRow(38, $rowstart, $columnsGiaTT[7]);
			$sheetSelect->setCellValueByColumnAndRow(39, $rowstart, $columnsGiaTT[8]);
			$sheetSelect->setCellValueByColumnAndRow(40, $rowstart, $columnsGiaTT[9]);
			$sheetSelect->setCellValueByColumnAndRow(41, $rowstart, $columnsGiaTT[10]);
			$rowstart++;
		}
		# Phan 4 PHÒNG KINH TẾ HẠ TẦNG
		$rowstart = 415;
		$phan4 = $data->phan4;
		foreach ($phan4 as $item) {
			$clolumsTH = $item->clolumsTH;
			$sheetSelect->setCellValueByColumnAndRow(17, $rowstart, $clolumsTH[0]);
			$sheetSelect->setCellValueByColumnAndRow(18, $rowstart, $clolumsTH[1]);
			$sheetSelect->setCellValueByColumnAndRow(19, $rowstart, $clolumsTH[2]);
			$sheetSelect->setCellValueByColumnAndRow(20, $rowstart, $clolumsTH[3]);
			$sheetSelect->setCellValueByColumnAndRow(21, $rowstart, $clolumsTH[4]);
			$sheetSelect->setCellValueByColumnAndRow(22, $rowstart, $clolumsTH[5]);
			$sheetSelect->setCellValueByColumnAndRow(23, $rowstart, $clolumsTH[6]);
			$columKH = $item->columnTKH;
			$sheetSelect->setCellValueByColumnAndRow(24, $rowstart, $columKH[0]);
			$sheetSelect->setCellValueByColumnAndRow(25, $rowstart, $columKH[1]);
			$sheetSelect->setCellValueByColumnAndRow(26, $rowstart, $columKH[2]);
			$sheetSelect->setCellValueByColumnAndRow(27, $rowstart, $columKH[3]);
			$sheetSelect->setCellValueByColumnAndRow(28, $rowstart, $columKH[4]);
			//giá ss
			$sheetSelect->setCellValueByColumnAndRow(29, $rowstart, $item->giaSS2010);
			$columnsGiaTT = $item->columnsGiaTT;
			//giá tt
			$sheetSelect->setCellValueByColumnAndRow(30, $rowstart, $columnsGiaTT[0]);
			$sheetSelect->setCellValueByColumnAndRow(31, $rowstart, $columnsGiaTT[1]);
			$sheetSelect->setCellValueByColumnAndRow(32, $rowstart, $columnsGiaTT[2]);
			$sheetSelect->setCellValueByColumnAndRow(33, $rowstart, $columnsGiaTT[3]);
			$sheetSelect->setCellValueByColumnAndRow(34, $rowstart, $columnsGiaTT[4]);
			$sheetSelect->setCellValueByColumnAndRow(35, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(36, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(37, $rowstart, $columnsGiaTT[6]);
			$sheetSelect->setCellValueByColumnAndRow(38, $rowstart, $columnsGiaTT[7]);
			$sheetSelect->setCellValueByColumnAndRow(39, $rowstart, $columnsGiaTT[8]);
			$sheetSelect->setCellValueByColumnAndRow(40, $rowstart, $columnsGiaTT[9]);
			$sheetSelect->setCellValueByColumnAndRow(41, $rowstart, $columnsGiaTT[10]);
			$rowstart++;
		}
		# Phan 5 CHỈ TIÊU XÃ HỘI TỈNH
		$rowstart = 453;
		$phan5 = $data->phan5;
		foreach ($phan5 as $item) {
			$clolumsTH = $item->clolumsTH;
			$sheetSelect->setCellValueByColumnAndRow(17, $rowstart, $clolumsTH[0]);
			$sheetSelect->setCellValueByColumnAndRow(18, $rowstart, $clolumsTH[1]);
			$sheetSelect->setCellValueByColumnAndRow(19, $rowstart, $clolumsTH[2]);
			$sheetSelect->setCellValueByColumnAndRow(20, $rowstart, $clolumsTH[3]);
			$sheetSelect->setCellValueByColumnAndRow(21, $rowstart, $clolumsTH[4]);
			$sheetSelect->setCellValueByColumnAndRow(22, $rowstart, $clolumsTH[5]);
			$sheetSelect->setCellValueByColumnAndRow(23, $rowstart, $clolumsTH[6]);
			$columKH = $item->columnTKH;
			$sheetSelect->setCellValueByColumnAndRow(24, $rowstart, $columKH[0]);
			$sheetSelect->setCellValueByColumnAndRow(25, $rowstart, $columKH[1]);
			$sheetSelect->setCellValueByColumnAndRow(26, $rowstart, $columKH[2]);
			$sheetSelect->setCellValueByColumnAndRow(27, $rowstart, $columKH[3]);
			$sheetSelect->setCellValueByColumnAndRow(28, $rowstart, $columKH[4]);
			//giá ss
			$sheetSelect->setCellValueByColumnAndRow(29, $rowstart, $item->giaSS2010);
			$columnsGiaTT = $item->columnsGiaTT;
			//giá tt
			$sheetSelect->setCellValueByColumnAndRow(30, $rowstart, $columnsGiaTT[0]);
			$sheetSelect->setCellValueByColumnAndRow(31, $rowstart, $columnsGiaTT[1]);
			$sheetSelect->setCellValueByColumnAndRow(32, $rowstart, $columnsGiaTT[2]);
			$sheetSelect->setCellValueByColumnAndRow(33, $rowstart, $columnsGiaTT[3]);
			$sheetSelect->setCellValueByColumnAndRow(34, $rowstart, $columnsGiaTT[4]);
			$sheetSelect->setCellValueByColumnAndRow(35, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(36, $rowstart, $columnsGiaTT[5]);
			$sheetSelect->setCellValueByColumnAndRow(37, $rowstart, $columnsGiaTT[6]);
			$sheetSelect->setCellValueByColumnAndRow(38, $rowstart, $columnsGiaTT[7]);
			$sheetSelect->setCellValueByColumnAndRow(39, $rowstart, $columnsGiaTT[8]);
			$sheetSelect->setCellValueByColumnAndRow(40, $rowstart, $columnsGiaTT[9]);
			$sheetSelect->setCellValueByColumnAndRow(41, $rowstart, $columnsGiaTT[10]);
			$rowstart++;
		}

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
		if (!file_exists(public_path('export'))) {
			mkdir(public_path('export'));
		}
		$writer->save(public_path('export') . "/" . $mau);
		return response()->json($mau);
	}
	public function viewdahuoai(Request $request)
	{
		set_time_limit(20000);
		$madonvi = Session::get('madonvi');
		$donvicha = Session::get('donvicha');
		//$madonvi = 94;
		//$donvicha = 94;
		if ($donvicha == null) $donvicha = $madonvi;
		if ($donvicha == 106) $donvicha = $madonvi;
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
		//$chitieu=$sheetSelect->getCellByColumnAndRow(2, 135)->getValue();
		//$chitieu=trim(str_replace("'","","a. Trồng trọt"));
		//$dschitieu=$this->getChitieuString($chitieu);
		//$maid=$chitieu;
		//$TotalofTHnam = $this->DataOfyearTH(2019, $listXaofHuyen, '2194', '255',8);
		//$GiaSS2010 = $this->SumdataXaTH(2010, $donvicha, '2194', '255',33);
		//dd($GiaSS2010);
		//return 200;
		//tieu de

		//if($loaimau==1)
		{
			$sheetSelect->setCellValueByColumnAndRow(1, 1, 'TỔNG HỢP GIÁ TRỊ SẢN XUẤT GIAI ĐOẠN ' . ($currentYear - 4) . ' - ' . ($currentYear) . ' VÀ DỰ BÁO GIAI ĐOẠN ' . ($currentYear + 1) . ' - ' . ($currentYear + 5));
			$sheetSelect->setCellValueByColumnAndRow(65, 1, $currentYear);
		}



		$Form = 255; //nong lam thuy san
		$rowstart = 43;
		$rowend = 140;
		for ($row = $rowstart; $row <= $rowend; $row++) {
			$chitieu = $sheetSelect->getCellByColumnAndRow(2, $row)->getValue();
			//$chitieu=trim(str_replace("'","",$chitieu));
			//$dschitieu=$this->getChitieuString($chitieu);
			//GIÁ TRỊ SẢN XUẤT
			if (strlen($chitieu) > 0) {
				$maid = $chitieu;
				////fill sản lượng 2015-2020 - Thực hiện 8/ KH 9
				$TotalofTHnam5 = $this->DataOfyearTH($currentYear - 5, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam4 = $this->DataOfyearTH($currentYear - 4, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam3 = $this->DataOfyearTH($currentYear - 3, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam2 = $this->DataOfyearTH($currentYear - 2, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam1 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
				$TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
				// fill ke hoach 2021-2025
				$TotalofKHnam1 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam2 = $this->DataOfyearTH($currentYear + 2, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam3 = $this->DataOfyearTH($currentYear + 3, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam4 = $this->DataOfyearTH($currentYear + 4, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam5 = $this->DataOfyearTH($currentYear + 5, $listXaofHuyen, $maid, $Form, 9);
				//fill gia so sanh
				$GiaSS2010 = $this->SumdataXaTH($currentYear - 10, $donvicha, $maid, $Form, 33);
				//fill chi so gia
				$GiaTT1 = $this->SumdataXaTH($currentYear - 5, $donvicha, $maid, $Form, 34);
				$GiaTT2 = $this->SumdataXaTH($currentYear - 4, $donvicha, $maid, $Form, 34);
				$GiaTT3 = $this->SumdataXaTH($currentYear - 3, $donvicha, $maid, $Form, 34);
				$GiaTT4 = $this->SumdataXaTH($currentYear - 2, $donvicha, $maid, $Form, 34);
				$GiaTT5 = $this->SumdataXaTH($currentYear - 1, $donvicha, $maid, $Form, 34);
				$GiaTT6 = $this->SumdataXaTH($currentYear, $donvicha, $maid, $Form, 34);
				$GiaTT7 = $this->SumdataXaTH($currentYear + 1, $donvicha, $maid, $Form, 34);
				$GiaTT8 = $this->SumdataXaTH($currentYear + 2, $donvicha, $maid, $Form, 34);
				$GiaTT9 = $this->SumdataXaTH($currentYear + 3, $donvicha, $maid, $Form, 34);
				$GiaTT10 = $this->SumdataXaTH($currentYear + 4, $donvicha, $maid, $Form, 34);
				$GiaTT11 = $this->SumdataXaTH($currentYear + 5, $donvicha, $maid, $Form, 34);

				$sheetSelect->setCellValueByColumnAndRow(17, $row, $TotalofTHnam5);
				$sheetSelect->setCellValueByColumnAndRow(18, $row, $TotalofTHnam4);
				$sheetSelect->setCellValueByColumnAndRow(19, $row, $TotalofTHnam3);
				$sheetSelect->setCellValueByColumnAndRow(20, $row, $TotalofTHnam2);
				$sheetSelect->setCellValueByColumnAndRow(21, $row, $TotalofTHnam1);
				$sheetSelect->setCellValueByColumnAndRow(22, $row, $TotalofTHnam);
				$sheetSelect->setCellValueByColumnAndRow(23, $row, $TotalofKHnam);
				$sheetSelect->setCellValueByColumnAndRow(24, $row, $TotalofKHnam1);
				$sheetSelect->setCellValueByColumnAndRow(25, $row, $TotalofKHnam2);
				$sheetSelect->setCellValueByColumnAndRow(26, $row, $TotalofKHnam3);
				$sheetSelect->setCellValueByColumnAndRow(27, $row, $TotalofKHnam4);
				$sheetSelect->setCellValueByColumnAndRow(28, $row, $TotalofKHnam5);
				//giá ss
				$sheetSelect->setCellValueByColumnAndRow(29, $row, $GiaSS2010);
				//giá tt
				$sheetSelect->setCellValueByColumnAndRow(30, $row, $GiaTT1);
				$sheetSelect->setCellValueByColumnAndRow(31, $row, $GiaTT2);
				$sheetSelect->setCellValueByColumnAndRow(32, $row, $GiaTT3);
				$sheetSelect->setCellValueByColumnAndRow(33, $row, $GiaTT4);
				$sheetSelect->setCellValueByColumnAndRow(34, $row, $GiaTT5);
				$sheetSelect->setCellValueByColumnAndRow(35, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(36, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(37, $row, $GiaTT7);
				$sheetSelect->setCellValueByColumnAndRow(38, $row, $GiaTT8);
				$sheetSelect->setCellValueByColumnAndRow(39, $row, $GiaTT9);
				$sheetSelect->setCellValueByColumnAndRow(40, $row, $GiaTT10);
				$sheetSelect->setCellValueByColumnAndRow(41, $row, $GiaTT11);
			}
		}
		$Form = 226; //CÔNG NGHIỆP - TTCN
		$rowstart = 141;
		$rowend = 172;
		for ($row = $rowstart; $row <= $rowend; $row++) {
			$chitieu = $sheetSelect->getCellByColumnAndRow(2, $row)->getValue();
			//$chitieu=trim(str_replace("'","",$chitieu));
			//$dschitieu=$this->getChitieuString($chitieu);
			//GIÁ TRỊ SẢN XUẤT
			if (strlen($chitieu) > 0) {
				$maid = $chitieu;
				////fill sản lượng 2015-2020 - Thực hiện 8/ KH 9
				$TotalofTHnam5 = $this->DataOfyearTH($currentYear - 5, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam4 = $this->DataOfyearTH($currentYear - 4, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam3 = $this->DataOfyearTH($currentYear - 3, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam2 = $this->DataOfyearTH($currentYear - 2, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam1 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
				$TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
				// fill ke hoach 2021-2025
				$TotalofKHnam1 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam2 = $this->DataOfyearTH($currentYear + 2, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam3 = $this->DataOfyearTH($currentYear + 3, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam4 = $this->DataOfyearTH($currentYear + 4, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam5 = $this->DataOfyearTH($currentYear + 5, $listXaofHuyen, $maid, $Form, 9);
				//fill gia so sanh
				$GiaSS2010 = $this->SumdataXaTH($currentYear - 10, $donvicha, $maid, $Form, 33);
				//fill chi so gia
				$GiaTT1 = $this->SumdataXaTH($currentYear - 5, $donvicha, $maid, $Form, 34);
				$GiaTT2 = $this->SumdataXaTH($currentYear - 4, $donvicha, $maid, $Form, 34);
				$GiaTT3 = $this->SumdataXaTH($currentYear - 3, $donvicha, $maid, $Form, 34);
				$GiaTT4 = $this->SumdataXaTH($currentYear - 2, $donvicha, $maid, $Form, 34);
				$GiaTT5 = $this->SumdataXaTH($currentYear - 1, $donvicha, $maid, $Form, 34);
				$GiaTT6 = $this->SumdataXaTH($currentYear, $donvicha, $maid, $Form, 34);
				$GiaTT7 = $this->SumdataXaTH($currentYear + 1, $donvicha, $maid, $Form, 34);
				$GiaTT8 = $this->SumdataXaTH($currentYear + 2, $donvicha, $maid, $Form, 34);
				$GiaTT9 = $this->SumdataXaTH($currentYear + 3, $donvicha, $maid, $Form, 34);
				$GiaTT10 = $this->SumdataXaTH($currentYear + 4, $donvicha, $maid, $Form, 34);
				$GiaTT11 = $this->SumdataXaTH($currentYear + 5, $donvicha, $maid, $Form, 34);

				$sheetSelect->setCellValueByColumnAndRow(17, $row, $TotalofTHnam5);
				$sheetSelect->setCellValueByColumnAndRow(18, $row, $TotalofTHnam4);
				$sheetSelect->setCellValueByColumnAndRow(19, $row, $TotalofTHnam3);
				$sheetSelect->setCellValueByColumnAndRow(20, $row, $TotalofTHnam2);
				$sheetSelect->setCellValueByColumnAndRow(21, $row, $TotalofTHnam1);
				$sheetSelect->setCellValueByColumnAndRow(22, $row, $TotalofTHnam);
				$sheetSelect->setCellValueByColumnAndRow(23, $row, $TotalofKHnam);
				$sheetSelect->setCellValueByColumnAndRow(24, $row, $TotalofKHnam1);
				$sheetSelect->setCellValueByColumnAndRow(25, $row, $TotalofKHnam2);
				$sheetSelect->setCellValueByColumnAndRow(26, $row, $TotalofKHnam3);
				$sheetSelect->setCellValueByColumnAndRow(27, $row, $TotalofKHnam4);
				$sheetSelect->setCellValueByColumnAndRow(28, $row, $TotalofKHnam5);
				//giá ss
				$sheetSelect->setCellValueByColumnAndRow(29, $row, $GiaSS2010);
				//giá tt
				$sheetSelect->setCellValueByColumnAndRow(30, $row, $GiaTT1);
				$sheetSelect->setCellValueByColumnAndRow(31, $row, $GiaTT2);
				$sheetSelect->setCellValueByColumnAndRow(32, $row, $GiaTT3);
				$sheetSelect->setCellValueByColumnAndRow(33, $row, $GiaTT4);
				$sheetSelect->setCellValueByColumnAndRow(34, $row, $GiaTT5);
				$sheetSelect->setCellValueByColumnAndRow(35, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(36, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(37, $row, $GiaTT7);
				$sheetSelect->setCellValueByColumnAndRow(38, $row, $GiaTT8);
				$sheetSelect->setCellValueByColumnAndRow(39, $row, $GiaTT9);
				$sheetSelect->setCellValueByColumnAndRow(40, $row, $GiaTT10);
				$sheetSelect->setCellValueByColumnAndRow(41, $row, $GiaTT11);
			}
		}
		$Form = 250; //XÂY DỰNG
		$rowstart = 173;
		$rowend = 177;
		for ($row = $rowstart; $row <= $rowend; $row++) {
			$chitieu = $sheetSelect->getCellByColumnAndRow(2, $row)->getValue();
			//$chitieu=trim(str_replace("'","",$chitieu));
			//$dschitieu=$this->getChitieuString($chitieu);
			//GIÁ TRỊ SẢN XUẤT
			if (strlen($chitieu) > 0) {
				$maid = $chitieu;
				////fill sản lượng 2015-2020 - Thực hiện 8/ KH 9
				$TotalofTHnam5 = $this->DataOfyearTH($currentYear - 5, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam4 = $this->DataOfyearTH($currentYear - 4, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam3 = $this->DataOfyearTH($currentYear - 3, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam2 = $this->DataOfyearTH($currentYear - 2, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam1 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
				$TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
				// fill ke hoach 2021-2025
				$TotalofKHnam1 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam2 = $this->DataOfyearTH($currentYear + 2, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam3 = $this->DataOfyearTH($currentYear + 3, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam4 = $this->DataOfyearTH($currentYear + 4, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam5 = $this->DataOfyearTH($currentYear + 5, $listXaofHuyen, $maid, $Form, 9);
				//fill gia so sanh
				$GiaSS2010 = $this->SumdataXaTH($currentYear - 10, $donvicha, $maid, $Form, 33);
				//fill chi so gia
				$GiaTT1 = $this->SumdataXaTH($currentYear - 5, $donvicha, $maid, $Form, 34);
				$GiaTT2 = $this->SumdataXaTH($currentYear - 4, $donvicha, $maid, $Form, 34);
				$GiaTT3 = $this->SumdataXaTH($currentYear - 3, $donvicha, $maid, $Form, 34);
				$GiaTT4 = $this->SumdataXaTH($currentYear - 2, $donvicha, $maid, $Form, 34);
				$GiaTT5 = $this->SumdataXaTH($currentYear - 1, $donvicha, $maid, $Form, 34);
				$GiaTT6 = $this->SumdataXaTH($currentYear, $donvicha, $maid, $Form, 34);
				$GiaTT7 = $this->SumdataXaTH($currentYear + 1, $donvicha, $maid, $Form, 34);
				$GiaTT8 = $this->SumdataXaTH($currentYear + 2, $donvicha, $maid, $Form, 34);
				$GiaTT9 = $this->SumdataXaTH($currentYear + 3, $donvicha, $maid, $Form, 34);
				$GiaTT10 = $this->SumdataXaTH($currentYear + 4, $donvicha, $maid, $Form, 34);
				$GiaTT11 = $this->SumdataXaTH($currentYear + 5, $donvicha, $maid, $Form, 34);

				$sheetSelect->setCellValueByColumnAndRow(17, $row, $TotalofTHnam5);
				$sheetSelect->setCellValueByColumnAndRow(18, $row, $TotalofTHnam4);
				$sheetSelect->setCellValueByColumnAndRow(19, $row, $TotalofTHnam3);
				$sheetSelect->setCellValueByColumnAndRow(20, $row, $TotalofTHnam2);
				$sheetSelect->setCellValueByColumnAndRow(21, $row, $TotalofTHnam1);
				$sheetSelect->setCellValueByColumnAndRow(22, $row, $TotalofTHnam);
				$sheetSelect->setCellValueByColumnAndRow(23, $row, $TotalofKHnam);
				$sheetSelect->setCellValueByColumnAndRow(24, $row, $TotalofKHnam1);
				$sheetSelect->setCellValueByColumnAndRow(25, $row, $TotalofKHnam2);
				$sheetSelect->setCellValueByColumnAndRow(26, $row, $TotalofKHnam3);
				$sheetSelect->setCellValueByColumnAndRow(27, $row, $TotalofKHnam4);
				$sheetSelect->setCellValueByColumnAndRow(28, $row, $TotalofKHnam5);
				//giá ss
				$sheetSelect->setCellValueByColumnAndRow(29, $row, $GiaSS2010);
				//giá tt
				$sheetSelect->setCellValueByColumnAndRow(30, $row, $GiaTT1);
				$sheetSelect->setCellValueByColumnAndRow(31, $row, $GiaTT2);
				$sheetSelect->setCellValueByColumnAndRow(32, $row, $GiaTT3);
				$sheetSelect->setCellValueByColumnAndRow(33, $row, $GiaTT4);
				$sheetSelect->setCellValueByColumnAndRow(34, $row, $GiaTT5);
				$sheetSelect->setCellValueByColumnAndRow(35, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(36, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(37, $row, $GiaTT7);
				$sheetSelect->setCellValueByColumnAndRow(38, $row, $GiaTT8);
				$sheetSelect->setCellValueByColumnAndRow(39, $row, $GiaTT9);
				$sheetSelect->setCellValueByColumnAndRow(40, $row, $GiaTT10);
				$sheetSelect->setCellValueByColumnAndRow(41, $row, $GiaTT11);
			}
		}
		$Form = 227; // DỊCH VỤ
		$rowstart = 178;
		$rowend = 210;
		for ($row = $rowstart; $row <= $rowend; $row++) {
			$chitieu = $sheetSelect->getCellByColumnAndRow(2, $row)->getValue();
			//$chitieu=trim(str_replace("'","",$chitieu));
			//$dschitieu=$this->getChitieuString($chitieu);
			//GIÁ TRỊ SẢN XUẤT
			if (strlen($chitieu) > 0) {
				$maid = $chitieu;
				////fill sản lượng 2015-2020 - Thực hiện 8/ KH 9
				$TotalofTHnam5 = $this->DataOfyearTH($currentYear - 5, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam4 = $this->DataOfyearTH($currentYear - 4, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam3 = $this->DataOfyearTH($currentYear - 3, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam2 = $this->DataOfyearTH($currentYear - 2, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam1 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
				$TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
				// fill ke hoach 2021-2025
				$TotalofKHnam1 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam2 = $this->DataOfyearTH($currentYear + 2, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam3 = $this->DataOfyearTH($currentYear + 3, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam4 = $this->DataOfyearTH($currentYear + 4, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam5 = $this->DataOfyearTH($currentYear + 5, $listXaofHuyen, $maid, $Form, 9);
				//fill gia so sanh
				$GiaSS2010 = $this->SumdataXaTH($currentYear - 10, $donvicha, $maid, $Form, 33);
				//fill chi so gia
				$GiaTT1 = $this->SumdataXaTH($currentYear - 5, $donvicha, $maid, $Form, 34);
				$GiaTT2 = $this->SumdataXaTH($currentYear - 4, $donvicha, $maid, $Form, 34);
				$GiaTT3 = $this->SumdataXaTH($currentYear - 3, $donvicha, $maid, $Form, 34);
				$GiaTT4 = $this->SumdataXaTH($currentYear - 2, $donvicha, $maid, $Form, 34);
				$GiaTT5 = $this->SumdataXaTH($currentYear - 1, $donvicha, $maid, $Form, 34);
				$GiaTT6 = $this->SumdataXaTH($currentYear, $donvicha, $maid, $Form, 34);
				$GiaTT7 = $this->SumdataXaTH($currentYear + 1, $donvicha, $maid, $Form, 34);
				$GiaTT8 = $this->SumdataXaTH($currentYear + 2, $donvicha, $maid, $Form, 34);
				$GiaTT9 = $this->SumdataXaTH($currentYear + 3, $donvicha, $maid, $Form, 34);
				$GiaTT10 = $this->SumdataXaTH($currentYear + 4, $donvicha, $maid, $Form, 34);
				$GiaTT11 = $this->SumdataXaTH($currentYear + 5, $donvicha, $maid, $Form, 34);

				$sheetSelect->setCellValueByColumnAndRow(17, $row, $TotalofTHnam5);
				$sheetSelect->setCellValueByColumnAndRow(18, $row, $TotalofTHnam4);
				$sheetSelect->setCellValueByColumnAndRow(19, $row, $TotalofTHnam3);
				$sheetSelect->setCellValueByColumnAndRow(20, $row, $TotalofTHnam2);
				$sheetSelect->setCellValueByColumnAndRow(21, $row, $TotalofTHnam1);
				$sheetSelect->setCellValueByColumnAndRow(22, $row, $TotalofTHnam);
				$sheetSelect->setCellValueByColumnAndRow(23, $row, $TotalofKHnam);
				$sheetSelect->setCellValueByColumnAndRow(24, $row, $TotalofKHnam1);
				$sheetSelect->setCellValueByColumnAndRow(25, $row, $TotalofKHnam2);
				$sheetSelect->setCellValueByColumnAndRow(26, $row, $TotalofKHnam3);
				$sheetSelect->setCellValueByColumnAndRow(27, $row, $TotalofKHnam4);
				$sheetSelect->setCellValueByColumnAndRow(28, $row, $TotalofKHnam5);
				//giá ss
				$sheetSelect->setCellValueByColumnAndRow(29, $row, $GiaSS2010);
				//giá tt
				$sheetSelect->setCellValueByColumnAndRow(30, $row, $GiaTT1);
				$sheetSelect->setCellValueByColumnAndRow(31, $row, $GiaTT2);
				$sheetSelect->setCellValueByColumnAndRow(32, $row, $GiaTT3);
				$sheetSelect->setCellValueByColumnAndRow(33, $row, $GiaTT4);
				$sheetSelect->setCellValueByColumnAndRow(34, $row, $GiaTT5);
				$sheetSelect->setCellValueByColumnAndRow(35, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(36, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(37, $row, $GiaTT7);
				$sheetSelect->setCellValueByColumnAndRow(38, $row, $GiaTT8);
				$sheetSelect->setCellValueByColumnAndRow(39, $row, $GiaTT9);
				$sheetSelect->setCellValueByColumnAndRow(40, $row, $GiaTT10);
				$sheetSelect->setCellValueByColumnAndRow(41, $row, $GiaTT11);
			}
		}


		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
		if (!file_exists(public_path('export'))) {
			mkdir(public_path('export'));
		}
		$writer->save(public_path('export') . "/" . $mau);
		// chuyen file pdf
		$fileName = explode('.', $mau);

		//$this->convertPDF(public_path('export') . "/".$mau , $fileName[0].".pdf");
		//return response()->json($fileName[0].".pdf");
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
		if ($donvicha == 106) $donvicha = $madonvi;
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
	public function ghichuDataOfyear($year, $listXa, $chitieu, $bieumau, $loaisolieu)
	{
		$total = '';
		foreach ($listXa as $xa) {
			$listBieumau = $this->getBieumauOfUnit($xa->id, $bieumau, $year, $loaisolieu);
			//dd($listBieumau);
			//return 200;
			$sum = $this->ghichuBieumau($chitieu, $listBieumau, $year);
			$total = $sum;
		}
		return $total;
	}
	public function DataOfyearTH($year, $listXa, $chitieu, $bieumau, $loaisolieu)
	{
		$total = 0;
		foreach ($listXa as $xa) {
			$listBieumau = tbl_solieutheobieu::where('donvinhap', $xa->id)
				->where('tbl_solieutheobieu.bieumau', $bieumau)
				->where('tbl_solieutheobieu.isDelete', 0)
				->where('tbl_solieutheobieu.namnhap', $year)
				->where('tbl_solieutheobieu.loaisolieu', $loaisolieu)
				->get();
			//dd($listBieumau);
			//return 200;
			$sum = $this->maxtotalDeltailBieumau($chitieu, $listBieumau, $year);
			$total += $sum;
		}
		return $total;
	}
	private function SumdataXaTH($year, $xa, $chitieu, $bieumau, $loaisolieu)
	{
		$total = 0;
		//  $listUnit = $this->getListUnitOfxa($xa);
		$listBieumau = tbl_solieutheobieu::where('donvinhap', $xa)
			->where('tbl_solieutheobieu.bieumau', $bieumau)
			->where('tbl_solieutheobieu.isDelete', 0)
			->where('tbl_solieutheobieu.namnhap', $year)
			->where('tbl_solieutheobieu.loaisolieu', $loaisolieu)
			->get();

		$sum = $this->maxtotalDeltailBieumau($chitieu, $listBieumau, $year);
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
				if ($value->mabieusolieu == $bieumau->id && $value->chitieu == $Chitieu) {
					$sum = $value->sanluong;
					break;
				}
			}
			$total += $sum;
		}
		return $total;
	}
	private function ghichuBieumau($Chitieu, $arrBieumau, $Time)
	{
		$ghichu = '';
		foreach ($arrBieumau as $bieumau) {
			// get Deltal
			$sum = DB::table('tbl_chitietsolieutheobieu')
				->where('mabieusolieu', $bieumau->id)
				->where('tbl_chitietsolieutheobieu.chitieu', $Chitieu)
				->where('tbl_chitietsolieutheobieu.isDelete', 0)
				->select('ghichu')->first();
			if ($sum != null)
				$ghichu = $sum->ghichu;
		}
		return $ghichu;
	}
	private function maxtotalDeltailBieumau($Chitieu, $arrBieumau, $Time)
	{
		$total = 0;
		foreach ($arrBieumau as $bieumau) {
			// get Deltal
			$sum = DB::table('tbl_chitietsolieutheobieu')
				->where('mabieusolieu', $bieumau->id)
				->where('tbl_chitietsolieutheobieu.chitieu', $Chitieu)
				->where('tbl_chitietsolieutheobieu.isDelete', 0)
				->sum('sanluong');
			if ($sum > $total) $total = $sum;
		}
		return $total;
	}


	private function getBieumauOfUnit($unit, $form, $year, $loaisolieu)
	{
		//$loaisolieu TH năm= 8, KH năm=9
		$data = tbl_solieutheobieu::where('donvinhap', $unit)
			->where('tbl_solieutheobieu.isDelete', 0)
			->where('bieumau', $form)
			->where('tbl_solieutheobieu.namnhap', $year)
			->where('tbl_solieutheobieu.loaisolieu', $loaisolieu)
			->get();
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
		if ($donvicha == 106) $donvicha = $madonvi;
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
	public function getDataDubao(Request $request)
	{

		$tblChitietbieumau = tbl_chitietbieumau::where('tbl_chitietbieumau.isDelete', 0)
			->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
			->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
			->select('tbl_chitietbieumau.id', 'tbl_chitietbieumau.bieumau', 'tbl_chitietbieumau.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->get();
		$tblsolieutheobieu = tbl_solieutheobieu::where('isDelete', 0)
			->get();
		$tbl_chitietsolieutheobieu = tbl_chitietsolieutheobieu::where('isDelete', 0)->get();
		$madonvi = Session::get('madonvi');
		$donvicha = Session::get('donvicha');
		if ($donvicha == null) $donvicha = $madonvi;
		$currentYear = $request->year;
		$periviousYear = $currentYear - 1;
		// $otherYear = $periviousYear - 1;
		$loaisolieu = $request->loaisolieu;
		$Form = $request->bieumau;
		$mau = $request->mau;
		$FormController = new NhaplieusolieuController();
		$listChitieu = $FormController->showDeltalChiTieu($Form);
		$tenloaisolieu = tbl_loaisolieu::where('id', $loaisolieu)->first();
		$Ultil = new ChitieuUltils();
		$TreeChitieu = $Ultil->getTreeChitieunew($Form);
		if ($mau != null) {
			$listChitieu = $FormController->showDeltalBieumauTH($mau);
			$TreeChitieu = $Ultil->getTreeChitieu($listChitieu);
		}
		$listXaofHuyen = null;
		if ($request->diaban == 1) {
			$listXaofHuyen = tbl_donvihanhchinh::where('madonvi', $request->location)
				->get();
		} else {
			// Tong hop bao cao theo xa
			$listXaofHuyen = tbl_donvihanhchinh::where('id', $request->location)
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
		//dd($datacha);
		//return 200;
		$data = tbl_chitieu::with('childrenAll')->where('tbl_chitieu.IsDelete', 0)
			->whereNotNull('tbl_chitieu.idcha')
			->join('tbl_chitietbieumau', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
			->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
			->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->orderBy('tbl_chitieu.thutu', 'desc')
			->orderBy('tbl_chitieu.id')
			->get();
		//dd($TreeChitieu);
		//return 200;

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
		$tbbieumau = tbl_bieumau::where('id', $Form)->first();



		return response()->json(['madonvi' => $madonvi, 'donvicha' => $donvicha, 'tree' => $TreeChitieu, 'listxahuyen' => $listXaofHuyen, 'data' => $data, 'datacha' => $datacha, 'tblChitietbieumau' => $tblChitietbieumau, 'tblsolieutheobieu' => $tblsolieutheobieu, 'tbl_chitietsolieutheobieu' => $tbl_chitietsolieutheobieu, 'tenloaisolieu' => $tenloaisolieu, 'bieumau' => $tbbieumau]);
	}
	public function getDataViewReport(Request $request)
	{


		$tblChitietbieumau = tbl_chitietbieumau::where('tbl_chitietbieumau.isDelete', 0)
			->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
			->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
			->select('tbl_chitietbieumau.id', 'tbl_chitietbieumau.bieumau', 'tbl_chitietbieumau.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->get();
		$tblsolieutheobieu = tbl_solieutheobieu::where('isDelete', 0)
			->get();
		$tbl_chitietsolieutheobieu = tbl_chitietsolieutheobieu::where('isDelete', 0)->get();
		$madonvi = Session::get('madonvi');
		$donvicha = Session::get('donvicha');
		//$madonvi = 94;
		//$donvicha = 94;
		if ($donvicha == null) $donvicha = $madonvi;
		if ($donvicha == 106) $donvicha = $madonvi;
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

		return response()->json(['madonvi' => $madonvi, 'donvicha' => $donvicha, 'tree' => $TreeChitieu, 'listxahuyen' => $listXaofHuyen, 'data' => $data, 'datacha' => $datacha, 'tblChitietbieumau' => $tblChitietbieumau, 'tblsolieutheobieu' => $tblsolieutheobieu, 'tbl_chitietsolieutheobieu' => $tbl_chitietsolieutheobieu, 'tenloaisolieu' => $tenloaisolieu]);
	}
	/**
	 * Hàm chuyển đổi Excel sang PDF
	 *
	 * @param string  fileExcel $fileExcel đường dẫn file excel muốn chuyển đổi
	 * @param string PageOrientation $PageOrientation phương nằm ngang hay nằm dọc Values: default, portrait, landscape
	 * @param string PageSize $PageSize Kích thước khổ giấy Values: default, letter, lettersmall, tabloid, ledger.....
	 * @return string
	 */
	public function convertPDF($file, $fileName) //$fileExcel, $PageOrientation = "landscape", $PageSize, $filename
	{

		$ID = '953efc4a-7773-4804-8697-62f77531300d';
		$key = 'c36085d8-102e-4e88-8f68-920f557ffdfa';
		$url = 'https://api2.docconversionapi.com/jobs/create';

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => array('inputFile' => new CURLFile($file), 'conversionParameters' => '{"pdfType" : "1B", "fitToPage" : true}', 'outputFormat' => 'pdf', 'async' => 'false'),
			CURLOPT_HTTPHEADER => array(
				"X-ApplicationID: " . $ID,
				"X-SecretKey: " . $key
			),
		));

		$response = curl_exec($curl);
		$result = json_decode($response);
		file_put_contents(public_path('export') . '\\' . $fileName, fopen($result->fileDownloadUrl, 'r'));
		curl_close($curl);
		return $response; //$fileName;
	}
	public function viewcayanqua_chupu(Request $request)
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
		//$TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, '2657', '278',8);
		//$GiaSS2010 = $this->SumdataXaTH($currentYear-10, $donvicha, '2640', '216',33);
		//dd($TotalofTHnam);
		//return 200;
		//tieu de

		$sheetSelect->setCellValueByColumnAndRow(76, 1, $currentYear);

		$Form = 278; //cay an qua
		$rowstart = 6;
		$rowend = 22;
		for ($row = $rowstart; $row <= $rowend; $row++) {
			$chitieu = $sheetSelect->getCellByColumnAndRow(2, $row)->getValue();
			//$chitieu=trim(str_replace("'","",$chitieu));
			//$dschitieu=$this->getChitieuString($chitieu);
			//GIÁ TRỊ SẢN XUẤT
			if (strlen($chitieu) > 0) {
				$maid = $chitieu;
				////fill sản lượng 2015-2020 - Thực hiện 8/ KH 9
				$TotalofTHnam5 = $this->DataOfyearTH($currentYear - 5, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam4 = $this->DataOfyearTH($currentYear - 4, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam3 = $this->DataOfyearTH($currentYear - 3, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam2 = $this->DataOfyearTH($currentYear - 2, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam1 = $this->DataOfyearTH($currentYear - 1, $listXaofHuyen, $maid, $Form, 8);
				$TotalofTHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 8);
				$TotalofKHnam = $this->DataOfyearTH($currentYear, $listXaofHuyen, $maid, $Form, 9);
				// fill ke hoach 2021-2025
				$TotalofKHnam1 = $this->DataOfyearTH($currentYear + 1, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam2 = $this->DataOfyearTH($currentYear + 2, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam3 = $this->DataOfyearTH($currentYear + 3, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam4 = $this->DataOfyearTH($currentYear + 4, $listXaofHuyen, $maid, $Form, 9);
				$TotalofKHnam5 = $this->DataOfyearTH($currentYear + 5, $listXaofHuyen, $maid, $Form, 9);
				//fill gia so sanh
				$GiaSS2010 = $this->SumdataXaTH($currentYear - 10, $donvicha, $maid, $Form, 33);
				//fill chi so gia
				$GiaTT1 = $this->SumdataXaTH($currentYear - 5, $donvicha, $maid, $Form, 34);
				$GiaTT2 = $this->SumdataXaTH($currentYear - 4, $donvicha, $maid, $Form, 34);
				$GiaTT3 = $this->SumdataXaTH($currentYear - 3, $donvicha, $maid, $Form, 34);
				$GiaTT4 = $this->SumdataXaTH($currentYear - 2, $donvicha, $maid, $Form, 34);
				$GiaTT5 = $this->SumdataXaTH($currentYear - 1, $donvicha, $maid, $Form, 34);
				$GiaTT6 = $this->SumdataXaTH($currentYear, $donvicha, $maid, $Form, 34);
				$GiaTT7 = $this->SumdataXaTH($currentYear + 1, $donvicha, $maid, $Form, 34);
				$GiaTT8 = $this->SumdataXaTH($currentYear + 2, $donvicha, $maid, $Form, 34);
				$GiaTT9 = $this->SumdataXaTH($currentYear + 3, $donvicha, $maid, $Form, 34);
				$GiaTT10 = $this->SumdataXaTH($currentYear + 4, $donvicha, $maid, $Form, 34);
				$GiaTT11 = $this->SumdataXaTH($currentYear + 5, $donvicha, $maid, $Form, 34);

				$sheetSelect->setCellValueByColumnAndRow(17, $row, $TotalofTHnam5);
				$sheetSelect->setCellValueByColumnAndRow(18, $row, $TotalofTHnam4);
				$sheetSelect->setCellValueByColumnAndRow(19, $row, $TotalofTHnam3);
				$sheetSelect->setCellValueByColumnAndRow(20, $row, $TotalofTHnam2);
				$sheetSelect->setCellValueByColumnAndRow(21, $row, $TotalofTHnam1);
				$sheetSelect->setCellValueByColumnAndRow(22, $row, $TotalofTHnam);
				$sheetSelect->setCellValueByColumnAndRow(23, $row, $TotalofKHnam);
				$sheetSelect->setCellValueByColumnAndRow(24, $row, $TotalofKHnam1);
				$sheetSelect->setCellValueByColumnAndRow(25, $row, $TotalofKHnam2);
				$sheetSelect->setCellValueByColumnAndRow(26, $row, $TotalofKHnam3);
				$sheetSelect->setCellValueByColumnAndRow(27, $row, $TotalofKHnam4);
				$sheetSelect->setCellValueByColumnAndRow(28, $row, $TotalofKHnam5);
				//giá ss
				$sheetSelect->setCellValueByColumnAndRow(29, $row, $GiaSS2010);
				//giá tt
				$sheetSelect->setCellValueByColumnAndRow(30, $row, $GiaTT1);
				$sheetSelect->setCellValueByColumnAndRow(31, $row, $GiaTT2);
				$sheetSelect->setCellValueByColumnAndRow(32, $row, $GiaTT3);
				$sheetSelect->setCellValueByColumnAndRow(33, $row, $GiaTT4);
				$sheetSelect->setCellValueByColumnAndRow(34, $row, $GiaTT5);
				$sheetSelect->setCellValueByColumnAndRow(35, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(36, $row, $GiaTT6);
				$sheetSelect->setCellValueByColumnAndRow(37, $row, $GiaTT7);
				$sheetSelect->setCellValueByColumnAndRow(38, $row, $GiaTT8);
				$sheetSelect->setCellValueByColumnAndRow(39, $row, $GiaTT9);
				$sheetSelect->setCellValueByColumnAndRow(40, $row, $GiaTT10);
				$sheetSelect->setCellValueByColumnAndRow(41, $row, $GiaTT11);
			}
		}



		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
		if (!file_exists(public_path('export'))) {
			mkdir(public_path('export'));
		}
		$writer->save(public_path('export') . "/" . $mau);
		// chuyen file pdf
		$fileName = explode('.', $mau);

		$this->convertPDF(public_path('export') . "/" . $mau, $fileName[0] . ".pdf");
		//return response()->json($fileName[0].".pdf");
		return response()->json($mau);
	}
}
