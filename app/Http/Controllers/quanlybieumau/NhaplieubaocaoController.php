<?php

namespace App\Http\Controllers\quanlybieumau;

use App\Http\Controllers\Controller;
use App\tbl_bieumau;
use App\tbl_chitietbieumau;
use App\tbl_chitietsolieutheobieu;
use App\tbl_chitieu;
use App\tbl_donvihanhchinh;
use App\tbl_solieutheobieu;
use Exception;
use Illuminate\Http\Request;
use Session;
use stdClass;
use DB;

class NhaplieubaocaoController extends Controller
{
    private $result = array();
    public function viewNhaplieu()
    {
        return view('nhaplieu.danhsachnhaplieu');
    }

    public function view()
    {
        return view('nhaplieu.nhaplieu');
    }

    public function index()
    {
        $madonvi = Session::get('madonvi');
        $data = tbl_solieutheobieu::where('tbl_solieutheobieu.isDelete', '=', 0)
            ->where('tbl_solieutheobieu.loaibaocao', '=', 1)
            ->where('tbl_solieutheobieu.donvinhap', '=', $madonvi)
            ->join('tbl_bieumau', 'tbl_bieumau.id', 'tbl_solieutheobieu.bieumau')

            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_solieutheobieu.taikhoan')
            ->select('tbl_solieutheobieu.id', 'tbl_bieumau.tenbieumau', 'tbl_bieumau.sohieu', 'tbl_taikhoan.tentaikhoan', 'tbl_solieutheobieu.created_at', 'tbl_solieutheobieu.namnhap')
            ->get();
        return json_encode($data);
    }

    public function indexCurrentyear()
    {
        $currentYear = date('Y');
        $madonvi = Session::get('madonvi');

        $data = tbl_solieutheobieu::where('tbl_solieutheobieu.isDelete', '=', 0)
            // ->where('tbl_solieutheobieu.loaibaocao', '=', 1)
            //->where('tbl_solieutheobieu.namnhap', '=', $currentYear)
            ->where('tbl_solieutheobieu.donvinhap', '=', $madonvi)
            ->join('tbl_bieumau', 'tbl_bieumau.id', 'tbl_solieutheobieu.bieumau')
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_solieutheobieu.taikhoan')
            ->join('tbl_loaisolieu', 'tbl_loaisolieu.id', 'tbl_solieutheobieu.loaisolieu')
            ->select('tbl_solieutheobieu.id', 'tbl_bieumau.tenbieumau', 'tbl_bieumau.sohieu', 'tbl_taikhoan.tentaikhoan', 'tbl_solieutheobieu.created_at', 'tbl_solieutheobieu.namnhap', 'tbl_loaisolieu.tenloaisolieu')
            ->get();
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $arrJson = json_decode($request->bieumau);
        foreach ($arrJson as $value) {
            $bieumau = tbl_solieutheobieu::find($value->id);
            $bieumau->isDelete = 1;
            $bieumau->save();
        }
        return 200;
    }

