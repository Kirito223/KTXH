<?php

namespace App\Http\Controllers\baocao;

use App\Http\Controllers\Controller;
use App\Http\Requests\tbl_bieumauRequest;
use App\tbl_bieumau;
use App\tbl_chitietbieumau;
use Illuminate\Http\Request;
use Session;
use stdClass;
use DB;

class Quanlybaocao extends Controller
{
    public function view(Request $request)
    {
        return view('quanlybaocao.baocao');
    }

    public function edtBaocao()
    {
        return view('quanlybaocao.formbaocao');
    }
    public function viewThemBaocao()
    {
        return view('quanlybaocao.formbaocao');
    }

    # lay danh sachc cac bieu mau ap dung
    public function danhsachBaocaoApdung()
    {
     $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        $data = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
        ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
           // ->where('loaibaocao', '=', 1)
            ->where('trangthai', '=', 1)
            //->where('tbl_bieumau.kybaocao', '=', $ky)
            ->where('tbl_bieumau.madonvi', '=', $madonvi)
            ->select(DB::raw('CONCAT(tbl_bieumau.tenbieumau,"-", tbl_taikhoan.tentaikhoan) AS tenbieumau'), 'tbl_bieumau.*')
            ->get()->toArray();
        $datacha = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
        ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
            //->where('loaibaocao', '=', 1)
            ->where('trangthai', '=', 1)
            // ->where('tbl_bieumau.kybaocao', '=', $ky)
            ->select(DB::raw('CONCAT(tbl_bieumau.tenbieumau,"-", tbl_taikhoan.tentaikhoan) AS tenbieumau'), 'tbl_bieumau.*')
            ->where('tbl_bieumau.madonvi', '=', $donvicha)
            ->get()->toArray();
        $data = array_merge($data, $datacha);
        return \response()->json($data);
	}

    public function index()
    {
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        $data = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
			
            ->where('loaibaocao', '=', 1)
            ->where('trangthai', '=', 1)
            ->where('tbl_bieumau.madonvi', '=', $madonvi)
			
            //->Where(function ($query) use ($madonvi, $donvicha) {
            //    $query->Orwhere('tbl_bieumau.madonvi', '=', $madonvi)
            //        ->Orwhere('tbl_bieumau.madonvi', '=', $donvicha);
           // })
            ->select('tbl_bieumau.*', 'tbl_taikhoan.tentaikhoan')
            ->get()->toArray();
       
        return json_encode($data);
    }

    public function store(Request $request)
    {
        // Upload file vao thu muc chua tai lieu
        $file = null;
        if (!isset($request->fileedit) && isset($request->file)) {
            $request->file->move(public_path('upload'), $request->file->getClientOriginalName());
            $file = $request->file->getClientOriginalName();
        }
        $bieumau = new tbl_bieumau();
        $bieumau->sohieu = $request->sohieu;
        $bieumau->tenbieumau = $request->tenbieumau;
        $bieumau->madonvi = $request->donvi;
        $bieumau->taikhoan = $request->taikhoan;
        $bieumau->soquyetdinh = $request->soquyetdinh;
        $bieumau->ngayquyetdinh = $request->ngayquyetdinh;
        $bieumau->loaibaocao = $request->loaibaocao;
        $bieumau->kybaocao = $request->kybaocao;
        $bieumau->loaisolieu = $request->loaisolieu;
        $bieumau->loaibaocao = 1;
        $bieumau->isDelete = 0;
        if ($request->trangthaiapdung == "true") {
            $bieumau->trangthai = 1;
        }
        $bieumau->mota = $request->mota;
        $bieumau->file = $file;
        if ($bieumau->save()) {
            // lưu thông tin chi tiết biểu mẫu
            $id = $bieumau->id;
            $chiteu = json_decode($request->chitieu);
            foreach ($chiteu as $value) {
                $chitiet = new tbl_chitietbieumau();
                $chitiet->bieumau = $id;
                $chitiet->chitieu = $value;
                $chitiet->isDelete = 0;
                $chitiet->save();
            }
            return response(200);
        } else {
            return response(301);
        }
    }

    public function show($id)
    {
        $bieumau = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
            ->where('tbl_bieumau.id', '=', $id)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
            // ->join('tbl_phongban', 'tbl_phongban.id', 'tbl_taikhoan.phongban')
            ->select(
                'tbl_bieumau.id',
                'tbl_bieumau.*',
                'tbl_taikhoan.tentaikhoan',
                'tbl_taikhoan.id as idTaikhoan'
            )
            ->get();
        $chitietbieumau = tbl_chitietbieumau::where('isDelete', '=', 0)
            ->where('bieumau', '=', $id)
            ->select('chitieu')
            ->get()->toArray();
        $result = new stdClass();
        $result->thongtinchung = $bieumau;
        $result->chitiet = $chitietbieumau;
        return response()->json($result);
    }

    public function update(tbl_bieumauRequest $request)
    {
        $bieumau = tbl_bieumau::find($request->id);
        $bieumau->sohieu = $request->sohieu;
        $bieumau->tenbieumau = $request->tenbieumau;
        $bieumau->madonvi = $request->donvi;
        $bieumau->taikhoan = $request->taikhoan;
        $bieumau->soquyetdinh = $request->soquyetdinh;
        $bieumau->ngayquyetdinh = $request->ngayquyetdinh;
        $bieumau->loaibaocao = $request->loaibaocao;
        $bieumau->kybaocao = $request->kybaocao;
        $bieumau->loaisolieu = $request->loaisolieu;
        $bieumau->mota = $request->mota;
        $request->trangthaiapdung ? $bieumau->trangthai = 1 : $bieumau->trangthai = 0;

            // Upload file
            if ($request->file != "null") {
                $request->file->move('upload', $request->file->getClientOriginalName());
                $file = $request->file->getClientOriginalName();
                $bieumau->file = $file;
            }
    
        if ($bieumau->save()) {
            # Xoa chi tiet bieu mau cu
            $chitietcu = tbl_chitietbieumau::where('bieumau', '=', $request->id)
                ->where('isDelete', '=', 0)
                ->get();

            foreach ($chitietcu as $value) {
                $value->isDelete = 1;
                $value->save();
            }
            # lưu thông tin chi tiết biểu mẫu
            $id = $bieumau->id;
            $chiteu = json_decode($request->chitieu);
            foreach ($chiteu as $value) {
                $chitiet = new tbl_chitietbieumau();
                $chitiet->bieumau = $id;
                $chitiet->chitieu = $value;
                $chitiet->isDelete = 0;
                $chitiet->save();
            }
            return response(200);
        } else {
            return response(301);
        }
    }

    public function apply($id)
    {
        $bieumau = tbl_bieumau::find($id);
        $bieumau->trangthai = 1;
        if ($bieumau->save()) {
            return response(200);
        } else {
            return response(301);
        }
    }

    public function destroy(tbl_bieumauRequest $request)
    {
        $sucess = false;
        $bieumau = $request->bieumau;
        $bieumau = json_decode($bieumau);
        foreach ($bieumau as $value) {
            $obj = tbl_bieumau::find($value->id);
            $obj->isDelete = 1;
            $obj->save();
            $sucess = true;
        }
        if ($sucess == true) {
            return response(200);
        } else {
            return response(301);
        }
    }
}
