<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_xaphuongRequest;
use App\tbl_xaphuong;

class tbl_xaphuongController extends Controller
{
    public function index()
    {
        $tbl_xaphuongs = tbl_xaphuong::latest()->get();

        return response(['data' => $tbl_xaphuongs], 200);
    }

    public function store(tbl_xaphuongRequest $request)
    {
        $tbl_xaphuong = tbl_xaphuong::create($request->all());

        return response(['data' => $tbl_xaphuong], 201);
    }

    public function show($id)
    {
        $tbl_xaphuong = tbl_xaphuong::findOrFail($id);

        return response(['data', $tbl_xaphuong], 200);
    }

    public function update(tbl_xaphuongRequest $request, $id)
    {
        $tbl_xaphuong = tbl_xaphuong::findOrFail($id);
        $tbl_xaphuong->update($request->all());

        return response(['data' => $tbl_xaphuong], 200);
    }

    public function destroy($id)
    {
        tbl_xaphuong::destroy($id);

        return response(['data' => null], 204);
    }

    public function listXaphuongwithQuanhuyen($id)
    {
        $list = tbl_xaphuong::where('_district_id', $id)
            ->where('isDelete', 0)->get();
        return response()->json($list);
    }
}