    public function DowloadExcel(Request $request)
    {
        $getBieumau = tbl_bieumau::where('tbl_bieumau.id', '=', $request->bieumau)->first();

        $chitiet = tbl_chitietbieumau::where('tbl_chitietbieumau.bieumau', '=', $getBieumau->id)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->where('tbl_chitietbieumau.isDelete', '=', 0)
            ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_donvitinh.tendonvi')
            ->get();

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
        foreach ($chitiet as $value) {
            $activeSheet->setCellValueExplicit('A' . $row, $value->tenchitieu, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $activeSheet->setCellValueExplicit('C' . $row, $value->tendonvi, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $activeSheet->setCellValueExplicit('F' . $row, $value->id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $row++;
        }
        $activeSheet->getStyle("A8:C" . $row)->applyFromArray(array(
            'borders' => array(
                'outline' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                    'size' => 1,
                ),
                'inside' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                    'size' => 1,
                ),
            ),
        ));
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
        if (!file_exists(public_path('export'))) {
            mkdir(public_path('export'));
        }
        $writer->save(public_path('export') . '/bieumauNhapBaocao.xlsx');
        return 'bieumauNhapBaocao.xlsx';
    }
    public function importExcel(Request $request)
    {
        if (!file_exists(storage_path('upload'))) {
            mkdir(storage_path('upload'));
        }
        $request->file->storeAs('upload', "nhapbieumau.xlsx");
        $file = storage_path('app/upload' . '/nhapbieumau.xlsx');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getActiveSheet()->toArray();
        $result = array();
        $nhaplieubaocao = new NhaplieubaocaoController();
        for ($index = 7; $index < count($sheet); $index++) {
            $item = $sheet[$index];
            if ($item[0] != null) {
                $ParentId = $this->getInfochitieu($item[5]);
                $obj = new stdClass();
                $obj->id = $item[5]; // Id cua chi tieu;
                $obj->ten = $item[0];
                $obj->sanluong = $item[1];
                $obj->donvi = $item[2];
                if ($ParentId != null) {
                    $obj->idcha = $ParentId->idcha;
                } else {
                    $obj->idcha = null;
                }

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

    public function getInfochitieu($Id)
    {
        $chitieu = tbl_chitieu::where('tbl_chitieu.id', '=', $Id)
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id', 'tbl_chitieu.idcha', 'tbl_chitieu.tenchitieu', 'tbl_donvitinh.tendonvi')
            ->first();
        return $chitieu;
    }

    private $treeBieumau = array();
    public function getTemplate($idTemplate)
    {
        $deltailTemplate = tbl_chitietbieumau::where('tbl_chitietbieumau.bieumau', '=', $idTemplate)
            ->where('tbl_chitietbieumau.isDelete', '=', 0)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitietbieumau.id', 'tbl_chitietbieumau.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->get();
        $this->data_tree($deltailTemplate, null);

        return response()->json(["data" => $this->treeBieumau, "code" => 200]);
    }

    private function data_tree($data, $parent_id)
    {
        foreach ($data as $key => $value) {
            if ($value->idcha == $parent_id) {
                $obj = new stdClass();
                $obj->id = $value->chitieu;
                $obj->ten = $value->tenchitieu;
                $obj->donvi = $value->tendonvi;
                $obj->idcha = $parent_id;
                $obj->sanluong = 0;
                array_push($this->treeBieumau, $obj);
                $id = $value->chitieu;
                $this->data_tree($data, $id);
            }
        }
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

    # Save Data in Database
    public function importData(Request $request)
    {
        $sheet = json_decode($request->dataImport);

        //try {
        if (!isset($request->edit)) {
            if ($this->checkDataExist($request->donvi, $request->diaban, $request->namnhap, $request->mabieumau, $request->kynhap, $request->loaisolieu)) {

                // Doc du lieu tu file excel
                $solieutheobieu = new tbl_solieutheobieu();
                $solieutheobieu->bieumau = $request->mabieumau;
                $solieutheobieu->donvinhap = $request->donvi;
                $solieutheobieu->taikhoan = $request->taikhoan;
                $solieutheobieu->diaban = $request->diaban;
                $solieutheobieu->capnhap = 0;
                $solieutheobieu->loaisolieu = $request->loaisolieu;
                $solieutheobieu->kynhap = $request->kynhap;
                $solieutheobieu->namnhap = $request->namnhap;
                $solieutheobieu->loaibaocao = 1;

                if ($solieutheobieu->save()) {

                    for ($index = 0; $index < count($sheet); $index++) {
                        $item = $sheet[$index];
                        $chitiet = new tbl_chitietsolieutheobieu();
                        $chitiet->mabieusolieu = $solieutheobieu->id;

                        $chitiet->chitieu = $item->id;
                        if ($item->sanluong != null) {
                            $chitiet->sanluong = $item->sanluong;
                        } else {
                            $chitiet->sanluong = 0;
                        }
                        $chitiet->madonvi = $request->donvi;
                        $rs =  $chitiet->save();
                    }
                    return response()->json(['success' => 200]);
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
            $solieutheobieu->capnhap = 0;
            $solieutheobieu->loaisolieu = $request->loaisolieu;
            $solieutheobieu->kynhap = $request->kynhap;
            $solieutheobieu->namnhap = $request->namnhap;
            $solieutheobieu->loaibaocao = 1;
            if ($solieutheobieu->save()) {
                // Delete old data
                $olddata = tbl_chitietsolieutheobieu::where('mabieusolieu', $request->edit)
                    ->get();
                foreach ($olddata as $odata) {
                    tbl_chitietsolieutheobieu::destroy($odata->id);
                }
                for ($index = 0; $index < count($sheet); $index++) {
                    $item = $sheet[$index];
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
                return response()->json(['success' => 200]);
            }
        }
        //} catch (Exception $ex) {
        //    return response()->json(['error' => $ex]);
        // }
    }

    public function show($id)
    {
        try {
            $Report = tbl_solieutheobieu::where('tbl_solieutheobieu.id', '=', $id)
                ->where('tbl_solieutheobieu.isDelete', '=', 0)->first();
            $DetailReport = tbl_chitietsolieutheobieu::where('tbl_chitietsolieutheobieu.isDelete', 0)
                ->where('tbl_chitietsolieutheobieu.mabieusolieu', $id)
                ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietsolieutheobieu.chitieu')
                ->select('tbl_chitietsolieutheobieu.id', 'tbl_chitietsolieutheobieu.sanluong', 'tbl_chitietsolieutheobieu.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha')
                ->get();
            $Result = new stdClass();
            $Result->Report = $Report;
            $Result->Detail = $DetailReport;
            return response()->json($Result, 200);
        } catch (Exception $ex) {
            return response()->json(["error" => $ex]);
        }
    }

    public function ReviewReport($id)
    {
        try {
            $Report = tbl_solieutheobieu::where('tbl_solieutheobieu.id', '=', $id)
                ->where('tbl_solieutheobieu.isDelete', '=', 0)->first();
            $Unit = tbl_donvihanhchinh::where('tbl_donvihanhchinh.id', $Report->donvinhap)
                ->join('tbl_tinh', 'tbl_tinh.id', 'tbl_donvihanhchinh.diaban')
                ->select('tbl_tinh._name')
                ->first();
            $DetailReport = tbl_chitietsolieutheobieu::where('tbl_chitietsolieutheobieu.isDelete', 0)
                ->where('tbl_chitietsolieutheobieu.mabieusolieu', $id)
                ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietsolieutheobieu.chitieu')
                ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
                ->select('tbl_chitietsolieutheobieu.id', 'tbl_chitietsolieutheobieu.sanluong', 'tbl_chitietsolieutheobieu.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
                ->get();

            # De quy tao cay chi tieu
            $this->TreeChitieu($DetailReport);
            $Result = new stdClass();
            $Result->Report = $Report;
            $Result->Detail = $this->result;
            $Result->unit = $Unit->_name;
            return response()->json($Result, 200);
        } catch (Exception $ex) {
            dd($ex);
        }
    }
    public function TreeChitieu($array, $parent_id = 0)
    {
        // TODO loop and check idcha of item same is parent_id
        foreach ($array as $value) {
            if ($value->idcha == $parent_id) {
                $item = new stdClass();
                $item->idChitieu = (int) $value->chitieu;
                $item->id = $value->id;
                $item->idcha = $value->idcha;
                $item->tenchitieu = $value->tenchitieu;
                $item->sanluong = $value->sanluong;
                $item->donvitinh = $value->tendonvi;
                array_push($this->result, $item);
                // TODO: De quy de lay nhung thang con thuoc thang cha
                $this->TreeChitieu($array, $value->chitieu);
            }
        }
    }

    # Sum report
    # Sum data Template
    public function ListTempalatewithIdBieumau(Request $request)
    {
        $data = tbl_solieutheobieu::where('tbl_solieutheobieu.isDelete', 0)
            ->where('tbl_solieutheobieu.loaibaocao', '=', 1)
            ->where('tbl_solieutheobieu.bieumau', '=', $request->bieumau)
            ->where('tbl_solieutheobieu.namnhap', '=', $request->namnhap)
            ->where('tbl_solieutheobieu.donvinhap', '=', $request->donvi)
            ->where('tbl_solieutheobieu.diaban', '=', $request->diaban)
            ->join('tbl_bieumau', 'tbl_bieumau.id', 'tbl_solieutheobieu.bieumau')
            ->join('tbl_kybaocao', 'tbl_kybaocao.id', 'tbl_solieutheobieu.kynhap')
            ->select('tbl_solieutheobieu.id', 'tbl_kybaocao.tenky', 'tbl_solieutheobieu.namnhap', 'tbl_bieumau.tenbieumau', 'tbl_solieutheobieu.created_at')
            ->orderBy('tbl_solieutheobieu.namnhap', 'desc')
            ->get();
        return response()->json($data);
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

    public function getBieuMauwithdonvihanhchinh($donvi, $bieumau)
    {
        $bieumau = tbl_solieutheobieu::where('diaban', $donvi)->where('bieumau', $bieumau)
            ->where('loaibaocao', 1)
            ->where('isDelete', 0)->get();
        return $bieumau;
    }

    public function accumulateDataBieumau(Request $request)
    {
        $accumulate = array();
        $result = array();
        $BieumauSelect = json_decode($request->bieumau);
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

    public function ChitieuNhaplieu($idTemplate)
    {
        $deltailTemplate = tbl_chitietbieumau::where('tbl_chitietbieumau.bieumau', '=', $idTemplate)
            ->where('tbl_chitietbieumau.isDelete', '=', 0)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitietbieumau.id', 'tbl_chitietbieumau.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->get();
        $result = array();
        foreach ($deltailTemplate as $key => $value) {
            $obj = new stdClass();
            $obj->id = $value->chitieu;
            $obj->ten = $value->tenchitieu;
            $obj->donvi = $value->tendonvi;
            $obj->idcha = $value->idcha;
            $obj->sanluong = 0;
            array_push($result, $obj);
        }
        return response()->json(["data" => $result, "code" => 200]);
    }


    public function chonbieumausolieu($id)
    {
        $bieumau = tbl_solieutheobieu::find($id);
        $deltailTemplate = tbl_chitietbieumau::where('tbl_chitietbieumau.bieumau', '=', $bieumau->bieumau)
            ->where('tbl_chitietbieumau.isDelete', '=', 0)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitietbieumau.id', 'tbl_chitietbieumau.chitieu', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->get();
        $result = array();
        foreach ($deltailTemplate as $key => $value) {
            $obj = new stdClass();
            $obj->id = $value->chitieu;
            $obj->ten = $value->tenchitieu;
            $obj->donvi = $value->tendonvi;
            $obj->idcha = $value->idcha;
            $obj->sanluong = 0;
            array_push($result, $obj);
        }
        return response()->json(["data" => $result, "code" => 200]);
    }
    public function getListBieumauNhaplieu($bieumau)
    {
        $danhsachBieumau = tbl_solieutheobieu::where('tbl_solieutheobieu.bieumau', $bieumau)->where('tbl_solieutheobieu.isDelete', 0)
            ->join('tbl_bieumau', 'tbl_bieumau.id', 'tbl_solieutheobieu.bieumau')
            ->join('tbl_taikhoan', 'tbl_taikhoan.id', 'tbl_solieutheobieu.taikhoan')
            ->select('tbl_solieutheobieu.id', 'tbl_bieumau.sohieu', DB::raw('CONCAT(tbl_bieumau.tenbieumau,"-", tbl_taikhoan.tentaikhoan) AS tenbieumau'), 'tbl_solieutheobieu.created_at', 'tbl_taikhoan.tentaikhoan', 'tbl_solieutheobieu.namnhap')
            ->get();
        return response()->json($danhsachBieumau);
    }
}
