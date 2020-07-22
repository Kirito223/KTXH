<?php

namespace App\Http\Controllers\baocao;

use App\Http\Controllers\Controller;
use App\tbl_baocao;
use App\tbl_chitietbaocao;
use App\tbl_donvihanhchinh;
use App\tbl_guibaocao;
use App\tbl_kybaocao;
use App\tbl_taikhoan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Session;
use stdClass;

class DanhsachBaocaoController extends Controller
{
    public function view()
    {
        return view('quanlybaocao.danhsachbaocao');
    }

    public function viewThongtinBaocao()
    {
        return view('quanlybaocao.themmoibaocao');
    }

    public function index()
    {
        $nguoicapnhat = session('userid');
        $madonvi = session('madonvi');
        $data = tbl_baocao::where('tbl_baocao.deleted_at', '=', null)
            ->where('nguoicapnhat', $nguoicapnhat)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_baocao.nguoicapnhat')
            ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_baocao.kybaocao')
            ->select('tbl_baocao.*', 'tbl_taikhoan.tentaikhoan', 'tbl_kybaocao.tenky')
            ->get()->toArray();
        // Lay danh sach cac bao cao duoc gui toi

        $baocaolienquan = tbl_guibaocao::where('donvinhan', $madonvi)->where('tbl_guibaocao.isDelete', 0)
            ->join('tbl_baocao', 'tbl_guibaocao.baocao', 'tbl_baocao.id')
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_baocao.nguoicapnhat')
            ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_baocao.kybaocao')
            ->select('tbl_baocao.*', 'tbl_taikhoan.tentaikhoan', 'tbl_kybaocao.tenky')
            ->get()->toArray();
        $data = array_merge($data, $baocaolienquan);
        return response()->json($data);
    }

    public function danhSachdonvihanhchinh($tinh)
    {
        $danhsach = tbl_donvihanhchinh::where('tbl_donvihanhchinh.isDelete', '=', 0)
            ->where('tbl_donvihanhchinh.diaban', '=', $tinh)
            ->join('tbl_tinh', 'tbl_tinh.id', 'tbl_donvihanhchinh.diaban')
            ->join('tbl_xaphuong', 'tbl_xaphuong.id', 'tbl_donvihanhchinh.phuong')
            ->select('tbl_donvihanhchinh.*', 'tbl_donvihanhchinh.tendonvi', 'tbl_tinh._name as tinh', 'tbl_xaphuong._name as phuong')
            ->get();
        return json_encode($danhsach);
    }

    public function danhsachKybaocao()
    {
        $data = tbl_kybaocao::where('isDelete', '=', 0)->get();
        return json_encode($data);
    }

