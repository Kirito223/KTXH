<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_quyen;
use App\tbl_route;
use Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
class tbl_quyenController extends Controller
{
    public function view()
    {
    }

    public function index()
    {
        $quyens = tbl_quyen::where('isDelete', 0)->get();
        $routes = tbl_route::get();
        return view('ktxh.quyen', [
            'quyens' => $quyens,
            'routes' => $routes
            ]);
    }

    public function store(Request $request)
    {
        $messages = [
            'tenquyen.required' => '- Tên quyền không được bỏ trống',
            'mota.required'  => '- Mô tả không được bỏ trống',
            'route.required' => '- Route không được bỏ trống'
        ];  
        $validator = Validator::make($request->all(), [
            'tenquyen' => 'required',
            'mota' => 'required',
            'route' => 'required'
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            $quyen = new tbl_quyen;
            $quyen->tenquyen = $request->input('tenquyen');
            $quyen->mota = $request->input('mota');
            $quyen->isDelete = 0;
            $quyen->save();
            $quyen->routes()->sync($request->input('route'));
            $quyen->save();
            session()->flash('success', "Quyền mới đã được tạo");
            return response()->json(['success'=> ["Quyền mới đã được tạo"]]);
        }
    }

    public function update(Request $request, $id) {
        $messages = [
            'tenquyen.required' => '- Tên quyền không được bỏ trống',
            'mota.required'  => '- Mô tả không được bỏ trống',
            'route.required' => '- Route không được bỏ trống',
        ];  
        $validator = Validator::make($request->all(), [
            'tenquyen' => 'required',
            'mota' => 'required',
            'route' => 'required'
        ], $messages);
        if(!$validator->passes()){
            return response()->json(['error'=>$validator->errors()->all()]);
        } else {
            $quyen = tbl_quyen::findOrFail($id);
            $quyen->tenquyen = $request->input('tenquyen');
            $quyen->mota = $request->input('mota');
            $quyen->routes()->sync($request->input('route'));
            $quyen->save();
            session()->flash('success', "Chỉnh sửa quyền thành công");
            return response()->json(['success'=> ["Chỉnh sửa quyền thành công"]]);
        }
    }

    public function destroy($id)
    {
        $quyen = tbl_quyen::findOrFail($id);
        $quyen->isDelete = true;
        $quyen->routes()->sync([]);
        $quyen->save();
        session()->flash('success', "Đã xóa quyền thành công");
        return response()->json(['success'=> "Đã xóa quyền thành công"]);    
    }
	
	public function addAvailableRouteToDB() {
        
		DB::table('tbl_route')->delete();
		//tbl_route::truncate();
        $routes = array_map(function ($route) {
            return $route->uri;
            }, (array) Route::getRoutes()->getIterator());
        $routes = array_map(function ($route) {
            return explode("/", $route)[0]; 
         }, $routes);
        $routes = array_unique($routes);
        foreach($routes as $routeItem) {
            $route = new tbl_route;
            $route->route = $routeItem;
            $route->save();
        }
        return response()->json(['success'=> ["Đã update các route"]]);
    }
}   
