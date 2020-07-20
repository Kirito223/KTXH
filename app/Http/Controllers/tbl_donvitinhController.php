<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_donvitinhRequest;
use App\tbl_donvitinh;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

class tbl_donvitinhController extends Controller
{

    public function index()
    {
        $donvitinhs = DB::table('tbl_donvitinh')->where('isDelete', 0)->orderBy('thutu')->orderBy('id', 'DESC')->get();
        return view('donvitinh', ['donvitinhs' => $donvitinhs]);
    }
    public function getlistdonvitinh()
    {
        $data =  tbl_donvitinh::where('IsDelete', '0')->get();
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function store(Request $request)
    {
        $messages = [
            'maso.required' => 'Mã số không được bỏ trống',
            'tendonvi.required'  => 'Tên đơn vị không được bỏ trống',
        ];
        $validator = Validator::make($request->all(), [
            'maso' => 'required',
            'tendonvi' => 'required',
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $donvitinh = new tbl_donvitinh;
            $donvitinh->maso = $request->input('maso');
            $donvitinh->tendonvi = $request->input('tendonvi');
            $donvitinh->mota = $request->input('mota');
            $donvitinh->apdung = $request->input('apdung') == "true";
            $donvitinh->thutu = 0;
            $donvitinh->isDelete = 0;
            $donvitinh->save();
            session()->flash('success', "Đơn vị tính mới đã được tạo");
            return response()->json(['success' => ["Đơn vị tính mới đã được tạo"]]);
        }
    }

    public function show($id)
    {
        return tbl_donvitinh::find($id);
    }

    public function updateItem(Request $request, $id)
    {
        $messages = [
            'maso.required' => 'Mã số không được bỏ trống',
            'tendonvi.required'  => 'Tên đơn vị không được bỏ trống',
        ];
        $validator = Validator::make($request->all(), [
            'maso' => 'required',
            'tendonvi' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $donvitinh = tbl_donvitinh::findOrFail($id);
            $donvitinh->maso = $request->input('maso');
            $donvitinh->tendonvi = $request->input('tendonvi');
            $donvitinh->mota = $request->input('mota');
            $donvitinh->apdung = $request->input('apdung') == "true";
            $donvitinh->save();
            session()->flash('success', "Chỉnh sửa đơn vị tính thành công");
            return response()->json(['success' => $id]);
        }
    }

    public function update(Request $request)
    {
        if (request('thutu') != null) {
            $orders = json_decode(request('thutu'));

            $donvitinh = tbl_donvitinh::findOrFail(1);
            for ($i = 0; $i < count($orders); $i++) {
                $donvitinh = tbl_donvitinh::findOrFail($orders[$i]);
                $donvitinh->thutu = $i + 1;
                $donvitinh->save();
            }
        }
        $checkedItems = $request->input('apdung');
        $checkedItemsArray = explode(',', $checkedItems);
        DB::table('tbl_donvitinh')->update(['apdung' => 0]);
        if (!is_null($checkedItems)) {
            DB::table('tbl_donvitinh')->whereIn('id', $checkedItemsArray)->update(['apdung' => 1]);
        }
        return redirect('/donvitinh')->with('success', 'Đã lưu thay đổi thành công');
    }

    public function destroy($id)
    {
        $donvitinh = tbl_donvitinh::find($id);
        $donvitinh->isDelete = true;
        $donvitinh->save();
        session()->flash('success', "Đã xóa đơn vị tính thành công");
        return response()->json(['success' => $id]);
    }
}
