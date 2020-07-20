function checkStatusCheckBox(element) {
    let check = false;
    if (typeof element == "string") {
        check = $("#" + element).is(":checked");
    }
    if (typeof element == "object") {
        check = $(element).is(":checked");
    }
    return check;
}

function listNam() {
    let nam = [];
    for (let index = 1990; index < 2100; index++) {
        nam.push(index);
    }
    return nam;
}

function ShowReport(urlReport = "", viewReport = "", hideTollbar = false) {
    //view report
    Stimulsoft.Base.StiLicense.loadFromFile(
        "../stimulsoft/Libs/Reports.JS/license.key"
    );
    //đặt ngôn ngữ
    Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile(
        "../stimulsoft/Libs/Reports.JS/Localization/vi.xml"
    );

    var options = new Stimulsoft.Viewer.StiViewerOptions();
    options.height = "200vh";
    options.appearance.scrollbarsMode = true;
    // options.toolbar.showDesignButton = true;
    if (hideTollbar == true) {
        options.toolbar.visible = false;
    }

    options.toolbar.showOpenButton = false;
    options.toolbar.printDestination =
        Stimulsoft.Viewer.StiPrintDestination.Direct;
    options.appearance.htmlRenderMode =
        Stimulsoft.Report.Export.StiHtmlExportMode.Table;
    options.toolbar.showAboutButton = false;
    var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
    viewer.renderHtml(viewReport);
    var report = new Stimulsoft.Report.StiReport();
    report.loadFile(urlReport);
    viewer.report = report;
    return viewer;
}

/***
 * This is function show report with data and parameter
 *@param urlReprort: this is url of report
  @param datas: this is data report type array
    @param Parameter: parameter your want pass to report, type parameter is Map
   @param viewReport: Name of div your want insert report
 */
function ShowReportData(
    urlReport = "",
    datas = null,
    Parameter = [],
    viewReport,
    designmode = true,
    hideTollbar = false
) {
    Stimulsoft.Base.StiLicense.loadFromFile(
        "../stimulsoft/Libs/Reports.JS/license.key"
    );
    Stimulsoft.Base.Localization.StiLocalization.setLocalizationFile(
        "../stimulsoft/Libs/Reports.JS/Localization/vi.xml"
    );
    var options = new Stimulsoft.Viewer.StiViewerOptions();
    options.height = "200vh";
    options.appearance.scrollbarsMode = true;
    options.toolbar.showOpenButton = false;
    options.toolbar.printDestination =
        Stimulsoft.Viewer.StiPrintDestination.Direct;
    options.appearance.htmlRenderMode =
        Stimulsoft.Report.Export.StiHtmlExportMode.Table;
    options.toolbar.showAboutButton = false;
    if (hideTollbar == true) {
        options.toolbar.visible = false;
    }
    switch (designmode) {
        case false:
            var optiondesigner = new Stimulsoft.Designer.StiDesignerOptions();
            var designer = new Stimulsoft.Designer.StiDesigner(
                optiondesigner,
                "StiDesiger",
                false
            );
            designer.renderHtml(viewReport);
            var report = new Stimulsoft.Report.StiReport();
            report.loadFile(urlReport);
            var dataSet = new Stimulsoft.System.Data.DataSet("datas");
            dataSet.readJson(datas);
            report.regData(dataSet.dataSetName, "", dataSet);
            report.dictionary.synchronize();
            designer.report = report;
            break;
        default:
            //Viewer
            var viewer = new Stimulsoft.Viewer.StiViewer(
                options,
                "StiViewer",
                false
            );
            viewer.renderHtml(viewReport);
            var report = new Stimulsoft.Report.StiReport();
            // var datas = { "databases" : [{"libelle":"myLibelle1","libcourt":"myLibCourt1","name":"name1"},{"libelle":"myLibelle2","libcourt":"myLibCourt2","name":"name2"}]};
            var dataSet = new Stimulsoft.System.Data.DataSet("datas");
            dataSet.readJson(datas);
            report.regData(dataSet.dataSetName, "", dataSet);
            // report.dictionary.synchronize();
            report.loadFile(urlReport);
            for (let element of Parameter) {
                let variables = report.dictionary.variables.getByName(
                    element[0]
                );
                variables.value = element[1];
            }
            report.dictionary.synchronize();
            viewer.report = report;
            break;
    }
}
export default {
    checkStatusCheckBox,
    listNam,
    ShowReport,
    ShowReportData
};
