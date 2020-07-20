<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_kybaocaoRequest;
use App\tbl_kybaocao;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

class tbl_kybaocaoController extends Controller
{
    public function view()
    {
    }

    public function index()
    {
        $kybaocaos = DB::table('tbl_kybaocao')->where('isDelete', 0)->orderBy('thutu')->orderBy('id', 'DESC')->get();
        return view('kybaocao', ['kybaocaos' => $kybaocaos]);
    }

    public function kyBaocao()
    {
        $data = tbl_kybaocao::where('isDelete', 0)->get();
        return json_encode($data);
    }

    public function store(Request $request)
    {
        $messages = [
            'tenky.required' => 'Tên kỳ báo cáo không được bỏ trống',
            'mota.required'  => 'Mô tả không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tenky' => 'required',
            'mota' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $kybaocao = new tbl_kybaocao;
            $kybaocao->tenky = $request->input('tenky');
            $kybaocao->mota = $request->input('mota');
            $kybaocao->thutu = 0;
            $kybaocao->isDelete = 0;
            $kybaocao->save();
            session()->flash('success', "Kỳ báo cáo mới đã được tạo");
            return response()->json(['success' => ["Kỳ báo cáo mới đã được tạo"]]);
        }
    }

    public function show($id)
    {
    }

    public function updateItem(Request $request, $id)
    {
        $messages = [
            'tenky.required' => 'Tên kỳ báo cáo không được bỏ trống',
            'mota.required'  => 'Mô tả không được bỏ trống'
        ];
        $validator = Validator::make($request->all(), [
            'tenky' => 'required',
            'mota' => 'required'
        ], $messages);
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $kybaocao = tbl_kybaocao::findOrFail($id);
            $kybaocao->tenky = $request->input('tenky');
            $kybaocao->mota = $request->input('mota');
            $kybaocao->save();
            session()->flash('success', "Chỉnh sửa kỳ báo cáo thành công");
            return response()->json(['success' => $id]);
        }
    }

    public function update(Request $request)
    {
        if (request('thutu') != null) {
            $orders = json_decode(request('thutu'));
            for ($i = 0; $i < count($orders); $i++) {
                $kybaocao = tbl_kybaocao::findOrFail($orders[$i]);
                $kybaocao->thutu = $i + 1;
                $kybaocao->save();
            }
        }
        return redirect('/kybaocao')->with('success', 'Đã lưu thay đổi thành công');
    }

    public function destroy($id)
    {
        $kybaocao = tbl_kybaocao::find($id);
        $kybaocao->isDelete = true;
        $kybaocao->save();
        session()->flash('success', "Đã xóa kỳ báo cáo thành công");
        return response()->json(['success' => $id]);
    }
}
