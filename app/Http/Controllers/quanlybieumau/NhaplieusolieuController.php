<?php

namespace App\Http\Controllers\quanlybieumau;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Quanlydanhmuc\Quanlychitieu\Chitieu;
use App\tbl_bieumau;
use App\tbl_chitietbieumau;
use App\tbl_chitieu;
use App\tbl_chitietsolieutheobieu;
use App\tbl_donvihanhchinh;
use App\tbl_solieutheobieu;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Session;
use stdClass;
use Illuminate\Support\Facades\DB;

class NhaplieusolieuController extends Controller
{
    public function view()
    {
        return view('bieumaunhaplieu.bieumau');
    }

    public function index()
    {
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        //echo $donvicha;
        $data = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
            ->where('tbl_bieumau.loaibaocao', '=', 0)
            ->where('tbl_bieumau.madonvi', '=', $madonvi)
            //->Where(function ($query) use ($madonvi, $donvicha) {
            //    $query->Orwhere('tbl_bieumau.madonvi', '=', $madonvi)
            //        ->Orwhere('tbl_bieumau.madonvi', '=', $donvicha);
            // })
            ->where('tbl_bieumau.trangthai', '=', 1)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
            ->select('tbl_bieumau.id', 'tbl_bieumau.trangthai', 'tbl_bieumau.sohieu', DB::raw('CONCAT(tbl_bieumau.tenbieumau,"-", tbl_taikhoan.tentaikhoan) AS tenbieumau'), 'tbl_taikhoan.tentaikhoan', 'tbl_bieumau.created_at', 'file')
            ->get()->toArray();
        return json_encode($data);
    }
    public function danhsachbieumau()
    {
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        //echo $donvicha;
        $data = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
            //->where('tbl_bieumau.loaibaocao', '=', 0)
            ->Where(function ($query) use ($madonvi, $donvicha) {
                $query->Orwhere('tbl_bieumau.madonvi', '=', $madonvi)
                    ->Orwhere('tbl_bieumau.madonvi', '=', $donvicha);
            })
            ->where('tbl_bieumau.trangthai', '=', 1)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
            ->select('tbl_bieumau.id', 'tbl_bieumau.trangthai', 'tbl_bieumau.sohieu', DB::raw('CONCAT(tbl_bieumau.tenbieumau,"-", tbl_taikhoan.tentaikhoan) AS tenbieumau'), 'tbl_taikhoan.tentaikhoan', 'tbl_bieumau.created_at', 'file')
            ->get()->toArray();
        return json_encode($data);
    }
    public function danhsachbieumaubaocao()
    {
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        //echo $donvicha;
        $data = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
            ->where('tbl_bieumau.loaibaocao', '=', 1)
            ->Where(function ($query) use ($madonvi, $donvicha) {
                $query->Orwhere('tbl_bieumau.madonvi', '=', $madonvi)
                    ->Orwhere('tbl_bieumau.madonvi', '=', $donvicha);
            })
            ->where('tbl_bieumau.trangthai', '=', 1)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
            ->select('tbl_bieumau.id', 'tbl_bieumau.trangthai', 'tbl_bieumau.sohieu', DB::raw('CONCAT(tbl_bieumau.tenbieumau,"-", tbl_taikhoan.tentaikhoan) AS tenbieumau'), 'tbl_taikhoan.tentaikhoan', 'tbl_bieumau.created_at', 'file')
            ->get()->toArray();
        return json_encode($data);
    }
    public function listBieumauActive()
    {
        $madonvi = Session::get('madonvi');
        $donvicha = Session::get('donvicha');
        $data = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
            ->where('tbl_bieumau.loaibaocao', '=', 0)
            ->Where(function ($query) use ($madonvi, $donvicha) {
                $query->Orwhere('tbl_bieumau.madonvi', '=', $madonvi)
                    ->Orwhere('tbl_bieumau.madonvi', '=', $donvicha);
            })
            ->where('tbl_bieumau.trangthai', '=', 1)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
            ->select('tbl_bieumau.id', 'tbl_bieumau.trangthai', 'tbl_bieumau.sohieu', DB::raw('CONCAT(tbl_bieumau.tenbieumau,"-", tbl_taikhoan.tentaikhoan) AS tenbieumau'), 'tbl_taikhoan.tentaikhoan', 'tbl_bieumau.created_at')
            ->get()->toArray();
        return json_encode($data);
    }


