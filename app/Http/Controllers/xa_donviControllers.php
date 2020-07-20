<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\xa_donvi;
use App\admin_users;
use DataTables;
use App\th_truong;
use App\th_xa;
use DB;
use Hash;
use voku\helper\AntiXSS;
use Session;


class xa_donviControllers extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
       
    }
    public function listDonVi(){

         $data = DB::table('xa_donvi')->select('xa_donvi.*','admin_users.email','admin_users.id as adminid')->join('admin_users','admin_users.id','xa_donvi.user_id')
         ->get();
      
        return DataTables::of($data)->make(true);
    }
     public function ListTable(Request $rq){
             $antiXss = new AntiXSS();
         $data = DB::table('xa_donvi')->select('xa_donvi.*','admin_users.email','admin_users.id as adminid')->join('admin_users','admin_users.id','xa_donvi.user_id')
         ->where('xa_donvi.id',$rq->id)->first();
      
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function InsertAndUpdate(Request $rq){
        $mahuyen = Session::get('mahuyen');
        if($rq->id == null){
            $donvi = new xa_donvi();
            
            $donvi->loaitaikhoan = $rq->loaitaikhoan;
            $donvi->chucdanh1 = $rq->chucdanh1;
            $donvi->ma_qhns = $rq->ma_qhns;
            $donvi->dienthoai =  $rq->dienthoai;
            $donvi->email = $rq->email;
            $donvi->chucdanh2 = $rq->chucdanh2;
            $donvi->ketoan = $rq->ketoan;
            $donvi->truongphong = $rq->truongphong;
            $donvi->chucdanh_qd2 = $rq->chucdanh_qd2;
            $donvi->tennguoidung = $rq->tennguoidung ;
            $donvi->tendonvi = $rq->donvi;
            $donvi->nguoiky = $rq->nguoiky;
            $donvi->truongphongtc = $rq->truongphongtc;
            $donvi->chucdanh_qd =$rq->chucdanh_qd;
            $donvi->nguoiky_qd = $rq->nguoiky_qd;

            $admin_users = new admin_users();
            $admin_users->name = $rq->tennguoidung;
            $email = admin_users::where('email','=',$rq->email)->first();
            if($email != null){
                        return 500;// Trả về mã code email đã tổn tại
               }
               else
               {
                $admin_users->email = $rq->email;
            }
               
            $admin_users->password = Hash::make($rq->password);
            $admin_users->role =$rq->loaitaikhoan; 
            $admin_users->mahuyen =$mahuyen; 
            $success = $admin_users->save();
            $donvi->user_id =  $admin_users->id;
            $success = $donvi->save();
            // them ma truong hoac ma xa
            if($rq->matruong>0)
            {
                $thTruong = th_truong::find($rq->matruong);
                $thTruong->madonvi = $donvi->id;
                 $success = $thTruong->save();
            }
            if($rq->maxa>0)
            {
                $thXa = th_xa::find($rq->maxa);
                $thXa->madonvi = $donvi->id;
                 $success = $thXa->save();
            }

            

            return $success?200:500;
        }else{
            $donvi = xa_donvi::find($rq->id);
            $donvi->loaitaikhoan = $rq->loaitaikhoan;
            $donvi->chucdanh1 = $rq->chucdanh1;
            $donvi->ma_qhns = $rq->ma_qhns;
            $donvi->dienthoai =  $rq->dienthoai;
            $donvi->email = $rq->email;
            $donvi->chucdanh2 = $rq->chucdanh2;
            $donvi->ketoan = $rq->ketoan;
            $donvi->truongphong = $rq->truongphong;
            $donvi->chucdanh_qd2 = $rq->chucdanh_qd2;
            $donvi->tennguoidung = $rq->tennguoidung ;
            $donvi->tendonvi = $rq->donvi;
            $donvi->nguoiky = $rq->nguoiky;
            $donvi->truongphongtc = $rq->truongphongtc;
            $donvi->chucdanh_qd =$rq->chucdanh_qd;
            $donvi->nguoiky_qd = $rq->nguoiky_qd;
            $success = $donvi->save();
            return $success?200:500;
        }
    }

    public function delDonVi(Request $rq){
        if($rq->id != null)
        {
            $donvi=xa_donvi::find($rq->id);
            $success = admin_users::destroy($donvi->user_id);
            $success = xa_donvi::destroy($rq->id);
            return $success?200:500;
        }
    }
}
