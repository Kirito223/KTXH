<?php
// cau hinh
Route::get('thongtindonvi', function () {
  return view('thongtindonvi.list');
});




Route::get('cauhinh', function () {
  return view('cauhinh.list');
});
Route::get('dscauhinh', 'CauhinhController@list');
Route::get('cauhinh/{id}', 'CauhinhController@getCauHinh');
Route::post('InsertAndUpdateCauHinh', 'CauhinhController@InsertAndUpdate');
Route::post('delCauhinh', 'CauhinhController@delCauhinh');

/***********************
 * Route CRUD admin user
 * *********************
 * 
 */
Route::get('/home', 'HomeController@home')->name('home');


Route::get('quantrivien', 'adminUserControllers@quantrivien');
Route::get('ListUser', 'adminUserControllers@ListUser');
Route::get('getlistrole', 'adminUserControllers@getlistrole');
Route::post('getUser', 'adminUserControllers@getUser');
Route::post('InsertAdminUser', 'adminUserControllers@InsertAdminUser');
Route::post('updateAdminUser', 'adminUserControllers@updateAdminUser');
Route::post('updateAdminPassword', 'adminUserControllers@updateAdminPassword');
Route::post('deladminUser', 'adminUserControllers@deladminUser');
Route::post('resetAdminPassword', 'adminUserControllers@resetAdminPassword');

//  kết thúc




// report
Route::get('reports', 'ktxh\ReportController@viewreport');
Route::get('listchitieuReportmau1', 'ktxh\ReportController@listchitieumau1Report');
Route::get('listchitieuReportmau2', 'ktxh\ReportController@listchitieumau2Report');
Route::get('listchitieuReportmau4B', 'ktxh\ReportController@listchitieumau4BReport');
Route::get('listchitieuReportmau6', 'ktxh\ReportController@listchitieumau6Report');
Route::get('listchitieuReportmau7', 'ktxh\ReportController@listchitieumau7Report');
Route::get('listchitieuReportmau8', 'ktxh\ReportController@listchitieumau8Report');



//Route::get('/', function () {
//   return view('home.index');
//});

// Route::get('/login', function () {
//     return view('admin.login');
// });


//route huy
//quản lý chỉ tiêu
Route::get('listchitieu','Quanlydanhmuc\Quanlychitieu\Chitieu@viewchitieu')->middleware('auth:taikhoan', 'checkpermission:super-admin');
Route::get('getlistchitieu','Quanlydanhmuc\Quanlychitieu\Chitieu@getchitieu');
Route::post('InsertChitieu','Quanlydanhmuc\Quanlychitieu\Chitieu@InsertChitieu');
Route::post('InsertChitieuCon','Quanlydanhmuc\Quanlychitieu\Chitieu@InsertChitieuCon');
Route::post('UpdateChitieu','Quanlydanhmuc\Quanlychitieu\Chitieu@UpdateChitieu');
Route::post('DelChitieu','Quanlydanhmuc\Quanlychitieu\Chitieu@DelChitieu');
Route::post('DelChitieulistcheckbox','Quanlydanhmuc\Quanlychitieu\Chitieu@DelChitieulistcheckbox');
	//thung rac chi tieu
Route::get('listchitieutrash','Quanlydanhmuc\Quanlychitieu\Chitieu@viewchitieutrash');
Route::get('getlistchitieutrash','Quanlydanhmuc\Quanlychitieu\Chitieu@getchitieutrash');
Route::post('RestoreChitieulistcheckbox','Quanlydanhmuc\Quanlychitieu\Chitieu@RestoreChitieulistcheckbox');
Route::post('DelAllChitieulistcheckbox','Quanlydanhmuc\Quanlychitieu\Chitieu@DelAllChitieulistcheckbox');
#Route::get('listchitieu', 'Chitieu@viewchitieu');
#Route::get('getlistchitieu', 'Chitieu@getchitieu');
#Route::post('InsertChitieu', 'Chitieu@InsertChitieu');
#Route::post('UpdateChitieu', 'Chitieu@UpdateChitieu');
#Route::post('DelChitieu', 'Chitieu@DelChitieu');

