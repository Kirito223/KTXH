<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_phongbanRequest;
use App\tbl_phongban;
use App\tbl_taikhoan;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Auth;

class tbl_phongbanController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:taikhoan');
    }

    public function DanhsachPhongban()
    {
        $phongban = tbl_phongban::where('isDelete', '=', 0)->get();
        return response()->json($phongban);
    }

    public function view()
    {
    }

    public function index()
    {
    }

    public function store(Request $request)
    {
        $messages = [
            'tenphongban.required' => 'Tên phòng ban không được bỏ trống',
            'madonvi.required' => 'Đơn vị trực thuộc không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tenphongban' => 'required',
            'madonvi' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $phongban = new tbl_phongban;
            $phongban->tenphongban = $request->input('tenphongban');
            $phongban->mota = $request->input('mota');
            $phongban->madonvi = $request->input('madonvi');
            $phongban->isDelete = 0;
            $phongban->save();
            session()->flash('success', "Phòng ban mới đã được tạo");
            return response()->json(['success' => ["Phòng ban mới đã được tạo"]]);
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'tenphongban.required' => 'Tên phòng ban không được bỏ trống',
            'madonvi.required' => 'Đơn vị trực thuộc không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tenphongban' => 'required',
            'madonvi' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $phongban = tbl_phongban::findOrFail($id);
            $phongban->tenphongban = $request->input('tenphongban');
            $phongban->mota = $request->input('mota');
            $phongban->madonvi = $request->input('madonvi');
            $phongban->save();
            session()->flash('success', "Chỉnh sửa phòng ban thành công");
            return response()->json(['success' => $id]);
        }
    }

    public function destroy($id)
    {
        $phongban = tbl_phongban::findOrFail($id);
        $phongban->isDelete = true;
        if ($phongban->save()) {
            session()->flash('success', "Đã xóa phòng ban thành công");
            return response()->json(['success' => $id]);
        } else {
            return response("false", 201);
        }
    }

    public function generateUsersBaseOnPhongban(Request $request, $id)
    {
		if($id == "none") {
		$users = tbl_taikhoan::Where('donvi', $request->input('idDonvi'))->get();
			foreach ($users as $user) {
                $user->phongban = "Không thuộc phòng ban";
            }
		} else {
       $users = tbl_taikhoan::Where('phongban', $id)->get();
            foreach ($users as $user) {
				if($user->tbl_phongban!=null)
                $user->phongban = $user->tbl_phongban->tenphongban;
            }
		}
        return response()->json(['success' => $users]);
    }

    public function AddUsersToPhongban(Request $request)
    {
        $messages = [
            'phongban.required' => 'Id phòng ban không được bỏ trống',
            'usersid.required' => 'Chưa chọn người dùng để thêm vào'
        ];
        $validator = Validator::make($request->all(), [
            'phongban' => 'required',
            'usersid' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $usersId = $request->input('usersid');
			if($request->input('phongban') == "none") {
				if (!is_null($usersId)) {
                DB::table('tbl_taikhoan')->whereIn('id', $usersId)->update(['donvi' => $request->input('idDonvi')]);
				}
				$users = tbl_taikhoan::Where('donvi', $request->input('idDonvi'))->get();
				foreach ($users as $user) {
					$user->phongban = "Không thuộc phòng ban";
				}
			} else {
				if (!is_null($usersId)) {
                DB::table('tbl_taikhoan')->whereIn('id', $usersId)->update(['phongban' => $request->input('phongban')]);
				}
				$users = tbl_taikhoan::Where('phongban', $request->input('phongban'))->get();
				foreach ($users as $user) {
					$user->phongban = $user->tbl_phongban->tenphongban;
				}
			}
			$taikhoanThuocDonviIdsArr = Auth::user()->getTaiKhoanThuocDonViIdsArr();
            $usersNotInPhongban = Auth::user()->isSuperAdmin() ? tbl_taikhoan::Where('phongban', null)->Where('donvi', 0)->get() : tbl_taikhoan::Where('phongban', null)->Where('donvi', 0)->WhereIn('taikhoantao', $taikhoanThuocDonviIdsArr)->get();
            return response()->json([
                'success' => $users,
                'usernotinphongban' => $usersNotInPhongban
            ]);
        }
    }

    public function RemoveUserFromPhongban(Request $request)
    {
        $messages = [
            'userid.required' => 'Chưa chọn người dùng để xóa',
            'phongban.required' => 'Id phòng ban không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'phongban' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
			if($request->input('phongban') == "none") {
				$userId = $request->input('userid');
				if (!is_null($userId)) {
					$user = tbl_taikhoan::findOrFail($userId);
					$user->donvi = 0;
					$user->save();
				}
            	$users = tbl_taikhoan::Where('donvi', $request->input('idDonvi'))->get();
				foreach ($users as $user) {
					$user->phongban = "Không thuộc phòng ban";
				}
			} else {
				$userId = $request->input('userid');
            if (!is_null($userId)) {
                $user = tbl_taikhoan::findOrFail($userId);
                $user->phongban = null;
                $user->save();
				}
				$users = tbl_taikhoan::Where('phongban', $request->input('phongban'))->get();
				foreach ($users as $user) {
					$user->phongban = $user->tbl_phongban->tenphongban;
				}
			}
			$taikhoanThuocDonviIdsArr = Auth::user()->getTaiKhoanThuocDonViIdsArr();
            $usersNotInPhongban = Auth::user()->isSuperAdmin() ? tbl_taikhoan::Where('phongban', null)->Where('donvi', 0)->get() : tbl_taikhoan::Where('phongban', null)->Where('donvi', 0)->WhereIn('taikhoantao', $taikhoanThuocDonviIdsArr)->get();
            return response()->json([
                'success' => $users,
                'usernotinphongban' => $usersNotInPhongban
            ]);
        }
    }
}
