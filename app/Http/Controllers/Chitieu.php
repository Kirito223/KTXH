<?php

namespace App\Http\Controllers;

use App\tbl_chitieu;
use Session;

class Chitieu extends Controller
{
    public function viewchitieu()
    {
        return view('ktxh.chitieu');
    }

    public function getchitieu()
    {
        $data = tbl_chitieu::all();
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function getSlectChitieu()
    {
        $madonvi = Session::get('madonvi');

        $data = tbl_chitieu::select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_donvitinh.tendonvi', 'tbl_chitieu.idcha')
            ->where('tbl_chitieu.isDelete', 0)
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->whereIn('tbl_chitieu.id', function ($query) use ($madonvi) {
                $query->select('matieuchi')
                    ->from('tbl_donvi_tieuchi')
                    ->where('tbl_donvi_tieuchi.madonvi', '=', $madonvi);
            })
            ->get();
        $this->data_tree($data, null);
        return response()->json($this->tree);
    }
	

    private $tree = array();

    private function data_tree($data, $parent_id)
    {
        foreach ($data as $key => $value) {
            if ($value->idcha == $parent_id) {
                array_push($this->tree, $value);
                $id = $value->id;
                $this->data_tree($data, $id);
            }
        }
    }
}
