<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\MessageBag;
use Auth;
use App\User;
use Session;
use Validator;
use App\xa_donvi;
use App\tbl_donvihanhchinh;
use App\tbl_taikhoan;

class AdminLoginController extends Controller
{
    public function getLogin()
    {
        return view('admin.login');
    }


    public function postLogin(LoginRequest $request)
    {
        // Kiểm tra dữ liệu nhập vào
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
        $messages = [
            'email.required' => 'Email là trường bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Mật khẩu là trường bắt buộc',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Điều kiện dữ liệu không hợp lệ sẽ chuyển về trang đăng nhập và thông báo lỗi
            return redirect('/')->withErrors($validator)->withInput();
        } else {
            // Nếu dữ liệu hợp lệ sẽ kiểm tra trong csdl
            $email = $request->input('email');
            $password = $request->input('password');

            if (Auth::attempt(['email' => $email, 'password' => $password])) {

                $user = Auth::user(); //
                 //xa_donvi::select('id')->where('user_id',$user->id)->first();
                $phongban = tbl_taikhoan::where('tbl_taikhoan.id', '=', $user->id)
                    ->join('tbl_phongban', 'tbl_phongban.id', 'tbl_taikhoan.donvi')
                    ->select('tbl_phongban.id', 'tbl_phongban.tenphongban')
                    ->first();
				$phongban = tbl_taikhoan::where('tbl_taikhoan.id', '=', $user->id)
                    ->join('tbl_phongban', 'tbl_phongban.id', 'tbl_taikhoan.donvi')
                    ->select('tbl_phongban.id', 'tbl_phongban.tenphongban', 'tbl_phongban.madonvi')
                    ->first();
                $madonvi = $phongban->madonvi;
                $dvhc = tbl_donvihanhchinh::find($madonvi);
				
                Session::put('userid', $user->id);
                Session::put('name', $user->name);
                Session::put('phongbanid', $phongban->id);
                Session::put('tenphongban', $phongban->tenphongban);
                Session::put('madonvi', $madonvi);
                Session::put('tendonvi', $dvhc->tendonvi);

                //if(Auth::user()->role == 1)
                return redirect('home')->with('thongbao', 'Đăng nhập thành công');
                // else if(Auth::user()->role == 2)
                //     return redirect()->route('home')->with('thongbao','Đăng nhập thành công');
                // else if(Auth::user()->role == 3)
                //     return redirect()->route('home')->with('thongbao','Đăng nhập thành công');                 
            } else {
                // Kiểm tra không đúng sẽ hiển thị thông báo lỗi
                $errors = new MessageBag(['errorlogin' => 'Email hoặc mật khẩu không đúng']);
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
    }
    public function getLogout()
    {
        Auth::logout();
        Session::put('madonvi', '0');
        return redirect()->route('getLogin');
    }
}