Route::get('getSelectChitieu', 'Chitieu@getSlectChitieu');


//danh sách biểu mẫu
Route::get('listdanhsachbieumau','Khaithacthongtin\Danhsachbieumau\Danhsachbieumau@viewdanhsachbieumau')->middleware('auth:taikhoan');
Route::get('getlistbieumau','Khaithacthongtin\Danhsachbieumau\Danhsachbieumau@getlistbieumau')->middleware('auth:taikhoan');
Route::get('getlistdonvihanhchinh','Khaithacthongtin\Danhsachbieumau\Danhsachbieumau@getlistdonvihanhchinh')->middleware('auth:taikhoan');
Route::post('loadlistbieumau','Khaithacthongtin\Danhsachbieumau\Danhsachbieumau@loadlistbieumau');
Route::get('loadchitietbieumau/{id}','Khaithacthongtin\Danhsachbieumau\Danhsachbieumau@loadchitietbieumau')->middleware('auth:taikhoan');
Route::post('loadtableinfobieumau','Khaithacthongtin\Danhsachbieumau\Danhsachbieumau@loadtableinfobieumau');


//so sánh số liệu theo kỳ năm
Route::get('listsosanhsolieu','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@viewsosanhsolieu');
	Route::get('getlisttinh','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@getlisttinh');
	Route::post('getlisthuyen','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@getlisthuyen');
	Route::post('getlistxa','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@getlistxa');
	Route::get('getdonvihanhchinh','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@getdonvihanhchinh');
	Route::get('getkybaocao','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@getkybaocao');
	Route::post('getmadonvi','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@getmadonvi');
	Route::get('getsolieutheobieumau','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@getsolieutheobieumau');
	Route::get('getloaisolieu','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@getloaisolieu');
Route::post('loadsosanhsolieu','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@loadsosanhsolieu');
	Route::post('getbieudo','Khaithacthongtin\Sosanhsolieuky_nam\Sosanhsolieuky_nam@getbieudo');





//dự báo số liệu
Route::get('listdubaosolieu','Khaithacthongtin\Dubaosolieu\Dubaosolieu@viewdubaosolieu');



//so sánh số liệu theo địa bàn
Route::get('listsosanhsolieutheodiaban','Khaithacthongtin\Sosanhsolieutheodiaban\Sosanhsolieutheodiaban@viewsosanhsolieutheodiaban');
Route::post('loadsosanhsolieudiaban','Khaithacthongtin\Sosanhsolieutheodiaban\Sosanhsolieutheodiaban@loadsosanhsolieudiaban');
Route::post('loadsosanhsolieudonvi','Khaithacthongtin\Sosanhsolieutheodiaban\Sosanhsolieutheodiaban@loadsosanhsolieudonvi');
Route::post('getbieudodonvi','Khaithacthongtin\Sosanhsolieutheodiaban\Sosanhsolieutheodiaban@getbieudodonvi');



//tra cứu số liệu theo biểu mẫu
Route::get('listtracuusolieubieumau','Khaithacthongtin\Tracuusolieutheobieumau\Tracuusolieutheobieumau@viewtracuusolieutheobieumau');
Route::post('loadsolieutheobieumau','Khaithacthongtin\Tracuusolieutheobieumau\Tracuusolieutheobieumau@loadsolieutheobieumau');

//tra cứu số liệu theo chỉ tieu
Route::get('listtracuusolieuchitieu','Khaithacthongtin\Tracuusolieutheochitieu\Tracuusolieutheochitieu@viewtracuusolieutheochitieu');
Route::post('loadsolieutheochitieu','Khaithacthongtin\Tracuusolieutheochitieu\Tracuusolieutheochitieu@loadsolieutheochitieu');

