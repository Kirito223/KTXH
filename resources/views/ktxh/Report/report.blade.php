@extends('master')
@section('title','Chi tiết biểu mẫu')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<h4 class="widget-title">Danh sách biểu mẫu</h4>
			</header>
			<hr class="widget-separator">
			<div class="widget-body">
				<div class="row">		
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputEmail1">Chọn biểu mẫu</label>
							<select type ="text" class="form-control" id="maubieu">
								<option value="1">Mẫu Quyết định</option>
								<option value="2">Công văn triển khai</option>
								<option value="3">Mẫu đề xuất</option>
								<option value="4">Mẫu dự toán ngân sách</option>
								<option value="5">Kế hoạch phát triển kinh tế</option>
								<option value="6">Danh mục chỉ tiêu KTXH</option>
							</select>
						</div>
					</div>	
				</div>				
				<div class="row" >
					<button type="button" class="btn btn-primary" id="timkiem" style="float: right;"><i class='fa fa-search' ></i> Tìm kiếm</button>
				</div>				
			</div>
		</div>
	</div>
</div>
<div id="content"></div>	



<script type="text/javascript">
	$(document).ready(function () {

$('#timkiem').click(function() {
	let maubieu = $('#maubieu').val();
	if(maubieu ==1){
        maubieu1();
	}else if(maubieu == 2){
		maubieu2();
	}else if(maubieu == 3){
		maubieu3();
	}else if(maubieu == 4){
		maubieu4();
	}else if(maubieu == 5){
		maubieu5();
	}else if(maubieu == 6){
		maubieu6();
	}
 });







function maubieu1(){
	axios.get('listchitieuReportmau1').then(function(response) {
	var datacha = response.data[0];
	var data = response.data[1];

//view report
Stimulsoft.Base.StiLicense.loadFromFile("stimulsoft/license.key");
//đặt ngôn ngữ
Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("stimulsoft/Localization/vi.xml");
// Set full screen mode for the viewer
// Đặt chế độ toàn màn hình cho người xem
var options = new Stimulsoft.Viewer.StiViewerOptions();
options.height = "200vh";
options.appearance.scrollbarsMode = true;
// options.toolbar.showDesignButton = true;
options.toolbar.showOpenButton = false;
options.toolbar.printDestination = Stimulsoft.Viewer.StiPrintDestination.Direct;
options.appearance.htmlRenderMode = Stimulsoft.Report.Export.StiHtmlExportMode.Table;
options.toolbar.showAboutButton = false;
// options.appearance.fullScreenMode = true;



//DESIGER
//StiOptions.WebServer.url = 'http://localhost';
//var options = new Stimulsoft.Designer.StiDesignerOptions();
//var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
//designer.renderHtml('content');
//var report = new Stimulsoft.Report.StiReport();
//var chitieu = { "databases" : [{"cha":datacha},{"data":data}]};
// var chitieu = data;
//var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
//dataSet.readJson(chitieu);
//report.regData(dataSet.dataSetName, "Chitieu", dataSet);
//report.loadFile("ktxh/Report/maubieuII1.mrt");
//report.dictionary.synchronize();
//designer.report = report;

////Viewer
var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
viewer.renderHtml('content');
var report = new Stimulsoft.Report.StiReport();
report.loadFile("ktxh/Report/maubieuII1.mrt");
var chitieu = { "databases" : [{"cha":datacha},{"data":data}]};
// var chitieu = data;
var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
dataSet.readJson(chitieu);
report.regData(dataSet.dataSetName, "Chitieu", dataSet);
report.dictionary.synchronize();
viewer.report = report;
});

}




function maubieu2(){
	axios.get('listchitieuReportmau2').then(function(response) {
	var data = response.data;

//view report
Stimulsoft.Base.StiLicense.loadFromFile("stimulsoft/license.key");
//đặt ngôn ngữ
Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("stimulsoft/Localization/vi.xml");
// Set full screen mode for the viewer
// Đặt chế độ toàn màn hình cho người xem
var options = new Stimulsoft.Viewer.StiViewerOptions();
options.height = "200vh";
options.appearance.scrollbarsMode = true;
// options.toolbar.showDesignButton = true;
options.toolbar.showOpenButton = false;
options.toolbar.printDestination = Stimulsoft.Viewer.StiPrintDestination.Direct;
options.appearance.htmlRenderMode = Stimulsoft.Report.Export.StiHtmlExportMode.Table;
options.toolbar.showAboutButton = false;
// options.appearance.fullScreenMode = true;



//DESIGER
// StiOptions.WebServer.url = 'http://localhost';
//var options = new Stimulsoft.Designer.StiDesignerOptions();
//var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
//designer.renderHtml('content');
//var report = new Stimulsoft.Report.StiReport();
//var chitieu = { "databases" : [{"data":data}]};
// var chitieu = data;
//var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
//dataSet.readJson(chitieu);
//report.regData(dataSet.dataSetName, "Chitieu", dataSet);
//report.loadFile("ktxh/Report/maubieuII2.mrt");
//report.dictionary.synchronize();
//designer.report = report;

    //Viewer
    var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    viewer.renderHtml('content');
    var report = new Stimulsoft.Report.StiReport();
    report.loadFile("ktxh/Report/maubieuII2.mrt");
    var chitieu = { "databases" : [{"data":data}]};
 // var chitieu = data;
 var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
 dataSet.readJson(chitieu);
 report.regData(dataSet.dataSetName, "Chitieu", dataSet);
 report.dictionary.synchronize();
 viewer.report = report;
});

}


function maubieu3(){
	axios.get('listchitieuReportmau4B').then(function(response) {
	var data = response.data;

//view report
Stimulsoft.Base.StiLicense.loadFromFile("stimulsoft/license.key");
//đặt ngôn ngữ
Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("stimulsoft/Localization/vi.xml");
// Set full screen mode for the viewer
// Đặt chế độ toàn màn hình cho người xem
var options = new Stimulsoft.Viewer.StiViewerOptions();
options.height = "200vh";
options.appearance.scrollbarsMode = true;
// options.toolbar.showDesignButton = true;
options.toolbar.showOpenButton = false;
options.toolbar.printDestination = Stimulsoft.Viewer.StiPrintDestination.Direct;
options.appearance.htmlRenderMode = Stimulsoft.Report.Export.StiHtmlExportMode.Table;
options.toolbar.showAboutButton = false;
// options.appearance.fullScreenMode = true;



//DESIGER
// StiOptions.WebServer.url = 'http://localhost';
//var options = new Stimulsoft.Designer.StiDesignerOptions();
//var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
//designer.renderHtml('content');
//var report = new Stimulsoft.Report.StiReport();
//var chitieu = { "databases" : [{"data":data}]};
// var chitieu = data;
//var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
//dataSet.readJson(chitieu);
//report.regData(dataSet.dataSetName, "Chitieu", dataSet);
//report.loadFile("ktxh/Report/maubieuII4B.mrt");
//report.dictionary.synchronize();
//designer.report = report;

    //Viewer
    var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    viewer.renderHtml('content');
    var report = new Stimulsoft.Report.StiReport();
    report.loadFile("ktxh/Report/maubieuII4B.mrt");
    var chitieu = { "databases" : [{"data":data}]};
 // var chitieu = data;
 var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
 dataSet.readJson(chitieu);
 report.regData(dataSet.dataSetName, "Chitieu", dataSet);
 report.dictionary.synchronize();
 viewer.report = report;
});

}






function maubieu4(){
	axios.get('listchitieuReportmau6').then(function(response) {
	var datacha = response.data[0];
	var data = response.data[1];

//view report
Stimulsoft.Base.StiLicense.loadFromFile("stimulsoft/license.key");
//đặt ngôn ngữ
Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("stimulsoft/Localization/vi.xml");
// Set full screen mode for the viewer
// Đặt chế độ toàn màn hình cho người xem
var options = new Stimulsoft.Viewer.StiViewerOptions();
options.height = "200vh";
options.appearance.scrollbarsMode = true;
// options.toolbar.showDesignButton = true;
options.toolbar.showOpenButton = false;
options.toolbar.printDestination = Stimulsoft.Viewer.StiPrintDestination.Direct;
options.appearance.htmlRenderMode = Stimulsoft.Report.Export.StiHtmlExportMode.Table;
options.toolbar.showAboutButton = false;
// options.appearance.fullScreenMode = true;



//DESIGER
// StiOptions.WebServer.url = 'http://localhost';
//var options = new Stimulsoft.Designer.StiDesignerOptions();
//var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
//designer.renderHtml('content');
//var report = new Stimulsoft.Report.StiReport();
//var chitieu = { "databases" : [{"cha":datacha},{"data":data}]};
// var chitieu = data;
//var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
//dataSet.readJson(chitieu);
//report.regData(dataSet.dataSetName, "Chitieu", dataSet);
//report.loadFile("ktxh/Report/maubieuII6C.mrt");
//report.dictionary.synchronize();
//designer.report = report;

    //Viewer
    var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    viewer.renderHtml('content');
    var report = new Stimulsoft.Report.StiReport();
    report.loadFile("ktxh/Report/maubieuII6C.mrt");
    var chitieu = { "databases" : [{"cha":datacha},{"data":data}]};
 // var chitieu = data;
 var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
 dataSet.readJson(chitieu);
 report.regData(dataSet.dataSetName, "Chitieu", dataSet);
 report.dictionary.synchronize();
 viewer.report = report;
});

}



function maubieu5(){
	axios.get('listchitieuReportmau7').then(function(response) {
	var datacha = response.data[0];
	var data = response.data[1];

//view report
Stimulsoft.Base.StiLicense.loadFromFile("stimulsoft/license.key");
//đặt ngôn ngữ
Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("stimulsoft/Localization/vi.xml");
// Set full screen mode for the viewer
// Đặt chế độ toàn màn hình cho người xem
var options = new Stimulsoft.Viewer.StiViewerOptions();
options.height = "200vh";
options.appearance.scrollbarsMode = true;
// options.toolbar.showDesignButton = true;
options.toolbar.showOpenButton = false;
options.toolbar.printDestination = Stimulsoft.Viewer.StiPrintDestination.Direct;
options.appearance.htmlRenderMode = Stimulsoft.Report.Export.StiHtmlExportMode.Table;
options.toolbar.showAboutButton = false;
// options.appearance.fullScreenMode = true;



//DESIGER
// StiOptions.WebServer.url = 'http://localhost';
//var options = new Stimulsoft.Designer.StiDesignerOptions();
//var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
//designer.renderHtml('content');
//var report = new Stimulsoft.Report.StiReport();
//var chitieu = { "databases" : [{"cha":datacha},{"data":data}]};
// var chitieu = data;
//var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
//dataSet.readJson(chitieu);
//report.regData(dataSet.dataSetName, "Chitieu", dataSet);
//report.loadFile("ktxh/Report/maubieuII7C.mrt");
//report.dictionary.synchronize();
//designer.report = report;

    //Viewer
    var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    viewer.renderHtml('content');
    var report = new Stimulsoft.Report.StiReport();
    report.loadFile("ktxh/Report/maubieuII7C.mrt");
    var chitieu = { "databases" : [{"cha":datacha},{"data":data}]};
 // var chitieu = data;
 var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
 dataSet.readJson(chitieu);
 report.regData(dataSet.dataSetName, "Chitieu", dataSet);
 report.dictionary.synchronize();
 viewer.report = report;
});

}




function maubieu6(){
	axios.get('listchitieuReportmau8').then(function(response) {
	var datacha = response.data[0];
	var data = response.data[1];

//view report
Stimulsoft.Base.StiLicense.loadFromFile("stimulsoft/license.key");
//đặt ngôn ngữ
Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("stimulsoft/Localization/vi.xml");
// Set full screen mode for the viewer
// Đặt chế độ toàn màn hình cho người xem
var options = new Stimulsoft.Viewer.StiViewerOptions();
options.height = "200vh";
options.appearance.scrollbarsMode = true;
// options.toolbar.showDesignButton = true;
options.toolbar.showOpenButton = false;
options.toolbar.printDestination = Stimulsoft.Viewer.StiPrintDestination.Direct;
options.appearance.htmlRenderMode = Stimulsoft.Report.Export.StiHtmlExportMode.Table;
options.toolbar.showAboutButton = false;
// options.appearance.fullScreenMode = true;



//DESIGER
// StiOptions.WebServer.url = 'http://localhost';
//var options = new Stimulsoft.Designer.StiDesignerOptions();
//var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
//designer.renderHtml('content');
//var report = new Stimulsoft.Report.StiReport();
//var chitieu = { "databases" : [{"cha":datacha},{"data":data}]};
// var chitieu = data;
//var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
//dataSet.readJson(chitieu);
//report.regData(dataSet.dataSetName, "Chitieu", dataSet);
//report.loadFile("ktxh/Report/maubieuII8C.mrt");
//report.dictionary.synchronize();
//designer.report = report;

    //Viewer
    var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    viewer.renderHtml('content');
    var report = new Stimulsoft.Report.StiReport();
    report.loadFile("ktxh/Report/maubieuII8C.mrt");
    var chitieu = { "databases" : [{"cha":datacha},{"data":data}]};
 // var chitieu = data;
 var dataSet = new Stimulsoft.System.Data.DataSet("chitieu");
 dataSet.readJson(chitieu);
 report.regData(dataSet.dataSetName, "Chitieu", dataSet);
 report.dictionary.synchronize();
 viewer.report = report;
});

}


});
</script>
@endsection