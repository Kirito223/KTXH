@extends('master')
@section('title','Danh Sách Biểu Mẫu')
@section('content')

<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">Danh sách biểu mẫu</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Chọn Năm</label>
                                <div id="cbNam"></div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <input class="hidden-item" id="kehoachIdInput" value="{{ $kehoachId }}"></input>
                            <div class="form-group">

                                <label for="exampleInputEmail1">Chọn biểu mẫu</label>
                                <select type="text" class="form-control" id="maubieu">
                                    <option value="ii1">Mẫu Quyết định</option>
                                    <option value="2">Công văn triển khai</option>
                                    <option value="ii4b">Mẫu đề xuất</option>
                                    <option value="4">Mẫu dự toán ngân sách</option>
                                    <option value="ii2">Kế hoạch phát triển kinh tế</option>
                                    <option value="6">Danh mục chỉ tiêu KTXH</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <button type="button" class="btn btn-primary" id="timkiem" style="float: right;">
                            <i class="fa fa-search" aria-hidden="true"></i> Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="widget-body">
                <div class="row">
                    <div id="content"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="module">
    import Ultil from "/js/Ultil.js";
		$(document).ready(() => {
			$("#cbNam").dxDateBox({
       			value: new Date(),
				displayFormat: "d/MM/yyyy",
    		});
		});

		
		document.getElementById('timkiem').addEventListener('click', function(e) {
        let kehoachId = document.getElementById('kehoachIdInput').value;
        let maubieuType = document.getElementById('maubieu').value; e.preventDefault();
			
		if (kehoachId.length > 0 && maubieuType.length > 0) {
        $.ajax({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'GET',
          url: '/getbieumaukhktxhdata/' + kehoachId + '/' + maubieuType,
          success: function(data) {
          if (!$.isEmptyObject(data.error)) {
          // printErrorMsg(data.error, "print-error-msg-on-edit"  ;
          } else {
          	console.log(data.success);
			var data = data.success;
              //view report
              Stimulsoft.Base.StiLicense.loadFromFile("../stimulsoft/Libs/Reports.JS/license.key");
              //đặt ngôn ngữ
			Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("../stimulsoft/Libs/Reports.JS/Localization/vi.xml");
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

              // Create the report viewer with specified options
              // Tạo trình xem báo cáo với các tùy chọn được chỉ định
              //var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
              //viewer.renderHtml('content');
              // Create a new report instance
              // Tạo một ví dụ báo cáo mới
              //var report = new Stimulsoft.Report.StiReport();
              // Load report from url
              // Tải báo cáo từ url
              //report.loadFile("../../../report/phongtaichinh/2.1.12.quyetdinhdieuchinhtcht.mrt");
              // Assign report to the viewer, the report will be built automatically after rendering the viewer
              // Gán báo cáo cho người xem, báo cáo sẽ được xây dựng tự động sau khi hiển thị trình xem
              //viewer.report = report;
						

			  if(maubieuType == "ii1"){
			////DESIGER
			  //var options = new Stimulsoft.Designer.StiDesignerOptions();
              //var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
			  //var report = new Stimulsoft.Report.StiReport();
              //designer.renderHtml('content');
			  //report.loadFile("../report/maubieuII1.mrt");
			  //var datas = data;
              //var dataSet = new Stimulsoft.System.Data.DataSet("datas");
              //dataSet.readJson(datas);
              //report.regData(dataSet.dataSetName, "datas", dataSet);     
              //report.dictionary.synchronize();
              //designer.report = report;
				  
			    //Viewer
    			var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    			viewer.renderHtml('content');
    			var report = new Stimulsoft.Report.StiReport();
    			report.loadFile("../report/maubieuII1.mrt");
    			var datas = data;
    			var dataSet = new Stimulsoft.System.Data.DataSet("datas");
    			dataSet.readJson(datas);
    			report.regData(dataSet.dataSetName, "datas", dataSet);
    			report.dictionary.synchronize();
    			viewer.report = report;
				  
			  }else if(maubieuType == "ii4b"){
				  
			  //var options = new Stimulsoft.Designer.StiDesignerOptions();
              //var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
			  //var report = new Stimulsoft.Report.StiReport();
              //designer.renderHtml('content');
			  //report.loadFile("../report/maubieuII4B.mrt");
			  //var datas = data;
              //var dataSet = new Stimulsoft.System.Data.DataSet("datas");
              //dataSet.readJson(datas);
              //report.regData(dataSet.dataSetName, "datas", dataSet);     
              //report.dictionary.synchronize();
              //designer.report = report;
				  
				//Viewer
    			var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    			viewer.renderHtml('content');
    			var report = new Stimulsoft.Report.StiReport();
    			report.loadFile("../report/maubieuII4B.mrt");
    			var datas = data;
    			var dataSet = new Stimulsoft.System.Data.DataSet("datas");
    			dataSet.readJson(datas);
    			report.regData(dataSet.dataSetName, "datas", dataSet);
    			report.dictionary.synchronize();
    			viewer.report = report;
				  
			  }else if(maubieuType == "ii2"){
				  
			  //var options = new Stimulsoft.Designer.StiDesignerOptions();
              //var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
			  //var report = new Stimulsoft.Report.StiReport();
              //designer.renderHtml('content');
			  //report.loadFile("../report/maubieuII2.mrt");
			  //var datas = data;
              //var dataSet = new Stimulsoft.System.Data.DataSet("datas");
              //dataSet.readJson(datas);
              //report.regData(dataSet.dataSetName, "datas", dataSet);     
              //report.dictionary.synchronize();
              //designer.report = report;
			
				//Viewer
    			var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    			viewer.renderHtml('content');
    			var report = new Stimulsoft.Report.StiReport();
    			report.loadFile("../report/maubieuII2.mrt");
    			var datas = data;
    			var dataSet = new Stimulsoft.System.Data.DataSet("datas");
    			dataSet.readJson(datas);
    			report.regData(dataSet.dataSetName, "datas", dataSet);
    			report.dictionary.synchronize();
    			viewer.report = report;			
			 }
          	}
          },
          //error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            //}
          });
        }
		
		var kehoachId2 = 14;
		var maubieuType2= "ii1";

        $.ajax({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'GET',
          url: '/getbieumaukhktxhdata/' + kehoachId2 + '/' + maubieuType2,
          success: function(data2){
          if (!$.isEmptyObject(data2.error)) {
          // printErrorMsg(data.error, "print-error-msg-on-edit"  ;
          } else {
		var data3= data2.success;
		if(maubieuType == 6){	
        //let location = $("#cbHuyen").dxSelectBox("instance").option("value");
        let nam = $("#cbNam").dxDateBox("instance").option("value");
        let province = "";
       	let nameReport = "Biểu 01";
        axios.post("summartChitieuKTXH", {
           location: 20,
           year: 2020,
           form: 26,
        }).then((res) => {
        let parameter = new Map();
        parameter.set("province", province);
        parameter.set("firstyear", nam.getFullYear() - 2);
        parameter.set("twoyear", nam.getFullYear() - 1);
        parameter.set("threeyear", nam.getFullYear());
        parameter.set("no", "Biểu mẫu ");
        parameter.set("title", nameReport);
							
        let data0 = res.data;
		let data = Object.assign(data0, data3);
		//view report
   		Stimulsoft.Base.StiLicense.loadFromFile("../stimulsoft/Libs/Reports.JS/license.key");
        //đặt ngôn ngữ
		Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("../stimulsoft/Libs/Reports.JS/Localization/vi.xml");
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
                			
		//var options = new Stimulsoft.Designer.StiDesignerOptions();
        //var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
		//var report = new Stimulsoft.Report.StiReport();
        //designer.renderHtml('content');
		//report.loadFile("/report/maubieuII8C.mrt");
		//var datas = data;
        //var dataSet = new Stimulsoft.System.Data.DataSet("datas");
        //dataSet.readJson(datas);
        //report.regData(dataSet.dataSetName, "datas", dataSet);     
        //report.dictionary.synchronize();
        //designer.report = report;
			
				//Viewer
    			var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    			viewer.renderHtml('content');
    			var report = new Stimulsoft.Report.StiReport();
    			report.loadFile("/report/maubieuII8C.mrt");
    			var datas = data;
    			var dataSet = new Stimulsoft.System.Data.DataSet("datas");
    			dataSet.readJson(datas);
    			report.regData(dataSet.dataSetName, "datas", dataSet);
    			report.dictionary.synchronize();
    			viewer.report = report;			
        });
				
		}else if(maubieuType == 4){		
			
        //let location = $("#cbHuyen").dxSelectBox("instance").option("value");
        let nam = $("#cbNam").dxDateBox("instance").option("value");
        let province = "";
       	let nameReport = "Biểu 01";
        axios.post("summartChitieuKTXH", {
            location: 20,
            year: 2020,
            form: 26,
         }).then((res) => {
         let parameter = new Map();
         parameter.set("province", province);
         parameter.set("firstyear", nam.getFullYear() - 2);
         parameter.set("twoyear", nam.getFullYear() - 1);
         parameter.set("threeyear", nam.getFullYear());
         parameter.set("no", "Biểu mẫu ");
         parameter.set("title", nameReport);

         let data0 = res.data;
		 let data = Object.assign(data0, data3);
         //view report
         Stimulsoft.Base.StiLicense.loadFromFile("../stimulsoft/Libs/Reports.JS/license.key");
         //đặt ngôn ngữ
		 Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("../stimulsoft/Libs/Reports.JS/Localization/vi.xml");
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
                			
		 //var options = new Stimulsoft.Designer.StiDesignerOptions();
         //var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
		//var report = new Stimulsoft.Report.StiReport();
         //designer.renderHtml('content');
		 //report.loadFile("/report/maubieuII6C.mrt");
		 //var datas = data;
         //var dataSet = new Stimulsoft.System.Data.DataSet("datas");
         //dataSet.readJson(datas);
         //report.regData(dataSet.dataSetName, "datas", dataSet);     
         //report.dictionary.synchronize();
         //designer.report = report;
			
				//Viewer
    			var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    			viewer.renderHtml('content');
    			var report = new Stimulsoft.Report.StiReport();
    			report.loadFile("/report/maubieuII6C.mrt");
    			var datas = data;
    			var dataSet = new Stimulsoft.System.Data.DataSet("datas");
    			dataSet.readJson(datas);
    			report.regData(dataSet.dataSetName, "datas", dataSet);
    			report.dictionary.synchronize();
    			viewer.report = report;				
         });
			
		}else if(maubieuType == 2){		
			
        //let location = $("#cbHuyen").dxSelectBox("instance").option("value");
        let nam = $("#cbNam").dxDateBox("instance").option("value");
        let province = "";
        let nameReport = "Biểu 01";
        axios.post("summartChitieuKTXH", {
            location: 20,
            year: 2020,
            form: 26,
         }).then((res) => {
         let parameter = new Map();
         parameter.set("province", province);
         parameter.set("firstyear", nam.getFullYear() - 2);
         parameter.set("twoyear", nam.getFullYear() - 1);
         parameter.set("threeyear", nam.getFullYear());
         parameter.set("no", "Biểu mẫu ");
         parameter.set("title", nameReport);

         let data0 = res.data;
		 let data = Object.assign(data0, data3);

         //view report
         Stimulsoft.Base.StiLicense.loadFromFile("../stimulsoft/Libs/Reports.JS/license.key");
         //đặt ngôn ngữ
		 Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile("../stimulsoft/Libs/Reports.JS/Localization/vi.xml");
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
			
		 //var options = new Stimulsoft.Designer.StiDesignerOptions();
         //var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesiger", false);
		 //var report = new Stimulsoft.Report.StiReport();
         //designer.renderHtml('content');
		 //var datas = data;
         //var dataSet = new Stimulsoft.System.Data.DataSet("datas");
         //dataSet.readJson(datas);
		 //report.loadFile("/report/maubieuII7C.mrt");
         //report.regData(dataSet.dataSetName, "datas", dataSet); 
         //report.dictionary.synchronize();
         //designer.report = report;
			
				//Viewer
    			var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    			viewer.renderHtml('content');
    			var report = new Stimulsoft.Report.StiReport();
    			report.loadFile("/report/maubieuII7C.mrt");
    			var datas = data;
    			var dataSet = new Stimulsoft.System.Data.DataSet("datas");
    			dataSet.readJson(datas);
    			report.regData(dataSet.dataSetName, "datas", dataSet);
    			report.dictionary.synchronize();
    			viewer.report = report;					
         }); 
		}
		}
		  }
	 });
    
			
			
       
     });
    </script>
@endsection