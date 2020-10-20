<?php

namespace App\Http\Controllers\Quanlydanhmuc\Quanlychitieu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\tbl_chitieu;
use phpDocumentor\Reflection\Types\This;
use Session;

class Chitieu extends Controller
{


	public function viewchitieu()
	{
		return view('ktxh.Quanlydanhmuc.Quanlychitieu.chitieu');
	}

	public function getchitieu()
	{
		$data =  tbl_chitieu::select('tbl_chitieu.*', 'tbl_donvitinh.tendonvi')
			->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
			->where('tbl_chitieu.madonvi', '=', $this->sessionHelper->getDepartmentId())
			->where('tbl_chitieu.IsDelete', '0')->get();

		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function InsertChitieu(Request $rq)
	{
		try {
			$tbl_chitieu = new tbl_chitieu();
			$tbl_chitieu->machitieu = $rq->machitieu;
			$tbl_chitieu->tenchitieu = $rq->tenchitieu;
			$tbl_chitieu->donvitinh = $rq->donvi;
			if ($rq->idcha != null) {
				$tbl_chitieu->parent_id = $rq->idcha;
				$tbl_chitieu->idcha = $rq->idcha;
			}
			$tbl_chitieu->madonvi = $this->sessionHelper->getDepartmentId();
			$success = $tbl_chitieu->save();
			return response()->json(['msg' => 'ok', 'data' => $success], 200);
		} catch (\Exception $ex) {
			print $ex;
		}
	}
	public function InsertChitieuCon(Request $rq)
	{
		$madonvi = Session::get('madonvi');
		$tbl_chitieu = new tbl_chitieu();
		$tbl_chitieu->machitieu = $rq->machitieu;
		$tbl_chitieu->tenchitieu = $rq->tenchitieu;
		$tbl_chitieu->idcha = $rq->idcha;
		$tbl_chitieu->donvitinh = $rq->donvi;
		$tbl_chitieu->parent_id = $rq->idcha;
		$tbl_chitieu->madonvi = $madonvi;
		$success = $tbl_chitieu->save();
		return json_encode($success, JSON_UNESCAPED_UNICODE);
	}
	public function UpdateChitieu(Request $rq, $id)
	{
		try {
			$tbl_chitieu = tbl_chitieu::find($id);
			$tbl_chitieu->machitieu = $rq->machitieu;
			$tbl_chitieu->tenchitieu = $rq->tenchitieu;
			$tbl_chitieu->donvitinh = $rq->donvi;
			if ($rq->idcha != null) {
				$tbl_chitieu->parent_id = $rq->idcha;
				$tbl_chitieu->idcha = $rq->idcha;
			}
			$tbl_chitieu->madonvi = $this->sessionHelper->getDepartmentId();
			$success = $tbl_chitieu->save();
			return response()->json(['msg' => 'ok', 'data' => $success], 200);
		} catch (\Exception $ex) {
			print $ex;
		}
	}

	public function DelChitieu(Request $rq)
	{
		try {
			$arr = json_decode($rq->data);
			foreach ($arr as $item) {
				$tbl_chitieu = tbl_chitieu::find($item->id);
				$tbl_chitieu->IsDelete = 1;
				$tbl_chitieu->save();
			}
			return response()->json(['msg' => 'ok', 'data' => "ok"], 200);
		} catch (\Exception $th) {
			print($th);
		}
	}

	public function DelChitieulistcheckbox(Request $rq)
	{
		$tbl_chitieu = tbl_chitieu::find($rq->id);
		$tbl_chitieu->IsDelete = 1;
		$success = $tbl_chitieu->save();
		return json_encode($success, JSON_UNESCAPED_UNICODE);
	}

	//thung rac chi tieu
	public function viewchitieutrash()
	{
		return view('ktxh.Quanlydanhmuc.Quanlychitieu.trashbin');
	}

	public function getchitieutrash()
	{
		// $data =  tbl_chitieu::with('allChildren')->get();
		$data =  tbl_chitieu::where('IsDelete', '1')->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function RestoreChitieulistcheckbox(Request $rq)
	{
		$tbl_chitieu = tbl_chitieu::find($rq->id);
		$tbl_chitieu->IsDelete = 0;
		$success = $tbl_chitieu->save();
		return json_encode($success, JSON_UNESCAPED_UNICODE);
	}

	public function DelAllChitieulistcheckbox(Request $rq)
	{
		$tbl_chitieu = tbl_chitieu::destroy($rq->id);
		return json_encode($success, JSON_UNESCAPED_UNICODE);
	}
}
