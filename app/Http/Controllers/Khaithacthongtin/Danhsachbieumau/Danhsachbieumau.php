<?php

namespace App\Http\Controllers\Khaithacthongtin\Danhsachbieumau;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\tbl_donvitinhController;
use App\tbl_bieumau;
use App\tbl_donvihanhchinh;
use App\tbl_chitietbieumau;
use App\tbl_chitietsolieutheobieu;
use App\tbl_chitieu;
use App\tbl_chitieuses;
use App\tbl_solieutheobieu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;


class Danhsachbieumau extends Controller
{
	public function viewdanhsachbieumau()
	{
		return view('ktxh.Khaithacthongtin.Danhsachbieumau.list');
	}

	public function getlistbieumau()
	{
		$data =  tbl_bieumau::with("tbl_donvihanhchinh")->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function getlistdonvihanhchinh()
	{
		$data =  tbl_donvihanhchinh::all();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function loadlistbieumau(Request $rq)
	{
		// dd($rq);
		//date ngay quyet dinh
		if ($rq->ngayquyetdinh1 != null) {
			$quyetdinhtu = date('Y-m-d', strtotime(str_replace('/', '-', $rq->ngayquyetdinh1)));
		} else $quyetdinhtu = $rq->ngayquyetdinh1;

		if ($rq->ngayquyetdinh2 != null) {
			$quyetdinhden = date('Y-m-d', strtotime(str_replace('/', '-', $rq->ngayquyetdinh2)));
		} else $quyetdinhden = $rq->ngayquyetdinh2;


		//date ngay tao
		if ($rq->ngaytao1 != null) {
			$ngaytaotu = date('Y-m-d', strtotime(str_replace('/', '-', $rq->ngaytao1)));
		} else $ngaytaotu = $rq->ngaytao1;

		if ($rq->ngaytao2 != null) {
			$ngaytaoden = date('Y-m-d', strtotime(str_replace('/', '-', $rq->ngaytao2)));
		} else $ngaytaoden = $rq->ngaytao2;

		$tenbieumau = $rq->tenbieumau;
		$bieumauso = $rq->bieumauso;
		$coquandaotao = $rq->coquandaotao;
		$quyetdinhso = $rq->quyetdinhso;
		$loaibieumau = $rq->loaibieumau;

		$data = tbl_bieumau::where('tbl_bieumau.isDelete', 0)
			->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
			->join('tbl_phongban', 'tbl_phongban.id', 'tbl_bieumau.madonvi')
			->when($quyetdinhtu, function ($query) use ($quyetdinhtu) {
				$query->where('ngayquyetdinh', '>=', $quyetdinhtu);
			})->when($quyetdinhden, function ($query) use ($quyetdinhden) {
				$query->where('ngayquyetdinh', '<=', $quyetdinhden);
			})->when($ngaytaotu, function ($query) use ($ngaytaotu) {
				$query->where('create_at', '>=', $ngaytaotu);
			})->when($ngaytaoden, function ($query) use ($ngaytaoden) {
				$query->where('create_at', '<=', $ngaytaoden);
			})->when($tenbieumau, function ($query) use ($tenbieumau) {
				$query->where('tbl_bieumau.id', '=', $tenbieumau);
			})->when($bieumauso, function ($query) use ($bieumauso) {
				$query->where('tbl_bieumau.id', '=', $bieumauso);
			})->when($coquandaotao, function ($query) use ($coquandaotao) {
				$query->where('madonvi', '=', $coquandaotao);
			})->when($quyetdinhso, function ($query) use ($quyetdinhso) {
				$query->where('soquyetdinh', '=', $quyetdinhso);
			})->when($loaibieumau, function ($query) use ($loaibieumau) {
				$query->where('loaibaocao', '=', $loaibieumau);
			})
			->select('tbl_bieumau.sohieu', 'tbl_bieumau.tenbieumau', 'tbl_bieumau.soquyetdinh', 'tbl_bieumau.ngayquyetdinh', 'tbl_bieumau.created_at', 'tbl_bieumau.id', 'tbl_bieumau.madonvi', 'tbl_taikhoan.tendangnhap', 'tbl_phongban.tenphongban as tendonvi')
			->get();
		return response()->json($data);
	}

	public function loadchitietbieumau(Request $rq, $id)
	{
		$data = tbl_bieumau::where("tbl_bieumau.id", $id)
			->join('tbl_phongban', 'tbl_phongban.id', 'tbl_bieumau.madonvi')
			->select('tbl_bieumau.*', 'tbl_phongban.tenphongban')
			->get();
		// dd($data->id);
		// nho check truong hop khong ton tai $id
		return view("ktxh.Khaithacthongtin.Danhsachbieumau.infobieumau")->with('data', $data);
	}


	public function loadtableinfobieumau(Request $rq)
	{
		$Time = Carbon::now();
		$codeUnit = session('madonvi');
		$datas = tbl_chitietbieumau::where('bieumau', $rq->id)->with(['tbl_chitieu' => function ($author) {
			$author->select('id');
		}])->get();
		$datas = $datas->pluck('tbl_chitieu.id');

		$Result = [];
		foreach ($datas as $key) {
			$data = tbl_chitieu::descendantsAndSelf($key)
				->toArray();
			foreach ($data as $value) {
				$Item = new stdClass();
				$Item->tenchitieu = $value['tenchitieu'];
				$Item->machitieu = $value['machitieu'];
				$Item->id = $value['id'];
				$Item->parent_id = $value['parent_id'];
				$donvitinhController = new tbl_donvitinhController();
				$donvi = $donvitinhController->show($value['donvitinh']);
				$Item->donvitinh = $donvi->tendonvi;
				$KHYearCurrent = $this->CaculatorKHNam($rq->id, $value['id'], $Time->year, $codeUnit);
				$KHYearPrev = $this->CaculatorKHNam($rq->id, $value['id'], $Time->year - 1, $codeUnit);
				if ($KHYearPrev > 0) {
					$Item->KHYearPercent = ($KHYearCurrent / $KHYearPrev) * 100;
				} else {
					$Item->KHYearPercent = 100;
				}
				$Item->KHdate = ($KHYearCurrent * 12) / 6;
				$Item->Quanity = $Item->KHdate * 12;
				$Item->Quanityreal = $Item->KHdate;
				$KHdateprev = ($KHYearPrev * 12) / 6;
				if ($KHdateprev > 0) {
					$Item->Compare = $KHdateprev / $Item->KHdate * 100;
				} else {
					$Item->Compare =  100;
				}
				$Item->day = round($KHYearCurrent / 365);
				$Item->KHYear = $KHYearCurrent;
				if (in_array($Item, $Result) == false) {
					array_push($Result, $Item);
				}
			}
		}
		return response()->json($Result);
	}

	private function CaculatorKHNam($bieumau, $chitieu, $time, $unit)
	{
		$sum = 0;
		$listBieumau = tbl_solieutheobieu::where('isDelete', 0)
			->where('bieumau', $bieumau)
			->where('namnhap', $time)
			->where('donvinhap', $unit)
			->get();
		foreach ($listBieumau as $bieumau) {
			$detail = tbl_chitietsolieutheobieu::where('isDelete', 0)
				->where('mabieusolieu', $bieumau->id)
				->where('chitieu', $chitieu)
				->sum('sanluong');
			$sum += $detail;
		}
		return $sum;
	}
}
