<?php

namespace App\Http\Controllers;

use App\devvn_tinhthanhpho;
use Illuminate\Http\Request;

class devvn_tinhthanhphoController extends Controller
{
    public function view()
    {
    }

    public function index()
    {
        $data = devvn_tinhthanhpho::all();
        return json_encode($data);
    }

    public function store($request)
    {
    }

    public function show($id)
    {
    }

    public function update($request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
