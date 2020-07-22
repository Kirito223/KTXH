<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\quanlybieumau\NhaplieusolieuController;
use App\Http\Controllers\Ultils\ChitieuUltils;
use App\tbl_chitietbieumau;
use App\tbl_chitieu;
use App\tbl_donvihanhchinh;
use App\tbl_solieutheobieu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class ProductionPlanReportController extends Controller
{
    public function viewProductionPlan()
    {
        return view('report\productionplanreport');
    }

    public function viewReport(Request $request)
    {
        $currentYear = $request->year;
        $periviousYear = $currentYear - 1;
        // $otherYear = $periviousYear - 1;
        $Form = $request->bieumau;
        $FormController = new NhaplieusolieuController();
        $listChitieu = $FormController->showDeltalBieumauTH($Form);
        $Ultil = new ChitieuUltils();
        $TreeChitieu = $Ultil->getTreeChitieu($listChitieu);
        $dulieu = new stdClass();
        $Result = array();
        $listXaofHuyen = tbl_donvihanhchinh::where('madonvi', $request->location)
            ->get();
        $datacha = tbl_chitietbieumau::where('bieumau', '=', $Form)
            ->where('tbl_chitieu.idcha', null)
            ->join('tbl_chitieu', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            //->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->select(DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as id'), 'tbl_chitieu.tenchitieu', DB::raw('CAST(tbl_chitieu.idcha AS varchar(10)) as idcha'), 'tbl_donvitinh.tendonvi', DB::raw('CAST(tbl_chitieu.id AS varchar(10)) as strid'))

            ->get();

        $data = tbl_chitieu::with('childrenAll')->where('tbl_chitieu.IsDelete', 0)
            ->where('tbl_chitietbieumau.bieumau', '=', $Form)
            ->whereNotNull('tbl_chitieu.idcha')
            ->join('tbl_chitietbieumau', 'tbl_chitieu.id', 'tbl_chitietbieumau.chitieu')
            ->join('tbl_donvitinh', 'tbl_donvitinh.id', 'tbl_chitieu.donvitinh')
            ->select('tbl_chitieu.id', 'tbl_chitieu.tenchitieu', 'tbl_chitieu.idcha', 'tbl_donvitinh.tendonvi')
            ->get();

        foreach ($TreeChitieu as $chitieu) {
            $Item = new stdClass();
            $Item->id = $chitieu->id;
            $Item->chitieu = $chitieu->ten;
            $Item->idcha = $chitieu->idcha;
            $Item->strid = strval($chitieu->id);
            $Item->donvi = $chitieu->donvi;
            $TotalofPerviousYear = $this->DataOfyear($periviousYear, $listXaofHuyen, $chitieu->id, $Form);
            $TotalofCurrentYear = $this->DataOfyear($currentYear, $listXaofHuyen, $chitieu->id, $Form);
            $Item->KHpreYear = $TotalofPerviousYear;
            $Item->KHcurrentYear = $TotalofCurrentYear;
            $Item->estimate = $TotalofPerviousYear;
            # Detail KH and TH of xa
            $DetailXa = array();
            foreach ($listXaofHuyen as $xa) {
                $KH = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form);
                $TH = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form);
                $KHYear = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form);
                array_push($DetailXa, $KH);
                array_push($DetailXa, $TH);
                array_push($DetailXa, $KHYear);
            }
            $Item->Detail = $DetailXa;
            array_push($Result, $Item);
        }
        $dulieu->nutcha = $datacha;
        $dulieu->nutcon = $data;
        $dulieu->chitiet = $Result;
        $dulieu->chitiet1 = $Result;
        $dulieu->chitiet2 = $Result;
        $dulieu->chitiet3 = $Result;
        $dulieu->chitiet4 = $Result;
        return response()->json($dulieu);
    }
    public function Exportdata(Request $request)
    {
        $currentYear = $request->year;
        $periviousYear = $currentYear - 1;
        $otherYear = $periviousYear - 1;
        $Form = $request->bieumau;
        $FormController = new NhaplieusolieuController();
        $listChitieu = $FormController->showDeltalBieumauTH($Form);
        $Ultil = new ChitieuUltils();
        $TreeChitieu = $Ultil->getTreeChitieu($listChitieu);
        $Result = array();

        // Tong hop bao cao theo huyen
        $listXaofHuyen = null;
        if ($request->diaban == 1) {
            $listXaofHuyen = tbl_donvihanhchinh::where('madonvi', $request->location)
            ->get();
        } else {
            // Tong hop bao cao theo xa
            $listXaofHuyen = tbl_donvihanhchinh::where('id', $request->location)
            ->get();
        }
        
        // Gan so level cho chi tieu;
        foreach ($TreeChitieu as $items) {
            $items->level = $this->findLevel($TreeChitieu, $items, 1);
        }

        foreach ($TreeChitieu as $chitieu) {
            $Item = new stdClass();
            $Item->id = $chitieu->id;
            $Item->chitieu = $chitieu->ten;
            $Item->idcha = $chitieu->idcha;
            $Item->donvi = $chitieu->donvi;
            $Item->level = $chitieu->level;
            $TotalofPerviousYear = $this->DataOfyear($periviousYear, $listXaofHuyen, $chitieu->id, $Form);
            $TotalofCurrentYear = $this->DataOfyear($currentYear, $listXaofHuyen, $chitieu->id, $Form);
            $Item->KHpreYear = $TotalofPerviousYear;
            $Item->KHcurrentYear = $TotalofCurrentYear;
            $Item->estimate = $TotalofPerviousYear;
            # Detail KH and TH of xa
            $DetailXa = array();
            foreach ($listXaofHuyen as $xa) {
                $KH = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form);
                $TH = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form);
                $KHYear = $this->SumdataXa($currentYear, $xa->id, $chitieu->id, $Form);
                array_push($DetailXa, $KH);
                array_push($DetailXa, $TH);
                array_push($DetailXa, $KHYear);
            }
            $Item->Detail = $DetailXa;
            array_push($Result, $Item);
        }
        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/Excel') . '/chitieuNN.xlsx');
        $sheet->setActiveSheetIndex(0);
        $activeSheet = $sheet->getActiveSheet();

        // Create column xa in excel file
        $column = count($listXaofHuyen) * 3;
        for ($index = 0; $index < $column; $index++) {
            $activeSheet->insertNewColumnBefore('G', 3);
        }
        $column = 7;
        // Insert name xa to column
        foreach ($listXaofHuyen as $item) {
            $activeSheet->mergeCellsByColumnAndRow($column, 3, $column + 2, 3);
            $activeSheet->setCellValueByColumnAndRow($column, 3, $item->tendonvi);
            $column += 3;
        }
        // column TH, KH xa
        $arrColumnXa = array();
        $arrColPara = new stdClass();
        $column = 7;
        for ($index = 0; $index < count($listXaofHuyen); $index++) {
            $activeSheet->setCellValueByColumnAndRow($column, 4, 'TH');
            $activeSheet->setCellValueByColumnAndRow($column + 1, 4, 'KH');
            $activeSheet->setCellValueByColumnAndRow(($column + 2), 4, 'KH năm ' . $currentYear);
            array_push($arrColumnXa, array('TH' => $column, 'KH' => $column + 1, 'KHNam' => $column + 2));
            $column = $column + 3;
        }
        // Write data
        $rowIndex = 1;
        $row = 5;
        $lastcol = 0;
        foreach ($Result as $Item) {
            $activeSheet->setCellValueByColumnAndRow(1, $row, $rowIndex);
            $activeSheet->setCellValueByColumnAndRow(2, $row, $this->writeLevel($Item->level) . $Item->chitieu);
            $activeSheet->setCellValueByColumnAndRow(3, $row, $Item->donvi);
            $activeSheet->setCellValueByColumnAndRow(4, $row, $Item->KHpreYear);
            $activeSheet->setCellValueByColumnAndRow(5, $row, $Item->estimate);
            $activeSheet->setCellValueByColumnAndRow(6, $row, $Item->KHcurrentYear);
            // Write detail xa
            $colDetail = 7;
            $detail = $Item->Detail;
            foreach ($detail as $ItemDetail) {
                $activeSheet->setCellValueByColumnAndRow($colDetail, $row, $ItemDetail);
                $colDetail++;
            }
            $lastcol = $colDetail;
            $row++;
            $rowIndex++;
        }
        $lastCellAddress = $activeSheet->getCellByColumnAndRow($lastcol - 1, $row)->getCoordinate();
        $now = Carbon::now();
        $tieude = "KẾ HOẠCH SẢN XUẤT NÔNG LÂM NGHIỆP NĂM 2020 TRÊN ĐỊA BÀN HUYỆN " . mb_strtoupper($request->namelocation, 'UTF-8');

        $colhead = count($listXaofHuyen) * 5;
        $activeSheet->mergeCellsByColumnAndRow(1, 1, $colhead, 1);
        $activeSheet->setCellValue('A1', $tieude);
        $activeSheet->mergeCellsByColumnAndRow(1, 2, $colhead, 2);
        $activeSheet->setCellValue('A2', "(Kèm theo Báo cáo số        /BC-NN ngày " . $now->day . " tháng " . $now->month . " năm " . $now->year . " của Phòng Nông nghiệp & PTNT " . $request->namelocation . ")");
        $activeSheet->getStyle("A1:" . $lastCellAddress)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeSheet->getStyle("A2:" . $lastCellAddress)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $lastRow = $activeSheet->getHighestRow();
        $activeSheet->getStyle("B5:B" . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
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

        $activeSheet->getStyle('A3:' . $lastCellAddress)->applyFromArray($styleArray);
        $activeSheet->setCellValue('F3', "KH " . $now->year);
        $activeSheet->setCellValue('E3', "Ước thực hiện " . ($now->year - 1));
        $activeSheet->setCellValue('D3', "KH " . ($now->year - 1));
        $activeSheet->getStyle('B1:B' . $activeSheet->getHighestRow())
            ->getAlignment()->setWrapText(true);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
        if (!file_exists(public_path('export'))) {
            mkdir(public_path('export'));
        }
        $writer->save(public_path('export') . '/chitieuNN.xlsx');
        return response()->json("chitieuNN.xlsx");
    }

    private function writeLevel($level)
    {
        $str = "";
        for ($l = 0; $l < $level; $l++) {
            $str .= "   ";
        }
        return $str;
    }

    public function findLevel($data, $item, $lv)
    {
        $obj = $this->findParent($data, $item);
        if ($obj != null) {
            $lv++;
            return $this->findLevel($data, $obj, $lv);
        } else {
            return $lv;
        }
    }

    private function findParent($data, $obj)
    {
        foreach ($data as $values) {
            if ($values->id == $obj->idcha) {
                return $values;
            }
        }
        return null;
    }

    private function findChild($data, $itemParent)
    {
        $Result = array();
        foreach ($data as $value) {
            if ($value->idcha == $itemParent) {
                array_push($Result, $value);
            }
        }
        return $Result;
    }

    private function checkWrite($arr, $item)
    {
        foreach ($arr as $value) {
            if ($value->id == $item) {
                return true;
            }
        }
        return false;
    }

    public function DataOfyear($year, $listXa, $chitieu, $bieumau)
    {
        $total = 0;
        foreach ($listXa as $xa) {
            $listBieumau = $this->getBieumauOfUnit($xa->id, $bieumau, $year);
            $sum = $this->totalDeltailBieumau($chitieu, $listBieumau, $year);
            $total += $sum;
        }
        return $total;
    }
    private function SumdataXa($year, $xa, $chitieu, $bieumau)
    {
        $total = 0;
        //  $listUnit = $this->getListUnitOfxa($xa);
        $listBieumau = $this->getBieumauOfUnit($xa, $bieumau, $year);
        $sum = $this->totalDeltailBieumau($chitieu, $listBieumau, $year);
        $total += $sum;
        return $total;
    }

    private function totalDeltailBieumau($Chitieu, $arrBieumau, $Time)
    {
        $total = 0;
        foreach ($arrBieumau as $bieumau) {
            // get Deltal
            $sum = DB::table('tbl_chitietsolieutheobieu')
                ->where('mabieusolieu', $bieumau->id)
                ->where('tbl_chitietsolieutheobieu.chitieu', $Chitieu)
                ->where('tbl_chitietsolieutheobieu.isDelete', 0)
                ->sum('sanluong');
            $total += $sum;
        }
        return $total;
    }

    private function getBieumauOfUnit($unit, $form, $year)
    {
        $data = tbl_solieutheobieu::where('donvinhap', $unit)
            ->where('tbl_solieutheobieu.isDelete', 0)
            ->where('bieumau', $form)
            ->where('tbl_solieutheobieu.namnhap', $year)
            ->get();
        return $data;
    }
    private function totalDeltailBieumauwithKy($Chitieu, $arrBieumau, $Time)
    {
        $total = 0;
        foreach ($arrBieumau as $bieumau) {
            // get Deltail
            $sum = DB::table('tbl_chitietsolieutheobieu')
                ->where('mabieusolieu', $bieumau->id)
                ->where('tbl_chitietsolieutheobieu.chitieu', $Chitieu)
                ->where('tbl_chitietsolieutheobieu.isDelete', 0)
                ->where('tbl_solieutheobieu.namnhap', $Time)
                ->sum('sanluong');
            $total += $sum;
        }
        return $total;
    }

    private function getListUnitOfxa($maxa)
    {
        $listUnit = tbl_donvihanhchinh::where('tbl_donvihanhchinh.isDelete', 0)
            ->where('tbl_donvihanhchinh.madonvi', '=', $maxa)
            ->select('tbl_donvihanhchinh.id', 'tbl_donvihanhchinh.tendonvi')
            ->get();
        return $listUnit;
    }

    // Bao cao tong hop cap xa
    public function danhsachXa()
    {
        $madiaban = session('madiabandvch');
        $data = tbl_donvihanhchinh::where('isDelete', 0)
            ->where('madonvi',  $madiaban)
            ->get();
        return response()->json($data);
    }
}
