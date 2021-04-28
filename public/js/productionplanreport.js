import Ultil, { initBieumau } from "../js/Ultil.js";
import { process } from "../js/viewProductPlanReport.js";
import { processReport } from "../js/viewReport.js";
var cbBieuMau;
var danhsachBieumau;
var idBieumau = undefined;
var cbLoaibieumau;
var cbLoaisolieu;
var diaban = 1;
var loaimau = 1;
var madonvi;
var mahuyen;
$(document).ready(() => {
    if ($("#productionplanreport").length) {
        loadData();
        initEvent();
        if (localStorage.isphongban != 0) {
            //$("#selectbox").hide();
        }
        madonvi = $("#madonvi").val();
        mahuyen = $("#mahuyen").val();
        // thiết lập cho chư puh
        if (madonvi == 20 || mahuyen == 20) {
            $("#btnGiaTri").hide();
        }
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
        itemTemplate: function (data) {
            return (
                "<div class='custom-item' title='" +
                data.tendonvi +
                "'>" +
                data.tendonvi +
                "</div>"
            );
        },
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
        itemTemplate: function (data) {
            return (
                "<div class='custom-item' title='" +
                data.tenbieumau +
                "'>" +
                data.tenbieumau +
                "</div>"
            );
        },
    });

    cbBieuMau = $("#cbBieumau").dxSelectBox("instance");

    $("#cbNam").dxDateBox({
        value: new Date(),
    });
    $("#cbDiaban").dxSelectBox({
        dataSource: [
            { id: 3, name: "Tỉnh" },
            { id: 1, name: "Huyện" },
            { id: 2, name: "Xã" },
        ],
        displayExpr: "name",
        valueExpr: "id",
        onValueChanged: (e) => {
            diaban = e.value;
            changevalue();
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
            } else if (e.value == 2) {
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
            } else {
                axios
                    .get("danhsachTinh")
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

    Ultil.ShowReport("../public/report/ReportCTKT.mrt", "report", false);
}
function changevalue() {
    axios
        .get("danhsachbieumau")
        .then((res) => {
            let result = res.data;
            let myresult = [];
            result.forEach((item) => {
                //if((diaban==1 && item.id!=238&&item.id!=237&&item.id!=241)||(diaban==3&&item.id!=233)||(diaban==2 && item.id!=238&&item.id!=237&&item.id!=241&&item.id!=233))
                myresult.push(item);
            });
            $("#cbBieumau")
                .dxSelectBox("instance")
                .option("dataSource", myresult);
        })
        .catch((err) => {
            console.error(err);
        });
    cbBieuMau = $("#cbBieumau").dxSelectBox("instance");
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

    $("#btnGiaiDoan").on("click", async () => {
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
        let report = await process(
            location,
            nam.getFullYear(),
            cbBieuMau.option("value"),
            cbBieuMau.option("value"),
            $("#cbSoLieu").dxSelectBox("instance").option("value"),
            $("#cbHuyen").dxSelectBox("instance").option("text"),
            diaban
        );
        axios
            .get("danhsachBieumau/3/" + diaban)
            .then((ress) => {
                let data = ress.data;
                let maubaocao = data[0].filename;

                let para = new Map();
                /* para.set("date", nam.getDate());
                                            para.set("month", nam.getMonth() + 1);
                                            para.set("year", nam.getFullYear());
                                            para.set("location", province);*/
                Ultil.ShowReportData(
                    `../public/report/` + maubaocao,
                    report,
                    para,
                    "report",
                    false,
                    false
                );
            })
            .catch((err) => {
                console.log(err);
            });
    });
    $("#btnGiaTri").on("click", async () => {
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
        let report = await process(
            location,
            nam.getFullYear(),
            cbBieuMau.option("value"),
            cbBieuMau.option("value"),
            $("#cbSoLieu").dxSelectBox("instance").option("value"),
            $("#cbHuyen").dxSelectBox("instance").option("text"),
            diaban
        );
        let para = new Map();
        /* para.set("date", nam.getDate());
                                            para.set("month", nam.getMonth() + 1);
                                            para.set("year", nam.getFullYear());
                                            para.set("location", province);*/
        Ultil.ShowReportData(
            `../public/report/chuyendt.mrt`,
            report,
            para,
            "report",
            false,
            false
        );
    });

    $("#btnThembieumau").on("click", function (e) {
        loadDanhsachBieumau();
        restInputBieumau();
        $("#modalThembieumau").modal("show");
    });

    madonvi = $("#madonvi").val();
    mahuyen = $("#mahuyen").val();
    var list = [
        {
            id: "1",
            ids: "btnGiaTri",
            Name: "Xuất báo cáo tổng hợp KTXH",
        },
        {
            id: "2",
            ids: "baocaogiaidoan",
            Name: "Báo cáo giai đoạn",
        },
        {
            id: "3",
            ids: "baocaogiatri",
            Name: "Báo cáo giá trị sản xuất",
        },
    ];
    if (mahuyen == 20 || madonvi == 20) {
        list = [
            {
                id: "1",
                ids: "btnGiaTri",
                Name: "Xuất báo cáo tổng hợp KTXH",
            },
            {
                id: "2",
                ids: "baocaogiaidoan",
                Name: "Báo cáo giai đoạn",
            },
            {
                id: "3",
                ids: "baocaogiatri",
                Name: "Báo cáo giá trị sản xuất",
            },
            {
                id: "4",
                ids: "baocaocayanqua",
                Name: "Báo cáo cây ăn quả",
            },
        ];
    }
    $("#selectbox").dxDropDownButton({
        text: "Xuất báo cáo tổng hợp KTXH",
        icon: "export",
        stylingMode: "contained",
        type: "success",
        items: list,
        elementAttr: {
            class: "btn btn-primary",
        },
        itemTemplate: function (data) {
            if (data.id == 1) {
                return (
                    "<div class='custom-item'id='" +
                    data.ids +
                    "' title='" +
                    data.Name +
                    "'>" +
                    data.Name +
                    "</div>"
                );
            } else if (data.id == 2) {
                return (
                    "<div class='custom-item' id='" +
                    data.ids +
                    "' title='" +
                    data.Name +
                    "'>" +
                    data.Name +
                    "</div>"
                );
            } else if (data.id == 3) {
                return (
                    "<div class='custom-item' id='" +
                    data.ids +
                    "' title='" +
                    data.Name +
                    "'>" +
                    data.Name +
                    "</div>"
                );
            } else if (data.id == 4) {
                return (
                    "<div class='custom-item' id='" +
                    data.ids +
                    "' title='" +
                    data.Name +
                    "'>" +
                    data.Name +
                    "</div>"
                );
            }
        },
        onItemClick: function (e) {
            let bieumau = cbBieuMau.option("value");
            if (
                bieumau == 248 ||
                bieumau == 233 ||
                bieumau == 238 ||
                bieumau == 272
            ) {
            } else {
                alert(
                    "Bạn chọn sai biểu mẫu để xuất dữ liệu! Vui lòng chọn lại!"
                );
                Swal.close();
                return;
            }
            if (e.itemData.id == 1) {
                let location = $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("value");
                let nam = $("#cbNam").dxDateBox("instance").option("value");
                let province = $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("text");
                let loaisolieu = $("#cbSoLieu")
                    .dxSelectBox("instance")
                    .option("text");

                Swal.fire({
                    title: "Đang tải báo cáo vui lòng chờ trong giây lát",
                    text: "Đang tải báo cáo vui lòng chờ",
                    icon: "info",
                    showConfirmButton: false,
                });

                let diaban = $("#cbDiaban")
                    .dxSelectBox("instance")
                    .option("value");
                let maubaocao = "Mau.xlsx";
                //if(diaban==1)maubaocao='Mau_huyen.xlsx';
                axios
                    .get("danhsachBieumau/4/" + diaban)
                    .then((ress) => {
                        let data = ress.data;
                        maubaocao = data[0].filename;
                        axios
                            .post("reportofsanxuat", {
                                location: location,
                                year: nam.getFullYear(),
                                mau: maubaocao,
                                loaimau: 1,
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
                                // Swal.close();
                                // window.location = "/export/"+res.data;
                            })
                            .catch((err) => {
                                Swal.close();
                                console.log(err);
                            });
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            } else if (e.itemData.id == 2) {
                let location = $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("value");
                let nam = $("#cbNam").dxDateBox("instance").option("value");
                let province = $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("text");
                let loaisolieu = $("#cbSoLieu")
                    .dxSelectBox("instance")
                    .option("text");

                Swal.fire({
                    title: "Đang tải báo cáo vui lòng chờ trong giây lát",
                    text: "Đang tải báo cáo vui lòng chờ",
                    icon: "info",
                    showConfirmButton: false,
                });

                let diaban = $("#cbDiaban")
                    .dxSelectBox("instance")
                    .option("value");

                let maubaocao = "giaidoan_huyen.xlsx";
                if (diaban == 1) maubaocao = "giaidoan_huyen.xlsx";
                if (diaban == 3) maubaocao = "giaidoan_tinh.xlsx";
                axios
                    .get("danhsachBieumau/5/" + diaban)
                    .then((ress) => {
                        let data = ress.data;
                        maubaocao = data[0].filename;
                        axios
                            .post("reportofsanxuat", {
                                location: location,
                                year: nam.getFullYear(),
                                mau: maubaocao,
                                loaimau: 1,
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
                                //window.location = "/export/" + res.data;
                            })
                            .catch((err) => {
                                Swal.close();
                                console.log(err);
                            });
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            } else if (e.itemData.id == 3) {
                let location = $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("value");
                let nam = $("#cbNam").dxDateBox("instance").option("value");
                let province = $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("text");
                let loaisolieu = $("#cbSoLieu")
                    .dxSelectBox("instance")
                    .option("text");

                Swal.fire({
                    title: "Đang tải báo cáo vui lòng chờ trong giây lát",
                    text: "Đang tải báo cáo vui lòng chờ",
                    icon: "info",
                    showConfirmButton: false,
                });

                let diaban = $("#cbDiaban")
                    .dxSelectBox("instance")
                    .option("value");

                let maubaocao = "gtsx_dahuoai.xlsx";

                axios
                    .get("danhsachBieumau/6/" + diaban)
                    .then((ress) => {
                        let data = ress.data;
                        maubaocao = data[0].filename;
                        axios
                            .post("reportofsanxuat", {
                                location: location,
                                year: nam.getFullYear(),
                                mau: maubaocao,
                                loaimau: 1,
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
                                //window.location = "/export/" + res.data;
                            })
                            .catch((err) => {
                                Swal.close();
                                console.log(err);
                            });
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
            // cay an qua
            else if (e.itemData.id == 4) {
                let location = $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("value");
                let nam = $("#cbNam").dxDateBox("instance").option("value");
                let province = $("#cbHuyen")
                    .dxSelectBox("instance")
                    .option("text");
                let loaisolieu = $("#cbSoLieu")
                    .dxSelectBox("instance")
                    .option("text");

                Swal.fire({
                    title: "Đang tải báo cáo vui lòng chờ trong giây lát",
                    text: "Đang tải báo cáo vui lòng chờ",
                    icon: "info",
                    showConfirmButton: false,
                });

                let diaban = $("#cbDiaban")
                    .dxSelectBox("instance")
                    .option("value");

                let maubaocao = "cayanqua_chupuh.xlsx";

                axios
                    .get("danhsachBieumau/7/" + diaban)
                    .then((ress) => {
                        let data = ress.data;
                        maubaocao = data[0].filename;
                        axios
                            .post("reportofsanxuat", {
                                location: location,
                                year: nam.getFullYear(),
                                mau: maubaocao,
                                loaimau: 2,
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
                                //window.location = "/export/" + res.data;
                            })
                            .catch((err) => {
                                Swal.close();
                                console.log(err);
                            });
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        },
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
        .get("danhsachBieumau/1/1")
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
                            .post(
                                "getDataViewReport",
                                {
                                    location: location,
                                    year: nam.getFullYear(),
                                    bieumau: cbBieuMau.option("value"),
                                    loaisolieu: loaisolieu,
                                    namelocation: $("#cbHuyen")
                                        .dxSelectBox("instance")
                                        .option("text"),
                                    diaban: diaban,
                                },
                                { timeout: 90000000 }
                            )
                            .then((res) => {
                                processReport(
                                    location,
                                    nam.getFullYear(),
                                    cbBieuMau.option("value"),
                                    cbBieuMau.option("value"),
                                    $("#cbSoLieu")
                                        .dxSelectBox("instance")
                                        .option("value"),
                                    $("#cbHuyen")
                                        .dxSelectBox("instance")
                                        .option("text"),
                                    diaban,
                                    cbBieuMau.option("text"),
                                    res.data
                                ).then((value) => {
                                    console.log("viewReport", value);
                                    let para = new Map();
                                    Ultil.ShowReportData(
                                        `../public/report/${item.filename}`,
                                        value,
                                        para,
                                        "report",
                                        false,
                                        false
                                    );

                                    Swal.close();
                                    $("#modelDanhsachBieumau").modal("toggle");
                                });
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
