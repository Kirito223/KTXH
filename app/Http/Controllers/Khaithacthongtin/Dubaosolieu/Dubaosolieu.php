<?php

namespace App\Http\Controllers\Khaithacthongtin\Dubaosolieu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Dubaosolieu extends Controller
{
	public function viewdubaosolieu()
	{
		return view('ktxh.Khaithacthongtin.Dubaosolieu.list');
	}
}
