<?php

namespace App\Http\Controllers\Khaithacthongtin\Sosanhsolieuky_nam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\devvn_quanhuyen;
use App\devvn_tinhthanhpho;
use App\tbl_donvihanhchinh;
use App\tbl_solieutheobieu;
use App\tbl_kybaocao;
use App\tbl_chitieu;
use App\tbl_chitieuses;
use App\tbl_bieumau;
use App\tbl_loaisolieu;
use App\tbl_chitietbieumau;
use App\tbl_chitietsolieutheobieu;
use stdClass;
use Session;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class Sosanhsolieuky_nam extends Controller
{
	public function viewsosanhsolieu()
	{
		return view('ktxh.Khaithacthongtin.Sosanhsolieuky_nam.list');
	}
	public function getlisttinh()
	{
		$data = DB::table('tbl_tinh')->where('isDelete',0)
		->select('id', '_name as tinh')
		->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getlisthuyen(Request $rq)
	{
		$data = DB::table('tbl_quanhuyen')->where('isDelete',0)
		->where("_province_id", $rq->matinh)->select('id', '_name as huyen', '_province_id as matinh','isDelete')
		->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getlistxa(Request $rq)
	{
		$data = DB::table('tbl_xaphuong')->where('isDelete',0)
		->where("_district_id", $rq->mahuyen)->select('id', '_name as xa', '_district_id as mahuyen')
		->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getmadonvi(Request $rq)
	{
		$data = tbl_donvihanhchinh::where('id', $rq->id)->where('isDelete',0)->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getdonvihanhchinh(Request $rq)
	{
		 $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
		if( $donvicha==null)
		{
			$data = tbl_donvihanhchinh::where('isDelete',0)
				->Where(function ($query) use ($madonvi, $donvicha) {
					$query->Orwhere('tbl_donvihanhchinh.id', '=', $madonvi)
						->Orwhere('tbl_donvihanhchinh.madonvi', '=', $donvicha);
				})
				->get();
			return json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		else
		{
			$data = tbl_donvihanhchinh::where('isDelete',0)
				->where('tbl_donvihanhchinh.id', '=', $madonvi)
				->get();
			return json_encode($data, JSON_UNESCAPED_UNICODE);
		}
	}
	public function getsolieutheobieumau(Request $rq)
	{
		$data = tbl_bieumau::where('isDelete',0)->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getloaisolieu(Request $rq)
	{
		// $loaisolieuid = tbl_bieumau::where('id',$rq->id)->get();
		// $idlsl = $loaisolieuid->pluck('loaisolieu')->filter();
		$data = tbl_loaisolieu::where('isDelete',0)->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getkybaocao(Request $rq)
	{
		// $kybaocao = tbl_bieumau::where('id',$rq->id)->get();
		// $idksl = $kybaocao->pluck('kybaocao')->filter();
		$data = tbl_kybaocao::where('isDelete',0)->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function loadsosanhsolieu(Request $rq)
	{

		$tinh = $rq->tinh;
		$huyen = $rq->huyen;
		$xa = $rq->xa;
		$solieudonvi = $rq->solieudonvi;
		$sosanhsl = $rq->sosanhsl;
		$loaisolieu = $rq->loaisolieu;
		$kysolieu = $rq->kysolieu;
		$datepicker1 = $rq->thang1;
		$cungky = $rq->cungky;
		// $datepicker2 = $rq->datepicker2;
		$datepicker3 = $rq->nam1;
		$datepicker4 = $rq->nam2;

		if($solieudonvi == null && $sosanhsl == null && $loaisolieu == null && $kysolieu == null && $datepicker1 == null && $datepicker3 == null && $datepicker4 == null){
			$chitieu = DB::table('tbl_chitieu')
			->join('tbl_donvitinh','tbl_chitieu.donvitinh','=','tbl_donvitinh.id')
			->select('tbl_chitieu.id','tbl_chitieu.idcha','tbl_donvitinh.tendonvi','tbl_chitieu.tenchitieu')
			->get();
		}else{
			$chitieu = DB::table('tbl_chitieu')
			->join('tbl_donvitinh','tbl_chitieu.donvitinh','=','tbl_donvitinh.id')
			->select('tbl_chitieu.id','tbl_chitieu.idcha','tbl_donvitinh.tendonvi','tbl_chitieu.tenchitieu')
			->get();

			$bieumau = DB::table('tbl_bieumau')
			->where('tbl_bieumau.id', '=', $sosanhsl)
			//->where('tbl_bieumau.loaisolieu', '=', $loaisolieu)
			//->where('tbl_bieumau.kybaocao', '=', $kysolieu)	
			//->whereYear('tbl_bieumau.ngayquyetdinh', '<=', $datepicker4)
			//->whereYear('tbl_bieumau.ngayquyetdinh', '>=', $datepicker3)
				->get();
			//dd($bieumau);
			//return 200;

			$arrsosanhsl = [];
			if($cungky==0)
			{
				foreach ($bieumau as $key) {
					$data0 =DB::table('tbl_solieutheobieu')
					->where('bieumau',$key->id)
					->where('tbl_solieutheobieu.loaisolieu',$loaisolieu)
					->where('tbl_solieutheobieu.diaban',$solieudonvi)
					->when($datepicker3, function ($query) use ($datepicker3) {
						$query->where('tbl_solieutheobieu.namnhap', '>=', $datepicker3);
					})->when($datepicker4, function ($query) use ($datepicker4) {
						$query->where('tbl_solieutheobieu.namnhap', '<=', $datepicker4);
					})->get();
					foreach ($data0 as $value) {
						if (in_array($value, $arrsosanhsl, TRUE) == false) {
							array_push($arrsosanhsl, $value);
						}
					}
				}
			}
			else
			{
				$tbloaisolieu=DB::table('tbl_loaisolieu')->get();
				
				foreach ($bieumau as $key) 
				{
					foreach($tbloaisolieu as $row)
					{
						$data0 =DB::table('tbl_solieutheobieu')
						->where('bieumau',$key->id)
						->where('tbl_solieutheobieu.loaisolieu',$row->id)
						->where('tbl_solieutheobieu.diaban',$solieudonvi)
						->get();
						
						foreach ($data0 as $value) {
							if (in_array($value, $arrsosanhsl, TRUE) == false) {
								array_push($arrsosanhsl, $value);
							}
						}
					}
				}
			}
			//dd($arrsosanhsl);
			//return 200;

			$chitietsolieutheobieu = [];
			foreach ($arrsosanhsl as $key) {
				$data0 =tbl_chitietsolieutheobieu::where('mabieusolieu',$key->id)
				->with(['tbl_chitieu' => function($qr){
					$qr->with('tbl_donvitinh')->select('id','tenchitieu','donvitinh','idcha');
				}])
				->with(['tbl_solieutheobieu' => function($qr){
					$qr->select('id','bieumau','namnhap');
				}])
				->get();
				foreach ($data0 as $value) {
					if (in_array($value, $arrsosanhsl, TRUE) == false) {
						array_push($chitietsolieutheobieu, $value);
					}
				}
			}
			return json_encode([$chitieu,$chitietsolieutheobieu,$bieumau], JSON_UNESCAPED_UNICODE);
		}
		return json_encode([$chitieu], JSON_UNESCAPED_UNICODE);
	}




}