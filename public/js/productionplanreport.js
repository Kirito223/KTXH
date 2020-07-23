import Ultil from "../js/Ultil.js";
var cbBieuMau;
$(document).ready(() => {
    if ($("#productionplanreport").length) {
        loadData();
        initEvent();
    }
});
function loadData() {
    $("#cbHuyen").dxSelectBox({
        // dataSource: "listDonvihanhchinParent",
        displayExpr: "tendonvi",
        valueExpr: "id",
    });
    $("#cbBieumau").dxSelectBox({
        dataSource: "Bieumaubaocaoindex",
        displayExpr: "tenbieumau",
        valueExpr: "id",
    });

    cbBieuMau = $("#cbBieumau").dxSelectBox("instance");

    $("#cbNam").dxDateBox({
        value: new Date(),
    });
    $("#cbDiaban").dxSelectBox({
        dataSource: [
            { id: 1, name: "Huyện" },
            { id: 2, name: "Xã" },
        ],
        displayExpr: "name",
        valueExpr: "id",
        onValueChanged: (e) => {
            if (e.value == 1) {
                axios
                    .get("listDonvihanhchinParent")
                    .then((res) => {
                        $("#cbHuyen")
                            .dxSelectBox("instance")
                            .option("dataSource", res.data);
                    })
                    .catch((err) => {
                        console.error(err);
                    });
            } else {
                axios
                    .get("danhsachXa")
                    .then((res) => {
                        $("#cbHuyen")
                            .dxSelectBox("instance")
                            .option("dataSource", res.data);
                    })
                    .catch((err) => {
                        console.error(err);
                    });
            }
        },
    });

    Ultil.ShowReport("../report/ReportCTKT.mrt", "report", true);
}
function initEvent() {
    $("#btnView").on("click", () => {
        let location = $("#cbHuyen").dxSelectBox("instance").option("value");
        let nam = $("#cbNam").dxDateBox("instance").option("value");
        let province = $("#cbHuyen").dxSelectBox("instance").option("text");
        Swal.fire({
            title: "Đang tải dữ liệu vui lòng chờ trong giây lát",
            text: "Đang tải dữ liệu vui lòng chờ",
            icon: "info",
            showConfirmButton: false,
        });
        let diaban = $("#cbDiaban").dxSelectBox("instance").option("value");
        axios
            .post("reportofProductionPlanreport", {
                location: location,
                year: nam.getFullYear(),
                bieumau: cbBieuMau.option("value"),
                namelocation: $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("text"),
                diaban: diaban,
            })
            .then((res) => {
                Swal.close();
                let para = new Map();
                para.set("date", nam.getDate());
                para.set("month", nam.getMonth() + 1);
                para.set("year", nam.getFullYear());
                para.set("location", province);
                Ultil.ShowReportData(
                    "../report/ReportCTKT.mrt",
                    res.data,
                    para,
                    "report",
                    true,
                    true
                );
            })
            .catch((err) => {
                Swal.close();
                console.log(err);
            });
    });
    $("#btnSearch").on("click", () => {
        let location = $("#cbHuyen").dxSelectBox("instance").option("value");
        let nam = $("#cbNam").dxDateBox("instance").option("value");
        let province = $("#cbHuyen").dxSelectBox("instance").option("text");

        let nameReport = cbBieuMau.option("text");
        Swal.fire({
            title: "Đang tải báo cáo vui lòng chờ trong giây lát",
            text: "Đang tải báo cáo vui lòng chờ",
            icon: "info",
            showConfirmButton: false,
        });

        let diaban = $("#cbDiaban").dxSelectBox("instance").option("value");
        axios
            .post("exportDataProductionPlanreport", {
                location: location,
                year: nam.getFullYear(),
                bieumau: cbBieuMau.option("value"),
                namelocation: $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("text"),
                diaban: diaban,
            })
            .then((res) => {
                Swal.close();
                window.location = "Download/" + res.data;
            })
            .catch((err) => {
                Swal.close();
                console.log(err);
            });
    });
}