//end route huy







// code tu 22-04-2020
Route::get('/loaisolieu', 'tbl_loaisolieuController@index')->middleware('auth:taikhoan', 'checkpermission:super-admin');
Route::post('/loaisolieu', 'tbl_loaisolieuController@store');
Route::put('/loaisolieu', 'tbl_loaisolieuController@update');
Route::put('/loaisolieu/{id}', 'tbl_loaisolieuController@updateItem');
Route::delete('/loaisolieu/{id}', 'tbl_loaisolieuController@destroy');


Route::get('/donvitinh', 'tbl_donvitinhController@index')->middleware('auth:taikhoan', 'checkpermission:super-admin');
Route::post('/donvitinh', 'tbl_donvitinhController@store');
Route::put('/donvitinh', 'tbl_donvitinhController@update');
Route::put('/donvitinh/{id}', 'tbl_donvitinhController@updateItem');
Route::delete('/donvitinh/{id}', 'tbl_donvitinhController@destroy');
Route::get('getlistdonvitinh', 'tbl_donvitinhController@getlistdonvitinh');

Route::get('/kybaocao', 'tbl_kybaocaoController@index')->middleware('auth:taikhoan', 'checkpermission:super-admin');
Route::post('/kybaocao', 'tbl_kybaocaoController@store');
Route::put('/kybaocao', 'tbl_kybaocaoController@update');
Route::put('/kybaocao/{id}', 'tbl_kybaocaoController@updateItem');
Route::delete('/kybaocao/{id}', 'tbl_kybaocaoController@destroy');

//code đức 22-04-2020
# Cac Route dung chung

Route::get('indexTinh', 'devvn_tinhthanhphoController@index');
Route::get('listQuanhuyen', 'tbl_quanhuyenController@listHuyen');
Route::get('indexLoaisolieu', 'tbl_loaisolieuController@Danhsachloaidoituong');
Route::get('Download/{file}', 'DownloadController@DownloadFile');
Route::get('danhsachDIaban/{tinh}', 'baocao\DanhsachBaocaoController@danhSachdonvihanhchinh');
Route::get('danhsachkybaocao', 'tbl_kybaocaoController@kyBaocao');
Route::get('danhsachdonvihanhchinh', 'tbl_donvihanhchinhController@danhsachdonvihanhchinh');
Route::get('danhsachPhongban', 'tbl_phongbanController@DanhsachPhongban');
Route::get('getTemplateReport/{idTemplate}', 'quanlybieumau\NhaplieubaocaoController@getTemplate');
Route::get('listDistrictWithProvince/{id}', 'tbl_quanhuyenController@listDistrictWithProvince');
Route::get('listXaphuongwithQuanhuyen/{id}', 'tbl_xaphuongController@listXaphuongwithQuanhuyen');



