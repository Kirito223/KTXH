<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_donvihanhchinhRequest;
use App\tbl_donvihanhchinh;
use App\tbl_chitieu;
use App\tbl_phongban;
use App\tbl_taikhoan;
use App\tbl_tinh;
use Illuminate\Http\Request;
use Validator;
use Auth;
class tbl_donvihanhchinhController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:taikhoan');
    }	
	
    public function index()
    {
        $donvihanhchinhs = tbl_donvihanhchinh::where('madonvi', null)->where('isDelete', 0)->get();
        $donvihanhchinhsAll = tbl_donvihanhchinh::where('isDelete', 0)->get();
        $tinhAll = tbl_tinh::all();
        return view('ktxh.donvihanhchinh', [
            'donvihanhchinhs' => $donvihanhchinhs,
            'donvihanhchinhsAll' => $donvihanhchinhsAll,
            'tinh' => $tinhAll,
        ]);
    }
    public function danhsachdonvihanhchinh()
    {
        $diaban = session('madiaban');
        $donvihanhchinh = null;
        if ($diaban != null) {
            $donvihanhchinh = tbl_donvihanhchinh::where('diaban', $diaban)
                ->where('isDelete', '=', 0)->get();
        } else {
            $donvihanhchinh = tbl_donvihanhchinh::where('isDelete', '=', 0)->get();
        }

        return response()->json($donvihanhchinh);
    }
	
	 public function listDonvihanhchinParent()
    {
        $donvihanhchinh = tbl_donvihanhchinh::where('isDelete', '=', 0)
            ->where('madonvi', '=', null)
            ->get();
        return response()->json($donvihanhchinh);
    }
	
    public function store(Request $request)
    {
        $messages = [
            'tendonvi.required' => 'Tên đơn vị không được bỏ trống',
            'thuoc.required'  => 'Thuộc loại đơn vị không được bỏ trống',
			'sodienthoai.required'  => 'Số điện thoại không được bỏ trống',
			'email.required'  => 'Email không được bỏ trống',
			'diachi.required'  => 'Địa chỉ không được bỏ trống',
			'mota.required'  => 'Mô tả không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tendonvi' => 'required',
            'thuoc' => 'required',
			'sodienthoai' => 'required',
			'email' => 'required',
			'diachi' => 'required',
			'mota' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $donvihanhchinh = new tbl_donvihanhchinh;
            $donvihanhchinh->tendonvi = $request->input('tendonvi');
            $donvihanhchinh->thuoc = $request->input('thuoc');
            $donvihanhchinh->sodienthoai = $request->input('sodienthoai');
            $donvihanhchinh->email = $request->input('email');
            $donvihanhchinh->madonvi = $request->input('donvihanhchinhcha') == 'none' ? null : $request->input('donvihanhchinhcha');
            $donvihanhchinh->diachi = $request->input('diachi');
            $donvihanhchinh->mota = $request->input('mota');
            $donvihanhchinh->isDelete = 0;
            $donvihanhchinh->diaban = $request->input('tinh');
            $donvihanhchinh->phuong = $request->input('phuong');
            $donvihanhchinh->save();
            session()->flash('success', "Đơn vị hành chính mới đã được tạo");
            return response()->json(['success' => ["Đơn vị hành chính đã được tạo"]]);
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'tendonvi.required' => 'Tên đơn vị không được bỏ trống',
            'thuoc.required'  => 'Thuộc loại đơn vị không được bỏ trống',
			'sodienthoai.required'  => 'Số điện thoại không được bỏ trống',
			'email.required'  => 'Email không được bỏ trống',
			'diachi.required'  => 'Địa chỉ không được bỏ trống',
			'mota.required'  => 'Mô tả không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tendonvi' => 'required',
            'thuoc' => 'required',
			'sodienthoai' => 'required',
			'email' => 'required',
			'diachi' => 'required',
			'mota' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $donvihanhchinh = tbl_donvihanhchinh::findOrFail($id);
            $donvihanhchinh->tendonvi = $request->input('tendonvi');
            $donvihanhchinh->thuoc = $request->input('thuoc');
            $donvihanhchinh->sodienthoai = $request->input('sodienthoai');
            $donvihanhchinh->email = $request->input('email');
            $donvihanhchinh->madonvi = $request->input('donvihanhchinhcha') == 'none' ? null : $request->input('donvihanhchinhcha');
            $donvihanhchinh->diachi = $request->input('diachi');
            $donvihanhchinh->diaban = $request->input('tinh');
            $donvihanhchinh->phuong = $request->input('phuong');
            $donvihanhchinh->mota = $request->input('mota');
            $donvihanhchinh->save();
            session()->flash('success', "Chỉnh sửa đơn vị hành chính thành công");
            return response()->json(['success' => $id]);
        }
    }

    public function destroy($id)
    {
        $donvihanhchinh = tbl_donvihanhchinh::find($id);
        $donvihanhchinh->isDelete = true;
        $donvihanhchinh->save();
        $donvihanhchinh->phongbans()->update(['isDelete' => true]);
        $phongbanIdsArr = $donvihanhchinh->phongbans->pluck('id');
        DB::table('tbl_taikhoan')->whereIn('phongban', $phongbanIdsArr)->orWhere('donvi', $donvihanhchinh->id)->update(['phongban' => null, 'donvi' => 0]);
        session()->flash('success', "Đã xóa đơn vị hành chính thành công");
        return response()->json(['success'=> $id]);
    }

    public function editchitieu($id)
    {
        $donvihanhchinh = tbl_donvihanhchinh::findOrFail($id);
        $chitieusAll = tbl_chitieu::Where('idcha', null)->Where('IsDelete', 0)->get();
        return view('ktxh.donvihanhchinh-edit-chitieu', [
            'donvihanhchinh' => $donvihanhchinh,
            'chitieusAll' => $chitieusAll
        ]);
    }
    public function editusers($id)
    {
        $donvihanhchinh = tbl_donvihanhchinh::findOrFail($id);
        $phongbans = $donvihanhchinh->phongbans->where('isDelete', 0);
		$taikhoanThuocDonviIdsArr = Auth::user()->getTaiKhoanThuocDonViIdsArr();
        $unassignedUsers = Auth::user()->isSuperAdmin() ? tbl_taikhoan::Where('phongban', null)->Where('donvi', 0)->get() : tbl_taikhoan::Where('phongban', null)->Where('donvi', 0)->WhereIn('taikhoantao', $taikhoanThuocDonviIdsArr)->get();
        return view('ktxh.donvihanhchinh-edit-users', [
            'donvihanhchinh' => $donvihanhchinh,
            'phongbans' => $phongbans,
            'unassignedUsers' => $unassignedUsers
        ]);
    }

    public function editphongban($id)
    {
        $donvihanhchinh = tbl_donvihanhchinh::findOrFail($id);
        $phongbans = tbl_phongban::Where('madonvi', $id)->Where('isDelete', 0)->OrderBy('created_at', 'DESC')->get();
        return view('ktxh.donvihanhchinh-edit-phongban', [
            'donvihanhchinh' => $donvihanhchinh,
            'phongbans' => $phongbans
        ]);
    }

    public function updatechitieu(Request $request, $id)
    {
        $donvihanhchinh = tbl_donvihanhchinh::findOrFail($id);
        $donvihanhchinh->chitieus()->sync($request->input('chitieu'));
        return redirect()->route('edichitieudvhc', ['id' => $id])->with('success', 'Đã cập nhật chỉ tiêu');
    }

    public function khoitaodulieu(Request $request, $id)
    {
        $messages = [
            'tendonvi.required' => 'Tên đơn vị nguồn không được bỏ trống',
        ];
        $validator = Validator::make($request->all(), [
            'iddonvinguon' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $donvidich = tbl_donvihanhchinh::findOrFail($id);
            if (count($donvidich->chitieus) > 0) {
                return response()->json(['error' => ['Đơn vị đã có chỉ tiêu, không thể khởi tạo']]);
            }
            $donvinguon = tbl_donvihanhchinh::findOrFail($request->input('iddonvinguon'));
            $chitieuArr = array();
            foreach ($donvinguon->chitieus as $chitieu) {
                array_push($chitieuArr, $chitieu->id);
            }
            $donvidich->chitieus()->sync($chitieuArr);
            session()->flash('success', "Khởi tạo dữ liệu thành công");
            return response()->json(['success' => $id]);
        }
    }
}
