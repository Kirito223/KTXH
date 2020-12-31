import Ultil, { initBieumau } from "../js/Ultil.js";
var cbBieuMau;
var danhsachBieumau;
var idBieumau = undefined;
var cbLoaibieumau;
var cbLoaisolieu;
$(document).ready(() => {
    if ($("#productionplanreport").length) {
        loadData();
        initEvent();
    }
});

function loadData() {
    cbLoaibieumau = $("#loaibieumau")
        .dxSelectBox({
            dataSource: [
                { value: 1, name: "Báo cáo cơ sở" },
                { value: 2, name: "Báo cáo tổng hợp" },
                { value: 3, name: "Báo cáo khác" },
            ],
            displayExpr: "name",
            valueExpr: "value",
        })
        .dxSelectBox("instance");

    $("#cbHuyen").dxSelectBox({
        // dataSource: "listDonvihanhchinParent",
        displayExpr: "tendonvi",
        valueExpr: "id",
    });
    $("#cbSoLieu").dxSelectBox({
        dataSource: "getloaisolieu",
        displayExpr: "tenloaisolieu",
        valueExpr: "id",
    });
    $("#cbBieumau").dxSelectBox({
        dataSource: "danhsachbieumau",
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
                    .get("danhsachHuyen")
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

    Ultil.ShowReport("../report/ReportCTKT.mrt", "report", false);
}
function initEvent() {
    $("#btnLuuBieumau").on("click", function (e) {
        let fileBieumau = document.getElementById("fileBieumau").files[0];
        if (fileBieumau == undefined) {
            fileBieumau = null;
        }
        let formData = new FormData();
        formData.append("name", $("#txtTenbieumau").val());
        formData.append("file", fileBieumau);
        formData.append(
            "apdung",
            Ultil.checkStatusCheckBox("chkApdung") == true ? "1" : "0"
        );
        formData.append("loai", cbLoaibieumau.option("value"));

        let config = {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        };

        if (idBieumau == undefined) {
            if (fileBieumau == null) {
                Swal.fire(
                    "Vui lòng chọn tập tin biểu mẫu muốn tải lên",
                    "Chưa chọn tập tin biểu mẫu",
                    "warning"
                );
            } else {
                axios
                    .post("danhsachBieumau/store", formData, config)
                    .then((res) => {
                        if (res.data["code"] == 200) {
                            loadBieumau();
                            loadDanhsachBieumau();
                            $("#modalThembieumau").modal("toggle");
                            $("#modalBieumau").modal("toggle");
                        } else {
                            Swal.fire(
                                "Đã có lõi xảy ra vui lòng kiểm tra lại",
                                "Xảy ra lỗi",
                                "error"
                            );
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        } else {
            formData.append("id", idBieumau);
            axios
                .post("danhsachBieumau/edit", formData, config)
                .then((res) => {
                    if (res.data["code"] == 200) {
                        loadBieumau();
                        loadDanhsachBieumau();
                        $("#modalBieumau").modal("toggle");
                    } else {
                        Swal.fire(
                            "Đã có lõi xảy ra vui lòng kiểm tra lại",
                            "Xảy ra lỗi",
                            "error"
                        );
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    });
    $("#btnAddbieumau").on("click", (e) => {
        restInputBieumau();
        $("#modalBieumau").modal("show");
    });

    $("#btnView").on("click", () => {
        loadBieumau();
        $("#modelDanhsachBieumau").modal("show");
    });

    $("#btnThembieumau").on("click", function (e) {
        loadDanhsachBieumau();
        restInputBieumau();
        $("#modalThembieumau").modal("show");
    });
    $("#btnSearch").on("click", () => {
        let location = $("#cbHuyen").dxSelectBox("instance").option("value");
        let nam = $("#cbNam").dxDateBox("instance").option("value");
        let province = $("#cbHuyen").dxSelectBox("instance").option("text");
        let loaisolieu = $("#cbSoLieu").dxSelectBox("instance").option("text");
        Swal.fire({
            title: "Đang tải dữ liệu vui lòng chờ trong giây lát",
            text: "Đang tải dữ liệu vui lòng chờ",
            icon: "info",
            showConfirmButton: false,
        });
        let diaban = $("#cbDiaban").dxSelectBox("instance").option("value");
        axios
            .post("exportDataProductionPlanreport", {
                location: location,
                year: nam.getFullYear(),
                bieumau: cbBieuMau.option("value"),
                loaisolieu: $("#cbSoLieu")
                    .dxSelectBox("instance")
                    .option("value"),
                namelocation: $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("text"),
                diaban: diaban,
            })
            .then((res) => {
                Swal.close();
                window.open("Download/ChitieuNN.xlsx");
            })
            .catch((err) => {
                Swal.close();
                console.log(err);
            });
    });
}
function restInputBieumau() {
    $("#txtTenbieumau").val("");
    $("#fileBieumau").val(null);
    $(".fileBM").text("");
    $("#chkApdung").prop("checked", true);
    idBieumau = undefined;
}
async function loadBieumau() {
    await axios
        .get("danhsachBieumau/1")
        .then((res) => {
            let data = res.data;

            danhsachBieumau = data.map((item) => {
                return {
                    Name: item.name,
                    loai: Number(item.loai),
                    function: (e) => {
                        let location = $("#cbHuyen")
                            .dxSelectBox("instance")
                            .option("value");
                        let nam = $("#cbNam")
                            .dxDateBox("instance")
                            .option("value");
                        let province = $("#cbHuyen")
                            .dxSelectBox("instance")
                            .option("text");
                        let loaisolieu = $("#cbSoLieu")
                            .dxSelectBox("instance")
                            .option("text");
                        Swal.fire({
                            title:
                                "Đang tải dữ liệu vui lòng chờ trong giây lát",
                            text: "Đang tải dữ liệu vui lòng chờ",
                            icon: "info",
                            showConfirmButton: false,
                        });
                        let diaban = $("#cbDiaban")
                            .dxSelectBox("instance")
                            .option("value");
                        axios
                            .post("reportofProductionPlanreport", {
                                location: location,
                                year: nam.getFullYear(),
                                bieumau: cbBieuMau.option("value"),
                                loaisolieu: $("#cbSoLieu")
                                    .dxSelectBox("instance")
                                    .option("value"),
                                namelocation: $("#cbHuyen")
                                    .dxSelectBox("instance")
                                    .option("text"),
                                diaban: diaban,
                            })
                            .then((res) => {
                                Swal.close();
                                let para = new Map();
                                /* para.set("date", nam.getDate());
                                para.set("month", nam.getMonth() + 1);
                                para.set("year", nam.getFullYear());
                                para.set("location", province);*/
                                Ultil.ShowReportData(
                                    `../report/${item.filename}`,
                                    res.data,
                                    para,
                                    "report",
                                    true,
                                    false
                                );

                                $("#modelDanhsachBieumau").modal("toggle");
                            })
                            .catch((err) => {
                                Swal.close();
                                console.log(err);
                            });
                    },
                };
            });

            initBieumau(danhsachBieumau, "containerBieumau");
            $("#modelBieumau").modal("show");
        })
        .catch((err) => {
            console.error(err);
        });
}
async function loadDanhsachBieumau() {
    let gridBieumau = $("#gridBieumau");
    await axios
        .get("danhsachBieumau")
        .then((res) => {
            let Result = res.data;

            gridBieumau.empty();
            danhsachBieumau.length = 0;
            let count = 0;
            Result.forEach((item) => {
                count++;
                danhsachBieumau.push(item);
                gridBieumau.append(`<tr style=" display:${
                    count > 4 ? "none" : ""
                }">
                <td scope="row">${item.name}</td>
                <td>${item.filename}</td>
                <td><input type="checkbox" ${
                    item.apdung == "1" ? "checked" : ""
                } value="${item.id}"/></td>
                <td><button data-id="${
                    item.id
                }" class="btn btn-sm btn-danger btnDelBieumau"><i class="fas fa-trash fa-sm fa-fw"></i></button>
                <button data-id="${
                    item.id
                }" class="btn btn-sm btn-info btnEditBieumau"><i class="fas fa-edit fa-sm fa-fw"></i></button>
                </td>
            </tr>`);
            });

            var rowsShown = 4;
            // lay so luong cac dong trong bang
            var rowsTotal = $("#tableBieumau tbody tr").length;
            var numPages = rowsTotal / rowsShown; // so trang bang tong so dong/so luong dong tren 1 trang

            // Hien thi so trang
            $("#nav").html("");
            for (let i = 0; i < numPages; i++) {
                var pageNum = i + 1;
                $("#nav").append(
                    '<a href="#" rel="' + i + '">' + pageNum + "</a> "
                );
            }
            // An cot
            $("#data tbody tr").hide();
            $("#data tbody tr").slice(0, rowsShown).show();
            $("#nav a:first").addClass("active");
            $("#nav a").bind("click", function (e) {
                e.preventDefault();
                $("#nav a").removeClass("active");
                $(this).addClass("active");
                var currPage = $(this).attr("rel");
                var startItem = currPage * rowsShown;
                var endItem = startItem + rowsShown;
                $("#tableBieumau tbody tr")
                    .css("opacity", "0.0")
                    .hide()
                    .slice(startItem, endItem)
                    .css("display", "table-row")
                    .animate({ opacity: 1 }, 300);
            });

            // Gan cac chuc nang
            let btnDelBieumau = document.getElementsByClassName(
                "btnDelBieumau"
            );
            for (let index = 0; index < btnDelBieumau.length; index++) {
                const btnDel = btnDelBieumau[index];
                btnDel.onclick = function (e) {
                    axios
                        .post("danhsachBieumau/del", {
                            id: btnDel.dataset.id,
                        })
                        .then((res) => {
                            if (res.data["code"] == 200) {
                                loadDanhsachBieumau();
                                loadBieumau();
                            } else {
                                Swal.fire(
                                    "Đã xảy ra lỗi không xóa được",
                                    "Lỗi",
                                    "error"
                                );
                            }
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                };
            }

            let btnEditBieumau = document.getElementsByClassName(
                "btnEditBieumau"
            );
            // Nut sua bieu mau
            for (let index = 0; index < btnEditBieumau.length; index++) {
                const btn = btnEditBieumau[index];
                btn.onclick = function (e) {
                    let index = danhsachBieumau.findIndex(
                        (item) => item.id == btn.dataset.id
                    );
                    let bieumau = danhsachBieumau[index];
                    $("#txtTenbieumau").val(bieumau.name);
                    $(".fileBM").text(bieumau.filename);
                    if (bieumau.apdung == "1") {
                        $("#chkApdung").prop("checked", true);
                    } else {
                        $("#chkApdung").prop("checked", false);
                    }
                    cbLoaibieumau.option("value", bieumau.loai);
                    idBieumau = btn.dataset.id;
                    $("#modalBieumau").modal("show");
                };
            }
        })
        .catch((err) => {
            console.log(err);
        });
}