# Phan quan ly cac bieu mau nhập liệu
Route::get('viewDanhsachBieumauNhaplieu', 'quanlybieumau\NhaplieusolieuController@viewdanhsachBieumaunhaplieu')->middleware('auth:taikhoan');
Route::get('indexBieumauNhaplieu', 'quanlybieumau\NhaplieusolieuController@index')->middleware('auth:taikhoan');
Route::get('viewNhaplieuSolieu', 'quanlybieumau\NhaplieusolieuController@view')->middleware('auth:taikhoan');
Route::post('luuBieunauNhaplieuSolieu', 'quanlybieumau\NhaplieusolieuController@store');
Route::get('applyBieumauNhaplieu/{id}', 'quanlybieumau\NhaplieusolieuController@apply');
Route::get('showBieumauNhaplieu/{id}', 'quanlybieumau\NhaplieusolieuController@show');
Route::post('editBieumauNhaplieu', 'quanlybieumau\NhaplieusolieuController@update');
Route::post('destroyBieumauNhaplieu', 'quanlybieumau\NhaplieusolieuController@destroy');
# Phan quan ly bieu mau bao cao
Route::get('viewQuanlyBieumaubaocao', 'baocao\Quanlybaocao@view')->middleware('auth:taikhoan');
Route::get('Bieumaubaocaoindex', 'baocao\Quanlybaocao@index')->middleware('auth:taikhoan');
Route::get('formthembaocao', 'baocao\Quanlybaocao@viewThemBaocao')->middleware('auth:taikhoan');
Route::post('LuuBieumaubaocao', 'baocao\Quanlybaocao@store');
Route::get('editBieumauBaocao', 'baocao\Quanlybaocao@edtBaocao')->middleware('auth:taikhoan');
Route::get('ChitietBieumaubaocao/{id}', 'baocao\Quanlybaocao@show')->middleware('auth:taikhoan');
Route::post('SuaBieumaubaocao', 'baocao\Quanlybaocao@update');
Route::get('ApplyBieumauBaocao/{id}', 'baocao\Quanlybaocao@apply');
Route::post('destroyBieumauBaocao', 'baocao\Quanlybaocao@destroy');

# Phan nhap lieu so lieu
Route::get('viewListNhaplieu', 'quanlybieumau\NhaplieusolieuController@viewListNhaplieu')->middleware('auth:taikhoan');
Route::get('indexNhaplieuBieumau', 'quanlybieumau\NhaplieusolieuController@indexNhaplieuBieumau')->middleware('auth:taikhoan');
Route::get('showEditBieumau/{id}', 'quanlybieumau\NhaplieusolieuController@showEditBieumau')->middleware('auth:taikhoan');
Route::post('DelBieumau', 'quanlybieumau\NhaplieusolieuController@DelBieumau');
Route::get('viewNhaplieuBieumau', 'quanlybieumau\NhaplieusolieuController@viewNhaplieu')->middleware('auth:taikhoan');
Route::get('danhsachNhaplieuBieumau', 'quanlybieumau\NhaplieusolieuController@danhsachBieumauNhapLieu')->middleware('auth:taikhoan');
Route::post('downloadFileBieumauNhapLieu', 'quanlybieumau\NhaplieusolieuController@DowloadExcel');
Route::post('importFileBieumauNhapLieu', 'quanlybieumau\NhaplieusolieuController@importExcel');
Route::post('importDataBieumauNhapLieu', 'quanlybieumau\NhaplieusolieuController@importData');
Route::post('ListTempalatewithIdBieumau', 'quanlybieumau\NhaplieusolieuController@ListTempalatewithIdBieumau');
Route::post('accumulateDataBieumau', 'quanlybieumau\NhaplieusolieuController@accumulateDataBieumau');
Route::post('ListDataofLocation', 'quanlybieumau\NhaplieusolieuController@ListDataofLocation');
Route::post('SumDataofLocation', 'quanlybieumau\NhaplieusolieuController@SumDataofLocation');

