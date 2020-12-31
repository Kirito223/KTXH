var DataGrid;
var gridtemplate;
var gridlocaltion;
var idChitieu = undefined;
var idBieunhap = 0;
var templateedit;
var arrValueInput = [];
var arrGrid = [];
var html = "";
var arrImportExcel = [];

$(document).ready(() => {
    $.contextMenu({
        // define which elements trigger this menu
        selector: ".inputValue",
        build: function ($triggerElement, e) {
            return {
                items: {
                    lastupdate: {
                        name: "Số liệu cập nhật lần trước",
                        callback: function () {
                            console.log(e.target.dataset.chitieu);
                            if (e.rowIndex == -1) {
                                Swal.fire(
                                    "Chưa chọn biểu mẫu",
                                    "Vui lòng chọn biểu mẫu nhập liệu",
                                    "warning"
                                );
                            } else {
                                idChitieu = e.target.dataset.chitieu;

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
                    },
                    plus: {
                        name: "Cộng dồn theo các kỳ",
                        callback: function (key, opt) {
                            console.log(e.target.dataset.chitieu);
                            if (e.rowIndex == -1) {
                                Swal.fire(
                                    "Chưa chọn biểu mẫu",
                                    "Vui lòng chọn biểu mẫu nhập liệu",
                                    "warning"
                                );
                            } else {
                                idChitieu = e.target.dataset.chitieu;

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
                    },
                    plusLocation: {
                        name: "Cộng dồn theo địa bàn",
                        callback: function (key, opt) {
                            if (e.rowIndex == -1) {
                                Swal.fire(
                                    "Chưa chọn biểu mẫu",
                                    "Vui lòng chọn biểu mẫu nhập liệu",
                                    "warning"
                                );
                            } else {
                                idChitieu = e.target.dataset.chitieu;
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
                    },
                    goto: {
                        name: "Chuyển chỉ tiêu",
                        callback: function (key, opt) {
                            console.log(e.target.dataset.chitieu);
                        },
                    },
                    formula: {
                        name: "Tính theo công thức",
                        callback: function (key, opt) {
                            console.log(e.target.dataset.chitieu);
                        },
                    },
                },
            };
        },
    });
    initData();
    initEvent();
    loadDataEdit();
});

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
                showTable(detail);
                document.getElementById(
                    "GridCheckImportExcel"
                ).innerHTML = html;
                $("#tableChitieu").treetable({ expandable: false });
                mapValue(res.data);
                setEventInput();
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
                    width: 200,
                },
                { dataField: "tenloaisolieu", caption: "Loại số liệu" },
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
                        showTable(res.data);
                        document.getElementById(
                            "GridCheckImportExcel"
                        ).innerHTML = html;
                        $("#tableChitieu").treetable({ expandable: false });
                        mapValue(res.data);
                        setEventInput();
                    })
                    .catch((err) => {
                        console.error(err);
                    });
            }
        },
    });
}

function mapValue(data) {
    arrGrid.length = 0;

    if (data.data != undefined) {
        arrGrid = data.data.map((item) => {
            return {
                id: item.id,
                value:
                    item.sanluong == null || item.sanluong == undefined
                        ? 0
                        : item.sanluong,
                parent: item.idcha,
                unit: item.donvi,
            };
        });
    } else {
        arrGrid = data.Detail.map((item) => {
            return {
                id: item.chitieu,
                value:
                    item.sanluong == null || item.sanluong == undefined
                        ? 0
                        : item.sanluong,
                parent: item.idcha,
                unit: item.tendonvi,
            };
        });
    }
}

function setEventInput() {
    let inputValue = document.getElementsByClassName("inputValue");

    for (const input of inputValue) {
        input.addEventListener("keyup", function (evt) {
            if (evt.keyCode == 13) {
                let index = arrGrid.findIndex(
                    (x) => x.id == input.dataset.chitieu
                );
                if (arrImportExcel.length > 0) {
                    let indexExcel = arrImportExcel.findIndex(
                        (y) => y.id == input.dataset.chitieu
                    );
                    arrImportExcel[indexExcel].sanluong = input.value;
                }
                arrGrid[index].value = input.value;
                sumParent(arrGrid[index].parent, arrGrid[index].unit);

                // Focus next input
                if (arrGrid[index + 1] != undefined) {
                    let idSelect = arrGrid[index + 1].id;
                    let selectedInput = document.querySelector(
                        `.inputValue[data-chitieu="${idSelect}"]`
                    );
                    selectedInput.focus();
                }
            }
        });
    }
}

