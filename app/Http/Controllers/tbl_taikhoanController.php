<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_taikhoanRequest;
use App\tbl_taikhoan;
use App\tbl_nhomquyen;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Auth;

class tbl_taikhoanController extends Controller
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
        $donvi = Auth::user()->tbl_phongban !== null ? Auth::user()->tbl_phongban->donvihanhchinh : Auth::user()->tbl_donvihanhchinh;
		$taikhoanThuocDonviIdsArr = Auth::user()->getTaiKhoanThuocDonViIdsArr();
		if($donvi != null) {
			$phongbanIdsArr = $donvi->phongbans->pluck('id');
		}
        $taikhoans = Auth::user()->isSuperAdmin() ? tbl_taikhoan::where('isDelete', 0)->get() : tbl_taikhoan::where('isDelete', 0)->where(function($query) use($phongbanIdsArr, $donvi, $taikhoanThuocDonviIdsArr) {
            $query->whereIn('phongban', $phongbanIdsArr)->orWhere('donvi', $donvi->id)->orWhereIn('taikhoantao', $taikhoanThuocDonviIdsArr);
        })->get();
        $nhomquyens = tbl_nhomquyen::where('isDelete', 0)->get();
        return view('ktxh.taikhoan', [
            'taikhoans' => $taikhoans,
            'nhomquyens' => $nhomquyens
        ]);
    }

    public function store(Request $request)
    {
        $messages = [
            'tendangnhap.required' => 'Tên đăng nhập không được bỏ trống',
            'email.required' => 'Email không được bỏ trống',
            'matkhau.required'  => 'Mật khẩu không được bỏ trống',
            'tentaikhoan.required' => 'Tên tài khoản không được bỏ trống',
            'nhomquyen.required' => 'Nhóm quyền không được bỏ trống',
			'ho.required' => 'Họ không được bỏ trống',
			'ten.required' => 'Tên không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tendangnhap' => 'required',
            'email' => 'required',
            'matkhau' => 'required',
            'tentaikhoan' => 'required',
            'nhomquyen' => 'required',
			'ho' => 'required',
			'ten' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else if (tbl_taikhoan::where('tendangnhap', '=', $request->input('tendangnhap'))->count() > 0) {
            return response()->json(['error'=>['Đã tồn tại tên đăng nhập trên hệ thống, vui lòng chọn tên khác']]);
         } else {
            $taikhoan = new tbl_taikhoan;
            $taikhoan->tendangnhap = $request->input('tendangnhap');
            $taikhoan->email = $request->input('email');
            $taikhoan->matkhau = Hash::make($request->input('matkhau'));
            $taikhoan->tentaikhoan = $request->input('tentaikhoan');
            $taikhoan->ho = $request->input('ho');
            $taikhoan->ten = $request->input('ten');
			$taikhoan->taikhoantao = Auth::user()->id;
            $taikhoan->kichhoat = $request->input('kichhoat') == "true";
            $taikhoan->isDelete = 0;
            $taikhoan->save();
            $taikhoan->nhomquyens()->sync($request->input('nhomquyen'));
            $taikhoan->save();
            session()->flash('success', "Tài khoản mới đã được tạo");
            return response()->json(['success' => ["Tài khoản mới đã được tạo"]]);
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request)
    {
        if (!is_null($request->input('kichhoat'))) {
            $checkedItems = $request->input('kichhoat');
            $checkedItemsArray = explode(',', $checkedItems);
            DB::table('tbl_taikhoan')->update(['kichhoat' => 0]);
            DB::table('tbl_taikhoan')->whereIn('id', $checkedItemsArray)->update(['kichhoat' => 1]);
        }
        return redirect('/taikhoan')->with('success', 'Đã lưu thay đổi thành công');
    }

    public function updateItem(Request $request, $id)
    {
        $messages = [
            'tendangnhap.required' => 'Tên đăng nhập không được bỏ trống',
            'email.required'  => 'Email không được bỏ trống',
            'tentaikhoan.required' => 'Tên tài khoản không được bỏ trống',
            'nhomquyen.required' => 'Nhóm quyền không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tendangnhap' => 'required',
            'email' => 'required',
            'tentaikhoan' => 'required',
            'nhomquyen' => 'required',
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $taikhoan = tbl_taikhoan::findOrFail($id);
            $taikhoan->tendangnhap = $request->input('tendangnhap');
            $taikhoan->email = $request->input('email');
            if ($request->input('matkhau') != null) {
                $taikhoan->matkhau = Hash::make($request->input('matkhau'));
            }
            $taikhoan->tentaikhoan = $request->input('tentaikhoan');
            $taikhoan->ho = $request->input('ho');
            $taikhoan->ten = $request->input('ten');
            $taikhoan->kichhoat = $request->input('kichhoat') == "true";
            $taikhoan->nhomquyens()->sync($request->input('nhomquyen'));
            $taikhoan->save();
            session()->flash('success', "Chỉnh sửa tài khoản thành công");
            return response()->json(['success' => ["Chỉnh sửa tài khoản thành công"]]);
        }
    }

    public function destroy($id)
    {
        $taikhoan = tbl_taikhoan::findOrFail($id);
        $taikhoan->isDelete = true;
        $taikhoan->save();
        session()->flash('success', "Đã xóa tài khoản thành công");
        return response()->json(['success' => $id]);
    }
}