    public function store(Request $request)
    {
        try {
            // Luu bao cao
            $file = "";
            if ($request->file != "null") {
                $request->file->storeAs('upload', $request->file->getClientOriginalName());
                $file = $request->file->getClientOriginalName();
            } else {
                $file = null;
            }

            $baocao = new tbl_baocao();
            $baocao->sohieu = $request->sohieu;
            $baocao->tieude = $request->tieude;
            $baocao->kybaocao = $request->kybaocao;
            $baocao->ngaycapnhatsaucung = $request->ngaysaucung;
            $baocao->nguoicapnhat = $request->nguoicapnhat;
            $baocao->gui = 0;
            $baocao->trangthai = $request->hoanthanh;
            $baocao->nambaocao = $request->nam;
            $baocao->file = $file;
            if ($baocao->save()) {
                // Luu chi tiet bao cao
                $chitiet = new tbl_chitietbaocao();
                $chitiet->baocao = $baocao->id;
                $chitiet->donvinhan = $request->donvinhan;
                $chitiet->noidung = $request->noidung;
                $chitiet->cacbieusolieu = $request->cacbieusolieu;
                if ($chitiet->save()) {
                    return 200;
                }
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    public function show($id)
    {
        $baocao = tbl_baocao::where('tbl_baocao.id', $id)->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_baocao.kybaocao')
            ->select('tbl_baocao.*', 'tbl_kybaocao.tenky', 
                'tbl_baocao.nguoiky')->first();
        $chitiet = tbl_chitietbaocao::where('baocao', '=', $id)->first();
        $nguoiky = null;
        if ($baocao->nguoiky != null) {
            $nguoiky = tbl_taikhoan::find($baocao->nguoiky);
        }
        $result = new stdClass();
        $result->bieumau = $baocao;
        $result->chitiet = $chitiet;
        $result->nguoiky = $nguoiky;
        return json_encode($result);
    }

    public function update(Request $request)
    {
        try {
            // Luu bao cao
            $file = "";
            if ($request->file != "null") {
                $request->file->storeAs('upload', $request->file->getClientOriginalName());
                $file = $request->file->getClientOriginalName();
            } else {
                if ($request->fileEdit != "") {
                    $file = $request->fileEdit;
                } else {
                    $file = null;
                }
            }

            $baocao = tbl_baocao::find($request->id);
            $baocao->sohieu = $request->sohieu;
            $baocao->tieude = $request->tieude;
            $baocao->kybaocao = $request->kybaocao;
            $baocao->ngaycapnhatsaucung = $request->ngaysaucung;
            $baocao->nguoicapnhat = $request->nguoicapnhat;
            $baocao->gui = 0;
            $baocao->trangthai = $request->hoanthanh;
            $baocao->file = $file;
            if ($baocao->save()) {
                // Xoa bao chi tiet bao cao
                $find = tbl_chitietbaocao::where('baocao', '=', $request->id)->first();
                tbl_chitietbaocao::destroy($find->id);
                // Luu chi tiet bao cao
                $chitiet = new tbl_chitietbaocao();
                $chitiet->baocao = $baocao->id;
                $chitiet->donvinhan = $request->donvinhan;
                $chitiet->noidung = $request->noidung;
                $chitiet->cacbieusolieu = $request->cacbieusolieu;
                if ($chitiet->save()) {
                    return 200;
                }
            }
        } catch (Exception $ex) {
            printf($ex);
            return 500;
        }
    }

    public function destroy(Request $request)
    {
        $data = json_decode($request->baocao);
        foreach ($data as $value) {
            $baocao = tbl_baocao::find($value->id);
            $baocao->delete();
        }
    }
    # function send report
    public function send($id)
    {
        try {
            $date = Carbon::now();
            $user = session('userid');
            $baocao = tbl_baocao::find($id);
            $baocao->gui = 1;
            if ($baocao->save()) {
                $detail = tbl_chitietbaocao::where('baocao', $baocao->id)->first();
                $donvinhan = json_decode($detail->donvinhan);
                foreach ($donvinhan as $donvi) {
                    $send = new tbl_guibaocao();
                    $send->baocao = $baocao->id;
                    $send->donvinhan = $donvi->id;
                    $send->nguoigui = $user;
                    $send->ngaygui = $date;
                    $send->save();
                }
                return response()->json(['success' => 'Đã gửi báo cáo']);
            }
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    # Duyet bao cao

    public function Duyet($id)
    {

        $nguoiky = session('userid');
        $baocao = tbl_baocao::find($id);
        $baocao->nguoiky = $nguoiky;
        $baocao->ngayky = Carbon::now();
        if ($baocao->save()) {
            return response()->json(["Code" => 200, "Message" => "Đã duyệt báo cáo"]);
        } else {
            return response()->json(["Code" => 401, "Message" => "Đã xảy ra lỗi vui lòng kiểm tra lại"]);
        }
    }


    # Danh sach bao cao

    public function viewDanhsachbaocao()
    {
        return view('quanlybaocao\timbaocao');
    }

    public function indexTimkiem()
    {
        $admin = session('phongbanid');
        if ($admin != null) {
            $data = tbl_baocao::where('tbl_baocao.deleted_at', '=', null)
                ->where('tbl_phongban.id', '=', $admin)
                ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_baocao.nguoicapnhat')
                ->join('tbl_phongban', 'tbl_phongban.id', 'tbl_taikhoan.phongban')
                ->join('tbl_donvihanhchinh', 'tbl_donvihanhchinh.id', 'tbl_phongban.madonvi')
                ->join('tbl_xaphuong', 'tbl_xaphuong.id', 'tbl_donvihanhchinh.phuong')
                ->join('tbl_quanhuyen', 'tbl_quanhuyen.id', 'tbl_xaphuong._district_id')
                ->join('tbl_tinh', 'tbl_tinh.id', 'tbl_quanhuyen._province_id')
                ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_baocao.kybaocao')
                ->select(
                    'tbl_baocao.id',
                    'tbl_baocao.sohieu',
                    'tbl_baocao.tieude',
                    'tbl_kybaocao.tenky',
                    'tbl_baocao.ngaycapnhatsaucung',
                    'tbl_donvihanhchinh.tendonvi',
                    'tbl_tinh._name as tinh'
                )
                ->get();
        } else {
            $data = tbl_baocao::where('tbl_baocao.deleted_at', '=', null)
                ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_baocao.nguoicapnhat')
                ->join('tbl_donvihanhchinh', 'tbl_donvihanhchinh.id', 'tbl_taikhoan.donvi')
                ->join('tbl_xaphuong', 'tbl_xaphuong.id', 'tbl_donvihanhchinh.phuong')
                ->join('tbl_quanhuyen', 'tbl_quanhuyen.id', 'tbl_xaphuong._district_id')
                ->join('tbl_tinh', 'tbl_tinh.id', 'tbl_quanhuyen._province_id')
                ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_baocao.kybaocao')
                ->select(
                    'tbl_baocao.id',
                    'tbl_baocao.sohieu',
                    'tbl_baocao.tieude',
                    'tbl_kybaocao.tenky',
                    'tbl_baocao.ngaycapnhatsaucung',
                    'tbl_donvihanhchinh.tendonvi',
                    'tbl_tinh._name as tinh'
                )
                ->get();
        }

        return response()->json($data);
    }

    public function find(Request $request)
    {
        # cac bien se duoc gui tu request
        $donvigui = $request->donvigui;
        $nambaocao = $request->nambaocao;
        $phongban = $request->phongban;
        $kybaocao = $request->kybaocao;
        $tukhoa = $request->tukhoa;
        $admin = session('phongbanid');
        $data = null;
        if($admin != null){
            $data = tbl_baocao::where('tbl_baocao.deleted_at', '=', null)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_baocao.nguoicapnhat')
            ->join('tbl_donvihanhchinh', 'tbl_donvihanhchinh.id', 'tbl_taikhoan.donvi')
            ->join('tbl_xaphuong', 'tbl_xaphuong.id', 'tbl_donvihanhchinh.phuong')
            ->join('tbl_quanhuyen', 'tbl_quanhuyen.id', 'tbl_xaphuong._district_id')
            ->join('tbl_tinh', 'tbl_tinh.id', 'tbl_quanhuyen._province_id')
            ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_baocao.kybaocao')
            ->join('tbl_phongban', 'tbl_phongban.id', 'tbl_taikhoan.phongban')
            ->select(
                'tbl_baocao.id',
                'tbl_baocao.sohieu',
                'tbl_baocao.tieude',
                'tbl_kybaocao.tenky',
                'tbl_baocao.ngaycapnhatsaucung',
                'tbl_donvihanhchinh.tendonvi',
                'tbl_tinh._name as tinh'
            )
            ->when($tukhoa, function ($query) use ($tukhoa) {
                return $query->where('tieude', 'LIKE', '%' . $tukhoa . '%');
            })
            ->when($nambaocao, function ($query) use ($nambaocao) {
                return $query->where('nambaocao', '=', $nambaocao);
            })
            ->when($donvigui, function ($query) use ($donvigui) {
                return $query->where('tbl_donvihanhchinh.id', '=', $donvigui);
            })
            ->when($phongban, function ($query) use ($phongban) {
                return $query->where('tbl_phongban.id', '=', $phongban);
            })
            ->when($kybaocao, function ($query) use ($kybaocao) {
                return $query->where('tbl_kybaocao.id', '=', $kybaocao);
            })
            ->get();
        }else{
            $data = tbl_baocao::where('tbl_baocao.deleted_at', '=', null)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_baocao.nguoicapnhat')
            ->join('tbl_donvihanhchinh', 'tbl_donvihanhchinh.id', 'tbl_taikhoan.donvi')
            ->join('tbl_xaphuong', 'tbl_xaphuong.id', 'tbl_donvihanhchinh.phuong')
            ->join('tbl_quanhuyen', 'tbl_quanhuyen.id', 'tbl_xaphuong._district_id')
            ->join('tbl_tinh', 'tbl_tinh.id', 'tbl_quanhuyen._province_id')
            ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_baocao.kybaocao')
            ->select(
                'tbl_baocao.id',
                'tbl_baocao.sohieu',
                'tbl_baocao.tieude',
                'tbl_kybaocao.tenky',
                'tbl_baocao.ngaycapnhatsaucung',
                'tbl_donvihanhchinh.tendonvi',
                'tbl_tinh._name as tinh'
            )
            ->when($tukhoa, function ($query) use ($tukhoa) {
                return $query->where('tieude', 'LIKE', '%' . $tukhoa . '%');
            })
            ->when($nambaocao, function ($query) use ($nambaocao) {
                return $query->where('nambaocao', '=', $nambaocao);
            })
            ->when($kybaocao, function ($query) use ($kybaocao) {
                return $query->where('tbl_kybaocao.id', '=', $kybaocao);
            })
            ->when($donvigui, function ($query) use ($donvigui) {
                return $query->where('tbl_donvihanhchinh.id', '=', $donvigui);
            })
            ->get();
        }

        
        return response()->json($data, 200);
    }
    public function viewChitietBaocao()
    {
        return view('quanlybaocao\chitietbaocao');
    }

    public function viewDuyetbaocao()
    {
        $donvi = session('madonvi');
        $list = tbl_guibaocao::where('tbl_guibaocao.isDelete', 0)
            ->where('tbl_guibaocao.donvinhan', $donvi)
            ->join('tbl_baocao', 'tbl_baocao.id', 'tbl_guibaocao.baocao')
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_guibaocao.nguoigui')
            ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_baocao.kybaocao')
            ->select(
                'tbl_guibaocao.id',
                'tbl_guibaocao.ngaygui',
                'tbl_guibaocao.duyet',
                'tbl_baocao.sohieu',
                'tbl_baocao.tieude',
                'tbl_kybaocao.tenky',
                'tbl_taikhoan.tentaikhoan',
                'tbl_guibaocao.baocao'
            )->paginate(20);
        return view('baocaodagui\baocaodagui', ['baocao' => $list]);
    }
    public function viewDetailBaocao($id)
    {
        $baocao = $this->show($id);
        $baocao = json_decode($baocao);
        $unit = session('tendonvi');
        return view('baocaodagui\chitietbaocao', ['report' => $baocao, 'unit' => $unit]);
    }
    public function downloadFileDinhkem($file)
    {
        return response()->download(storage_path("app/upload/" . $file));
    }

    public function uploadFileKyso(Request $request)
    {
        if ($request->file != "null") {
            if (!\is_dir(\public_path('upload'))) {
                \mkdir(\public_path('upload'));
            }
            $request->file->move(public_path('upload'), $request->file->getClientOriginalName());
            $file = public_path('upload/' . $request->file->getClientOriginalName());
            return $request->file->getClientOriginalName();
        }
    }

    public function ViewXemchitietbaocao($id)
    {
        Session::put('idXembaocao', $id);
        return \view('quanlybaocao\xembaocao');
    }

   public function getChitiet()
    {
        $id = session('idXembaocao');
        $baocao = tbl_baocao::where('tbl_baocao.id', $id)
            ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_baocao.kybaocao')
            ->select(
                'tbl_baocao.sohieu',
                'tbl_baocao.tieude',
                'tbl_baocao.nambaocao',
                'tbl_baocao.trangthai',
                'tbl_kybaocao.tenky',
                'tbl_baocao.file', 
                'tbl_baocao.nguoiky'
            )->first();
        $chitiet = tbl_chitietbaocao::where('baocao', '=', $id)->first();
        $nguoiky = null;
        if ($baocao->nguoiky != null) {
            $nguoiky = tbl_taikhoan::find($baocao->nguoiky);
        }
        $result = new stdClass();
        $result->bieumau = $baocao;
        $result->chitiet = $chitiet;
        $result->nguoiky = $nguoiky;
        return \response()->json($result);
    }
}
