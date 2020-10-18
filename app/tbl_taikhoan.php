<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class tbl_taikhoan extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['tendangnhap',  'matkhau'];

    protected $table = 'tbl_taikhoan';

    public function getAuthPassword()
    {
        return $this->matkhau;
    }

    public function tbl_phongban()
    {
        return $this->belongsTo('App\tbl_phongban', 'phongban');
    }
    public function tbl_duyetbaocao()
    {
        return $this->belongsTo('App\tbl_duyetbaocao', 'nguoipheduyet');
    }

    public function nhomquyens()
    {
        return $this->belongsToMany('App\tbl_nhomquyen', 'tbl_taikhoan_nhomquyen', 'mataikhoan', 'manhomquyen');
    }
    public function tbl_guibaocao()
    {
        return $this->hasMany('App\tbl_guibaocao', 'nguoigui', 'id');
    }
    public function hasPermission($permissions)
    {
        foreach ($this->nhomquyens as $nhomquyen) {
            if ($nhomquyen->hasQuyen($permissions)) {
                return true;
            }
        }
        return false;
    }
	
	public function thongbaos() {
        return $this->belongsToMany('App\tbl_thongbao', 'tbl_taikhoan_thongbao', 'mataikhoan', 'mathongbao')->withPivot('isSeen', 'donvigui', 'thoigiangui')->where('isDelete', 0)->orderBy('tbl_taikhoan_thongbao.isSeen')->orderBy('tbl_taikhoan_thongbao.thoigiangui', 'DESC');
    }
	
	public function tbl_donvihanhchinh() {
        return $this->belongsTo('App\tbl_donvihanhchinh', 'donvi');
    }
	
	public function isSuperAdmin() {
        return $this->tendangnhap == "admin";
    }
	
	public function getTaiKhoanThuocDonViIdsArr() {
        $donvi = $this->tbl_phongban !== null ? $this->tbl_phongban->donvihanhchinh : $this->tbl_donvihanhchinh;
        $idsArr = [];
		if($donvi != null){
		  	foreach($donvi->tbl_taikhoan as $taikhoanThuocDonVi) {
            array_push($idsArr, $taikhoanThuocDonVi->id);
			}

		foreach($donvi->phongbans as $phongban) {
			if($phongban->taikhoans()->exists()){
				foreach($phongban->taikhoans as $taikhoanThuocPhongBan){
                array_push($idsArr, $taikhoanThuocPhongBan->id);
            	}
			}
        }
}
        return $idsArr;
    }
	
	public function getDonviChaId() {
		if($this->tbl_phongban !== null) {
			return $this->tbl_phongban->donvihanhchinh->madonvi;
		} else {
			if($this->tbl_donvihanhchinh !== null) {
				return $this->tbl_donvihanhchinh->madonvi;
			} else {
				return 0;
			}
		}
	}
	
	public function hasPermissionBasedOnRoute(string $route) {
        foreach($this->nhomquyens as $nhomquyen) {
            if($nhomquyen->hasRoute($route)) {
                return true;
            }
        }
        return false;
    }
	
	public function getDonVi() {
        return $this->tbl_phongban !== null ? $this->tbl_phongban->donvihanhchinh : $this->tbl_donvihanhchinh;
    }
	
	public function getDonViName() {
        if($this->phongban == null && $this->donvi == 0) {
            return null;
        }
        return $this->tbl_phongban !== null ? $this->tbl_phongban->donvihanhchinh->tendonvi : $this->tbl_donvihanhchinh->tendonvi;
    }
	
	public function hasAnyDonvicon() {
        $donvi = $this->getDonVi();
        if($donvi !== null) {
           $donvicon = $donvi->donvihanhchinhcon;
           if(count($donvicon) > 0) {
               return true;
           }
            return false;
        } 
        return false;
    }
}
