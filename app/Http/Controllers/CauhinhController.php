<?php

namespace App\Http\Controllers;

use App\th_cauhinh; 
use Illuminate\Http\Request;
use DataTables;
use Session; 
class CauhinhController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
       
    }
      
    public function list()
    {
         $madonvi = Session::get('madonvi');

         $data = th_cauhinh::where('th_cauhinh.madonvi',$madonvi)
         ->get();
      
        return DataTables::of($data)->make(true);

    }
    public function getCauHinh($id)
    {
        $data = th_cauhinh::where('th_cauhinh.id', '=', $id)
            ->first();
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function InsertAndUpdate(Request $rq){
        $madonvi = Session::get('madonvi');
        if($rq->id == null){
            $thCauhinh = new th_cauhinh();
            $thCauhinh->mucluong = $rq->mucluong;
            $thCauhinh->tien_dodunght = $rq->tien_dodunght;
            $thCauhinh->muchuongdantocthieuso = $rq->muchuongdantocthieuso;
            $thCauhinh->hotrochiphi = $rq->hotrochiphi;
            $thCauhinh->tungay = $rq->tungay;
            $thCauhinh->denngay = $rq->denngay;
            $thCauhinh->hocphi = $rq->hocphi;
            $thCauhinh->madonvi = $madonvi;

            $success = $thCauhinh->save();
            return $success?200:500;
        }else{
            $thCauhinh = th_cauhinh::find($rq->id);
            $thCauhinh->mucluong = $rq->mucluong;
            $thCauhinh->tien_dodunght = $rq->tien_dodunght;
            $thCauhinh->muchuongdantocthieuso = $rq->muchuongdantocthieuso;
            $thCauhinh->hotrochiphi = $rq->hotrochiphi;
            $thCauhinh->tungay = $rq->tungay;
            $thCauhinh->denngay = $rq->denngay;
            $thCauhinh->hocphi = $rq->hocphi;
            $thCauhinh->madonvi = $madonvi;

            $success = $thCauhinh->save();
            return $success?200:500;
        }
    }

    public function delCauhinh(Request $rq){
        if($rq->id != null){
            $success = th_cauhinh::destroy($rq->id);
            return $success?200:500;
        }
    }
    public function getMucluong($ngaybatdau, $ngayketthuc,$madonvi)
    {
        $data = th_cauhinh::where('tungay', '<=', $ngaybatdau)
            ->where('denngay', '>=', $ngayketthuc)
            ->where('madonvi', '=', $madonvi)
            ->select('mucluong')->first();
        $mucluong = 0;
        if ($data != null) {
            $mucluong = $data->mucluong;
        }

        return $mucluong;
    }
    public function getTienDoDung($ngaybatdau, $ngayketthuc,$madonvi)
    {
        $data = th_cauhinh::where('tungay', '<=', $ngaybatdau)
            ->where('denngay', '>=', $ngayketthuc)
            ->where('madonvi', '=', $madonvi)
            ->select('tien_dodunght')->first();
        $mucluong = 0;
        if ($data != null) {
            $mucluong = $data->tien_dodunght;
        }

        return $mucluong;
    }
     public function getMucHuong($ngaybatdau, $ngayketthuc,$madonvi)
    {
        $data = th_cauhinh::where('tungay', '<=', $ngaybatdau)
            ->where('denngay', '>=', $ngayketthuc)
            ->where('madonvi', '=', $madonvi)
            ->select('muchuongdantocthieuso')->first();
        $mucluong = 0;
        if ($data != null) {
            $mucluong = $data->muchuongdantocthieuso;
        }

        return $mucluong;
    }
     public function getHoTroChiPhi($ngaybatdau, $ngayketthuc,$madonvi)
    {
        $data = th_cauhinh::where('tungay', '<=', $ngaybatdau)
            ->where('denngay', '>=', $ngayketthuc)
            ->where('madonvi', '=', $madonvi)
            ->select('hotrochiphi')->first();
        $mucluong = 0;
        if ($data != null) {
            $mucluong = $data->hotrochiphi;
        }

        return $mucluong;
    }
    public function getHocPhi($ngaybatdau, $ngayketthuc,$madonvi)
    {
        $data = th_cauhinh::where('tungay', '<=', $ngaybatdau)
            ->where('denngay', '>=', $ngayketthuc)
            ->where('madonvi', '=', $madonvi)
            ->select('hocphi')->first();
        $mucluong = 0;
        if ($data != null) {
            $mucluong = $data->hocphi;
        }

        return $mucluong;
    }
}
