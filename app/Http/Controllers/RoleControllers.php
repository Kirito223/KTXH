<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\roles;
use DataTables;
use DB;
class RoleControllers extends Controller
{
   /**
    get view role
    lấy danh sách role
    lấy cac trường của role
    thêm sữa role
    xóa role
     */
    public function __construct()
    {
        $this->middleware('auth');
       
    }

    public function role(){
        return View('admin.role');
    }
    public function Listrole(){        
       $data = roles::all();
       return json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    public function getrole(Request $rq){
      $data =  roles::find($rq->id);
      return json_encode($data, JSON_UNESCAPED_UNICODE);
  }

  public function InsertAndUpdateRole(Request $rq){
    if($rq->id == null){
        $roles = new roles();
        $roles->name = $rq->name;
        $roles->description = $rq->description;
        $success = $roles->save();
        return $success?200:500;
    }else{
        $find = roles::find($rq->id);
        $find->name = $rq->name;
        $find->description = $rq->description;
        $find->description = $rq->description;
        $success = $find->save();
        return $success?200:500;
    }
}

public function delRolec(Request $rq){
    $success = roles::destroy($rq->id);
    return $success?200:500;
}
}