# Phan nhap lieu bao cao
Route::get('viewNhaplieuBaocao', 'quanlybieumau\NhaplieubaocaoController@view')->middleware('auth:taikhoan');
Route::get('indexBieumauApdung/{ky}', 'baocao\Quanlybaocao@danhsachBaocaoApdung')->middleware('auth:taikhoan');
Route::post('downloadBieumau', 'quanlybieumau\NhaplieubaocaoController@DowloadExcel');
Route::post('importExcelBaocao',  'quanlybieumau\NhaplieubaocaoController@importExcel');
Route::post('nhapDulieu', 'quanlybieumau\NhaplieubaocaoController@importData');
Route::get('viewDanhsachNhaplieu', 'quanlybieumau\NhaplieubaocaoController@viewNhaplieu')->middleware('auth:taikhoan');
Route::get('Nhaplieuindex', 'quanlybieumau\NhaplieubaocaoController@index')->middleware('auth:taikhoan');
Route::post('xoaNhaplieuBaocao', 'quanlybieumau\NhaplieubaocaoController@destroy');
Route::get('indexInputReportCurrent', 'quanlybieumau\NhaplieubaocaoController@indexCurrentyear')->middleware('auth:taikhoan');
Route::get('ShowReportInput/{id}', 'quanlybieumau\NhaplieubaocaoController@ReviewReport')->middleware('auth:taikhoan');
Route::post('accumulateDataBaocao', 'quanlybieumau\NhaplieubaocaoController@accumulateDataBieumau');
Route::post('ListTempalatewithIdBaocao', 'quanlybieumau\NhaplieubaocaoController@ListTempalatewithIdBieumau');
Route::post('ListDataofLocationBaocao', 'quanlybieumau\NhaplieubaocaoController@ListDataofLocation');
Route::post('SumDataofLocationBaocao', 'quanlybieumau\NhaplieubaocaoController@SumDataofLocation');
# Phan quan ly bao cao
Route::get('viewDanhsachBaocao', 'baocao\DanhsachBaocaoController@view')->middleware('auth:taikhoan');
Route::get('indexDanhsachBaocao', 'baocao\DanhsachBaocaoController@index')->middleware('auth:taikhoan');
Route::get('viewThongtinBaocao', 'baocao\DanhsachBaocaoController@viewThongtinBaocao')->middleware('auth:taikhoan');
Route::post('storeBaocao', 'baocao\DanhsachBaocaoController@store');
Route::get('showBaocao/{id}', 'baocao\DanhsachBaocaoController@show')->middleware('auth:taikhoan');
Route::post('updateBaocao', 'baocao\DanhsachBaocaoController@update');
Route::post('deleteBaocao', 'baocao\DanhsachBaocaoController@destroy');
Route::get('sendBaocao/{id}', 'baocao\DanhsachBaocaoController@send');

# Phan tim kiem bao cao
Route::get('viewTimkiembaocao', 'baocao\DanhsachBaocaoController@viewDanhsachbaocao')->middleware('auth:taikhoan');
Route::get('indexTimkiemBaocao', 'baocao\DanhsachBaocaoController@indexTimkiem');
Route::post('TimKiembaocao', 'baocao\DanhsachBaocaoController@find');
Route::get('viewXemchitietBaocao', 'baocao\DanhsachBaocaoController@viewChitietBaocao');

Route::get('listBieumauActive', 'quanlybieumau\NhaplieusolieuController@listBieumauActive');


# code Tú
Route::get('donvihanhchinh', 'tbl_donvihanhchinhController@index')->middleware('auth:taikhoan');
Route::get('donvihanhchinh/{id}/editchitieu', 'tbl_donvihanhchinhController@editchitieu')->name('edichitieudvhc')->middleware('auth:taikhoan');
Route::get('donvihanhchinh/{id}/editusers', 'tbl_donvihanhchinhController@editusers')->middleware('auth:taikhoan');
Route::get('donvihanhchinh/{id}/editphongban', 'tbl_donvihanhchinhController@editphongban')->middleware('auth:taikhoan');
Route::post('/donvihanhchinh', 'tbl_donvihanhchinhController@store');
Route::post('/donvihanhchinh/{id}/khoitao', 'tbl_donvihanhchinhController@khoitaodulieu');
Route::post('/donvihanhchinh', 'tbl_donvihanhchinhController@store');
Route::put('/donvihanhchinh/{id}', 'tbl_donvihanhchinhController@update');
Route::delete('/donvihanhchinh/{id}', 'tbl_donvihanhchinhController@destroy');
Route::put('/donvihanhchinh/{id}/updatechitieu', 'tbl_donvihanhchinhController@updatechitieu');
Route::get("/donvihanhchinh/getchinhsua/{id}", "tbl_donvihanhchinhController@getchinhsua");

