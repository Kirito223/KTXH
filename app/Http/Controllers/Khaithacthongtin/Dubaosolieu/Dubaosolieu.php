<?php

namespace App\Http\Controllers\Khaithacthongtin\Dubaosolieu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\tbl_donvihanhchinh;
use App\tbl_solieutheobieu;
use App\tbl_kybaocao;
use App\tbl_chitieu;
use App\tbl_bieumau;
use App\tbl_loaisolieu;
use App\tbl_chitietbieumau;
use App\tbl_chitietsolieutheobieu;
use stdClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Session;
class Dubaosolieu extends Controller
{
	public function viewdubaosolieu()
	{
		return view('ktxh.Khaithacthongtin.Dubaosolieu.list');
	}
	public function getlistchitieudubaosl(){
		
		 $madonvi = Session::get('madonvi');

       $donvicha = Session::get('donvicha');
		
		
		$data = tbl_chitieu::where('idcha',null)
			->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
			->join('tbl_chitietsolieutheobieu', 'tbl_chitietsolieutheobieu.chitieu', 'tbl_chitieu.id')
            ->where('tbl_chitietsolieutheobieu.madonvi','=',$madonvi)
			->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->groupby('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->get();
		if(strlen($donvicha)==0) 
		{
		$data =  tbl_chitieu::
			where('idcha',null)
			->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->where('tbl_chitieu.madonvi','=', $madonvi)
			->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->groupby('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->get();
		}
		else
		{
			$data =  tbl_chitieu::
			where('idcha',null)
			->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->where('tbl_chitieu.madonvi','=', $donvicha)
			->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->groupby('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
			->get();
		}
        return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function loaddubaosolieu(Request $rq){
		$tinh = $rq->tinh;
		$huyen = $rq->huyen;
		$xa = $rq->xa;
		$donvibaocao = $rq->donvibaocao;
		$nam = $rq->nam;
		$loaisolieu = $rq->loaisolieu;
		$kysolieu = $rq->kysolieu;
		$chitieu = $rq->chitieu;

		if( $donvibaocao == null && $nam == null && $loaisolieu == null && $kysolieu == null && $chitieu == null){
			$chitieu = tbl_chitieu::where('isDelete', 0)->with('tbl_donvitinh')->get();
		}else{
			//$chitieu = tbl_chitieu::with('tbl_donvitinh')->descendantsAndSelf($chitieu)->toFlatTree();
			$chitieu = tbl_chitieu::where('isDelete', 0)->with('tbl_donvitinh')->get();

			// $chitieu = DB::table('tbl_chitieu')
			// ->when($chitieu, function ($query) use ($chitieu) {
			// 	$query->where('tbl_chitieu.id', '=', $chitieu);
			// })
			// ->join('tbl_donvitinh','tbl_chitieu.donvitinh','=','tbl_donvitinh.id')
			// ->select('tbl_chitieu.id','tbl_chitieu.idcha','tbl_donvitinh.tendonvi','tbl_chitieu.tenchitieu')
			// ->get();

			$bieumau = DB::table('tbl_bieumau')
			
			->where('tbl_solieutheobieu.diaban',$donvibaocao )
			->join('tbl_solieutheobieu','tbl_solieutheobieu.bieumau','tbl_bieumau.id')
			 ->select('tbl_bieumau.id')
			->groupby('tbl_bieumau.id')
			->get();	
			//->where('tbl_bieumau.loaisolieu', '=', $loaisolieu)
			//->where('tbl_bieumau.kybaocao', '=', $kysolieu)	
			//->whereYear('tbl_bieumau.ngayquyetdinh', '<=', $datepicker4)
			//->whereYear('tbl_bieumau.ngayquyetdinh', '>=', $datepicker3)
			

			$sosanhsl = [];
			foreach ($bieumau as $key) {
				$data0 =DB::table('tbl_solieutheobieu')
				->where('bieumau',$key->id)
				->where('tbl_solieutheobieu.namnhap', '=', $nam)
				->where('tbl_solieutheobieu.loaisolieu', '=', $loaisolieu)
				//->where('tbl_solieutheobieu.kysolieu', '=', $kysolieu)
				->get();
				foreach ($data0 as $value) {
					if (in_array($value, $sosanhsl, TRUE) == false) {
						array_push($sosanhsl, $value);
					}
				}
			}


			$chitietsolieutheobieu = [];
			foreach ($sosanhsl as $key) {
				$data0 = tbl_chitietsolieutheobieu::where('mabieusolieu',$key->id)				
				->with(['tbl_chitieu' => function($qr) use($chitieu){
					$qr->with('tbl_donvitinh')->select('id','tenchitieu','donvitinh','idcha');
				}])->with(['tbl_solieutheobieu' => function($qr){
					$qr->select('id','bieumau','namnhap');
				}])->get();
				foreach ($data0 as $value) {
					if (in_array($value, $sosanhsl, TRUE) == false) {
						array_push($chitietsolieutheobieu, $value);
					}
				}
			}
			return json_encode([$chitieu,$chitietsolieutheobieu], JSON_UNESCAPED_UNICODE);
		}

		return json_encode([$chitieu], JSON_UNESCAPED_UNICODE);
	}
}
