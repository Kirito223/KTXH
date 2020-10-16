var TreeInput;
var gridtemplate;
var gridlocaltion;
var idChitieu = undefined;
var idBieunhap = 0;
var templateedit;
var arrValueInput = [];
var arrGrid = [];
window.onload = function () {
    if ($("#nhapdulieutheobieu").length) {
        initData();
        initEvent();
        loadDataEdit();
    }
};

function loadDataEdit() {
    idBieunhap = localStorage.getItem("idBieunhap");
    idBieunhap = Number(idBieunhap);
    if (idBieunhap > 0) {
        axios
            .get("showEditBieumau/" + idBieunhap)
            .then((res) => {
                let data = res.data;
                let form = data.Form;
                templateedit = form.bieumau;
                $("#cbNamnhaplieu")
                    .dxSelectBox("instance")
                    .option("value", form.namnhap);
                $("#cbBieumau")
                    .dxSelectBox("instance")
                    .option("value", form.bieumau);
                $("#cbTinh")
                    .dxSelectBox("instance")
                    .option("value", form.diaban);
                $("#cbLoaisolieu")
                    .dxSelectBox("instance")
                    .option("value", form.loaisolieu);
                $("#cbKynhaplieu")
                    .dxSelectBox("instance")
                    .option("value", form.kynhap);
                $("#cbphamvi")
                    .dxSelectBox("instance")
                    .option("value", form.capnhap);
                let detail = data.Detail;
                arrGrid = detail;
                // init data to array Value Input
                loadDataToArray(detail);

                TreeInput.option("dataSource", detail);
            })
            .catch((err) => {
                console.log(err);
            });
    }
}

function loadDataToArray(data) {
    data.forEach((element) => {
        arrValueInput.push({
            id: element.id,
            value: element.sanluong == null ? 0 : element.sanluong,
            parent: element.idcha,
            unit: element.donvi,
        });
    });
}