Route::post('/phongban', 'tbl_phongbanController@store');
Route::put('/phongban/{id}', 'tbl_phongbanController@update');
Route::delete('/phongban/{id}', 'tbl_phongbanController@destroy');
Route::get('getusersthuocphongban/{id}', 'tbl_phongbanController@generateUsersBaseOnPhongban');
Route::post('/addusersvaophongban', 'tbl_phongbanController@AddUsersToPhongban');
Route::delete('/removeuserkhoiphongban', 'tbl_phongbanController@RemoveUserFromPhongban');
#code tú 7-5-2020
Route::get('/thongbao', 'tbl_thongbaoController@index')->middleware('auth:taikhoan');
Route::post('/thongbao/{id}', 'tbl_thongbaoController@updateItem');
Route::post('/thongbao', 'tbl_thongbaoController@store');
Route::put('/thongbao','tbl_thongbaoController@update');
Route::delete('/thongbao/{id}', 'tbl_thongbaoController@destroy');
Route::get('/downloadtaptinthongbao/{id}', 'tbl_thongbaoController@downloadfile');
Route::post('thongbao/{id}/sendthongbao', 'tbl_thongbaoController@sendThongbao');
Route::get('/danhsachthongbao', 'tbl_thongbaoController@danhsachthongbao')->middleware('auth:taikhoan');
Route::put('/thongbao/{id}/changethongbaostatus', 'tbl_thongbaoController@changethongbaostatus');
Route::get('/thongbao/{id}/getThongbaoInfo', 'tbl_thongbaoController@getThongbaoInfo')->middleware('auth:taikhoan');

Route::get('/diaban', 'tbl_diabanController@index')->middleware('auth:taikhoan', 'checkpermission:super-admin');
Route::post('/diaban', 'tbl_diabanController@store');
Route::put('/diaban/{id}','tbl_diabanController@update');
Route::delete('/diaban/{loaidiaban}/{id}','tbl_diabanController@destroy');

Route::get('/taikhoan', 'tbl_taikhoanController@index')->middleware('auth:taikhoan', 'checkpermission:quanly-taikhoan');
Route::post('/taikhoan', 'tbl_taikhoanController@store');
Route::put('/taikhoan/{id}', 'tbl_taikhoanController@updateItem');
Route::put('/taikhoan','tbl_taikhoanController@update');
Route::delete('/taikhoan/{id}', 'tbl_taikhoanController@destroy');

Route::get('/nhomquyen', 'tbl_nhomquyenController@index')->middleware('auth:taikhoan', 'checkpermission:super-admin');
Route::post('/nhomquyen', 'tbl_nhomquyenController@store');
Route::put('/nhomquyen/{id}', 'tbl_nhomquyenController@updateItem');
Route::put('/nhomquyen','tbl_nhomquyenController@update');
Route::delete('/nhomquyen/{id}', 'tbl_nhomquyenController@destroy');

Route::get('/', function() {
	return view('home.index');
})->middleware('auth:taikhoan');
Route::get('/dangnhap', 'TaiKhoanLoginController@getLogin')->name('login');
Route::post('/dangnhap', 'TaiKhoanLoginController@doLogin');
Route::post('/dangxuat', 'TaiKhoanLoginController@doLogout')->name('logout');
//Route::post('/dangnhap', 'AdminLoginController@postLogin');
Route::get('login', 'TaiKhoanLoginController@getLogin');

Route::get('/quanlytaikhoan', 'QLTaikhoanController@index');
Route::post('/thaydoimatkhau/{id}', 'QLTaikhoanController@changePassword');
Route::post('/thaydoithongtintaikhoan/{id}', 'QLTaikhoanController@changeInfo');

