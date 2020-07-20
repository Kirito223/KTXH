<?php

namespace App\Http\Controllers\Khaithacthongtin\Tracuusolieutheochitieu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\tbl_bieumau;
use App\tbl_chitietsolieutheobieu;
use App\tbl_chitieuses;
use App\tbl_loaisolieu;

class Tracuusolieutheochitieu extends Controller
{
	public function viewtracuusolieutheochitieu()
	{
		return view('ktxh.Khaithacthongtin.Tracuusolieutheochitieu.list');
	}
	public function loadsolieutheochitieu(Request $rq){
		$tinh = $rq->tinh;
		$huyen = $rq->huyen;
		$xa = $rq->xa;
		$donvithuthap = $rq->donvithuthap;
		$solieucuaky = $rq->solieucuaky;
		$solieucuanam = $rq->solieucuanam;
		$khoichitieu = $rq->khoichitieu;

		$datas = tbl_bieumau::when($xa, function($query) use ($xa){
			$query->where('madonvi', '=', $xa);
		})->when($donvithuthap, function($query) use ($donvithuthap){
			$query->where('madonvi', '=', $donvithuthap);
		})->when($solieucuaky, function($query) use ($solieucuaky){
			$query->where('kybaocao', '=', $solieucuaky);
		})->when($solieucuanam, function($query) use ($solieucuanam){
			$query->whereYear('ngayquyetdinh', '=', $solieucuanam);
		})->when($khoichitieu, function($query) use ($khoichitieu){
			$query->join('tbl_chitietsolieutheobieu','tbl_chitietsolieutheobieu.mabieusolieu', '=', 'tbl_bieumau.id')
			->where('tbl_chitietsolieutheobieu.chitieu',$khoichitieu)->get();
		})->get();

		$datasltheobieu = [];
		foreach ($datas as $key) {
			if($khoichitieu == null){
				$data0 = tbl_chitietsolieutheobieu::where('mabieusolieu',$key->id)->get();
			}else{
				$data0 = tbl_chitietsolieutheobieu::where('chitieu',$key->id)->get();
			}

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



		$th = DB::table('tbl_loaisolieu')->join('tbl_bieumau','tbl_bieumau.loaisolieu', '=', 'tbl_loaisolieu.id')->where('tbl_loaisolieu.id', '=',1)
		->join('tbl_chitietsolieutheobieu','tbl_chitietsolieutheobieu.mabieusolieu', '=', 'tbl_bieumau.id')
		->select('tbl_loaisolieu.id','tbl_loaisolieu.tenloaisolieu','tbl_chitietsolieutheobieu.mabieusolieu',DB::raw("count(tbl_chitietsolieutheobieu.chitieu) as count"),'tbl_chitietsolieutheobieu.chitieu')
		->groupBy('tbl_chitietsolieutheobieu.mabieusolieu','tbl_chitietsolieutheobieu.chitieu')
		->get();	


		$kh = DB::table('tbl_loaisolieu')->join('tbl_bieumau','tbl_bieumau.loaisolieu', '=', 'tbl_loaisolieu.id')->where('tbl_loaisolieu.id', '=',2)
		->join('tbl_chitietsolieutheobieu','tbl_chitietsolieutheobieu.mabieusolieu', '=', 'tbl_bieumau.id')
		->select('tbl_loaisolieu.id','tbl_loaisolieu.tenloaisolieu','tbl_chitietsolieutheobieu.mabieusolieu',DB::raw("count(tbl_chitietsolieutheobieu.chitieu) as count"),'tbl_chitietsolieutheobieu.chitieu')
		->groupBy('tbl_chitietsolieutheobieu.mabieusolieu','tbl_chitietsolieutheobieu.chitieu')
		->get();	





		return json_encode([$data,$th,$kh], JSON_UNESCAPED_UNICODE);
	}
















}
