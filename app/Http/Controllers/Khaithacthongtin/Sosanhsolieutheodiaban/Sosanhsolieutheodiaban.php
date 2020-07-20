<?php

namespace App\Http\Controllers\Khaithacthongtin\Sosanhsolieutheodiaban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\tbl_chitieuses;
use App\tbl_bieumau;
use App\tbl_chitietsolieutheobieu;
use App\devvn_quanhuyen;
use App\devvn_tinhthanhpho;
use App\devvn_xaphuongthitran;
use App\tbl_donvihanhchinh;

class Sosanhsolieutheodiaban extends Controller
{
	public function viewsosanhsolieutheodiaban()
	{
		return view('ktxh.Khaithacthongtin.Sosanhsolieutheodiaban.list');
	}

	public function loadsosanhsolieudiaban(Request $rq)
	{
		$tinh = $rq->tinh;
		$huyen = $rq->huyen;
		$xa = $rq->xa;
		$sosanhsolieudonvi = $rq->sosanhsolieudonvi;
		$sosanhduatheobieumau = $rq->sosanhduatheobieumau;
		$duatrenloaisolieu = $rq->duatrenloaisolieu;
		$sosanhtheokysolieu = $rq->sosanhtheokysolieu;
		$namsosanh = $rq->namsosanh;

		$slxa = devvn_xaphuongthitran::where('id',$xa)->select('madonvi','mahuyen','xa');
		$idxa = $slxa->pluck('madonvi')->toArray();



		$datas = tbl_bieumau::when($sosanhsolieudonvi, function($query) use ($sosanhsolieudonvi){
			$query->where('madonvi', '=', $sosanhsolieudonvi);
		})->when($sosanhduatheobieumau, function($query) use ($sosanhduatheobieumau){
			$query->where('id', '=', $sosanhduatheobieumau);
		})->when($duatrenloaisolieu, function($query) use ($duatrenloaisolieu){
			$query->where('loaibaocao', '=', $duatrenloaisolieu);
		})->when($sosanhtheokysolieu, function($query) use ($sosanhtheokysolieu){
			$query->where('kybaocao', '=', $sosanhtheokysolieu);
		})->when($namsosanh, function($query) use ($namsosanh){
			$query->whereYear('ngayquyetdinh', '=', $namsosanh);
		})->when($idxa, function($query) use ($idxa){
			$query->where('madonvi', '=', $idxa);
		})->get();

// dd($datas);

		$datasltheobieu = [];
		foreach ($datas as $key) {
			$data0 = tbl_chitietsolieutheobieu::where('mabieusolieu',$key->id)->get();
			foreach($data0 as $value){
				if(in_array($value, $datasltheobieu, TRUE)== false){
					array_push($datasltheobieu, $value);
				}
			}
		}
		// dd($datasltheobieu);

		$data = [];
		foreach ($datasltheobieu as $key) {
			$data1 = tbl_chitieuses::descendantsAndSelf($key->chitieu)->toArray();
			foreach($data1 as $value){
				if(in_array($value, $data, TRUE)== false){
					array_push($data, $value);
				}
			}
		}
		// dd($data);
		$idtinh = devvn_tinhthanhpho::where('id',$tinh)->get();
		$sltinh = [];
		foreach ($idtinh as $value) {
			$h = devvn_quanhuyen::where('matinh',$value->id)->selectRaw('count(matinh)as matinh')->get();
			foreach($h as $value){
				if(in_array($value, $sltinh, TRUE)== false){
					array_push($sltinh, $value);
				}
			}
		}
		

		$idhuyen = devvn_quanhuyen::where('id',$huyen)->get();
		$slhuyen = [];
		foreach ($idhuyen as $value) {
			$x = devvn_xaphuongthitran::where('madonvi',$idxa)->where('mahuyen',$value->id)->selectRaw('count(mahuyen)as mahuyen')->get();
			foreach($x as $value){
				if(in_array($value, $slhuyen, TRUE)== false){
					array_push($slhuyen, $value);
				}
			}
		}

		return json_encode([$data,$sltinh,$slhuyen], JSON_UNESCAPED_UNICODE);
	}








	public function loadsosanhsolieudonvi(Request $rq)
	{
		$tinh = $rq->tinh;
		$huyen = $rq->huyen;
		$xa = $rq->xa;
		$sosanhduatheochitieu = $rq->sosanhduatheochitieu;
		$duatrenloaisolieu = $rq->duatrenloaisolieu;
		$sosanhtheokysolieu = $rq->sosanhtheokysolieu;
		$thangsosanh = $rq->thangsosanh;
		$namsosanh = $rq->namsosanh;

		$slxa = devvn_xaphuongthitran::where('id',$xa)->select('madonvi','mahuyen','xa');
		$idxa = $slxa->pluck('madonvi')->toArray();



		$datas = tbl_bieumau::when($sosanhduatheochitieu, function($query) use ($sosanhduatheochitieu){
			$query->where('id', '=', $sosanhduatheochitieu);
		})->when($duatrenloaisolieu, function($query) use ($duatrenloaisolieu){
			$query->where('loaisolieu', '=', $duatrenloaisolieu);
		})->when($sosanhtheokysolieu, function($query) use ($sosanhtheokysolieu){
			$query->where('kybaocao', '=', $sosanhtheokysolieu);
		})->when($thangsosanh, function($query) use ($thangsosanh){
			$query->whereMonth('ngayquyetdinh', '=', $thangsosanh);
		})->when($namsosanh, function($query) use ($namsosanh){
			$query->whereYear('ngayquyetdinh', '=', $namsosanh);
		})->when($idxa, function($query) use ($idxa){
			$query->where('madonvi', '=', $idxa);
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

		$donvis = [];
		foreach ($datasltheobieu as $key){
			$data2 = tbl_donvihanhchinh::where('id',$key->madonvi)->get();
			foreach($data2 as $value){
				if(in_array($value, $donvis, TRUE)== false){
					array_push($donvis, $value);
				}
			}
		}

		return json_encode([$data,$donvis,$datasltheobieu], JSON_UNESCAPED_UNICODE);

	}


	public function getbieudodonvi(Request $rq){
		$idchitieu = $rq->idchitieu;
		$data = tbl_chitietsolieutheobieu::where('chitieu',$idchitieu)->get();
		// dd($data->toArray());
		// $first_names = array_column($idchitieu, 'madonvi');
		// $names = array_column($idchitieu, 'name');
		// $bieumau = [];
		// foreach ($first_names as $keys){
		// 	$slxa = tbl_chitietsolieutheobieu::where('madonvi', $keys)->with('tbl_chitieuses')->selectRaw('COUNT(madonvi)as madonvi,chitieu')->groupBy('chitieu')->get();
		// 	foreach($data2 as $values){
		// 		if(in_array($values, $bieumau, TRUE)== false){
		// 			array_push($bieumau, $values);
		// 		}
		// 	}
		// }





		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}






















}