function initData() {
    arrValueInput.length = 0;
    gridlocaltion = $("#grid-location")
        .dxDataGrid({
            selection: {
                mode: "multiple",
            },
            paging: {
                pageSize: 15,
                pageIndex: 1,
            },
            columns: [
                { dataField: "name", caption: "Đơn vị" },
                {
                    dataField: "sum",
                    caption: "Giá trị",
                },
            ],
        })
        .dxDataGrid("instance");
    gridtemplate = $("#grid-template")
        .dxDataGrid({
            selection: {
                mode: "multiple",
            },
            paging: {
                pageSize: 15,
                pageIndex: 1,
            },
            columns: [
                {
                    dataField: "tenbieumau",
                    caption: "Tên biểu mẫu",
                },
                { dataField: "tenky", caption: "Kỳ nhập" },
                {
                    dataField: "namnhap",
                    caption: "Năm",
                },
                {
                    dataField: "created_at",
                    caption: "Ngày tạo",
                    cellTemplate: function (container, options) {
                        container
                            .append(
                                `<p>` +
                                    moment(options.data.created_at).format(
                                        "DD/MM/YYYY"
                                    ) +
                                    `</p>`
                            )
                            .css("text-align", "center");
                    },
                },
            ],
        })
        .dxDataGrid("instance");

    TreeInput = $("#GridCheckImportExcel")
        .dxTreeList({
            keyExpr: "id",
            showRowLines: true,
            showBorders: true,
            autoExpandAll: true,
            parentIdExpr: "idcha",
            columnAutoWidth: true,
            editing: {
                allowUpdating: true,
                mode: "cell",
            },
            selection: {
                recursive: true,
            },
            onRowUpdating: function (e) {
                let old = e.oldData;
                let newdata = e.newData;
                UpdateParentNode(newdata.sanluong, old.id);
            },
            onCellPrepared: function (e) {
                if (e.rowType == "detailAdaptive") {
                    e.cellElement.addClass("adaptiveRowStyle");
                }
            },
            onContextMenuPreparing: function (e) {
                if (e.target == "content") {
                    // e.items can be undefined
                    if (!e.items) e.items = [];

                    // Add a custom menu item
                    e.items.push({
                        text: "Số liệu cập nhật lần trước",
                        icon: "fas fa-clock",
                        onItemClick: function () {
                            if (e.rowIndex == -1) {
                                Swal.fire(
                                    "Chưa chọn biểu mẫu",
                                    "Vui lòng chọn biểu mẫu nhập liệu",
                                    "warning"
                                );
                            } else {
                                idChitieu = e.row.data.id;

                                let namnhap = $("#cbNamnhaplieu")
                                    .dxSelectBox("instance")
                                    .option("value");
                                let bieumau = $("#cbBieumau")
                                    .dxSelectBox("instance")
                                    .option("value");
                                let diaban = $("#cbTinh")
                                    .dxSelectBox("instance")
                                    .option("value");
                                axios
                                    .post("ListTempalatewithIdBieumau", {
                                        bieumau: bieumau,
                                        namnhap: namnhap,
                                        donvi: window.madonvi,
                                        diaban: diaban,
                                    })
                                    .then((res) => {
                                        let data = res.data;
                                        gridtemplate.columnOption(
                                            "tenbieumau",
                                            { visible: true }
                                        );
                                        gridtemplate.option("dataSource", data);
                                    })
                                    .catch((err) => {
                                        console.log(err);
                                    });
                                $("#modelReportSelect").modal("show");
                            }
                        },
                    });
                    e.items.push({
                        text: "Cộng dồn theo các kỳ",
                        icon: "fas fa-clock",
                        onItemClick: function () {
                            if (e.rowIndex == -1) {
                                Swal.fire(
                                    "Chưa chọn biểu mẫu",
                                    "Vui lòng chọn biểu mẫu nhập liệu",
                                    "warning"
                                );
                            } else {
                                idChitieu = e.row.data;

                                let namnhap = $("#cbNamnhaplieu")
                                    .dxSelectBox("instance")
                                    .option("value");
                                let bieumau = $("#cbBieumau")
                                    .dxSelectBox("instance")
                                    .option("value");
                                let diaban = $("#cbTinh")
                                    .dxSelectBox("instance")
                                    .option("value");
                                axios
                                    .post("ListTempalatewithIdBieumau", {
                                        bieumau: bieumau,
                                        namnhap: namnhap,
                                        donvi: window.madonvi,
                                        diaban: diaban,
                                    })
                                    .then((res) => {
                                        let data = res.data;
                                        gridtemplate.columnOption(
                                            "tenbieumau",
                                            { visible: false }
                                        );
                                        gridtemplate.option("dataSource", data);
                                    })
                                    .catch((err) => {
                                        console.log(err);
                                    });
                                $("#modelReportSelect").modal("show");
                            }
                        },
                    });
                    e.items.push({
                        text: "Cộng dồn theo các địa bàn",
                        icon: "plus",
                        onItemClick: function () {
                            if (e.rowIndex == -1) {
                                Swal.fire(
                                    "Chưa chọn biểu mẫu",
                                    "Vui lòng chọn biểu mẫu nhập liệu",
                                    "warning"
                                );
                            } else {
                                idChitieu = e.row.data.id;
                                let namnhap = $("#cbNamnhaplieu")
                                    .dxSelectBox("instance")
                                    .option("value");
                                let bieumau = $("#cbBieumau")
                                    .dxSelectBox("instance")
                                    .option("value");
                                let diaban = $("#cbTinh")
                                    .dxSelectBox("instance")
                                    .option("value");
                                let kynhap = $("#cbKynhaplieu")
                                    .dxSelectBox("instance")
                                    .option("value");
                                if (namnhap == null) {
                                    Swal.fire(
                                        "Chưa chọn năm nhập liệu",
                                        "Xin vui lòng chọn năm nhập liệU",
                                        "warning"
                                    );
                                } else if (bieumau == null) {
                                    Swal.fire(
                                        "Chưa chọn biểu mẫu",
                                        "Xin vui lòng chọn biểu mẫu",
                                        "warning"
                                    );
                                } else if (diaban == null) {
                                    Swal.fire(
                                        "Chưa chọn địa bàn",
                                        "Xin vui lòng chọn địa bàn",
                                        "warning"
                                    );
                                } else if (kynhap == null) {
                                    Swal.fire(
                                        "Chưa chọn kỳ nhập",
                                        "Xin vui lòng chọn kỳ nhập",
                                        "warning"
                                    );
                                } else {
                                    axios
                                        .post("ListDataofLocation", {
                                            donvi: diaban,
                                            bieumau: bieumau,
                                        })
                                        .then((res) => {
                                            gridlocaltion.option(
                                                "dataSource",
                                                res.data
                                            );
                                            $("#modelLocaltion").modal("show");
                                        })
                                        .catch((err) => {
                                            console.log(err);
                                        });
                                }
                            }
                        },
                    });
                }
            },
            columns: [
                {
                    dataField: "ten",
                    caption: "Tên chỉ tiêu",
                },
                {
                    dataField: "sanluong",
                    caption: "Sản lượng",

                    cellTemplate: function (container, options) {
                        container
                            .append(`<input data-id="${options.data.id}"/>`)
                            .css("text-align", "center");
                    },
                },
                {
                    dataField: "donvi",
                    caption: "Đơn vị",
                },
            ],
        })
        .dxTreeList("instance");

    $("#cbTinh").dxSelectBox({
        dataSource: "danhsachdonvihanhchinh",
        displayExpr: "tendonvi",
        valueExpr: "id",
    });

    let option = [
        { text: "Toàn tỉnh", value: 0 },
        { text: "Chỉ huyện", value: 1 },
    ];
    $("#cbphamvi").dxSelectBox({
        dataSource: option,
        displayExpr: "text",
        valueExpr: "value",
    });
    $("#cbLoaisolieu").dxSelectBox({
        dataSource: "indexLoaisolieu",
        displayExpr: "tenloaisolieu",
        valueExpr: "id",
    });

    $("#cbKynhaplieu").dxSelectBox({
        dataSource: "danhsachkybaocao",
        displayExpr: "tenky",
        valueExpr: "id",
    });

    let nam = [];
    for (let index = 1990; index < 2070; index++) {
        nam.push(index);
    }
    $("#cbNamnhaplieu").dxSelectBox({
        dataSource: nam,
    });

    $("#cbNamnhaplieu")
        .dxSelectBox("instance")
        .option("value", new Date().getFullYear());
    $("#cbBieumau").dxSelectBox({
        dataSource: "listBieumauActive",
        displayExpr: "tenbieumau",
        valueExpr: "id",
        onValueChanged: function (e) {
            var idTemplate = e.value;
            if (idTemplate != templateedit) {
                axios
                    .get("getChitieuNhaplieu/" + idTemplate)
                    .then((res) => {
                        arrGrid = res.data.data;
                        loadDataToArray(arrGrid);
                        TreeInput.option("dataSource", arrGrid);
                    })
                    .catch((err) => {
                        console.error(err);
                    });
            }
        },
    });
}

