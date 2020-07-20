<?php

namespace App\Http\Controllers\Ultils;

use App\tbl_chitietbieumau;
use App\tbl_chitieu;
use stdClass;

class ChitieuUltils
{
    # This is function get Tree chi tieu 

    public function getTreeChitieuForTemplate($idTemplate)
    {
        $result = array();
        $deltailTemplate = tbl_chitietbieumau::where('tbl_chitietbieumau.bieumau', '=', $idTemplate)
            ->where('tbl_chitietbieumau.isDelete', '=',  0)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitietbieumau.id', 'tbl_chitietbieumau.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->get();

        foreach ($deltailTemplate as $item) {
            $ParentId = $item->idcha;
            $obj = new stdClass();
            $obj->id = $item->chitieu; // Id of chi tieu
            $obj->ten = $item->tenchitieu;
            $obj->sanluong = null;
            $obj->donvi = $item->tendonvi;
            $obj->idcha = $ParentId;
            array_push($result, $obj);
            $idFind = $ParentId;
            if ($ParentId != null) {
                // Find Parent node of this child node
                while ($idFind != null) {
                    $valueExist = $this->checkvalueExist($result, $idFind);
                    if ($valueExist == false) {
                        $chitieuf = $this->getInfochitieu($idFind);
                        $obj = new stdClass();
                        $obj->id = $chitieuf->id; // Id cua chi tieu;
                        $obj->ten = $chitieuf->tenchitieu;
                        $obj->sanluong = null;
                        $obj->donvi = $chitieuf->tendonvi;
                        $obj->idcha = $chitieuf->idcha;
                        array_push($result, $obj);
                        $idFind = $chitieuf->idcha;
                    } else {
                        $idFind = null;
                    }
                }
            }
        }
        return $result;
    }

    public function getTreeChitieu($arrChitieu)
    {
        $result = array();
        foreach ($arrChitieu as $item) {
            $ParentId = $item->idcha;
            $obj = new stdClass();
            $obj->id = $item->id; // Id of chi tieu
            $obj->ten = $item->tenchitieu;
            $obj->donvi = $item->tendonvi;
            $obj->idcha = $ParentId;
            array_push($result, $obj);
            $idFind = $ParentId;
            if ($ParentId != null) {
                // Find Parent node of this child node
                while ($idFind != null) {
                    $valueExist = $this->checkvalueExist($result, $idFind);
                    if ($valueExist == false) {
                        $chitieuf = $this->getInfochitieu($idFind);
                        $obj = new stdClass();
                        $obj->id = $chitieuf->id; // Id cua chi tieu;
                        $obj->ten = $chitieuf->tenchitieu;
                        $obj->donvi = $chitieuf->tendonvi;
                        $obj->idcha = $chitieuf->idcha;
                        array_push($result, $obj);
                        $idFind = $chitieuf->idcha;
                    } else {
                        $idFind = null;
                    }
                }
            }
        }
        return $result;
    }

    public function getInfochitieu($Id)
    {
        $chitieu = tbl_chitieu::where('tbl_chitieu.id', '=', $Id)
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_donvitinh.tendonvi')
            ->first();
        return $chitieu;
    }
    ## Check value exist in array if exist return true if not exist return false
    public function checkvalueExist($arr, $id)
    {
        foreach ($arr as $value) {
            if ($value->id == $id) {
                return true;
            }
        }
        return false;
    }
	
	public function buildTree($array)
    {
        $result = array();
        foreach ($array as $key => $item) {
            // gan cha vao mang
            array_push($result, $item);
            // Gan cac node con vao mang
            foreach ($array as $key => $v) {
                if ($v->id != $item->id && $v->idcha == $item->id) {
                    array_push($result, $item);
                }
            }
        }
        return $result;
    }
}
