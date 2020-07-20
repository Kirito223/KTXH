<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Closure;

class HomeController extends Controller
{
	
    public function __construct()
    {
        //$this->middleware('auth');

       
    }


    public function home()
    {
    	
            return view('home.index');       
    }




}