function sumParent(parent, unit) {
    if (parent != null || parent != undefined) {
        let child = arrGrid.filter((item) => {
            return item.parent == parent && item.unit == unit;
        });
        let sum = 0;
        child.forEach((item) => {
            sum += Number(item.value);
        });

        document.querySelector(
            `.inputValue[data-chitieu ="${parent}"]`
        ).value = sum;
    }
}

function showTable(result) {
    if (result.data != null) {
        result.data.forEach((element) => {
            let dataParent =
                element.idcha == null
                    ? ""
                    : ` data-tt-parent-id=${element.idcha}`;
            html += `<tr data-tt-id="${element.id}" ${dataParent}>
    <td>${element.ten}</td>
    <td>${element.donvi != null ? element.donvi : ""}</td>
    <td><input value="${
        element.sanluong != null ? element.sanluong : ""
    }" class="inputValue form-control" type="number" data-chitieu="${
                element.id
            }" /></td>
    </tr>`;
        });
    } else {
        result.forEach((element) => {
            let dataParent =
                element.idcha == null
                    ? ""
                    : ` data-tt-parent-id=${element.idcha}`;
            html += `<tr data-tt-id="${element.chitieu}" ${dataParent}>
        <td>${element.ten}</td>
        <td>${element.tendonvi != null ? element.tendonvi : ""}</td>
        <td><input value="${
            element.sanluong != null ? element.sanluong : ""
        }" class="inputValue form-control" type="number" data-chitieu="${
                element.chitieu
            }" /></td>
        </tr>`;
        });
    }
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
                    let data = res.data;
                    data.forEach((item) => {
                        let index = arrGrid.findIndex((x) => x.id == item.id);
                        arrGrid[index].value = item.quantity;
                        document.querySelector(
                            `.inputValue[data-chitieu ="${item.id}"]`
                        ).value = item.quantity;
                    });
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
                    let data = res.data;
                    data.forEach((item) => {
                        let index = arrGrid.findIndex((x) => x.id == item.id);
                        arrGrid[index].value = item.quantity;
                        document.querySelector(
                            `.inputValue[data-chitieu ="${item.id}"]`
                        ).value = item.quantity;
                    });
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
                    data.forEach((item) => {
                        let index = arrGrid.findIndex((x) => x.id == item.id);
                        arrGrid[index].value = item.quantity;
                        document.querySelector(
                            `.inputValue[data-chitieu ="${item.id}"]`
                        ).value = item.quantity;
                    });
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
                        arrGrid.length = 0;
                        res.data.forEach((item) => {
                            arrGrid.push({
                                id: item.id,
                                value: item.sanluong,
                                parent: item.idcha,
                                unit: item.donvi,
                            });
                            arrImportExcel.push({
                                id: item.id,
                                sanluong: item.sanluong,
                                sanluongkhkytruoc: item.sanluongkhkytruoc,
                                sanluongkhkynay: item.sanluongkhkynay,
                            });
                            document.querySelector(
                                `.inputValue[data-chitieu ="${item.id}"]`
                            ).value = item.sanluong;
                        });
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
        let input = document.getElementsByClassName("inputValue");
        for (const ip of input) {
            dataImport.push({
                id: ip.dataset.chitieu,
                sanluong: ip.value,
            });
        }

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

    $("#btnImporttonghop").on("click", () => {
        let dataImport = [];
        arrImportExcel.forEach((item) => {
            dataImport.push({
                id: item.id,
                sanluong: item.sanluong,
                sanluongkhkytruoc: item.sanluongkhkytruoc,
                sanluongkhkynay: item.sanluongkhkynay,
            });
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
                .post("importDataBieumauNhapLieutonghop", data, settings)
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

// hiển thị dữ liệu
function ShowData(data) {
    DataGrid.forEachNode(function (node) {
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
