<?php

namespace App\Http\Controllers;

use App\Http\Requests\tbl_bieumauRequest;
use App\tbl_bieumau;

class tbl_bieumauController extends Controller
{
    public function index()
    {
    }

    public function store(tbl_bieumauRequest $request)
    {
    }

    public function show($id)
    {
    }

    public function update(tbl_bieumauRequest $request, $id)
    {
    }

    public function destroy($id)
    {
        $bieumau = tbl_bieumau::find($id);
        $bieumau->isDelete = 1;
        if ($bieumau->save()) {
            return response("true", 200);
        } else {
            return response("false", 201);
        }
    }
}
