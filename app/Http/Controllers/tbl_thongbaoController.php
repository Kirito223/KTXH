<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_thongbaoRequest;
use App\tbl_thongbao;
use App\tbl_donvihanhchinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Validator;
use Auth;
use Carbon\Carbon;

class tbl_thongbaoController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:taikhoan');
    }
	
    public function view()
    {
        
    }

    public function index()
    {
		
        $donvi = Auth::user()->getDonvi();
		if($donvi != null) {
			$thongbaos = tbl_thongbao::Where('madonvi', $donvi->id)->Where('isDelete', 0)->orderBy('id', 'DESC')->get();
        	$donvihanhchinhcons = $donvi->donvihanhchinhcon()->Where('isDelete', 0)->get();
		} else {
			$thongbaos = [];
			$donvihanhchinhcons = [];
		}
        if(count($thongbaos) > 0) {
            foreach($thongbaos as $thongbao) {
                $thongbao->ngaybatdau = date('d/m/Y', strtotime($thongbao->ngaybatdau));
                $thongbao->ngayketthuc = date('d/m/Y', strtotime($thongbao->ngayketthuc));
            }
        }
        return view("ktxh.thongbao", [
            'thongbaos' => $thongbaos,
            'donvihanhchinhcons' => $donvihanhchinhcons
        ]);
    }

    public function store(Request $request)
    {
        $messages = [
            'tieude.required' => 'Tên tiêu đề không được bỏ trống',
            'ngaybatdau.required'  => 'Ngày bắt đầu không được bỏ trống',
            'ngayketthuc.required' => 'Ngày kết thúc không được bỏ trống',
            'noidung.required' => 'Nội dung không được bỏ trống',
            'taptin.required' => 'Tập tin không được bỏ trống'
        ];  
        $validator = Validator::make($request->all(), [
            'tieude' => 'required',
            'ngaybatdau' => 'required',
            'ngayketthuc' => 'required',
            'noidung' => 'required',
            'taptin' => 'required'
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            $thongbao = new tbl_thongbao;
            $thongbao->tieude = $request->input('tieude');
            $thongbao->ngaybatdau = date("Y-m-d", strtotime(str_replace('/', '-', $request->input('ngaybatdau'))));
            $thongbao->ngayketthuc = date("Y-m-d", strtotime(str_replace('/', '-', $request->input('ngayketthuc'))));
            if($request->hasFile('taptin')) {
                $fileName = $request->file('taptin')->getClientOriginalName();
                $uniqueFileName = time(). "-" . $fileName;
                $request->file('taptin')->storeAs('uploads', $uniqueFileName);
                $thongbao->taptin = $uniqueFileName;
            }
            $thongbao->kichhoat = $request->input('kichhoat') == "true";
            $thongbao->noidung = $request->input('noidung');
            $thongbao->madonvi = Auth::user()->tbl_phongban !== null ? Auth::user()->tbl_phongban->donvihanhchinh->id : Auth::user()->tbl_donvihanhchinh->id;
            $thongbao->isDelete = 0;
            $thongbao->save();
            session()->flash('success', "Thông báo mới đã được tạo");
            return response()->json(['success'=> ["Thông báo mới đã được tạo"]]);
        }
    }

    public function show($id)
    {
    }

    public function updateItem(Request $request , $id) {
        $messages = [
            'tieude.required' => 'Tiêu đề không được bỏ trống',
            'ngaybatdau.required'  => 'Ngày bắt đầu không được bỏ trống',
            'ngayketthuc.required' => 'Ngày kết thúc không được bỏ trống',
            'noidung.required' => 'Nội dung không được bỏ trống'
        ];  
        $validator = Validator::make($request->all(), [
            'tieude' => 'required',
            'ngaybatdau' => 'required',
            'ngayketthuc' => 'required',
            'noidung' => 'required'
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
             $thongbao = tbl_thongbao::findOrFail($id);
             $thongbao->tieude = $request->input('tieude');
             $thongbao->ngaybatdau = date("Y-m-d", strtotime(str_replace('/', '-', $request->input('ngaybatdau'))));
             $thongbao->ngayketthuc = date("Y-m-d", strtotime(str_replace('/', '-', $request->input('ngayketthuc'))));
             if($request->hasFile('taptin')) {
                if($thongbao->taptin != null) {
                    $filePath = storage_path('app\uploads\\') . $thongbao->taptin;
                    unlink($filePath);
                }
                $fileName = $request->file('taptin')->getClientOriginalName();
                $uniqueFileName = time(). "-" . $fileName;
                $request->file('taptin')->storeAs('uploads', $uniqueFileName);
                $thongbao->taptin = $uniqueFileName;
             }
             $thongbao->kichhoat = $request->input('kichhoat') == "true";
             $thongbao->noidung = $request->input('noidung');
             $thongbao->save();
             session()->flash('success', "Chỉnh sửa thông báo thành công");
            return response()->json(['success' => $id]);    
        }
    }

    public function update(Request $request)
    {
        $donvi = Auth::user()->getDonvi();
        if($donvi != null) {
                DB::table('tbl_thongbao')->where('madonvi', $donvi->id)->update(['kichhoat' => 0]);
                if(!is_null($request->input('apdung'))){
                    $checkedItems = $request->input('apdung');
                    $checkedItemsArray = explode(',', $checkedItems);
                    DB::table('tbl_thongbao')->where('madonvi', $donvi->id)->whereIn('id', $checkedItemsArray)->update(['kichhoat' => 1]);
                }
                
        }
        return redirect('/thongbao')->with('success', 'Đã lưu thay đổi thành công');
    }

    public function destroy($id)
    {
        $thongbao = tbl_thongbao::find($id);
        if($thongbao != null){
            $thongbao->isDelete = true;
            $thongbao->save();
            if($thongbao->taptin != null) {
                $filePath = storage_path('app\uploads\\') . $thongbao->taptin;
                unlink($filePath);
            }
            session()->flash('success', "Đã xóa thông báo thành công");
            return response()->json(['success'=> $id]);   
        } else {
            session()->flash('error', "Không tồn tại thông báo muốn xóa");
            return response()->json(['error'=> "Không tồn tại thông báo muốn xóa"]);            
        }
    }

    public function downloadfile($id){
        $thongbao = tbl_thongbao::findOrFail($id);
        if  ($thongbao->taptin !==null) {
        $filePath = storage_path('app\uploads\\') . $thongbao->taptin;
        return Response::download($filePath);
        } else {
        return response()->json(['error'=> "Không tồn tại tập tin"]);
        }
    }
	
	public function sendThongbao(Request $request, $id) {
        $messages = [
            'donvinhan.required' => 'Đơn vị nhận không được bỏ trống'
        ];  
        $validator = Validator::make($request->all(), [
            'donvinhan' => 'required'
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            $thongbao = tbl_thongbao::findOrFail($id);
            $donvinhanIds = $request->input('donvinhan');
            if(Auth::user()->phongban !== null) {
                $donvigui = Auth::user()->tbl_phongban->donvihanhchinh->tendonvi;
              } else {
                $donvigui = Auth::user()->tbl_donvihanhchinh->tendonvi;
                }
            $taikhoans = array();
            foreach($donvinhanIds as $donvinhanId) {
                $donvi = tbl_donvihanhchinh::findOrFail($donvinhanId); 
                foreach($donvi->tbl_taikhoan as $taikhoan) {
                    array_push($taikhoans, $taikhoan->id);
                }
                foreach($donvi->phongbans as $phongban) {
                    foreach($phongban->taikhoans as $taikhoan) {
                        array_push($taikhoans, $taikhoan->id);
                    }
                }
            }
            $thongbao->taikhoans()->attach($taikhoans, [
                'donvigui' => $donvigui,
                'thoigiangui' => Carbon::now()
                ]);
            session()->flash('success', "Gửi thông báo thành công");
            return response()->json(['success'=> "Gửi thông báo thành công"]);
        }
    } 

    public function danhsachthongbao() {
        $thongbaos = Auth::user()->thongbaos()->paginate(15);
        if(count($thongbaos) > 0) {
            foreach($thongbaos as $thongbao) {
                $thongbao->ngaybatdau = date('d/m/Y', strtotime($thongbao->ngaybatdau));
                $thongbao->ngayketthuc = date('d/m/Y', strtotime($thongbao->ngayketthuc));
            }
        }
        return view("ktxh.danhsachthongbao", [
            'thongbaos' => $thongbaos
        ]);
    }

    public function changeThongBaoStatus($id) {
        Auth::user()->thongbaos()->updateExistingPivot($id, ['isSeen' => true]);
        return response()->json(['success'=> 'Cập nhật thành công']);
    }
	
	public function getThongbaoInfo($id) {
        $thongbao = tbl_thongbao::findOrFail($id);
        $thongbao->ngaybatdau = date('d/m/Y', strtotime($thongbao->ngaybatdau));
        $thongbao->ngayketthuc = date('d/m/Y', strtotime($thongbao->ngayketthuc));
        Auth::user()->thongbaos()->updateExistingPivot($id, ['isSeen' => true]);
        return response()->json(['success' => $thongbao]);   
    }
	
	public function getThongbaoSentDetails($id) {
        $donvi = Auth::user()->getDonvi();
        if($donvi != null) {
            $donvihanhchinhcons = $donvi->donvihanhchinhcon;
        } else {
            $donvihanhchinhcons = [];
        }
        $donviconNameArr = array();
        foreach($donvihanhchinhcons as $dvhc) {
            array_push($donviconNameArr, $dvhc->tendonvi);
        }
        $thongbao = tbl_thongbao::findOrFail($id);
        $taikhoans = $thongbao->taikhoans()->get();
        foreach($taikhoans as $taikhoan) {
            $taikhoan->tendonvi = in_array($taikhoan->getDonViName(), $donviconNameArr) ? $taikhoan->getDonViName() : "";
        }
        $taikhoans = $taikhoans->groupBy('tendonvi');
        $taikhoans = json_encode($taikhoans);
        return response()->json(['success' => $taikhoans]);
    }
}
