<?php

namespace App\Http\Controllers\Khaithacthongtin\Sosanhsolieuky_nam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\devvn_quanhuyen;
use App\devvn_tinhthanhpho;
use App\devvn_xaphuongthitran;
use App\tbl_donvihanhchinh;
use App\tbl_solieutheobieu;
use App\tbl_kybaocao;
use App\tbl_chitieu;
use App\tbl_chitieuses;
use App\tbl_bieumau;
use App\tbl_loaisolieu;
use App\tbl_chitietbieumau;
use App\tbl_chitietsolieutheobieu;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class Sosanhsolieuky_nam extends Controller
{
	public function viewsosanhsolieu()
	{
		return view('ktxh.Khaithacthongtin.Sosanhsolieuky_nam.list');
	}
	public function getlisttinh(){
		$data = DB::table('tbl_tinh')
		->selectRaw('cast(id as CHAR)')->select('id','_name as tinh')
		->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getlisthuyen(Request $rq){
		$data = DB::table('tbl_quanhuyen')
		->selectRaw('cast(id as CHAR)')->where("_province_id",$rq->matinh)->select('id','_name as huyen','_province_id as matinh')
		->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getlistxa(Request $rq){
		$data = DB::table('tbl_xaphuong')
		->selectRaw('cast(id as CHAR)')->where("_district_id",$rq->mahuyen)->select('id','_name as xa','_district_id as mahuyen')
		->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getmadonvi(Request $rq){
		$data = devvn_xaphuongthitran::where('id',$rq->id)->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getdonvihanhchinh(Request $rq){
		$data = tbl_donvihanhchinh::all();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getsolieutheobieumau(Request $rq){
		$data = tbl_bieumau::all();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getloaisolieu(Request $rq){
		// $loaisolieuid = tbl_bieumau::where('id',$rq->id)->get();
		// $idlsl = $loaisolieuid->pluck('loaisolieu')->filter();
		$data = tbl_loaisolieu::all();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function getkybaocao(Request $rq){
		// $kybaocao = tbl_bieumau::where('id',$rq->id)->get();
		// $idksl = $kybaocao->pluck('kybaocao')->filter();
		$data = tbl_kybaocao::all();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function loadsosanhsolieu(Request $rq){

		$tinh = $rq->tinh;
		$huyen = $rq->huyen;
		$xa = $rq->xa;
		$solieudonvi = $rq->solieudonvi;
		$sosanhsl = $rq->sosanhsl;
		$loaisolieu = $rq->loaisolieu;
		$kysolieu = $rq->kysolieu;
		$datepicker1 = $rq->thang1;
		// $datepicker2 = $rq->datepicker2;
		$datepicker3 = $rq->nam1;
		$datepicker4 = $rq->nam2;

		if($rq->xa == null && $rq->solieudonvi == null && $rq->sosanhsl == null && $rq->kysolieu == null && $rq->thang1 == null && $rq->nam1 == null && $rq->nam2 == null && $rq->loaisolieu == null){
			$data = tbl_chitieuses::get()->toFlatTree();			
		}else{
			$datas = tbl_bieumau::when($tinh, function($query) use ($tinh){
				$query->get();
			})->when($huyen, function($query) use ($huyen){
				$query->get();
			})->when($xa, function($query) use ($xa){
				$query->where('madonvi', '=', $xa);
			})->when($solieudonvi, function($query) use ($solieudonvi){
				$query->where('madonvi', '=', $solieudonvi);
			})->when($sosanhsl, function($query) use ($sosanhsl){
				$query->where('id', '=', $sosanhsl);
			})->when($loaisolieu, function($query) use ($loaisolieu){
				$query->where('loaisolieu', '=', $loaisolieu);
			})->when($kysolieu, function($query) use ($kysolieu){
				$query->where('kybaocao', '=', $kysolieu);
			})->when($datepicker1, function($query) use ($datepicker1){
				$query->whereMonth('ngayquyetdinh', '=', $datepicker1);
			})->when($datepicker3, function($query) use ($datepicker3){
				$query->whereYear('ngayquyetdinh', '>=', $datepicker3);
			})->when($datepicker4, function($query) use ($datepicker4){
				$query->whereYear('ngayquyetdinh', '<=', $datepicker4);
			})->get();

			$datasltheobieu = [];
			foreach ($datas as $key) {
				$data0 = tbl_chitietsolieutheobieu::where('mabieusolieu',$key->id)->get();
				foreach($data0 as $value){
					if(in_array($value, $datasltheobieu, TRUE)== false){
						array_push($datasltheobieu, $value);
					}
				}
			}

			
			$data = [];
			foreach ($datasltheobieu as $key) {
				$data1 = tbl_chitieuses::descendantsAndSelf($key->chitieu)->toArray();
				foreach($data1 as $value){
					if(in_array($value, $data, TRUE)== false){
						array_push($data, $value);
					}
				}
			}



			$chitieusl = [];		
			$data2 = DB::table('tbl_chitietsolieutheobieu')->when($datepicker3, function($query) use ($datepicker3){
				$query->whereYear('created_at', '>=', $datepicker3);
			})->when($datepicker4, function($query) use ($datepicker4){
				$query->whereYear('created_at', '<=', $datepicker4);
			})->selectRaw('SUM(sanluong) as sanluong,YEAR(created_at) as year,chitieu')->groupBy('year','chitieu')->get();
			foreach($data2 as $value){
				if(in_array($value, $data, TRUE)== false){
					array_push($chitieusl, $value);
				}
			}




			return json_encode([$data,$chitieusl], JSON_UNESCAPED_UNICODE);
		}

		return json_encode([$data], JSON_UNESCAPED_UNICODE);
	}



	public function getbieudo(Request $rq){
		$nam1 = $rq->nam1;
		$nam2 = $rq->nam2;

		$data = DB::table('tbl_chitietsolieutheobieu')->where('chitieu',$rq->id)->when($nam1,function($query) use($nam1){
			$query->whereYear('created_at','>=',$nam1);
		})->when($nam2,function($query)use($nam2){
			$query->whereYear('created_at','<',$nam2);
		})->selectRaw('YEAR(created_at) year,sanluong')->groupby('year','sanluong')->get();


		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
















}
