<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_kehoachktxhxa;
use App\tbl_maubieukehoach;
use App\tbl_thonbieuii1;
use App\tbl_thanhvienbieuii1;
use App\tbl_lichcongtacbieuii2;
use App\tbl_kehoachbieuii4b;
use App\tbl_phongban;
use Illuminate\Support\Facades\DB;
use Auth;
use Validator;

class tbl_kehoachktxhxaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:taikhoan');
    }

    public function view()
    {
    }

    public function index()
    {   
      if(Auth::user()->phongban !== null) {
        $madonvi = Auth::user()->tbl_phongban->donvihanhchinh->id;
      } else {
        $madonvi = Auth::user()->donvi;
      }
        $kehoachktxhxas = tbl_kehoachktxhxa::Where('madonvi', $madonvi)->Where('isDelete', 0)->get();
        return view('ktxh.kehoachktxhxa', ['kehoachktxhxas' => $kehoachktxhxas]);
    }

    public function store(Request $request)
    {
      $messages = [
        'tenkehoach.required' => 'Tên kế hoach không được bỏ trống',
        'namthuchien.required'  => 'Năm thực hiện không được bỏ trống'
    ];  
    $validator = Validator::make($request->all(), [
        'tenkehoach' => 'required',
        'namthuchien' => 'required'
    ], $messages);
    if(!$validator->passes()){
        return response()->json(['error'=>$validator->errors()->all()]);
    } else {
        $kehoachktxhxa = new tbl_kehoachktxhxa;
        $kehoachktxhxa->tenkehoach = $request->input('tenkehoach');
        $kehoachktxhxa->namthuchien = $request->input('namthuchien');
        if(Auth::user()->phongban !== null) {
          $madonvi = Auth::user()->tbl_phongban->donvihanhchinh->id;
        } else {
          $madonvi = Auth::user()->donvi;
        }
        $kehoachktxhxa->madonvi = $madonvi;
        $kehoachktxhxa->save();
        $maubieuii1 = new tbl_maubieukehoach;
        $maubieuii1->tenbieu = 'ii1';
        $maubieuii1->kehoach = $kehoachktxhxa->id;
        $maubieuii1->save();
        $maubieuii2 = new tbl_maubieukehoach;
        $maubieuii2->tenbieu = 'ii2';
        $maubieuii2->kehoach = $kehoachktxhxa->id;
        $maubieuii2->save();
        $maubieuii4b = new tbl_maubieukehoach;
        $maubieuii4b->tenbieu = 'ii4b';
        $maubieuii4b->kehoach = $kehoachktxhxa->id;
        $maubieuii4b->save();
        session()->flash('success', "Kế hoạch mới đã được tạo");
        return response()->json(['success'=> ["Kế hoạch mới đã được tạo"]]);
    }
    }

    public function show($id)
    {
    }

    public function updateMaubieu(Request $request, $id)
    {
      $data = json_decode($request->input('data'), true);
      $kehoachktxhxa = tbl_kehoachktxhxa::findOrFail($id);
      // Input maubieuii1
      $maubieuii1 = tbl_maubieukehoach::where('tenbieu', 'ii1')->where('kehoach', $id)->first();
      $maubieuii1Inputs = $data['maubieuii1'];
      $maubieuii1->sobieumau = $maubieuii1Inputs['sobieumau'];
      $maubieuii1->ngaybanhanh = date("Y-m-d", strtotime(str_replace('/', '-', $maubieuii1Inputs['ngaybanhanh'])));
      $maubieuii1->vanbanchidao = $maubieuii1Inputs['vanbanchidao'];
      $maubieuii1->save();
      if(array_key_exists('thon', $maubieuii1Inputs)) {
        $thonInputs = $maubieuii1Inputs['thon'];
        $thonIdInputs = array_map(function($thon) {
             return $thon['id'];
        }, $thonInputs);
        $newAndOldIdArrOfNewlyCreatedThon = array();
        DB::table('tbl_thonbieuii1')->where('maubieuii1', $maubieuii1->id)->whereNotIn('id', $thonIdInputs)->update(['isDelete' => 1]);
        foreach($thonInputs as $thonInput) {
          if(!empty($thonInput['tenthon'])){
            if (strpos($thonInput['id'], 'new') !== false) {
              $thonbieuii1 = new tbl_thonbieuii1;
              $thonbieuii1->tenthon = $thonInput['tenthon'];
              $thonbieuii1->maubieuii1 = $maubieuii1->id;
              $thonbieuii1->save();
              $newAndOldIdArrOfNewlyCreatedThon[$thonInput['id']] = $thonbieuii1->id;
            } else {
            $thonbieuii1 = tbl_thonbieuii1::findOrFail($thonInput['id']); 
            $tenthonInput = $thonInput['tenthon'];
            $thonbieuii1->update([
              'tenthon' => $tenthonInput
            ]);
            }
          }
        }
      }
      
      if(array_key_exists('thanhvien', $maubieuii1Inputs)) {
        $thanhvienInputs = $maubieuii1Inputs['thanhvien'];
        $thanhvienIdInputs = array_map(function($thanhvien) {
          return $thanhvien['id'];
        }, $thanhvienInputs);
        DB::table('tbl_thanhvienbieuii1')->orWhere(function($query) use ($maubieuii1) {
          $query->where('tenloaidonvi', 'App\tbl_maubieukehoach')->where('iddonvi', $maubieuii1->id);
        })->whereNotIn('id', $thanhvienIdInputs)->orWhere(function($query) use ($thonIdInputs){
          $query->where('tenloaidonvi', 'App\tbl_thonbieuii1')->whereIn('iddonvi', $thonIdInputs);
        })->whereNotIn('id', $thanhvienIdInputs)->update(['isDelete' => 1]);
        foreach($thanhvienInputs as $thanhvienInput) {
            if(empty($thanhvienInput['id'])) {
              if(!empty($thanhvienInput['hoten']) || !empty($thanhvienInput['chucvu']) || !empty($thanhvienInput['nhiemvu'])) {
                $thanhvienbieuii1 = new tbl_thanhvienbieuii1;
                $thanhvienbieuii1->hoten = $thanhvienInput['hoten'];
                $thanhvienbieuii1->chucvu = $thanhvienInput['chucvu'];
                $thanhvienbieuii1->nhiemvu = $thanhvienInput['nhiemvu'];
                if($thanhvienInput['thuoc'] == "none") {
                  $thanhvienbieuii1->loaidonvi = 1;
                  $thanhvienbieuii1->thuocdonvi()->associate($maubieuii1);
                } else if(strpos($thanhvienInput['thuoc'], 'new') !== false) {
                  $thanhvienbieuii1->loaidonvi = 2;
                  $thonThanhvienBelongsTo = tbl_thonbieuii1::findOrFail($newAndOldIdArrOfNewlyCreatedThon[$thanhvienInput['thuoc']]);
                  $thanhvienbieuii1->thuocdonvi()->associate($thonThanhvienBelongsTo);
                } else {
                  $thanhvienbieuii1->loaidonvi = 2;
                  $thonThanhvienBelongsTo = tbl_thonbieuii1::findOrFail($thanhvienInput['thuoc']);
                  $thanhvienbieuii1->thuocdonvi()->associate($thonThanhvienBelongsTo);
                }
                $thanhvienbieuii1->save();
              }
            } else {
                $thanhvienbieuii1 = tbl_thanhvienbieuii1::findOrFail($thanhvienInput['id']);
                $thanhvienbieuii1->hoten = $thanhvienInput['hoten'];
                $thanhvienbieuii1->chucvu = $thanhvienInput['chucvu'];
                $thanhvienbieuii1->nhiemvu = $thanhvienInput['nhiemvu'];
                if($thanhvienInput['thuoc'] == "none") {
                  $thanhvienbieuii1->thuocdonvi()->associate($maubieuii1);
                } else if(strpos($thanhvienInput['thuoc'], 'new') !== false) {
                  $thonThanhvienBelongsTo = tbl_thonbieuii1::findOrFail($newAndOldIdArrOfNewlyCreatedThon[$thanhvienInput['thuoc']]);
                  $thanhvienbieuii1->thuocdonvi()->associate($thonThanhvienBelongsTo);
                } else {
                  $thonThanhvienBelongsTo = tbl_thonbieuii1::findOrFail($thanhvienInput['thuoc']);
                  $thanhvienbieuii1->thuocdonvi()->associate($thonThanhvienBelongsTo);
                }
                $thanhvienbieuii1->save();
            }
        }
      } else {}
      

      //input maubieuii2
      $maubieuii2 = tbl_maubieukehoach::where('tenbieu', 'ii2')->where('kehoach', $id)->first();
      $maubieuii2Inputs = $data['maubieuii2'];
      $maubieuii2->sobieumau = $maubieuii2Inputs['sobieumau'];
      $maubieuii2->ngaybanhanh = date("Y-m-d", strtotime(str_replace('/', '-', $maubieuii2Inputs['ngaybanhanh'])));
      $maubieuii2->truocngay = date("Y-m-d", strtotime(str_replace('/', '-', $maubieuii2Inputs['truocngay'])));
      $maubieuii2->save();
      
      if(array_key_exists('hoatdong', $maubieuii2Inputs)) {
        $hoatdongInputs = $maubieuii2Inputs['hoatdong'];
        $hoatdongIdInputs = array_map(function($hoatdong) {
          return $hoatdong['id'];
        }, $hoatdongInputs);
      DB::table('tbl_lichcongtacbieuii2')->where('maubieuii2', $maubieuii2->id)->whereNotIn('id', $hoatdongIdInputs)->update(['isDelete' => 1]);
        foreach($hoatdongInputs as $hoatdongInput) {
          if(empty($hoatdongInput['id'])) {
            if(!empty($hoatdongInput['noidung']) || !empty($hoatdongInput['thoigian']) || !empty($hoatdongInput['nguoichiutrachnhiem']) || !empty($hoatdongInput['nguoiphoihop']) || !empty($hoatdongInput['ketqua']) ) {
              $hoatdongbieuii2 = new tbl_lichcongtacbieuii2;
              $hoatdongbieuii2->noidung = $hoatdongInput['noidung'];
              $hoatdongbieuii2->thoigian = date("Y-m-d", strtotime(str_replace('/', '-', $hoatdongInput['thoigian'])));
              $hoatdongbieuii2->nguoichiutrachnhiem = $hoatdongInput['nguoichiutrachnhiem'];
              $hoatdongbieuii2->nguoiphoihop = $hoatdongInput['nguoiphoihop'];
              $hoatdongbieuii2->ketqua = $hoatdongInput['ketqua'];
              $hoatdongbieuii2->maubieuii2 = $maubieuii2->id;
              $hoatdongbieuii2->save();
            }
          } else {
              $hoatdongbieuii2 = tbl_lichcongtacbieuii2::findOrFail($hoatdongInput['id']);
              $hoatdongbieuii2->noidung = $hoatdongInput['noidung'];
              $hoatdongbieuii2->thoigian = date("Y-m-d", strtotime(str_replace('/', '-', $hoatdongInput['thoigian'])));
              $hoatdongbieuii2->nguoichiutrachnhiem = $hoatdongInput['nguoichiutrachnhiem'];
              $hoatdongbieuii2->nguoiphoihop = $hoatdongInput['nguoiphoihop'];
              $hoatdongbieuii2->ketqua = $hoatdongInput['ketqua'];
              $hoatdongbieuii2->maubieuii2 = $maubieuii2->id;
              $hoatdongbieuii2->save();
          }
        }
      }
      
      
      // Input maubieuii4b
      $maubieuii4b = tbl_maubieukehoach::where('tenbieu', 'ii4b')->where('kehoach', $id)->first();
      $maubieuii4bInputs = $data['maubieuii4b'];
      if(array_key_exists('dexuat', $maubieuii4bInputs)) {
        $dexuatInputs = $maubieuii4bInputs['dexuat'];
        $dexuatIdInputs = array_map(function($dexuat) {
          return $dexuat['id'];
        }, $dexuatInputs);
        DB::table('tbl_kehoachbieuii4b')->where('maubieuii4b', $maubieuii4b->id)->whereNotIn('id', $dexuatIdInputs)->update(['isDelete' => 1]);
        foreach($dexuatInputs as $dexuatInput) {
          if(empty($dexuatInput['id'])) {
            if(!empty($dexuatInput['hoatdong']) || !empty($dexuatInput['dvt']) || !empty($dexuatInput['soluong']) || !empty($dexuatInput['thoigian']) || !empty($dexuatInput['diadiem']) || !empty($dexuatInput['nguoichiutrachnhiem']) || !empty($dexuatInput['linhvuc']) || !empty($dexuatInput['ghichu']) || !empty($dexuatInput['tongso']) || !empty($dexuatInput['ngansach']) || !empty($dexuatInput['dangop']) || !empty($dexuatInput['dexuat'])) {
              $kehoachbieuii4b = new tbl_kehoachbieuii4b;
              $kehoachbieuii4b->hoatdong = $dexuatInput['hoatdong'];
              $kehoachbieuii4b->dvt = $dexuatInput['dvt'];
              $kehoachbieuii4b->soluong = empty($dexuatInput['soluong']) ? 0 : $dexuatInput['soluong'];
              $kehoachbieuii4b->thoigian = $dexuatInput['thoigian'];
              $kehoachbieuii4b->diadiem = $dexuatInput['diadiem'];
              $kehoachbieuii4b->nguoichiutrachnhiem = $dexuatInput['nguoichiutrachnhiem'];
              $kehoachbieuii4b->linhvuc = $dexuatInput['linhvuc'];
              $kehoachbieuii4b->ghichu = $dexuatInput['ghichu'];
              $kehoachbieuii4b->tongso = empty($dexuatInput['tongso']) ? 0 : $dexuatInput['tongso'];
              $kehoachbieuii4b->ngansach = empty($dexuatInput['ngansach']) ? 0 : $dexuatInput['ngansach'];
              $kehoachbieuii4b->dangop = empty($dexuatInput['dangop']) ? 0 : $dexuatInput['dangop'];
              $kehoachbieuii4b->dexuat = empty($dexuatInput['dexuat']) ? 0 : $dexuatInput['dexuat'];
              $kehoachbieuii4b->maubieuii4b = $maubieuii4b->id;
              $kehoachbieuii4b->save();
            }
          } else {
              $kehoachbieuii4b = tbl_kehoachbieuii4b::findOrFail($dexuatInput['id']);
              $kehoachbieuii4b->hoatdong = $dexuatInput['hoatdong'];
              $kehoachbieuii4b->dvt = $dexuatInput['dvt'];
              $kehoachbieuii4b->soluong = $dexuatInput['soluong'];
              $kehoachbieuii4b->thoigian = $dexuatInput['thoigian'];
              $kehoachbieuii4b->diadiem = $dexuatInput['diadiem'];
              $kehoachbieuii4b->nguoichiutrachnhiem = $dexuatInput['nguoichiutrachnhiem'];
              $kehoachbieuii4b->linhvuc = $dexuatInput['linhvuc'];
              $kehoachbieuii4b->ghichu = $dexuatInput['ghichu'];
              $kehoachbieuii4b->tongso = $dexuatInput['tongso'];
              $kehoachbieuii4b->ngansach = $dexuatInput['ngansach'];
              $kehoachbieuii4b->dangop = $dexuatInput['dangop'];
              $kehoachbieuii4b->dexuat = $dexuatInput['dexuat'];
              $kehoachbieuii4b->maubieuii4b = $maubieuii4b->id;
              $kehoachbieuii4b->save();
          }
        }
      }
      
      session()->flash('success', "Chỉnh sửa mẫu biểu thành công");
      return response()->json(['success' => 'success']); 
    }

    public function update(Request $request, $id) {
      $messages = [
        'tenkehoach.required' => 'Tên kế hoạch không được bỏ trống',
        'namthuchien.required'  => 'Năm thực hiện không được bỏ trống'
      ];
    $validator = Validator::make($request->all(), [
        'tenkehoach' => 'required',
        'namthuchien' => 'required'
    ], $messages);
    if(!$validator->passes()){
        return response()->json(['error'=>$validator->errors()->all()]);
    } else {
         $kehoachktxhxa = tbl_kehoachktxhxa::findOrFail($id);
         $kehoachktxhxa->tenkehoach = $request->input('tenkehoach');
         $kehoachktxhxa->namthuchien = $request->input('namthuchien');
         $kehoachktxhxa->save();
         session()->flash('success', "Chỉnh sửa kế hoạch thành công");
        return response()->json(['success' => $id]);    
    }
    }

    public function destroy($id)
    {
      $kehoachktxhxa = tbl_kehoachktxhxa::findOrFail($id);
      $kehoachktxhxa->isDelete = true;
      $kehoachktxhxa->save();
      session()->flash('success', "Đã xóa kế hoạch thành công");
      return response()->json(['success'=> $id]);    

    }

    public function details($id) {
      $kehoachktxhxa = tbl_kehoachktxhxa::findOrFail($id);
      foreach($kehoachktxhxa->maubieus as $maubieu) {
        switch($maubieu->tenbieu) {
            case "ii1":
            $maubieuii1 = $maubieu;
            $maubieuii1->ngaybanhanh = date('d/m/Y', strtotime($maubieuii1->ngaybanhanh));
            $maubieuii1->thons = $maubieuii1->thons->where('isDelete', 0);
            $maubieuii1->thanhviens = $maubieuii1->thanhviens->where('isDelete', 0);
            foreach($maubieuii1->thons as $thon) {
              $thon->thanhviens = $thon->thanhviens->where('isDelete', 0);
            }
            break;
            case "ii2":
            $maubieuii2 = $maubieu;
            $maubieuii2->ngaybanhanh = date('d/m/Y', strtotime($maubieuii2->ngaybanhanh));
            $maubieuii2->truocngay = date('d/m/Y', strtotime($maubieuii2->truocngay));
            $maubieuii2->lichcongtacs = $maubieuii2->lichcongtacs->where('isDelete', 0);
            foreach($maubieuii2->lichcongtacs as $lichcongtac) {
              $lichcongtac->thoigian = date('d/m/Y', strtotime($lichcongtac->thoigian));
            }
            break;
            case "ii4b":
            $maubieuii4b = $maubieu;
            $maubieuii4b->dexuats = $maubieuii4b->dexuats->where('isDelete', 0);
            break;  
        }    
      }
        
      return view('ktxh.kehoachktxhxa-details', [
          'kehoachktxhxa' => $kehoachktxhxa,
          'maubieuii1' => $maubieuii1,
          'maubieuii2' => $maubieuii2,
          'maubieuii4b' => $maubieuii4b 
      ]);  
    }
	
	public function viewBieuMau($id) {
      return view('ktxh.bieumaukhktxh', ['kehoachId' => $id]);
    } 

    public function getBieumauData($id, $bieumau) {
		$donvi = Auth::user()->tbl_phongban !== null ? Auth::user()->tbl_phongban->donvihanhchinh : Auth::user()->tbl_donvihanhchinh;
      switch($bieumau) {
        case 'ii1': 
          $kehoachktxhxa = tbl_kehoachktxhxa::with(['maubieus' => function ($query) use ($bieumau) {
            $query->where('tenbieu', $bieumau)->with(['thons' => function ($thonquery) {
              $thonquery->where('isDelete', 0)->with(['thanhviens' => function($thanhvienthonquery) {
			  		$thanhvienthonquery->where('isDelete', 0)->get();
			  }]);
            }])->with(['thanhviens' => function($thanhvienxaquery) {
				$thanhvienxaquery->where('isDelete', 0)->get();
			}]);
        }])->findOrFail($id);
        break;
        case 'ii2':
          $kehoachktxhxa = tbl_kehoachktxhxa::with(['maubieus' => function ($query) use ($bieumau) {
            $query->where('tenbieu', $bieumau)->with(['lichcongtacs' => function($lichquery) {
				$lichquery->where('isDelete', 0)->get();
			}]);
          }])->findOrFail($id);
        break;
        case 'ii4b':
          $kehoachktxhxa = tbl_kehoachktxhxa::with(['maubieus' => function ($query) use ($bieumau) {
            $query->where('tenbieu', $bieumau)->with(['dexuats' => function($dexuatquery) {
				$dexuatquery->where('isDelete', 0)->get();
			}]);
          }])->findOrFail($id);
        break;
      }
	$kehoachktxhxa->tendonvi = $donvi->tendonvi;
      $kehoachktxhxa=[$kehoachktxhxa];
      //$kehoachktxhxa = json_encode($kehoachktxhxa,true);
      return response()->json(['success'=> $kehoachktxhxa]);   
    }
}