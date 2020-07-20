<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_loaisolieuRequest;
use App\tbl_loaisolieu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

class tbl_loaisolieuController extends Controller
{
    public function view()
    {
    }

    public function index()
    {
        $loaisolieus = DB::table('tbl_loaisolieu')->where('isDelete', 0)->orderBy('thutu')->orderBy('id', 'DESC')->get();
        return view('loaisolieu', ['loaisolieus' => $loaisolieus]);
    }

    public function Danhsachloaidoituong()
    {
        $data = tbl_loaisolieu::where('isDelete', 0)->get();
        return json_encode($data);
    }

    public function store(Request $request)
    {
        $messages = [
            'tenloaisolieu.required' => 'Tên loại số liệu không được bỏ trống',
            'mota.required'  => 'Mô tả không được bỏ trống',
            'cachtinh.required' => 'Cách tính không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tenloaisolieu' => 'required',
            'mota' => 'required',
            'cachtinh' => 'required',
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $loaisolieu = new tbl_loaisolieu;
            $loaisolieu->tenloaisolieu = $request->input('tenloaisolieu');
            $loaisolieu->mota = $request->input('mota');
            $loaisolieu->cachtinh = $request->input('cachtinh');
            $loaisolieu->apdung = $request->input('apdung') == "true";
            $loaisolieu->thutu = 0;
            $loaisolieu->isDelete = 0;
            $loaisolieu->save();
            session()->flash('success', "Loại số liệu mới đã được tạo");
            return response()->json(['success' => ["Loại số liệu mới đã được tạo"]]);
        }
    }

    public function updateItem(Request $request, $id)
    {
        $messages = [
            'tenloaisolieu.required' => 'Tên loại số liệu không được bỏ trống',
            'mota.required'  => 'Mô tả không được bỏ trống',
            'cachtinh.required' => 'Cách tính không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tenloaisolieu' => 'required',
            'mota' => 'required',
            'cachtinh' => 'required',
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $loaisolieu = tbl_loaisolieu::findOrFail($id);
            $loaisolieu->tenloaisolieu = $request->input('tenloaisolieu');
            $loaisolieu->mota = $request->input('mota');
            $loaisolieu->cachtinh = $request->input('cachtinh');
            $loaisolieu->apdung = $request->input('apdung') == "true";
            $loaisolieu->save();
            session()->flash('success', "Chỉnh sửa loại số liệu thành công");
            return response()->json(['success' => $id]);
        }
    }


    public function show($id)
    {
    }

    public function update(Request $request)
    {

        if (request('thutu') != null) {
            $orders = json_decode(request('thutu'));
            for ($i = 0; $i < count($orders); $i++) {
                $loaidulieu = tbl_loaisolieu::findOrFail($orders[$i]);
                $loaidulieu->thutu = $i + 1;
                $loaidulieu->save();
            }
        }
        $checkedItems = $request->input('apdung');
        $checkedItemsArray = explode(',', $checkedItems);
        DB::table('tbl_loaisolieu')->update(['apdung' => 0]);
        if (!is_null($checkedItems)) {
            DB::table('tbl_loaisolieu')->whereIn('id', $checkedItemsArray)->update(['apdung' => 1]);
        }
        return redirect('/loaisolieu')->with('success', 'Đã lưu thay đổi thành công');
    }

    public function destroy($id)
    {
        $loaisolieu = tbl_loaisolieu::find($id);
        $loaisolieu->isDelete = true;
        $loaisolieu->save();
        session()->flash('success', "Đã xóa loại số liệu thành công");
        return response()->json(['success' => $id]);
    }
}
