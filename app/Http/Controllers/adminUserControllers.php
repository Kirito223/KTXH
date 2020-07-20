<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\admin_users;
use App\role_user;
use App\roles;
use DataTables;
use DB;
use Hash;
use Auth;
use voku\helper\AntiXSS;
class adminUserControllers extends Controller
{   
 /**
    get view role
    lấy danh sách role
    lấy cac trường của role
    thêm sữa role
    xóa role
     */
    




    public function quantrivien(){
        return View('admin.roleUser');
    }
    public function getlistrole(){
        return $data = roles::where('id','<>','1')->get();
    }
    public function ListUser(){
        $data = DB::table('admin_users')
        ->leftJoin('roles', 'roles.id','admin_users.role')       
        ->leftJoin('th_huyen', 'th_huyen.id','admin_users.mahuyen')       
        ->select('roles.name','admin_users.created_at','roles.id','admin_users.id','admin_users.email','admin_users.name as names','th_huyen.huyen')->get();
        return Datatables::of($data)->editColumn('created_at', function ($data) 
        {
            return date('d-m-Y', strtotime($data->created_at) );
        })->make(true);
    }

    public function getUser(Request $rq){
        $antiXss = new AntiXSS();
        $data =  admin_users::find($rq->id);
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function InsertAdminUser(Request $rq){
        if($rq->id == null){
            $admin_users = new admin_users();
            $admin_users->name = $rq->name;
            $admin_users->mahuyen = $rq->mahuyen;
            $email = admin_users::where('email','=',$rq->email)->first();
            if($email != null){
           return 500;// Trả về mã code email đã tổn tại
       }else{
        $admin_users->email = $rq->email;
    }    
    $admin_users->password = Hash::make($rq->password);
    $admin_users->role =$rq->role; 
    $success = $admin_users->save();
    return $success?200:500;
}else{          
    return $success?200:500;
}
}

public function updateAdminUser(Request $rq){        
    $find = admin_users::find($rq->id);     
    $find->name = $rq->name;
    $find->email = $rq->email;            
    $find->role = json_encode($rq->role);
    $success = $find->save();        
}

public function updateAdminPassword(Request $rq){        
    $find = admin_users::find($rq->id);     
    $find->password = Hash::make($rq->password);      
    $success = $find->save();        
}
public function resetAdminPassword(Request $rq){        
    $find = admin_users::find($rq->id);     
    $find->password = Hash::make('abc@123');      
    $success = $find->save();        
}

public function deladminUser(Request $rq){
    $success = admin_users::destroy($rq->id);
    return $success?200:500;
}





}
