<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\danhsachbieumau;
use Exception;
use Session;
class danhsachbieumauController extends Controller
{
    public function danhsachBieumau($loai)
    {
		$madonvi = Session::get('madonvi');
        return response()->json(danhsachbieumau::where('apdung', 1)
								->where('loai', $loai)
								 ->Where(function ($query) use ($madonvi) {
               						 $query->Orwhere('maxa', '=', $madonvi)
               					     ->OrwhereNull('maxa');
         						   })
								->get());
    }

    public function store(Request $request)
    {
		$madonvi = Session::get('madonvi');
        try {
            $fileBleumau = null;
            if (!file_exists(storage_path('upload'))) {
                mkdir(storage_path('upload'));
            }
			$fileBleumau = $madonvi.'_'.$request->file->getClientOriginalName();
             $request->file->move(public_path('report') , $fileBleumau);
                

            $bieumau = new danhsachbieumau();
            $bieumau->name = $request->name;
            $bieumau->maxa = $madonvi;
            $bieumau->apdung = $request->apdung;
            $bieumau->filename = $fileBleumau;
            $bieumau->loai = $request->loai;
            if ($bieumau->save()) {
                return response()->json(["code" => 200, "message" => $bieumau->id]);
            } else {
                return response()->json(["code" => 401, "message" => "fail"]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function edit(Request $request)
    {
        try {
            $fileBleumau = null;
            if (!file_exists(storage_path('upload'))) {
                mkdir(storage_path('upload'));
            }
            $f = $request->file;
            if ($f != "null") {
				$fileBleumau = $madonvi.'_'.$request->file->getClientOriginalName();
             	$request->file->move(public_path('report') , $fileBleumau);
                //$request->file->move(public_path('report') . $request->file->getClientOriginalName());
                //$fileBleumau = $request->file->getClientOriginalName();
            }
            $bieumau = danhsachbieumau::find($request->id);
            $bieumau->name = $request->name;
            if ($f != "null") {
                //$bieumau->filename = $request->file->getClientOriginalName();
                $bieumau->filename = $fileBleumau;
            }
            $bieumau->apdung = $request->apdung;
            
            $bieumau->loai = $request->loai;
            if ($bieumau->save()) {
                return response()->json(["code" => 200, "message" => $bieumau->id]);
            } else {
                return response()->json(["code" => 401, "message" => "fail"]);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $bieumau = danhsachbieumau::find($id);
            if (file_exists(public_path('report') . "/" . $bieumau->filename)) {
                unlink(public_path('report') . "/" . $bieumau->filename);
            }
            danhsachbieumau::destroy($id);
            return response()->json(["code" => 200, "message" => "ok"]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function show($id)
    {
        $bieumau = danhsachbieumau::find($id);
        return response()->json($bieumau);
    }
}
