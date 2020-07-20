<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_nhomquyen;
use App\tbl_quyen;
use Validator;
use Illuminate\Support\Facades\DB;

class tbl_nhomquyenController extends Controller
{
    public function view()
    {
    }

    public function index()
    {
        $nhomquyens = tbl_nhomquyen::where('isDelete', 0)->get();
        $quyens = tbl_quyen::where('isDelete', 0)->get();
        return view('ktxh.nhomquyen', [
            'nhomquyens' => $nhomquyens,
            'quyens' => $quyens
            ]);
    }

    public function store(Request $request)
    {
        $messages = [
            'tennhomquyen.required' => 'Tên nhóm quyền không được bỏ trống',
            'mota.required'  => 'Mô tả không được bỏ trống',
            'quyen.required' => 'Quyền không được bỏ trống'
        ];  
        $validator = Validator::make($request->all(), [
            'tennhomquyen' => 'required',
            'mota' => 'required',
            'quyen' => 'required'
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            $nhomquyen = new tbl_nhomquyen;
            $nhomquyen->tennhomquyen = $request->input('tennhomquyen');
            $nhomquyen->mota = $request->input('mota');
            $nhomquyen->kichhoat = $request->input('kichhoat') == "true";
            $nhomquyen->isDelete = 0;
            $nhomquyen->save();
            $nhomquyen->quyens()->sync($request->input('quyen'));
            $nhomquyen->save();
            session()->flash('success', "Nhóm quyền mới đã được tạo");
            return response()->json(['success'=> ["Nhóm quyền mới đã được tạo"]]);
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request)
    {
        if(!is_null($request->input('kichhoat'))){
            $checkedItems = $request->input('kichhoat');
            $checkedItemsArray = explode(',', $checkedItems);
            DB::table('tbl_nhomquyen')->update(['kichhoat' => 0]);    
            DB::table('tbl_nhomquyen')->whereIn('id', $checkedItemsArray)->update(['kichhoat' => 1]);
        }     
        return redirect('/nhomquyen')->with('success', 'Đã lưu thay đổi thành công');
    }

    public function updateItem(Request $request, $id) {
        $messages = [
            'tennhomquyen.required' => 'Tên nhóm quyền không được bỏ trống',
            'mota.required'  => 'Mô tả không được bỏ trống',
            'quyen.required' => 'Quyền không được bỏ trống',
        ];  
        $validator = Validator::make($request->all(), [
            'tennhomquyen' => 'required',
            'mota' => 'required',
            'quyen' => 'required'
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            $nhomquyen = tbl_nhomquyen::findOrFail($id);
            $nhomquyen->tennhomquyen = $request->input('tennhomquyen');
            $nhomquyen->mota = $request->input('mota');
            $nhomquyen->kichhoat = $request->input('kichhoat') == "true";
            $nhomquyen->quyens()->sync($request->input('quyen'));
            $nhomquyen->save();
            session()->flash('success', "Chỉnh sửa nhóm quyền thành công");
            return response()->json(['success'=> ["Chỉnh sửa nhóm quyền thành công"]]);
        }
    }

    public function destroy($id)
    {
        $nhomquyen = tbl_nhomquyen::findOrFail($id);
        $nhomquyen->isDelete = true;
        $nhomquyen->save();
        session()->flash('success', "Đã xóa nhóm quyền thành công");
        return response()->json(['success'=> $id]);    
    }
}