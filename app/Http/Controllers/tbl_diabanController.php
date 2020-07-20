<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_tinh;
use App\tbl_quanhuyen;
use App\tbl_xaphuong;
use Validator;

class tbl_diabanController extends Controller
{
    public function view()
    {
    }

    public function index()
    {
        $tinhs = tbl_tinh::where('isDelete', 0)->paginate(10);
        return view('ktxh.diaban', [
            'tinhs' => $tinhs
            ]);
    }

    public function store(Request $request)
    {
        $messages = [
            'tendiaban.required' => 'Tên địa bàn không được bỏ trống',
            'loaidiaban.required'  => 'Loại địa bàn không được bỏ trống'
        ];  
        $validator = Validator::make($request->all(), [
            'tendiaban' => 'required',
            'loaidiaban' => 'required'
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            switch($request->input('loaidiaban')) {
                case 'tinh':
                    $diaban = new tbl_tinh;
                    $diaban->_name = $request->input('tendiaban');
                    $diaban->save();
                break;
                case 'quanhuyen':
                    $diaban = new tbl_quanhuyen;
                    $diaban->_name = $request->input('tendiaban');
                    $diaban->_province_id = $request->input('diabantructhuocid');
                    $diaban->save();
                break;
                case 'xaphuong':
                    $diaban = new tbl_xaphuong;
                    $diaban->_name = $request->input('tendiaban');
                    $diaban->_district_id = $request->input('diabantructhuocid');
                    $diaban->save();
                    break;
            }
            session()->flash('success', "Địa bàn mới đã được tạo");
            return response()->json(['success'=> ["Địa bàn mới đã được tạo"]]);
        }   
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'tendiaban.required' => 'Tên địa bàn không được bỏ trống',
            'loaidiaban.required'  => 'Loại địa bàn không được bỏ trống'
        ];  
        $validator = Validator::make($request->all(), [
            'tendiaban' => 'required',
            'loaidiaban' => 'required'
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            switch($request->input('loaidiaban')) {
                case 'tinh':
                    $diaban = tbl_tinh::findOrFail($id);
                    $diaban->_name = $request->input('tendiaban');
                    $diaban->save();
                break;
                case 'quanhuyen':
                    $diaban = tbl_quanhuyen::findOrFail($id);
                    $diaban->_name = $request->input('tendiaban');
                    $diaban->save();
                break;
                case 'xaphuong':
                    $diaban = tbl_xaphuong::findOrFail($id);
                    $diaban->_name = $request->input('tendiaban');
                    $diaban->save();
                    break;
            }
            session()->flash('success', "Chỉnh sửa địa bàn thành công");
            return response()->json(['success'=> ["Chỉnh sửa địa bàn thành công"]]);
        }
    }

    public function destroy($loaidiaban ,$id)
    {
        switch($loaidiaban) {
            case 'tinh':
                $diaban = tbl_tinh::findOrFail($id);
                $diaban->isDelete = true;
                $diaban->save();
                break;
            case 'quanhuyen':
                $diaban = tbl_quanhuyen::findOrFail($id);
                $diaban->isDelete = true;
                $diaban->save();
                break;
            case 'xaphuong':
                $diaban = tbl_xaphuong::findOrFail($id);
                $diaban->isDelete = true;
                $diaban->save();
                break;
        }
        session()->flash('success', "Đã xóa địa bàn thành công");
        return response()->json(['success'=> $id]);    
    }
}
