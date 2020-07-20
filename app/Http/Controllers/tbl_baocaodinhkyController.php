<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_baocaodinhkyRequest;
use App\tbl_baocaodinhky;

class tbl_baocaodinhkyController extends Controller
{
    public function index()
    {
        $tbl_baocaodinhkies = tbl_baocaodinhky::latest()->get();

        return response(['data' => $tbl_baocaodinhkies], 200);
    }

    public function store(tbl_baocaodinhkyRequest $request)
    {
        // Them bao cao
    }

    public function show($id)
    {
        $tbl_baocaodinhky = tbl_baocaodinhky::findOrFail($id);

        return response($tbl_baocaodinhky, 200);
    }

    public function update(tbl_baocaodinhkyRequest $request, $id)
    {
        // Cap nhat bao cao
    }

    public function destroy($id)
    {
        $baocao = tbl_baocaodinhky::find($id);
        $baocao->isDelete = 1;
        if ($baocao->save()) {
            return response("true", 200);
        } else {
            return response("false", 201);
        }
    }
}
