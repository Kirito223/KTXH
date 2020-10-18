import Ultil from "../js/Ultil.js";
var cbBieuMau;
$(document).ready(() => {
    if ($("#summaryindicatorreport").length) {
        loadData();
        initEvent();
    }
});
function loadData() {
    $("#cbHuyen").dxSelectBox({
        dataSource: "danhsachdonvihanhchinh",
        displayExpr: "tendonvi",
        valueExpr: "id",
    });
    $("#cbBieumau").dxSelectBox({
        dataSource: "indexBieumauNhaplieu",
        displayExpr: "tenbieumau",
        valueExpr: "id",
    });

    cbBieuMau = $("#cbBieumau").dxSelectBox("instance");

    $("#cbNam").dxDateBox({
        value: new Date(),
        displayFormat: "d/MM/yyyy",
    });

    Ultil.ShowReport("../report/baocaochitieudaksong.mrt", "report");
}

function initEvent() {
    $("#btnSearch").on("click", () => {
        let location = $("#cbHuyen").dxSelectBox("instance").option("value");
        let nam = $("#cbNam").dxDateBox("instance").option("value");
        let province = $("#cbHuyen").dxSelectBox("instance").option("text");

        let nameReport = cbBieuMau.option("text");
        axios
            .post("tongbcDaksong", {
                location: location,
                year: nam.getFullYear(),
                form: cbBieuMau.option("value"),
            })
            .then((res) => {
                let parameter = new Map();
                parameter.set("province", province);
                parameter.set("firstyear", nam.getFullYear() - 2);
                parameter.set("twoyear", nam.getFullYear() - 1);
                parameter.set("threeyear", nam.getFullYear());
                parameter.set("no", "Biểu mẫu ");
                parameter.set("title", nameReport);

                let data = res.data;

                Ultil.ShowReportData(
                    "../report/baocaochitieudaksong.mrt",
                    data,
                    parameter,
                    "report",
                    true
                );
            })
            .catch((err) => {
                console.log(err);
            });
    });
}
