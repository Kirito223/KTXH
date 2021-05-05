import Ultil, { initBieumau } from "../js/Ultil.js";
import { process } from "../js/viewProductPlanReport.js";
import { processReport } from "../js/viewReport.js";
import xuatgiaidoan from "./xuatbaocaogiadoan.js";

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

    // Ultil.ShowReport("../public/report/ReportCTKT.mrt", "report", false);
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
                                loaibaocao: 1,
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
                if (diaban == 1) {
                    maubaocao = "giaidoan_huyen.xlsx";
                }

                if (diaban == 3) {
                    maubaocao = "giaidoan_tinh.xlsx";
                }

                axios
                    .get("danhsachBieumau/5/" + diaban)
                    .then((ress) => {
                        let data = ress.data;
                        // Lay mau excel
                        maubaocao = data[0].filename;
                        axios
                            .post("loadDataReport", {
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
                                // Bao cao giai doan cua huyen chupuh
                                if (
                                    res.data.donvicha == 20 ||
                                    res.data.madonvi == 20
                                ) {
                                    if (diaban == 1) {
                                        let data = res.data;
                                        // Tong Hop du lieu
                                        let xuatbaocao = new xuatgiaidoan();
                                        xuatbaocao.solieutheobieu =
                                            data.solieutheobieu;
                                        xuatbaocao.chitietsolieu =
                                            data.chitietsolieutheobieu;
                                        xuatbaocao.donvihanhchinh =
                                            data.donvihanhchinh;
                                        let donvicha = data.donvicha;
                                        let madonvi = data.madonvi;

                                        let baocao = {
                                            phan1: null,
                                            phan2: null,
                                            phan3: null,
                                        };

                                        // Danh sach chi tieu PHÒNG TÀI CHÍNH TỪ DÒNG
                                        let bieumau = 223;
                                        let chitieu = [
                                            3029,
                                            3077,
                                            3030,
                                            3031,
                                            3032,
                                            3033,
                                            3069,
                                            3070,
                                            3034,
                                            3071,
                                            3074,
                                            3075,
                                            3076,
                                            3072,
                                            3035,
                                            3073,
                                            3159,
                                            3160,
                                            3161,
                                        ];
                                        let year = nam.getFullYear();

                                        let Phan1 = [];
                                        let mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        let indexStart = 0;
                                        let indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan1[indexStart] = elementStart;
                                            Phan1[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }
                                        baocao.phan1 = Phan1;

                                        //CHỈ TIÊU XÃ HỘI HUYỆN
                                        bieumau = 237;
                                        chitieu = [
                                            1633,
                                            1642,
                                            2998,
                                            2999,
                                            3000,
                                            3001,
                                            3023,
                                            3002,
                                            3003,
                                            3004,
                                            3005,
                                            3006,
                                            3007,
                                            3008,
                                            3009,
                                            3010,
                                            3011,
                                            3012,
                                            3013,
                                            3014,
                                            3015,
                                            3016,
                                            3017,
                                            3020,
                                            3021,
                                            3018,
                                            3019,
                                        ];

                                        let Phan2 = [];
                                        mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        indexStart = 0;
                                        indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan2[indexStart] = elementStart;
                                            Phan2[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }

                                        baocao.phan2 = Phan2;

                                        //NÔNG LÂM THỦY SẢN

                                        bieumau = 277;
                                        chitieu = [
                                            2980,
                                            2640,
                                            2641,
                                            2642,
                                            2643,
                                            2644,
                                            2645,
                                            2646,
                                            2647,
                                            2648,
                                            2649,
                                            2650,
                                            2651,
                                            2652,
                                            2653,
                                            2654,
                                            2655,
                                            2672,
                                            2679,
                                            2680,
                                            2673,
                                            2684,
                                            2674,
                                            2675,
                                            2676,
                                            2677,
                                            2678,
                                            2323,
                                            2354,
                                            2355,
                                            2356,
                                            2357,
                                            2360,
                                            2361,
                                            2362,
                                            2358,
                                            2363,
                                            2364,
                                            2365,
                                            2366,
                                            2367,
                                            2368,
                                            2369,
                                            2370,
                                            2371,
                                            2374,
                                            2375,
                                            2376,
                                            2378,
                                            2379,
                                            2380,
                                            2381,
                                            2382,
                                            2383,
                                            2384,
                                            2385,
                                            2386,
                                            2387,
                                            2388,
                                            2389,
                                            2390,
                                            2391,
                                            2392,
                                            2393,
                                            2394,
                                            2395,
                                            2396,
                                            2397,
                                            2398,
                                            2399,
                                            2400,
                                            2401,
                                            2402,
                                            2403,
                                            2404,
                                            2405,
                                            2406,
                                            2407,
                                            2408,
                                            2409,
                                            2410,
                                            2411,
                                            2412,
                                            2413,
                                            2414,
                                            2415,
                                            2416,
                                            2417,
                                            2348,
                                            2418,
                                            2420,
                                            2421,
                                            2423,
                                            2424,
                                            2425,
                                            2426,
                                            2349,
                                            2428,
                                            2429,
                                            2430,
                                            2431,
                                            2432,
                                            2433,
                                            2434,
                                            2435,
                                            2436,
                                            2437,
                                            2438,
                                            2442,
                                            2443,
                                            2444,
                                            2445,
                                            2350,
                                            2449,
                                            2450,
                                            2451,
                                            2452,
                                            2453,
                                            2454,
                                            2330,
                                            2681,
                                            2682,
                                            2683,
                                            2331,
                                            2458,
                                            2459,
                                            2460,
                                            2477,
                                            2478,
                                            2479,
                                            2527,
                                            2528,
                                            2529,
                                            1625,
                                            2594,
                                            1626,
                                            2599,
                                            1627,
                                        ];

                                        let Phan3 = [];
                                        mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        indexStart = 0;
                                        indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan3[indexStart] = elementStart;
                                            Phan3[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }

                                        baocao.phan3 = Phan3;

                                        //PHÒNG KINH TẾ HẠ TẦNG
                                        bieumau = 220;
                                        chitieu = [
                                            1704,
                                            1707,
                                            1708,
                                            1705,
                                            1709,
                                            1710,
                                            1713,
                                            1714,
                                            1711,
                                            1715,
                                            1716,
                                            1706,
                                            1717,
                                            1718,
                                            1721,
                                            1722,
                                            1719,
                                            1720,
                                            1731,
                                            1562,
                                            1566,
                                            1567,
                                            1606,
                                            1607,
                                            1608,
                                            1609,
                                            1610,
                                            1612,
                                            1616,
                                            1617,
                                            1618,
                                            1619,
                                            1620,
                                            1621,
                                            1622,
                                            1623,
                                            5129,
                                        ];

                                        let Phan4 = [];
                                        mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        indexStart = 0;
                                        indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan4[indexStart] = elementStart;
                                            Phan4[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }

                                        baocao.phan4 = Phan4;

                                        //CHỈ TIÊU XÃ HỘI TỈNH
                                        bieumau = 237;
                                        chitieu = [
                                            3164,
                                            3165,
                                            3166,
                                            3167,
                                            3168,
                                            3169,
                                            3170,
                                            3171,
                                            3172,
                                            3173,
                                            3174,
                                            3175,
                                            3176,
                                            3177,
                                            3178,
                                            3179,
                                            3180,
                                            3181,
                                            3182,
                                            3183,
                                            3184,
                                            3185,
                                            3186,
                                            3187,
                                            3188,
                                            3190,
                                            3191,
                                            3192,
                                            3193,
                                            3194,
                                            3195,
                                            3196,
                                        ];

                                        let Phan5 = [];
                                        mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        indexStart = 0;
                                        indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan5[indexStart] = elementStart;
                                            Phan5[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }

                                        baocao.phan5 = Phan5;
                                        return {
                                            baocao: baocao,
                                            donvicha: donvicha,
                                            madonvi: madonvi,
                                        };
                                    }
                                    // Bao cao giai doan tinh
                                    if (diaban == 3) {
                                        let data = res.data;
                                        // Tong Hop du lieu
                                        let xuatbaocao = new xuatgiaidoan();
                                        xuatbaocao.solieutheobieu =
                                            data.solieutheobieu;
                                        xuatbaocao.chitietsolieu =
                                            data.chitietsolieutheobieu;
                                        xuatbaocao.donvihanhchinh =
                                            data.donvihanhchinh;
                                        let donvicha = data.donvicha;

                                        let baocao = {
                                            phan1: null,
                                            phan2: null,
                                            phan3: null,
                                        };

                                        // Danh sach chi tieu PHÒNG TÀI CHÍNH TỪ DÒNG
                                        let bieumau = 223;
                                        let chitieu = [
                                            3029,
                                            3077,
                                            3030,
                                            3031,
                                            3032,
                                            3033,
                                            3069,
                                            3070,
                                            3034,
                                            3071,
                                            3074,
                                            3075,
                                            3076,
                                            3072,
                                            3035,
                                            3073,
                                            3159,
                                            3160,
                                            3161,
                                        ];
                                        let year = nam.getFullYear();

                                        let Phan1 = [];
                                        let mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        let indexStart = 0;
                                        let indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan1[indexStart] = elementStart;
                                            Phan1[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }
                                        baocao.phan1 = Phan1;

                                        //CHỈ TIÊU XÃ HỘI HUYỆN
                                        bieumau = 237;
                                        chitieu = [
                                            1633,
                                            1642,
                                            2998,
                                            2999,
                                            3000,
                                            3001,
                                            3023,
                                            3002,
                                            3003,
                                            3004,
                                            3005,
                                            3006,
                                            3007,
                                            3008,
                                            3009,
                                            3010,
                                            3011,
                                            3012,
                                            3013,
                                            3014,
                                            3015,
                                            3016,
                                            3017,
                                            3020,
                                            3021,
                                            3018,
                                            3019,
                                        ];

                                        let Phan2 = [];
                                        mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        indexStart = 0;
                                        indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan2[indexStart] = elementStart;
                                            Phan2[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }

                                        baocao.phan2 = Phan2;

                                        //NÔNG LÂM THỦY SẢN

                                        bieumau = 277;
                                        chitieu = [
                                            2980,
                                            2640,
                                            2641,
                                            2642,
                                            2643,
                                            2644,
                                            2645,
                                            2646,
                                            2647,
                                            2648,
                                            2649,
                                            2650,
                                            2651,
                                            2652,
                                            2653,
                                            2654,
                                            2655,
                                            2672,
                                            2679,
                                            2680,
                                            2673,
                                            2684,
                                            2674,
                                            2675,
                                            2676,
                                            2677,
                                            2678,
                                            2323,
                                            2354,
                                            2355,
                                            2356,
                                            2357,
                                            2360,
                                            2361,
                                            2362,
                                            2358,
                                            2363,
                                            2364,
                                            2365,
                                            2366,
                                            2367,
                                            2368,
                                            2369,
                                            2370,
                                            2371,
                                            2374,
                                            2375,
                                            2376,
                                            2378,
                                            2379,
                                            2380,
                                            2381,
                                            2382,
                                            2383,
                                            2384,
                                            2385,
                                            2386,
                                            2387,
                                            2388,
                                            2389,
                                            2390,
                                            2391,
                                            2392,
                                            2393,
                                            2394,
                                            2395,
                                            2396,
                                            2397,
                                            2398,
                                            2399,
                                            2400,
                                            2401,
                                            2402,
                                            2403,
                                            2404,
                                            2405,
                                            2406,
                                            2407,
                                            2408,
                                            2409,
                                            2410,
                                            2411,
                                            2412,
                                            2413,
                                            2414,
                                            2415,
                                            2416,
                                            2417,
                                            2348,
                                            2418,
                                            2420,
                                            2421,
                                            2423,
                                            2424,
                                            2425,
                                            2426,
                                            2349,
                                            2428,
                                            2429,
                                            2430,
                                            2431,
                                            2432,
                                            2433,
                                            2434,
                                            2435,
                                            2436,
                                            2437,
                                            2438,
                                            2442,
                                            2443,
                                            2444,
                                            2445,
                                            2350,
                                            2449,
                                            2450,
                                            2451,
                                            2452,
                                            2453,
                                            2454,
                                            2330,
                                            2681,
                                            2682,
                                            2683,
                                            2331,
                                            2458,
                                            2459,
                                            2460,
                                            2477,
                                            2478,
                                            2479,
                                            2527,
                                            2528,
                                            2529,
                                            1625,
                                            2594,
                                            1626,
                                            2599,
                                            1627,
                                        ];

                                        let Phan3 = [];
                                        mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        indexStart = 0;
                                        indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan3[indexStart] = elementStart;
                                            Phan3[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }

                                        baocao.phan3 = Phan3;

                                        //PHÒNG KINH TẾ HẠ TẦNG
                                        bieumau = 220;
                                        chitieu = [
                                            1704,
                                            1707,
                                            1708,
                                            1705,
                                            1709,
                                            1710,
                                            1713,
                                            1714,
                                            1711,
                                            1715,
                                            1716,
                                            1706,
                                            1717,
                                            1718,
                                            1721,
                                            1722,
                                            1719,
                                            1720,
                                            1731,
                                            1562,
                                            1566,
                                            1567,
                                            1606,
                                            1607,
                                            1608,
                                            1609,
                                            1610,
                                            1612,
                                            1616,
                                            1617,
                                            1618,
                                            1619,
                                            1620,
                                            1621,
                                            1622,
                                            1623,
                                            5129,
                                        ];

                                        let Phan4 = [];
                                        mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        indexStart = 0;
                                        indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan4[indexStart] = elementStart;
                                            Phan4[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }

                                        baocao.phan4 = Phan4;

                                        //CHỈ TIÊU XÃ HỘI TỈNH
                                        bieumau = 237;
                                        chitieu = [
                                            3164,
                                            3165,
                                            3166,
                                            3167,
                                            3168,
                                            3169,
                                            3170,
                                            3171,
                                            3172,
                                            3173,
                                            3174,
                                            3175,
                                            3176,
                                            3177,
                                            3178,
                                            3179,
                                            3180,
                                            3181,
                                            3182,
                                            3183,
                                            3184,
                                            3185,
                                            3186,
                                            3187,
                                            3188,
                                            3190,
                                            3191,
                                            3192,
                                            3193,
                                            3194,
                                            3195,
                                            3196,
                                        ];

                                        let Phan5 = [];
                                        mid = Math.floor(
                                            (chitieu.length - 1) / 2
                                        );
                                        indexStart = 0;
                                        indexEnd = mid;
                                        while (
                                            indexStart < mid &&
                                            indexEnd < chitieu.length
                                        ) {
                                            let elementStart = xuatbaocao.total(
                                                year,
                                                chitieu[indexStart],
                                                bieumau,
                                                donvicha
                                            );

                                            let elementEnd = xuatbaocao.total(
                                                year,
                                                chitieu[indexEnd],
                                                bieumau,
                                                donvicha
                                            );

                                            Phan5[indexStart] = elementStart;
                                            Phan5[indexEnd] = elementEnd;

                                            indexStart++;
                                            indexEnd++;
                                        }

                                        baocao.phan5 = Phan5;
                                        return {
                                            baocao: baocao,
                                            donvicha: donvicha,
                                            madonvi: madonvi,
                                        };
                                    }
                                }

                                if (
                                    res.data.donvicha == 60 ||
                                    res.data.madonvi == 60
                                ) {
                                    let data = res.data;
                                    // Tong Hop du lieu
                                    let xuatbaocao = new xuatgiaidoan();
                                    xuatbaocao.solieutheobieu =
                                        data.solieutheobieu;
                                    xuatbaocao.chitietsolieu =
                                        data.chitietsolieutheobieu;
                                    xuatbaocao.donvihanhchinh =
                                        data.donvihanhchinh;
                                    let donvicha = data.donvicha;
                                    let madonvi = data.madonvi;
                                    let baocao = {
                                        phan1: null,
                                        phan2: null,
                                        phan3: null,
                                    };

                                    // nong lam thuy san
                                    let bieumau = 255;
                                    let chitieu = [
                                        2194,
                                        2195,
                                        2196,
                                        3252,
                                        2197,
                                        2198,
                                        2200,
                                        2201,
                                        2202,
                                        2203,
                                        2204,
                                        2205,
                                        2206,
                                        2207,
                                        2208,
                                        2850,
                                        2199,
                                        3253,
                                        3254,
                                        2851,
                                        2852,
                                        2853,
                                        2854,
                                        2855,
                                        2856,
                                        2857,
                                        2858,
                                        2859,
                                        2945,
                                        2716,
                                        2717,
                                        2718,
                                        2719,
                                        2720,
                                        2721,
                                        2722,
                                        2723,
                                        2724,
                                        2725,
                                        2726,
                                        2727,
                                        2728,
                                        2864,
                                        2865,
                                        2694,
                                        2806,
                                        2807,
                                        2808,
                                        2809,
                                        2810,
                                        2811,
                                        2812,
                                        2813,
                                        2695,
                                        2814,
                                        2816,
                                        2815,
                                        2817,
                                        2818,
                                        2820,
                                        2824,
                                        2825,
                                        2827,
                                        2828,
                                        2829,
                                        2830,
                                        2821,
                                        2831,
                                        2832,
                                        2833,
                                        2834,
                                        2835,
                                        2836,
                                        2837,
                                        2838,
                                        2839,
                                        2842,
                                        2822,
                                        2840,
                                        2841,
                                        2866,
                                        2843,
                                        2844,
                                        2823,
                                        2845,
                                        2846,
                                        2847,
                                        2790,
                                        2791,
                                        2792,
                                        2793,
                                    ];
                                    let year = nam.getFullYear();

                                    let Phan1 = [];
                                    let mid = Math.floor(
                                        (chitieu.length - 1) / 2
                                    );
                                    let indexStart = 0;
                                    let indexEnd = mid;
                                    while (
                                        indexStart < mid &&
                                        indexEnd < chitieu.length
                                    ) {
                                        let elementStart = xuatbaocao.total(
                                            year,
                                            chitieu[indexStart],
                                            bieumau,
                                            donvicha
                                        );

                                        let elementEnd = xuatbaocao.total(
                                            year,
                                            chitieu[indexEnd],
                                            bieumau,
                                            donvicha
                                        );

                                        Phan1[indexStart] = elementStart;
                                        Phan1[indexEnd] = elementEnd;

                                        indexStart++;
                                        indexEnd++;
                                    }
                                    baocao.phan1 = Phan1;

                                    bieumau = 226;
                                    chitieu = [
                                        2209,
                                        2210,
                                        2211,
                                        2212,
                                        2213,
                                        2214,
                                        2215,
                                        2216,
                                        2217,
                                        2218,
                                        2219,
                                        2220,
                                        2221,
                                        2222,
                                        2223,
                                        2224,
                                        2225,
                                        2226,
                                        2227,
                                        2228,
                                        2229,
                                        2230,
                                        2231,
                                        2232,
                                        2233,
                                        2234,
                                        2235,
                                        2236,
                                        2237,
                                        2238,
                                        2239,
                                    ];

                                    let Phan2 = [];
                                    mid = Math.floor((chitieu.length - 1) / 2);
                                    indexStart = 0;
                                    indexEnd = mid;
                                    while (
                                        indexStart < mid &&
                                        indexEnd < chitieu.length
                                    ) {
                                        let elementStart = xuatbaocao.total(
                                            year,
                                            chitieu[indexStart],
                                            bieumau,
                                            donvicha
                                        );

                                        let elementEnd = xuatbaocao.total(
                                            year,
                                            chitieu[indexEnd],
                                            bieumau,
                                            donvicha
                                        );

                                        Phan2[indexStart] = elementStart;
                                        Phan2[indexEnd] = elementEnd;

                                        indexStart++;
                                        indexEnd++;
                                    }

                                    baocao.phan2 = Phan2;

                                    bieumau = 250;
                                    chitieu = [3200, 2961, 3201, 3202];
                                    let Phan3 = [];
                                    mid = Math.floor((chitieu.length - 1) / 2);
                                    indexStart = 0;
                                    indexEnd = mid;
                                    while (
                                        indexStart < mid &&
                                        indexEnd < chitieu.length
                                    ) {
                                        let elementStart = xuatbaocao.total(
                                            year,
                                            chitieu[indexStart],
                                            bieumau,
                                            donvicha
                                        );

                                        let elementEnd = xuatbaocao.total(
                                            year,
                                            chitieu[indexEnd],
                                            bieumau,
                                            donvicha
                                        );

                                        Phan3[indexStart] = elementStart;
                                        Phan3[indexEnd] = elementEnd;

                                        indexStart++;
                                        indexEnd++;
                                    }

                                    baocao.phan3 = Phan3;

                                    bieumau = 227;
                                    chitieu = [
                                        2946,
                                        2947,
                                        2948,
                                        2949,
                                        2950,
                                        2951,
                                        2952,
                                        2953,
                                        2954,
                                        2955,
                                        2956,
                                        2957,
                                        2958,
                                        2959,
                                        2979,
                                        2964,
                                        2965,
                                        2966,
                                        2967,
                                        2968,
                                        2969,
                                        2970,
                                        2971,
                                        2972,
                                        2973,
                                        2974,
                                        2975,
                                        2976,
                                        2977,
                                        2978,
                                    ];
                                    let Phan4 = [];
                                    mid = Math.floor((chitieu.length - 1) / 2);
                                    indexStart = 0;
                                    indexEnd = mid;
                                    while (
                                        indexStart < mid &&
                                        indexEnd < chitieu.length
                                    ) {
                                        let elementStart = xuatbaocao.total(
                                            year,
                                            chitieu[indexStart],
                                            bieumau,
                                            donvicha
                                        );

                                        let elementEnd = xuatbaocao.total(
                                            year,
                                            chitieu[indexEnd],
                                            bieumau,
                                            donvicha
                                        );

                                        Phan4[indexStart] = elementStart;
                                        Phan4[indexEnd] = elementEnd;

                                        indexStart++;
                                        indexEnd++;
                                    }

                                    baocao.phan4 = Phan4;

                                    return {
                                        baocao: baocao,
                                        donvicha: donvicha,
                                        madonvi: madonvi,
                                    };
                                }

                                if (
                                    res.data.donvicha == 107 ||
                                    res.data.madonvi == 107
                                ) {
                                    let data = res.data;
                                    // Tong Hop du lieu
                                    let xuatbaocao = new xuatgiaidoan();
                                    xuatbaocao.solieutheobieu =
                                        data.solieutheobieu;
                                    xuatbaocao.chitietsolieu =
                                        data.chitietsolieutheobieu;
                                    xuatbaocao.donvihanhchinh =
                                        data.donvihanhchinh;
                                    let donvicha = data.donvicha;
                                    let madonvi = data.madonvi;

                                    if(donvicha == 106){
                                        donvicha = madonvi;
                                    }
                                    let baocao = {
                                        phan1: null,
                                        phan2: null,
                                    };

                                    // Danh sach chi tieu PHÒNG TÀI CHÍNH TỪ DÒNG
                                    let bieumau = 273;
                                    let chitieu = [
                                        4689,
                                        4690,
                                        4691,
                                        4692,
                                        4693,
                                        4694,
                                        4695,
                                        4696,
                                        4697,
                                        4698,
                                        4699,
                                        4700,
                                        4701,
                                        5001,
                                        4702,
                                        4703,
                                        4704,
                                        4706,
                                        4707,
                                        4829,
                                        4830,
                                        4831,
                                        4832,
                                        4833,
                                        4834,
                                        4835,
                                        4836,
                                        4837,
                                        4838,
                                        4839,
                                        4840,
                                        4841,
                                        4842,
                                        4843,
                                        4844,
                                        4522,
                                        4523,
                                        4524,
                                        4525,
                                        4857,
                                        4858,
                                        4859,
                                        4910,
                                        4912,
                                        4860,
                                        4861,
                                        4862,
                                        4925,
                                        4863,
                                        4937,
                                        5057,
                                        4881,
                                        4883,
                                        4884,
                                        4885,
                                        4886,
                                        4887,
                                        4888,
                                        4889,
                                        4890,
                                        4891,
                                        4892,
                                        4893,
                                        4894,
                                        5059,
                                        5063,
                                        5064,
                                        5065,
                                        5066,
                                        5067,
                                        5068,
                                        5060,
                                        5069,
                                        5070,
                                        5071,
                                        5072,
                                        5073,
                                        5074,
                                        5075,
                                        5076,
                                        5077,
                                        5078,
                                        5061,
                                        5079,
                                        5080,
                                        5081,
                                        5082,
                                        5083,
                                        5062,
                                        5084,
                                        5085,
                                        5086,
                                        4555,
                                        4556,
                                        4557,
                                        4558,
                                        5091,
                                        5092,
                                        5093,
                                        5094,
                                        5096,
                                        5097,
                                        5098,
                                        5099,
                                    ];
                                    let year = nam.getFullYear();

                                    let Phan1 = [];
                                    let mid = Math.floor(
                                        (chitieu.length - 1) / 2
                                    );
                                    let indexStart = 0;
                                    let indexEnd = mid;
                                    while (
                                        indexStart < mid &&
                                        indexEnd < chitieu.length
                                    ) {
                                        let elementStart = xuatbaocao.total(
                                            year,
                                            chitieu[indexStart],
                                            bieumau,
                                            donvicha
                                        );

                                        let elementEnd = xuatbaocao.total(
                                            year,
                                            chitieu[indexEnd],
                                            bieumau,
                                            donvicha
                                        );

                                        Phan1[indexStart] = elementStart;
                                        Phan1[indexEnd] = elementEnd;

                                        indexStart++;
                                        indexEnd++;
                                    }
                                    baocao.phan1 = Phan1;

                                    //CHỈ TIÊU XÃ HỘI HUYỆN
                                    bieumau = 262;
                                    chitieu = [
                                        4896,
                                        4897,
                                        4898,
                                        4900,
                                        4901,
                                        4903,
                                        4904,
                                        4906,
                                        4907,
                                        4909,
                                        4911,
                                        4913,
                                        4914,
                                        4915,
                                        4916,
                                        4917,
                                        4918,
                                        4919,
                                        4920,
                                        4921,
                                        4923,
                                        4926,
                                        4927,
                                        4928,
                                        4929,
                                        4930,
                                        4931,
                                        4932,
                                        4933,
                                        4934,
                                        4935,
                                        5054,
                                        5055,
                                        5022,
                                        5023,
                                        5024,
                                        5025,
                                        5026,
                                        5027,
                                        5028,
                                        5029,
                                        5030,
                                        5031,
                                        5032,
                                        5033,
                                        5035,
                                        5036,
                                        5037,
                                        5038,
                                        5039,
                                        5040,
                                        5041,
                                        5042,
                                        5043,
                                        5044,
                                        5045,
                                        5046,
                                        5047,
                                        5048,
                                        5049,
                                        5050,
                                        5051,
                                        5034,
                                    ];

                                    let Phan2 = [];
                                    mid = Math.floor((chitieu.length - 1) / 2);
                                    indexStart = 0;
                                    indexEnd = mid;
                                    while (
                                        indexStart < mid &&
                                        indexEnd < chitieu.length
                                    ) {
                                        let elementStart = xuatbaocao.total(
                                            year,
                                            chitieu[indexStart],
                                            bieumau,
                                            donvicha
                                        );

                                        let elementEnd = xuatbaocao.total(
                                            year,
                                            chitieu[indexEnd],
                                            bieumau,
                                            donvicha
                                        );

                                        Phan2[indexStart] = elementStart;
                                        Phan2[indexEnd] = elementEnd;

                                        indexStart++;
                                        indexEnd++;
                                    }

                                    baocao.phan2 = Phan2;
                                    return {
                                        baocao: baocao,
                                        donvicha: donvicha,
                                        madonvi: madonvi,
                                    };
                                }
                            })
                            .then((baocao) => {
                                console.log(baocao);
                                if (
                                    baocao.donvicha == 20 ||
                                    baocao.madonvi == 20
                                ) {
                                    if (diaban == 1) {
                                        maubaocao = "giaidoan_huyen.xlsx";
                                    }

                                    if (diaban == 3) {
                                        maubaocao = "giaidoan_tinh.xlsx";
                                    }
                                }
                                if (
                                    baocao.donvicha == 60 ||
                                    Number(baocao.madonvi) == 60
                                ) {
                                    maubaocao = "giaidoan.dahuoai.xlsx";
                                }

                                if (
                                    baocao.donvicha == 107 ||
                                    Number(baocao.madonvi) == 107
                                ) {
                                    maubaocao = "giaidoan_bacai.xlsx";
                                }
                                axios
                                    .post("reportofsanxuat", {
                                        location: location,
                                        year: nam.getFullYear(),
                                        mau: maubaocao,
                                        bieumau: cbBieuMau.option("value"),
                                        diaban: diaban,
                                        loaibaocao: 2,
                                        loaimau: 1,
                                        data: JSON.stringify(baocao.baocao),
                                    })
                                    .then((res) => {
                                        Swal.close();
                                        window.location = "/export/" + res.data;
                                    });
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
                        // axios
                        //     .post("reportofsanxuat", {
                        //         location: location,
                        //         year: nam.getFullYear(),
                        //         mau: maubaocao,
                        //         loaimau: 1,
                        //         bieumau: cbBieuMau.option("value"),
                        //         loaisolieu: $("#cbSoLieu")
                        //             .dxSelectBox("instance")
                        //             .option("value"),
                        //         namelocation: $("#cbHuyen")
                        //             .dxSelectBox("instance")
                        //             .option("text"),
                        //         diaban: diaban,
                        //     })
                        //     .then((res) => {
                        //         Swal.close();
                        //         //window.location = "/export/" + res.data;
                        //     })
                        //     .catch((err) => {
                        //         Swal.close();
                        //         console.log(err);
                        //     });
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
                        // axios
                        //     .post("reportofsanxuat", {
                        //         location: location,
                        //         year: nam.getFullYear(),
                        //         mau: maubaocao,
                        //         loaimau: 2,
                        //         bieumau: cbBieuMau.option("value"),
                        //         loaisolieu: $("#cbSoLieu")
                        //             .dxSelectBox("instance")
                        //             .option("value"),
                        //         namelocation: $("#cbHuyen")
                        //             .dxSelectBox("instance")
                        //             .option("text"),
                        //         diaban: diaban,
                        //     })
                        //     .then((res) => {
                        //         Swal.close();
                        //         //window.location = "/export/" + res.data;
                        //     })
                        //     .catch((err) => {
                        //         Swal.close();
                        //         console.log(err);
                        //     });
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