    public function viewdanhsachBieumaunhaplieu()
    {
        return view('bieumaunhaplieu.danhsachbieumaunhaplieu');
    }
    # Luu thong tin bieu mau nhap lieu
    public function store(Request $request)
    {
        try {
            // Upload file vao thu muc chua tai lieu
            $file = null;
            if (!isset($request->fileedit) && isset($request->file)) {
                $request->file->move(public_path('upload'), $request->file->getClientOriginalName());
                $file = $request->file->getClientOriginalName();
            }
            $bieumau = new tbl_bieumau();
            $bieumau->sohieu = $request->sohieu;
            $bieumau->tenbieumau = $request->tenbieumau;
            $bieumau->madonvi = session('madonvi');
            $bieumau->taikhoan = session('userid');
            $bieumau->soquyetdinh = $request->soquyetdinh;
            $bieumau->ngayquyetdinh = $request->ngayquyetdinh;
            $bieumau->loaibaocao = 0;
            $bieumau->mota = $request->mota;
            $bieumau->isDelete = 0;
            $bieumau->file = $file;
            if ($request->trangthaiapdung == "true") {
                $bieumau->trangthai = 1;
            }

            // Luu thong tin bieu mau
            if ($bieumau->save()) {

                $id = $bieumau->id;
                $chiteu = json_decode($request->chitieu);

                foreach ($chiteu as $value) {
                    $chitiet = new tbl_chitietbieumau();
                    $chitiet->bieumau = $id;
                    $chitiet->chitieu = $value;
                    $chitiet->isDelete = 0;
                    $chitiet->save();
                }
                return response(200);
            } else {
                return response(301);
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function downloadFileQuyetdinh($file)
    {
        return response()->download(public_path('upload/' . $file));
    }

    public function showDeltalBieumau($id)
    {
        $chitiet = tbl_chitietbieumau::where('bieumau', '=', $id)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->get();
        return $chitiet;
    }

    public function showDeltalBieumauTH($id)
    {
        $chitiet = tbl_chitietbieumau::where('bieumau', '=', $id)
            ->where('tbl_chitietbieumau.isDelete', 0)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->get();
        return $chitiet;
    }
    public function showDeltalChiTieu($id)
    {
        $chitiet = tbl_chitieu::where('tbl_chitieu.id', '=', $id)
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')

            ->get();
        return $chitiet;
    }

    public function show($id)
    {
        $bieumau = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
            ->where('tbl_bieumau.id', '=', $id)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
            // ->join('tbl_phongban', 'tbl_phongban.id', 'tbl_taikhoan.phongban')
            ->select(
                'tbl_bieumau.id',
                'tbl_bieumau.*',
                'tbl_taikhoan.tentaikhoan',
                'tbl_taikhoan.id as idTaikhoan'
            )
            ->get();
        $chitiet = tbl_chitietbieumau::where('bieumau', '=', $id)
            ->where('isDelete', 0)
            ->select('chitieu')
            ->get()->toArray();
        $result = new stdClass();
        $result->thongtinchung = $bieumau;
        $result->chitiet = $chitiet;
        $result = json_encode($result);
        return response($result);
    }
    // Chinh sua bieu mau
    public function update(Request $request)
    {
        $bieumau = tbl_bieumau::find($request->id);
        $bieumau->sohieu = $request->sohieu;
        $bieumau->tenbieumau = $request->tenbieumau;
        $bieumau->madonvi = session('madonvi');
        $bieumau->taikhoan = session('userid');
        $bieumau->soquyetdinh = $request->soquyetdinh;
        $bieumau->ngayquyetdinh = $request->ngayquyetdinh;
        $bieumau->mota = $request->mota;
        $request->trangthaiapdung ? $bieumau->trangthai = 1 : $bieumau->trangthai = 0;
        // Upload file
        if ($request->file != "null") {
            $request->file->move('upload', $request->file->getClientOriginalName());
            $file = $request->file->getClientOriginalName();
            $bieumau->file = $file;
        }

        if ($bieumau->save()) {
            # Xoa chi tiet bieu mau cu
            $chitietcu = tbl_chitietbieumau::where('bieumau', '=', $request->id)
                ->where('isDelete', '=', 0)
                ->get();

            foreach ($chitietcu as $value) {
                $value->isDelete = 1;
                $value->save();
            }
            # lưu thông tin chi tiết biểu mẫu
            $id = $bieumau->id;
            $chiteu = json_decode($request->chitieu);
            foreach ($chiteu as $value) {
                $chitiet = new tbl_chitietbieumau();
                $chitiet->bieumau = $id;
                $chitiet->chitieu = $value;
                $chitiet->isDelete = 0;
                $chitiet->save();
            }
            return response(200);
        } else {
            return response(301);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $bieumau = json_decode($request->bieumau);
            foreach ($bieumau as $value) {
                $obj = tbl_bieumau::find($value->id);
                $obj->isDelete = 1;
                $obj->save();
            }
            return 200;
        } catch (Exception $ex) {
            return 500;
        }
    }

    public function apply($id)
    {
        $bieumau = tbl_bieumau::find($id);
        $bieumau->trangthai = 1;
        if ($bieumau->save()) {
            return 200;
        } else {
            return 500;
        }
    }

    public function delFileQuyetdinh($id)
    {
        $bieumau = tbl_bieumau::find($id);
        $file = $bieumau->file;
        $bieumau->file = null;
        if ($bieumau->save()) {
            unlink(public_path('upload' . '/' . $file));
            return response()->json(["code" => 200, "message" => "ok"]);
        }
        return response()->json(["code" => 401, "message" => "fail"]);
    }

    # Phan nhap lieu bieu mau
    public function viewListNhaplieu()
    {
        return view('bieumaunhaplieu\listofnhaplieu');
    }

    public function viewNhaplieu()
    {
        return view('bieumaunhaplieu\nhaplieu');
    }

    public function indexNhaplieuBieumau()
    {
        $madonvi = 1; //Session::get('madonvi');
        $donvicha = 1; // Session::get('donvicha');

        $data = tbl_solieutheobieu::where('tbl_solieutheobieu.isDelete', 0)
            ->where('tbl_solieutheobieu.loaibaocao', 0)
            //->where('tbl_solieutheobieu.donvinhap', '=', $madonvi)
            ->Where(function ($query) use ($madonvi, $donvicha) {
                $query->Orwhere('tbl_solieutheobieu.donvinhap', '=', $madonvi)
                    ->Orwhere('tbl_solieutheobieu.donvinhap', '=', $donvicha);
            })
            ->join('tbl_bieumau', 'tbl_bieumau.id', 'tbl_solieutheobieu.bieumau')
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_solieutheobieu.taikhoan')
            ->join('tbl_loaisolieu', 'tbl_loaisolieu.id', 'tbl_solieutheobieu.loaisolieu')
            ->select('tbl_solieutheobieu.id', 'tbl_bieumau.sohieu', DB::raw('CONCAT(tbl_bieumau.tenbieumau,"-", tbl_taikhoan.tentaikhoan) AS tenbieumau'), 'tbl_loaisolieu.tenloaisolieu',  'tbl_solieutheobieu.created_at', 'tbl_taikhoan.tentaikhoan', 'tbl_solieutheobieu.namnhap')
            ->get()->toArray();
        return response()->json($data);
    }
    public function danhsachBieumauNhapLieu()
    {
        $data = tbl_bieumau::where('tbl_bieumau.isDelete', '=', 0)
            ->where('tbl_bieumau.trangthai', '=', 1)
            ->where('tbl_bieumau.loaibaocao', '=', 0)
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_bieumau.taikhoan')
            ->select('tbl_bieumau.id', 'tbl_bieumau.trangthai', 'tbl_bieumau.sohieu', 'tbl_bieumau.tenbieumau', 'tbl_taikhoan.tentaikhoan', 'tbl_bieumau.created_at')
            ->get();
        return json_encode($data);
    }

    public function showEditBieumau($id)
    {
        $Form = tbl_solieutheobieu::find($id);

        $Detail = tbl_chitietsolieutheobieu::where('tbl_chitietsolieutheobieu.mabieusolieu', '=',  $id)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietsolieutheobieu.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id as chitieu', 'tbl_chitieu.tenchitieu as tenchitieu', 'tbl_chitieu.idcha', 'tbl_chitietsolieutheobieu.id as idSolieu', 'tbl_chitietsolieutheobieu.sanluong', 'tbl_donvitinh.tendonvi as tendonvi')
            ->where('tbl_chitietsolieutheobieu.isDelete', 0)->get();

        return response()->json(['Form' => $Form, 'Detail' => $Detail]);
    }


    function buildTree($elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['idcha'] == $parentId) {
                $children = $this->buildTree($elements, $element['chitieu']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['chitieu']] = $element;
                unset($element);
            }
        }
        return $branch;
    }

    public function DelBieumau(Request $request)
    {
        try {
            $json = json_decode($request->bieumau);
            foreach ($json as $item) {
                $bieumau = tbl_solieutheobieu::find($item->id);
                $bieumau->isDelete = 1;
                if ($bieumau->save()) {
                    $detail = tbl_chitietsolieutheobieu::where('mabieusolieu', $item->id)->get();
                    foreach ($detail as $Itemdetail) {
                        $Itemdetail->isDelete = 1;
                        $Itemdetail->save();
                    }
                }
            }
            return response()->json(['success' => 200]);
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    private $tree = array();

    private function data_tree($data, $parent_id)
    {
        foreach ($data as $key => $value) {
            if ($value->idcha == $parent_id) {

                array_push($this->tree, $value);
                $id = $value->chitieu;
                $this->data_tree($data, $id);
            }
        }
    }

    public function DowloadExcel(Request $request)
    {
        $getBieumau = tbl_bieumau::where('tbl_bieumau.id', '=', $request->bieumau)->first();
        $chitiet = tbl_chitietbieumau::where('tbl_chitietbieumau.bieumau', '=', $request->bieumau)
            ->where('tbl_chitietbieumau.isDelete', '=', 0)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitietbieumau.id', 'tbl_chitietbieumau.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->get();
        $chitiet = $this->data_tree($chitiet, null);
        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/Excel') . '/xuatbieumau.xlsx');
        $sheet->setActiveSheetIndex(0);
        $activeSheet = $sheet->getActiveSheet();
        $row = 8;
        $activeSheet->setCellValueExplicit('A1', $getBieumau->tenbieumau, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $activeSheet->setCellValueExplicit('B2', $request->diaban, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $activeSheet->setCellValueExplicit('D2', $request->khuvuc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $activeSheet->setCellValueExplicit('B3', $request->loaisolieu, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $activeSheet->setCellValueExplicit('B4', $request->kynhaplieu, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $activeSheet->setCellValueExplicit('B5', $request->namnhaplieu, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        foreach ($this->tree as $value) {
            $activeSheet->setCellValueExplicit('A' . $row, $value->tenchitieu, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $activeSheet->setCellValueExplicit('E' . $row, $value->tendonvi, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $activeSheet->setCellValueExplicit('F' . $row, $value->id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $row++;
        }
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                    'borderSize' => 1,
                ],
                'inside' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], 'borderSize' => 1,
                ),
            ],
        ];
        $activeSheet->getStyle("A8:E" . $row)->applyFromArray($styleArray);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
        if (!file_exists(public_path('export'))) {
            mkdir(public_path('export'));
        }
        $writer->save(public_path('export') . '/bieumaunhaplieu.xlsx');
        return 'bieumaunhaplieu.xlsx';
    }

    public function importExcel(Request $request)
    {
        if (!file_exists(storage_path('upload'))) {
            mkdir(storage_path('upload'));
        }
        $request->file->storeAs('upload', "nhapsolieubieumau.xlsx");
        $file = storage_path('app/upload' . '/nhapsolieubieumau.xlsx');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getActiveSheet()->toArray();
        $result = array();
        $nhaplieubaocao = new NhaplieubaocaoController();

        for ($index = 7; $index < count($sheet); $index++) {
            $item = $sheet[$index];
            if ($item[0] != null) {
                $ParentId = $nhaplieubaocao->getInfochitieu($item[5]);

                $obj = new stdClass();
                $obj->id = $item[5]; // Id cua chi tieu;
                $obj->ten = $item[0];
                $obj->sanluong = $item[1];
                $obj->sanluongkhkytruoc = $item[2];
                $obj->sanluongkhkynay = $item[3];
                if ($ParentId != null) {
                    $obj->idcha = $ParentId->idcha;
                } else {
                    $obj->idcha = null;
                }
                $obj->donvi = $item[2];
                array_push($result, $obj);
                // IF object exist idcha
                if ($ParentId != null) {
                    $idFind = $ParentId->idcha;
                    if ($ParentId->idcha != null) {
                        // Find Parent node of this child node
                        while ($idFind != null) {
                            $valueExist = $nhaplieubaocao->checkvalueExist($result, $idFind);
                            if ($valueExist == false) {
                                $chitieuf = $nhaplieubaocao->getInfochitieu($idFind);
                                $obj = new stdClass();
                                $obj->id = $chitieuf->id; // Id cua chi tieu;
                                $obj->ten = $chitieuf->tenchitieu;
                                $obj->sanluong = null;
                                $obj->sanluongkhkytruoc = null;
                                $obj->sanluongkhkynay = null;
                                $obj->idcha = $chitieuf->idcha;
                                $obj->donvi = $chitieuf->tendonvi;
                                array_push($result, $obj);
                                $idFind = $chitieuf->idcha;
                            } else {
                                $idFind = null;
                            }
                        }
                    }
                }
            }
        }
        return response()->json($result);
    }

    private function checkDataExist($donvinhap, $diaban, $nam, $bieumau, $kynhap, $loaisolieu)
    {
        $check = tbl_solieutheobieu::where('diaban', $diaban)
            ->where('isDelete', 0)
            ->where('loaisolieu', $loaisolieu)
            ->where('donvinhap', $donvinhap)->where('namnhap', $nam)->where('bieumau', $bieumau)->where('kynhap', $kynhap)->first();
        if ($check == null) {
            return true;
        }
        return false;
    }
    // Nhap du lieu vao co so du lieu
    public function importData(Request $request)
    {


        if ($request->capnhap == "null") $request->capnhap = 0;

        $data = json_decode($request->dataImport);
        $now = Carbon::now();



        //try {
        if (!isset($request->edit)) {
            if ($this->checkDataExist($request->donvi, $request->diaban, $request->namnhap, $request->mabieumau, $request->kynhap, $request->loaisolieu)) {

                // Doc du lieu tu file excel
                $solieutheobieu = new tbl_solieutheobieu();
                $solieutheobieu->bieumau = $request->mabieumau;
                $solieutheobieu->donvinhap = 1; //$request->donvi;
                $solieutheobieu->taikhoan = $request->taikhoan;
                $solieutheobieu->diaban = $request->diaban;
                $solieutheobieu->capnhap = $request->capnhap;
                $solieutheobieu->loaisolieu = $request->loaisolieu;
                $solieutheobieu->kynhap = $request->kynhap;
                $solieutheobieu->namnhap = $request->namnhap;
                $solieutheobieu->loaibaocao = 0;
                // $solieutheobieu->created_at = $request->namnhap . "-" . $now->month . "-" . $now->day;
                if ($solieutheobieu->save()) {
                    for ($index = 0; $index < count($data); $index++) {
                        $item = $data[$index];
                        $chitiet = new tbl_chitietsolieutheobieu();
                        $chitiet->mabieusolieu = $solieutheobieu->id;
                        $chitiet->chitieu = $item->id;
                        if ($item->sanluong != null) {
                            $chitiet->sanluong = $item->sanluong;
                        } else {
                            $chitiet->sanluong = 0;
                        }
                        //   $chitiet->created_at = $request->namnhap . "-" . $now->month . "-" . $now->day;
                        $chitiet->madonvi = 1; //$request->donvi;
                        $chitiet->save();
                    }

                    return response()->json(['succes' => 200]);
                }
            } else {
                return response()->json(['succes' => 400]);
            }
        } else {
            // Doc du lieu tu file excel
            $solieutheobieu = tbl_solieutheobieu::find($request->edit);
            $solieutheobieu->bieumau = $request->mabieumau;
            $solieutheobieu->donvinhap = 1;
            $solieutheobieu->taikhoan = $request->taikhoan;
            $solieutheobieu->diaban = $request->diaban;
            $solieutheobieu->capnhap = $request->capnhap;
            $solieutheobieu->loaisolieu = $request->loaisolieu;
            $solieutheobieu->kynhap = $request->kynhap;
            $solieutheobieu->namnhap = $request->namnhap;
            $solieutheobieu->loaibaocao = 0;
            if ($solieutheobieu->save()) {
                // Delete old data
                $olddata = tbl_chitietsolieutheobieu::where('mabieusolieu', $request->edit)
                    ->get();
                foreach ($olddata as $odata) {
                    tbl_chitietsolieutheobieu::destroy($odata->id);
                }
                for ($index = 0; $index < count($data); $index++) {
                    $item = $data[$index];
                    $chitiet = new tbl_chitietsolieutheobieu();
                    $chitiet->mabieusolieu = $solieutheobieu->id;
                    $chitiet->chitieu = $item->id;
                    if ($item->sanluong != null) {
                        $chitiet->sanluong = $item->sanluong;
                    } else {
                        $chitiet->sanluong = 0;
                    }
                    $chitiet->madonvi = 1;
                    $chitiet->save();
                }
                return response()->json(['succes' => 200]);
            }
        }
        //} catch (Exception $ex) {
        //  return response()->json(['error' => $ex]);
        //}
    }
    public function importDatatonghop(Request $request)
    {


        if ($request->capnhap == "null") $request->capnhap = 0;

        $data = json_decode($request->dataImport);
        $now = Carbon::now();



        //try {
        if (!isset($request->edit)) {
            if ($this->checkDataExist($request->donvi, $request->diaban, $request->namnhap, $request->mabieumau, $request->kynhap, $request->loaisolieu)) {

                // Doc du lieu tu file excel
                $solieutheobieu = new tbl_solieutheobieu();
                $solieutheobieu->bieumau = $request->mabieumau;
                $solieutheobieu->donvinhap = $request->donvi;
                $solieutheobieu->taikhoan = $request->taikhoan;
                $solieutheobieu->diaban = $request->diaban;
                $solieutheobieu->capnhap = $request->capnhap;
                $solieutheobieu->loaisolieu = $request->loaisolieu;
                $solieutheobieu->kynhap = $request->kynhap;
                $solieutheobieu->namnhap = $request->namnhap;
                $solieutheobieu->loaibaocao = 0;
                // $solieutheobieu->created_at = $request->namnhap . "-" . $now->month . "-" . $now->day;
                if ($solieutheobieu->save()) {
                    for ($index = 0; $index < count($data); $index++) {
                        $item = $data[$index];
                        $chitiet = new tbl_chitietsolieutheobieu();
                        $chitiet->mabieusolieu = $solieutheobieu->id;
                        $chitiet->chitieu = $item->id;
                        if ($item->sanluong != null) {
                            $chitiet->sanluong = $item->sanluong;
                        } else {
                            $chitiet->sanluong = 0;
                        }
                        //   $chitiet->created_at = $request->namnhap . "-" . $now->month . "-" . $now->day;
                        $chitiet->madonvi = $request->donvi;
                        $chitiet->save();
                    }
                    //them du lieu cho mau ky truoc va mau ky nay
                    if ($this->checkDataExist($request->donvi, $request->diaban, $request->namnhap - 1, $request->mabieumau, $request->kynhap, 8)) {

                        // Doc du lieu tu file excel
                        $solieutheobieu = new tbl_solieutheobieu();
                        $solieutheobieu->bieumau = $request->mabieumau;
                        $solieutheobieu->donvinhap = $request->donvi;
                        $solieutheobieu->taikhoan = $request->taikhoan;
                        $solieutheobieu->diaban = $request->diaban;
                        $solieutheobieu->capnhap = $request->capnhap;
                        $solieutheobieu->loaisolieu = 8;
                        $solieutheobieu->kynhap = $request->kynhap;
                        $solieutheobieu->namnhap = $request->namnhap - 1;
                        $solieutheobieu->loaibaocao = 0;
                        // $solieutheobieu->created_at = $request->namnhap . "-" . $now->month . "-" . $now->day;
                        if ($solieutheobieu->save()) {
                            for ($index = 0; $index < count($data); $index++) {
                                $item = $data[$index];
                                $chitiet = new tbl_chitietsolieutheobieu();
                                $chitiet->mabieusolieu = $solieutheobieu->id;
                                $chitiet->chitieu = $item->id;
                                if ($item->sanluong != null) {
                                    $chitiet->sanluong = $item->sanluongkhkytruoc;
                                } else {
                                    $chitiet->sanluong = 0;
                                }
                                //   $chitiet->created_at = $request->namnhap . "-" . $now->month . "-" . $now->day;
                                $chitiet->madonvi = $request->donvi;
                                $chitiet->save();
                            }
                        }
                    }
                    if ($this->checkDataExist($request->donvi, $request->diaban, $request->namnhap - 1, $request->mabieumau, $request->kynhap, 9)) {

                        // Doc du lieu tu file excel
                        $solieutheobieu = new tbl_solieutheobieu();
                        $solieutheobieu->bieumau = $request->mabieumau;
                        $solieutheobieu->donvinhap = $request->donvi;
                        $solieutheobieu->taikhoan = $request->taikhoan;
                        $solieutheobieu->diaban = $request->diaban;
                        $solieutheobieu->capnhap = $request->capnhap;
                        $solieutheobieu->loaisolieu = 9;
                        $solieutheobieu->kynhap = $request->kynhap;
                        $solieutheobieu->namnhap = $request->namnhap - 1;
                        $solieutheobieu->loaibaocao = 0;
                        // $solieutheobieu->created_at = $request->namnhap . "-" . $now->month . "-" . $now->day;
                        if ($solieutheobieu->save()) {
                            for ($index = 0; $index < count($data); $index++) {
                                $item = $data[$index];
                                $chitiet = new tbl_chitietsolieutheobieu();
                                $chitiet->mabieusolieu = $solieutheobieu->id;
                                $chitiet->chitieu = $item->id;
                                if ($item->sanluong != null) {
                                    $chitiet->sanluong = $item->sanluongkhkynay;
                                } else {
                                    $chitiet->sanluong = 0;
                                }
                                //   $chitiet->created_at = $request->namnhap . "-" . $now->month . "-" . $now->day;
                                $chitiet->madonvi = $request->donvi;
                                $chitiet->save();
                            }
                        }
                    }




                    return response()->json(['succes' => 200]);
                }
            } else {
                return response()->json(['succes' => 400]);
            }
        } else {
            // Doc du lieu tu file excel
            $solieutheobieu = tbl_solieutheobieu::find($request->edit);
            $solieutheobieu->bieumau = $request->mabieumau;
            $solieutheobieu->donvinhap = $request->donvi;
            $solieutheobieu->taikhoan = $request->taikhoan;
            $solieutheobieu->diaban = $request->diaban;
            $solieutheobieu->capnhap = $request->capnhap;
            $solieutheobieu->loaisolieu = $request->loaisolieu;
            $solieutheobieu->kynhap = $request->kynhap;
            $solieutheobieu->namnhap = $request->namnhap;
            $solieutheobieu->loaibaocao = 0;
            if ($solieutheobieu->save()) {
                // Delete old data
                $olddata = tbl_chitietsolieutheobieu::where('mabieusolieu', $request->edit)
                    ->get();
                foreach ($olddata as $odata) {
                    tbl_chitietsolieutheobieu::destroy($odata->id);
                }
                for ($index = 0; $index < count($data); $index++) {
                    $item = $data[$index];
                    $chitiet = new tbl_chitietsolieutheobieu();
                    $chitiet->mabieusolieu = $solieutheobieu->id;
                    $chitiet->chitieu = $item->id;
                    if ($item->sanluong != null) {
                        $chitiet->sanluong = $item->sanluong;
                    } else {
                        $chitiet->sanluong = 0;
                    }
                    $chitiet->madonvi = $request->donvi;
                    $chitiet->save();
                }
                return response()->json(['succes' => 200]);
            }
        }
        //} catch (Exception $ex) {
        //  return response()->json(['error' => $ex]);
        //}
    }
    # Sum data Template
    public function ListTempalatewithIdBieumau(Request $request)
    {
        $data = tbl_solieutheobieu::where('tbl_solieutheobieu.isDelete', 0)
            ->where('tbl_solieutheobieu.loaibaocao', '=', 0)
            ->where('tbl_solieutheobieu.bieumau', '=', $request->bieumau)
            ->where('tbl_solieutheobieu.namnhap', '=', $request->namnhap)
            ->where('tbl_solieutheobieu.donvinhap', '=', $request->donvi)
            ->where('tbl_solieutheobieu.diaban', '=', $request->diaban)
            ->join('tbl_bieumau', 'tbl_bieumau.id', 'tbl_solieutheobieu.bieumau')
            ->join('tbl_loaisolieu', 'tbl_loaisolieu.id', 'tbl_solieutheobieu.loaisolieu')
            ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_solieutheobieu.kynhap')
            ->select('tbl_solieutheobieu.id', 'tbl_kybaocao.tenky', 'tbl_loaisolieu.tenloaisolieu', 'tbl_solieutheobieu.namnhap', 'tbl_bieumau.tenbieumau', 'tbl_solieutheobieu.created_at')
            ->orderBy('tbl_solieutheobieu.namnhap', 'desc')
            ->get();
        return response()->json($data);
    }

    public function accumulateDataBieumau(Request $request)
    {
        $accumulate = array();
        $result = array();
        $BieumauSelect = json_decode($request->bieumau);

        if (isset($request->chitieu)) {
        }
        $chiteu = $this->getDetailBieumau($BieumauSelect[0]->id);
        if (!isset($request->chitieu)) {
            foreach ($BieumauSelect as $bieumau) {
                $detail = $this->getDetailBieumau($bieumau->id);
                $result = array_merge($result, $detail);
            }
        } else {
            foreach ($BieumauSelect as $bieumau) {
                $detail = $this->getDetailBieumau($bieumau->id, $request->chitieu);
                $result = array_merge($result, $detail);
            }
        }

        foreach ($chiteu as $value) {
            $quantity = 0;
            $ItemAcc = new stdClass();
            foreach ($result as $item) {
                if ($item["chitieu"] == $value["chitieu"]) {
                    $quantity += $value["sanluong"];
                    $ItemAcc->id = (int) $item["chitieu"];
                    $ItemAcc->quantity = $quantity;
                }
            }
            array_push($accumulate, $ItemAcc);
        }
        return response()->json($accumulate);
    }

    private function getDetailBieumau($id, $chitieu = null)
    {
        if ($chitieu == null) {
            $data = tbl_chitietsolieutheobieu::where('mabieusolieu', $id)->get()->toArray();
            return $data;
        } else {
            $data = tbl_chitietsolieutheobieu::where('mabieusolieu', $id)
                ->where('chitieu', $chitieu)
                ->get()->toArray();
            return $data;
        }
    }

    public function ListDataofLocation(Request $request)
    {
        $result = array();
        $donvihanhchinh = tbl_donvihanhchinh::find($request->donvi);
        $listdonvihanhchinh = tbl_donvihanhchinh::where('diaban', $donvihanhchinh->diaban)->get();
        foreach ($listdonvihanhchinh as $value) {
            $bieumau = $this->getBieuMauwithdonvihanhchinh($value->id, $request->bieumau);
            $arrBieumau = array();
            foreach ($bieumau as $ItemBieumau) {
                $detail = $this->getDetailBieumau($ItemBieumau->id);
                $arrBieumau = array_merge($arrBieumau, $detail);
            }
            // Tong hop lai toan bo bao cao
            $Report = new stdClass();
            $Report->id = $value->id;
            $Report->name = $value->tendonvi;
            $sum = 0;
            foreach ($arrBieumau as $Item) {
                $sum += $Item['sanluong'];
            }
            $Report->sum = $sum;
            array_push($result, $Report);
        }

        return response()->json($result);
    }

    public function SumDataofLocation(Request $request)
    {
        // Tong hop toan bo cac chi tieu
        $listdonvihanhchinh = json_decode($request->donvi);
        $sumsanluong = array();
        if (!isset($request->chitieu)) {
            foreach ($listdonvihanhchinh as $donvi) {
                # lay danh sach bieu mau cua don vi hanh chinh
                $bieumau = $this->getBieuMauwithdonvihanhchinh($donvi->id, $request->bieumau);
                $arrBieumau = array();
                $idBieumau = 0;
                foreach ($bieumau as $ItemBieumau) {
                    $detail = $this->getDetailBieumau($ItemBieumau->id);
                    $arrBieumau = array_merge($arrBieumau, $detail);
                    $idBieumau = $ItemBieumau->id;
                }
                // Tong hop lai toan bo bao cao
                $chiteuofBieumau = $this->getDetailBieumau($idBieumau);
                foreach ($chiteuofBieumau as $Itemchitieu) {
                    $sum = 0;
                    foreach ($arrBieumau as $Item) {
                        if ($Item['chitieu'] == $Itemchitieu['chitieu']) {
                            $sum += $Item['sanluong'];
                        }
                    }
                    $sanluong = new stdClass();
                    $sanluong->id = (int) $Itemchitieu['chitieu'];
                    $sanluong->quantity = $sum;
                    array_push($sumsanluong, $sanluong);
                }
            }

            return response()->json($sumsanluong);
        } else {
            // Tong hop cac chi tieu rieng le
            foreach ($listdonvihanhchinh as $donvi) {
                # lay danh sach bieu mau cua don vi hanh chinh
                $bieumau = $this->getBieuMauwithdonvihanhchinh($donvi->id, $request->bieumau);
                $arrBieumau = array();
                $idBieumau = 0;
                foreach ($bieumau as $ItemBieumau) {
                    $detail = $this->getDetailBieumau($ItemBieumau->id, $request->chitieu);
                    $arrBieumau = array_merge($arrBieumau, $detail);
                    $idBieumau = $ItemBieumau->id;
                }
                // Tong hop lai toan bo bao cao
                $chiteuofBieumau = $this->getDetailBieumau($idBieumau, $request->chitieu);
                foreach ($chiteuofBieumau as $Itemchitieu) {
                    $sum = 0;
                    foreach ($arrBieumau as $Item) {
                        if ($Item['chitieu'] == $Itemchitieu['chitieu']) {
                            $sum += $Item['sanluong'];
                        }
                    }
                    $sanluong = new stdClass();
                    $sanluong->id = (int) $Itemchitieu['chitieu'];
                    $sanluong->quantity = $sum;
                    array_push($sumsanluong, $sanluong);
                }
            }

            return response()->json($sumsanluong);
        }
    }

    public function getBieuMauwithdonvihanhchinh($donvi, $bieumau)
    {
        $bieumau = tbl_solieutheobieu::where('diaban', $donvi)->where('bieumau', $bieumau)
            ->where('loaibaocao', 0)
            ->where('isDelete', 0)->get();
        return $bieumau;
    }
}
