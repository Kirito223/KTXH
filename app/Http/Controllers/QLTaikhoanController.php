<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_taikhoan;
use Validator;
use Auth;
use Illuminate\Support\Facades\Hash;

class QLTaikhoanController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:taikhoan');
    }

    public function index()
    {
        $taikhoan = tbl_taikhoan::findOrFail(Auth::guard('taikhoan')->user()->id);
        return view('ktxh.quanlytaikhoan', [
            'taikhoan' => $taikhoan
        ]);
    }

    public function changePassword(Request $request ,$id) {
        $messages = [
            'matkhau.required' => 'Mật khẩu không được bỏ trống'
        ];  
        $validator = Validator::make($request->all(), [
            'matkhau' => 'required',
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
             $taikhoan = tbl_taikhoan::findOrFail($id);
             $taikhoan->matkhau = Hash::make($request->input('matkhau'));
             $taikhoan->save();
             session()->flash('success', "Thay đổi mật khẩu thành công");
            return response()->json(['success' => $id]);    
        }
    }

    public function changeInfo(Request $request ,$id){
        $messages = [
            'tentaikhoan.required' => 'Tên tài khoản không được bỏ trống',
            'email.required'  => 'Email không được bỏ trống',
            'ho.required' => 'Họ không được bỏ trống',
            'ten.required' => 'Tên không được bỏ trống'
        ];  
        $validator = Validator::make($request->all(), [
            'tentaikhoan' => 'required',
            'email' => 'required',
            'ho' => 'required',
            'ten' => 'required',
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
             $taikhoan = tbl_taikhoan::findOrFail($id);
             $taikhoan->tentaikhoan = $request->input('tentaikhoan');
             $taikhoan->email = $request->input('email');
             $taikhoan->ho = $request->input('ho');
             $taikhoan->ten = $request->input('ten');
             $taikhoan->save();
             session()->flash('success', "Thay đổi thông tin tài khoản thành công");
            return response()->json(['success' => $id]);    
        }
    }
}