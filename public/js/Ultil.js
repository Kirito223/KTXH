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

export function initBieumau(Mauluongxuat, container) {
    document.getElementById(container).innerHTML = "";
    let u = document.createElement("u");
    u.setAttribute("class", "homeproduct");
    for (let index = 0; index < Mauluongxuat.length; index++) {
        const element = Mauluongxuat[index];
        let li = document.createElement("li");
        li.setAttribute("class", "item-bieumau");
        li.setAttribute("data-toggle", "tooltip");
        li.setAttribute("data-placement", "left");
        li.setAttribute("title", element.Name);

        let divicon = document.createElement("div");
        divicon.setAttribute("class", "iconbieumau");

        let i = document.createElement("i");
        i.setAttribute("class", "fa fa-file");

        if (element.loai == 1) {
            i.classList.add("type1");
        }
        if(element.loai == 2){
            i.classList.add("type2");
        }
        if(element.loai == 3){
            i.classList.add("type3");
        }
        let h5 = document.createElement("h5");
        let text = document.createTextNode(
            element.Name.length > 10
                ? element.Name.substring(0, 10)
                : element.Name
        );
        li.appendChild(divicon);
        divicon.appendChild(i);
        h5.appendChild(text);
        li.appendChild(h5);
        li.addEventListener("click", element.function);
        u.appendChild(li);
    }
    document.getElementById(container).appendChild(u);
}

export default {
    checkStatusCheckBox,
    listNam,
    ShowReport,
    ShowReportData,
};