//Route::get('/', ['as' => 'getLogin', 'uses' => 'AdminLoginController@getLogin']);
//Route::post('login', ['as' => 'postLogin', 'uses' => 'AdminLoginController@postLogin']);
Route::get('logout', ['as' => 'getLogout', 'uses' => 'AdminLoginController@getLogout']);
//Route::get('role', 'RoleControllers@role');
//Route::get('Listrole', 'RoleControllers@Listrole');
//Route::post('getrole', 'RoleControllers@getrole');
//Route::post('InsertAndUpdateRole', 'RoleControllers@InsertAndUpdateRole');
//Route::post('delRolec', 'RoleControllers@delRolec');

//Code duc 12/05/2020

# Bao cao chi tieu KTXH
Route::get('viewReportChitieuKTXH', 'Report\SummaryIndicatorReportController@viewReport')->middleware('auth:taikhoan');
Route::post('summartChitieuKTXH', 'Report\SummaryIndicatorReportController@Summary');
#Phan bao cao san xuat
Route::get('viewProductionPlanreport', 'Report\ProductionPlanReportController@viewProductionPlan')->middleware('auth:taikhoan');
Route::post('exportDataProductionPlanreport', 'Report\ProductionPlanReportController@Exportdata');
Route::post('reportofProductionPlanreport', 'Report\ProductionPlanReportController@viewReport');
Route::get('listDonvihanhchinParent', 'tbl_donvihanhchinhController@listDonvihanhchinParent');

Route::get('info', 'TestDataController@info');
#code tu 20-05-2020
Route::get('/kehoachktxhxa', 'tbl_kehoachktxhxaController@index')->middleware('auth:taikhoan');
Route::get('/kehoachktxhxa/{id}/details', 'tbl_kehoachktxhxaController@details')->middleware('auth:taikhoan');
Route::post('/kehoachktxhxa','tbl_kehoachktxhxaController@store');
Route::put('/kehoachktxhxa/{id}','tbl_kehoachktxhxaController@update');
Route::put('/kehoachktxhxa/{id}/maubieu','tbl_kehoachktxhxaController@updateMaubieu');
Route::delete('/kehoachktxhxa/{id}','tbl_kehoachktxhxaController@destroy');

Route::get('/unauthorizedpage/previousUrl={previousUrl}', 'UnauthorizedController@index')->where('previousUrl', '(.*)');

Route::get('/bieumaukhktxh/{id}', 'tbl_kehoachktxhxaController@viewBieuMau');
Route::get('/getbieumaukhktxhdata/{id}/{bieumau}', 'tbl_kehoachktxhxaController@getBieumauData');

Route::get("downloadFileDinhkem/{file}", "baocao\DanhsachBaocaoController@downloadFileDinhkem");
Route::post('uploadFileKyso', "baocao\DanhsachBaocaoController@uploadFileKyso");

Route::get('xemchitietbaocao/{id}', "baocao\DanhsachBaocaoController@ViewXemchitietbaocao");
Route::get('thongtinchitiet', "baocao\DanhsachBaocaoController@getChitiet");

#Duyet bao cao

Route::get('Duyet/{id}', 'baocao\DanhsachBaocaoController@Duyet');

# Middleware for dynamic authentication & authorization
Route::group(['middleware' => ['checkallowedroute', 'auth:taikhoan']], function() {
    Route::get('/quyen', 'tbl_quyenController@index');
	
  });


Route::post('/quyen', 'tbl_quyenController@store');
Route::put('/quyen/{id}', 'tbl_quyenController@update');
Route::delete('/quyen/{id}', 'tbl_quyenController@destroy');
Route::post('/updateroute', 'tbl_quyenController@addAvailableRouteToDB');

# lay thong tin bieu mau
Route::get('getChitieuNhaplieu/{idTemplate}', 'quanlybieumau\NhaplieubaocaoController@ChitieuNhaplieu');
Route::get('downloadFileQD/{file}', 'quanlybieumau\NhaplieusolieuController@downloadFileQuyetdinh');
Route::get('delFileQuyetdinh/{id}', 'quanlybieumau\NhaplieusolieuController@delFileQuyetdinh');