<?php

namespace App\Http\Controllers\ktxh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\tbl_chitieu;
use App\tbl_thonbieuii1;
use App\tbl_thanhvienbieuii1;
use App\tbl_lichcongtacbieuii2;
use App\tbl_kehoachbieuii4b;
class ReportController extends Controller
{
	public function viewreport()
	{
		return view('ktxh.Report.report');
	}

	public function listchitieumau1Report(){
		$data1 =  tbl_thanhvienbieuii1::where('IsDelete',0)->get();
		$data2 = tbl_thonbieuii1::where('IsDelete',0)->get();
		return json_encode([$data1,$data2], JSON_UNESCAPED_UNICODE);
	}

	public function listchitieumau2Report(){
		$data =  tbl_lichcongtacbieuii2::where('IsDelete',0)->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function listchitieumau4BReport(){
		$data =  tbl_kehoachbieuii4b::where('IsDelete',0)->get();
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function listchitieumau6Report(){
		$datacha =  tbl_chitieu::where('idcha',null)->where('IsDelete',0)->get();
		$data = tbl_chitieu::with('children')->where('IsDelete',0)->get();
		return json_encode([$datacha,$data], JSON_UNESCAPED_UNICODE);
	}

	public function listchitieumau7Report(){
		$datacha =  tbl_chitieu::where('idcha',null)->where('IsDelete',0)->get();
		$data = tbl_chitieu::with('children')->where('IsDelete',0)->get();
		return json_encode([$datacha,$data], JSON_UNESCAPED_UNICODE);
	}

	public function listchitieumau8Report(){
		$datacha =  tbl_chitieu::where('idcha',null)->where('IsDelete',0)->get();
		$data = tbl_chitieu::with('children')->where('IsDelete',0)->get();
		return json_encode([$datacha,$data], JSON_UNESCAPED_UNICODE);
	}











}
