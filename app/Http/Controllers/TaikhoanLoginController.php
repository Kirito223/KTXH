<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\MessageBag;
use App\User;
use Session;
use Validator;
use Hash;
use App\tbl_donvihanhchinh;
use App\tbl_taikhoan;
use App\tbl_tinh;
use Exception;

class TaikhoanLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getLogin()
    {
        return view('ktxh.dangnhap');
    }

    public function doLogin1(Request $request)
    {
        try {
            $tendangnhap = $request->input('tendangnhap');
            $taikhoan = tbl_taikhoan::where('tendangnhap', '=', $tendangnhap)
                //->join('tbl_donvihanhchinh', 'tbl_donvihanhchinh.id', 'taikhoan.donvi')
                ->firstOrFail();
            $password = $request->input('password');
            if (Auth::guard('taikhoan')->attempt(array($this->username() => $tendangnhap, 'password' => $password))) {
                $phongban = tbl_taikhoan::where('tbl_taikhoan.id', '=', $taikhoan->id)
                    ->leftJoin('tbl_phongban', 'tbl_phongban.id', 'tbl_taikhoan.phongban')
                    ->leftJoin('tbl_donvihanhchinh', 'tbl_donvihanhchinh.id', 'tbl_taikhoan.donvi')
                    ->select('tbl_phongban.id', 'tbl_phongban.tenphongban', 'tbl_donvihanhchinh.diaban', 'tbl_phongban.madonvi', 'tbl_donvihanhchinh.tendonvi', 'tbl_donvihanhchinh.id as idDonvihanhchinh', 'tbl_donvihanhchinh.madonvi as donvicha')
                    ->first();
                //Session::put('donvichae', $phongban->idDonvihanhchinh);
                //if ($phongban != null) 
                {
                    $province = tbl_tinh::find($phongban->diaban);
                    Session::put('userid', $taikhoan->id);
                    Session::put('name', $taikhoan->tentaikhoan);
                    Session::put('phongbanid', $phongban->id);
                    Session::put('tenphongban', $phongban->tenphongban);
                    Session::put('madonvi', $phongban->idDonvihanhchinh);
                    Session::put('tendonvi',  $phongban->tendonvi);
                   
                    Session::put('nameprovince', $province->_name);
                    Session::put('idprovince', $province->id);
                    Session::put('madiaban', $phongban->diaban);
                    //Session::put('donvicha', $taikhoan->getDonviChaId());
                    if ($phongban->id) {
                        Session::put('admin', true);
                    }
                    return redirect('/home')->with('success', 'Đăng nhập thành công');
                }
                Session::put('userid', $taikhoan->id);
                Session::put('name', $taikhoan->tentaikhoan);
                if ($phongban == null) {
                    Session::put('tenphongban', "Không thuộc đơn vị");
                }
                return redirect('/quanlytaikhoan')->with('success', 'Đăng nhập thành công');
            }
            return redirect('/')->with('fail', 'Người dùng không tồn tại hoặc thông tin đăng nhập sai vui lòng kiểm tra lại');
        } catch (Exception $ex) {
            return redirect('/')->with('fail', $ex);
        }
    }

    public function doLogin(Request $request)
    {
        $messages = [
            'tendangnhap.required' => 'Tên đăng nhập không được bỏ trống',
            'password.required' => 'Mật khẩu không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tendangnhap' => 'required',
            'password' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return redirect('/dangnhap')->withErrors($validator);
        }
        $tendangnhap = $request->input('tendangnhap');
        $taikhoan = tbl_taikhoan::where('tendangnhap', '=', $tendangnhap)->first();
        if ($taikhoan == null) {
            return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
                'errorlogin' => 'Tên đăng nhập không tồn tại, vui lòng nhập lại'
            ]);
        }
        $password = $request->input('password');
        if (Auth::guard('taikhoan')->attempt(array($this->username() => $tendangnhap, 'password' => $password))) {
            $phongban = tbl_taikhoan::where('tbl_taikhoan.id', '=', $taikhoan->id)
                ->leftJoin('tbl_phongban', 'tbl_phongban.id', 'tbl_taikhoan.phongban')
                ->join('tbl_donvihanhchinh', 'tbl_donvihanhchinh.id', 'tbl_taikhoan.donvi')
                ->select('tbl_phongban.id', 'tbl_phongban.tenphongban', 'tbl_donvihanhchinh.diaban', 'tbl_phongban.madonvi', 'tbl_donvihanhchinh.tendonvi', 'tbl_donvihanhchinh.id as idDonvihanhchinh', 'tbl_donvihanhchinh.madonvi as madv')
                ->first();
            if ($phongban != null) {
                $province = tbl_tinh::find($phongban->diaban);
                Session::put('userid', $taikhoan->id);
                Session::put('name', $taikhoan->tentaikhoan);
                Session::put('phongbanid', $phongban->id);
                Session::put('tenphongban', $phongban->tenphongban);
                Session::put('madonvi', $phongban->idDonvihanhchinh);
                Session::put('tendonvi',  $phongban->tendonvi);
                Session::put('nameprovince', $province->_name);
                Session::put('idprovince', $province->id);
                Session::put('madiaban', $phongban->diaban);
                Session::put('madiabandvch', $phongban->madv);
                if ($phongban->id) {
                    Session::put('admin', true);
                }
                //return redirect('/home')->with('success', 'Đăng nhập thành công');
            }
            Session::put('userid', $taikhoan->id);
            Session::put('name', $taikhoan->tentaikhoan);
            if ($phongban == null) {
                Session::put('tenphongban', "Không thuộc đơn vị");
            }
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công');
        }
        return redirect()->back()->withInput($request->only('tendangnhap'))->withErrors([
            'errorlogin' => 'Sai mật khẩu, vui lòng nhập lại'
        ]);
    }

    public function doLogout()
    {
        Auth::guard('taikhoan')->logout();
        return redirect()->route('login');
    }

    public function username()
    {
        return 'tendangnhap';
    }
}