function initEvent() {
    // TODO sum with report selected
    $("#sum-with-report").on("click", () => {
        let namnhap = $("#cbNamnhaplieu")
            .dxSelectBox("instance")
            .option("value");
        let bieumau = $("#cbBieumau").dxSelectBox("instance").option("value");
        let diaban = $("#cbTinh").dxSelectBox("instance").option("value");
        if (namnhap == null) {
            Swal.fire(
                "Chưa chọn năm nhập liệu",
                "Xin vui lòng chọn năm nhập liệU",
                "warning"
            );
        } else if (bieumau == null) {
            Swal.fire(
                "Chưa chọn biểu mẫu",
                "Xin vui lòng chọn biểu mẫu",
                "warning"
            );
        } else if (diaban == null) {
            Swal.fire(
                "Chưa chọn địa bàn",
                "Xin vui lòng chọn địa bàn",
                "warning"
            );
        } else {
            axios
                .post("ListTempalatewithIdBieumau", {
                    bieumau: bieumau,
                    namnhap: namnhap,
                    donvi: window.madonvi,
                    diaban: diaban,
                })
                .then((res) => {
                    let data = res.data;
                    arrValueInput.length = 0;
                    loadDataToArray(data);
                    gridtemplate.columnOption("tenbieumau", { visible: true });
                    gridtemplate.option("dataSource", data);
                })
                .catch((err) => {
                    console.log(err);
                });
            $("#modelReportSelect").modal("show");
        }
    });
    // TODO Sum data with time
    $("#sum-with-time").on("click", () => {
        let namnhap = $("#cbNamnhaplieu")
            .dxSelectBox("instance")
            .option("value");
        let bieumau = $("#cbBieumau").dxSelectBox("instance").option("value");
        let diaban = $("#cbTinh").dxSelectBox("instance").option("value");
        if (namnhap == null) {
            Swal.fire(
                "Chưa chọn năm nhập liệu",
                "Xin vui lòng chọn năm nhập liệU",
                "warning"
            );
        } else if (bieumau == null) {
            Swal.fire(
                "Chưa chọn biểu mẫu",
                "Xin vui lòng chọn biểu mẫu",
                "warning"
            );
        } else if (diaban == null) {
            Swal.fire(
                "Chưa chọn địa bàn",
                "Xin vui lòng chọn địa bàn",
                "warning"
            );
        } else {
            axios
                .post("ListTempalatewithIdBieumau", {
                    bieumau: bieumau,
                    namnhap: namnhap,
                    donvi: window.madonvi,
                    diaban: diaban,
                })
                .then((res) => {
                    let data = res.data;
                    gridtemplate.columnOption("tenbieumau", { visible: false });
                    gridtemplate.option("dataSource", data);
                })
                .catch((err) => {
                    console.log(err);
                });
            $("#modelReportSelect").modal("show");
        }
    });
    // TODO get lis data of location
    $("#sum-with-location").on("click", () => {
        let namnhap = $("#cbNamnhaplieu")
            .dxSelectBox("instance")
            .option("value");
        let bieumau = $("#cbBieumau").dxSelectBox("instance").option("value");
        let diaban = $("#cbTinh").dxSelectBox("instance").option("value");
        let kynhap = $("#cbKynhaplieu").dxSelectBox("instance").option("value");
        if (namnhap == null) {
            Swal.fire(
                "Chưa chọn năm nhập liệu",
                "Xin vui lòng chọn năm nhập liệU",
                "warning"
            );
        } else if (bieumau == null) {
            Swal.fire(
                "Chưa chọn biểu mẫu",
                "Xin vui lòng chọn biểu mẫu",
                "warning"
            );
        } else if (diaban == null) {
            Swal.fire(
                "Chưa chọn địa bàn",
                "Xin vui lòng chọn địa bàn",
                "warning"
            );
        } else if (kynhap == null) {
            Swal.fire(
                "Chưa chọn kỳ nhập",
                "Xin vui lòng chọn kỳ nhập",
                "warning"
            );
        } else {
            axios
                .post("ListDataofLocation", { donvi: diaban, bieumau: bieumau })
                .then((res) => {
                    gridlocaltion.option("dataSource", res.data);
                    $("#modelLocaltion").modal("show");
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    });
    // TODO sum data with location
    $("#btnSumlocation").on("click", () => {
        let bieumau = $("#cbBieumau").dxSelectBox("instance").option("value");
        let diaban = gridlocaltion.getSelectedRowsData();
        if (idChitieu === undefined) {
            axios
                .post("SumDataofLocation", {
                    donvi: JSON.stringify(diaban),
                    bieumau: bieumau,
                })
                .then((res) => {
                    ShowData(res.data);
                    $("#modelLocaltion").modal("toggle");
                })
                .catch((err) => {
                    console.log(err);
                });
        } else {
            axios
                .post("SumDataofLocation", {
                    donvi: JSON.stringify(diaban),
                    bieumau: bieumau,
                    chitieu: idChitieu,
                })
                .then((res) => {
                    ShowData(res.data);
                    idChitieu = undefined;
                    $("#modelLocaltion").modal("toggle");
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    });
    // TODO Sum folllow perivious Report
    $("#btnplus").on("click", () => {
        let rowselect = gridtemplate.getSelectedRowsData();
        if (idChitieu === undefined) {
            axios
                .post("accumulateDataBieumau", {
                    bieumau: JSON.stringify(rowselect),
                })
                .then((res) => {
                    let data = res.data;
                    ShowData(data);
                    $("#modelReportSelect").modal("toggle");
                })
                .catch((err) => {
                    console.log(err);
                });
        } else {
            axios
                .post("accumulateDataBieumau", {
                    bieumau: JSON.stringify(rowselect),
                    chitieu: idChitieu,
                })
                .then((res) => {
                    let data = res.data;

                    ShowData(data);
                    idChitieu = undefined;
                    $("#modelReportSelect").modal("toggle");
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    });
    // TODO Tai bieu mau nhap lieu
    $("#btnTaibieumau").on("click", () => {
        let ky = $("#cbKynhaplieu").dxSelectBox("instance").option("value");
        let bieumau = $("#cbBieumau").dxSelectBox("instance").option("value");

        let diaban = $("#cbTinh").dxSelectBox("instance").option("text");
        let khuvuc = $("#cbphamvi").dxSelectBox("instance").option("text");
        let loaisolieu = $("#cbLoaisolieu")
            .dxSelectBox("instance")
            .option("text");
        let kynhaplieu = $("#cbKynhaplieu")
            .dxSelectBox("instance")
            .option("text");
        let namnhaplieu = $("#cbNamnhaplieu")
            .dxSelectBox("instance")
            .option("text");

        if (checkselect()) {
            axios
                .post("downloadFileBieumauNhapLieu", {
                    bieumau: bieumau,
                    ky: ky,
                    diaban: diaban,
                    khuvuc: khuvuc,
                    loaisolieu: loaisolieu,
                    kynhaplieu: kynhaplieu,
                    namnhaplieu: namnhaplieu,
                })
                .then((res) => {
                    if (res.data == 405) {
                        Swal.fire(
                            "Sai thông tin",
                            "Kỳ báo cáo không đúng với biểu mẫu vui lòng kiểm tra lại",
                            "warning"
                        );
                    } else {
                        window.open("Download/" + res.data);
                        $("#modalNhapExcel").css("display", "block");
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    });

    $("#btnNhap").on("click", () => {
        let file = document.getElementById("file").files[0];
        let data = new FormData();
        if (file == null) {
            Swal.fire("Cảnh báo", "Bạn chưa chọn file để tải lên", "warning");
        } else {
            data.append("file", file);
            let settings = {
                headers: { "content-type": "multipart/form-data" },
            };
            axios
                .post("importFileBieumauNhapLieu", data, settings)
                .then((res) => {
                    if (res.status == 200) {
                        TreeInput.option("dataSource", res.data);
                        $("#modalImportFromExcel").modal("toggle");
                    } else {
                        Swal.fire(
                            "Lỗi",
                            "Đã xảy ra lỗi vui lòng thử lại sau",
                            "error"
                        );
                    }
                })
                .catch((response) => {
                    console.log(response);
                });
        }
    });
    $("#btnImportFromExcel").on("click", () => {
        $("#modalImportFromExcel").modal("show");
    });

    $("#btnExit").on("click", () => {
        window.location = "/viewListNhaplieu";
        localStorage.setItem("idBieunhap", 0);
    });

    $("#btnImport").on("click", () => {
        let dataImport = [];
        TreeInput.forEachNode(function (node) {
            if (node.data.sanluong == "") {
                dataImport.push({
                    id: node.data.id,
                    sanluong: null,
                });
            } else {
                dataImport.push({
                    id: node.data.id,
                    sanluong: node.data.sanluong,
                });
            }
        });

        let bieumau = $("#cbBieumau").dxSelectBox("instance").option("value");
        let diaban = $("#cbTinh").dxSelectBox("instance").option("value");
        let loaisolieu = $("#cbLoaisolieu")
            .dxSelectBox("instance")
            .option("value");
        let kynhaplieu = $("#cbKynhaplieu")
            .dxSelectBox("instance")
            .option("value");
        let namnhaplieu = $("#cbNamnhaplieu")
            .dxSelectBox("instance")
            .option("value");
        let capnhap = $("#cbphamvi").dxSelectBox("instance").option("value");
        if (checkselect()) {
            let data = new FormData();
            data.append("mabieumau", bieumau);
            data.append("donvi", window.madonvi);
            data.append("taikhoan", window.idnguoidung);
            data.append("diaban", diaban);
            data.append("loaisolieu", loaisolieu);
            data.append("kynhap", kynhaplieu);
            data.append("namnhap", namnhaplieu);
            data.append("capnhap", capnhap);
            data.append("dataImport", JSON.stringify(dataImport));
            if (idBieunhap > 0) {
                data.append("edit", idBieunhap);
            }
            let settings = {
                headers: { "content-type": "multipart/form-data" },
            };
            axios
                .post("importDataBieumauNhapLieu", data, settings)
                .then((res) => {
                    if (res.data["succes"] == 200) {
                        Swal.fire(
                            "Nhập dữ liệu thành công",
                            "Bạn đã nhập dữ liệu thành công",
                            "success"
                        );
                        window.location = "viewListNhaplieu";
                    } else if (res.data["succes"] == 400) {
                        Swal.fire(
                            "Dữ liệu đã tồn tại không thể thêm số liệu tương tự",
                            "Dữ liệu đã tồn tại",
                            "error"
                        );
                    } else {
                        Swal.fire(
                            "Đã xảy ra lỗi không thể lưu dữ liệu",
                            "Lỗi",
                            "error"
                        );
                    }
                })
                .catch((response) => {
                    console.log(response);
                });
        }
    });
}
// Ham tinh tong cac chi tieu
function UpdateParentNode(value, idItem) {
    let indexInValue = arrValueInput.findIndex((x) => x.id == idItem);

    let indexGrid = arrGrid.findIndex((x) => x.id == idItem);
    if (arrValueInput[indexInValue].value == null) {
        arrValueInput[indexInValue].value = value;

        arrGrid[indexGrid].sanluong = value;
        updateGrid(arrValueInput[indexInValue].parent, value);
    } else {
        let oldValue = arrValueInput[indexInValue].value;
        if (oldValue < value) {
            let plus = Number(value) - Number(oldValue);
            arrValueInput[indexInValue].value = Number(oldValue) + plus;
            arrGrid[indexGrid].sanluong = Number(oldValue) + plus;
            updateGrid(
                arrValueInput[indexInValue].parent,
                Number(oldValue) + plus
            );
        } else if (oldValue > value) {
            let minus = Number(oldValue) - Number(oldValue);
            arrValueInput[indexInValue].value =
                Number(oldValue) - Number(minus);
            arrGrid[indexGrid].sanluong = Number(oldValue) - Number(minus);
            updateGrid(
                arrValueInput[indexInValue].parent,
                Number(oldValue) - Number(minus)
            );
        }
    }
}

function updateGrid(idcha, sanluong) {
    // Cong cac so lieu trong mang arrValueInput mang cha
    let indexInput = arrValueInput.findIndex((x) => x.id == idcha);
    if (indexInput != -1) {
        let suminput = 0;
        let donvicha = arrValueInput[indexInput].unit;
        arrValueInput.forEach((item) => {
            if (item.parent == idcha && item.unit == donvicha) {
                suminput += Number(item.value);
            }
        });
        arrValueInput[indexInput].value = suminput;
        // Cap nhat lai cho mang data grid
        let indexDataGrid = arrGrid.findIndex((x) => x.id == idcha);

        let sumgrid = 0;
        arrGrid.forEach((item) => {
            if (item.idcha == idcha && item.donvi == donvicha) {
                sumgrid += Number(item.sanluong);
            }
        });
        arrGrid[indexDataGrid].sanluong = sumgrid;
        TreeInput.refresh();
    }
}

// hiển thị dữ liệu
function ShowData(data) {
    TreeInput.forEachNode(function (node) {
        let index = data.findIndex((x) => x.id == node.data.id);
        if (index != -1) {
            let indexvalue = arrValueInput.findIndex(
                (z) => z.id == node.data.id
            );
            if (indexvalue == -1) {
                arrValueInput.push({
                    id: node.data.id,
                    value: data[index].quantity,
                    parent: node.data.idcha,
                });
            }
            node.data.sanluong = data[index].quantity;
        }
    });

    TreeInput.refresh();
}
function checkselect() {
    let bieumau = $("#cbBieumau").dxSelectBox("instance").option("value");

    let diaban = $("#cbTinh").dxSelectBox("instance").option("value");

    let loaisolieu = $("#cbLoaisolieu").dxSelectBox("instance").option("value");
    let kynhaplieu = $("#cbKynhaplieu").dxSelectBox("instance").option("value");
    let namnhaplieu = $("#cbNamnhaplieu")
        .dxSelectBox("instance")
        .option("value");
    if (bieumau == null) {
        Swal.fire(
            "Chưa chọn biểu mẫu",
            "Xin vui lòng chọn biểu mẫu",
            "warning"
        );
        return false;
    } else if (diaban == null) {
        Swal.fire(
            "Chưa chọn tỉnh thành",
            "Xin vui lòng chọn tỉnh thành",
            "warning"
        );
        return false;
    } else if (loaisolieu == null) {
        Swal.fire(
            "Chưa chọn loại số liệu",
            "Xin vui lòng chọn loại số liệu",
            "warning"
        );
        return false;
    } else if (kynhaplieu == null) {
        Swal.fire(
            "Chưa chọn kỳ nhập liệu",
            "Xin vui lòng chọn kỳ nhập liệu",
            "warning"
        );
        return false;
    } else if (namnhaplieu == null) {
        Swal.fire(
            "Chưa chọn năm nhập liệu",
            "Xin vui lòng chọn năm số liệu",
            "warning"
        );
        return false;
    } else {
        return true;
    }
}
export default {
    checkselect,
};
