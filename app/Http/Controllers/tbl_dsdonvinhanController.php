<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_dsdonvinhanRequest;
use App\tbl_dsdonvinhan;

class tbl_dsdonvinhanController extends Controller
{
    public function index()
    {
    }

    public function store(tbl_dsdonvinhanRequest $request)
    {
        $tbl_dsdonvinhan = tbl_dsdonvinhan::create($request->all());

        return response(['data' => $tbl_dsdonvinhan], 201);
    }

    public function show($id)
    {
        $tbl_dsdonvinhan = tbl_dsdonvinhan::findOrFail($id);

        return response(['data', $tbl_dsdonvinhan], 200);
    }

    public function update(tbl_dsdonvinhanRequest $request, $id)
    {
        $tbl_dsdonvinhan = tbl_dsdonvinhan::findOrFail($id);
        $tbl_dsdonvinhan->update($request->all());

        return response(['data' => $tbl_dsdonvinhan], 200);
    }

    public function destroy($id)
    {
        tbl_dsdonvinhan::destroy($id);

        return response(['data' => null], 204);
    }
}
