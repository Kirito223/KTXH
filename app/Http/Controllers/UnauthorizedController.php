<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnauthorizedController extends Controller
{
    public function index(Request $request, $previousUrl) {
        $request->session()->flash('previousUrl', $previousUrl);
        return view('ktxh.unauthorizedpage');
    }
}
