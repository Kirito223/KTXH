import Ultil from "../js/Ultil.js";

var gridDonvi;
var gridBieumau;
var arrDonvi = [];
var arrBieumau = [];
var idBaocao = 0;
var fileEdit = "";
var diabanedit;
var selectedDiabanEdit = [];
var selectedBieumau = [];
var listDonvi;
var urlFile = "";
$(document).ready(() => {
    if ($("#thongtinbaocao").length) {
        idBaocao = Number(localStorage.getItem("Baocao"));
        listDonvi = $("#listDonvi");
        loadData();
        if (idBaocao > 0) {
            loadEdit();
        }

        initEvent();
    }
});
// Load thong tin chinh sua bao cao
function loadEdit() {
    if (localStorage.view == 1) {
        $("#btnLuu").addClass("hidden");
    }

    axios
        .get("showBaocao/" + idBaocao)
        .then((res) => {
            if (res.status == 200) {
                const data = res.data;
                let baocao = data.bieumau;
                $("#kybaocao")
                    .dxSelectBox("instance")
                    .option("value", baocao.kybaocao);
                $("#sokyhieubaocao").val(baocao.sohieu);
                $("#tieudebaocao").val(baocao.tieude);
                $("#nambaocao")
                    .dxSelectBox("instance")
                    .option("value", baocao.nambaocao);

                if (baocao.trangthai == 1) {
                    $("#hoanthanh").prop("checked", true);
                }
                if (baocao.file != null) {
                    fileEdit = baocao.file;
                    fileEdit = fileEdit.split("{/}");
                    let f = "";
                    for (let index = 1; index < fileEdit.length; index++) {
                        f += fileEdit[index];
                    }
                    $("#filedinhkem").html(
                        `<a href="https://api.lihanet.com/filesigned/${f}">${f}</a><i id="deleteFile" style="color:red;" class="fa fa-trash" aria-hidden="true"></i>`
                    );
                    $("#deleteFile").on("click", () => {
                        $("#filedinhkem").remove();
                        fileEdit = "";
                    });
                }
                let chitiet = data.chitiet;
                arrDonvi = JSON.parse(chitiet.donvinhan);
                diabanedit = arrDonvi[0].diaban; // Bien chua id dia ban duoc chon
                arrDonvi.forEach((element) => {
                    selectedDiabanEdit.push(element.id);
                });
                gridDonvi.option("dataSource", arrDonvi);
                arrBieumau = JSON.parse(chitiet.cacbieusolieu);
                arrBieumau.forEach((item) => {
                    selectedBieumau.push(item.id);
                });
                // hien thi len bang du lieu
                gridBieumau.option("dataSource", arrBieumau);

                CKEDITOR.on("instanceReady", function (evt) {
                    CKEDITOR.instances.noidung.setData(chitiet.noidung);
                });
            } else {
                Swal.fire(
                    "Có lỗi",
                    "Đã có lỗi xảy ra vui lòng thử lại sau",
                    "error"
                );
            }
        })
        .catch((err) => {
            console.log(err);
        });
}
function loadData() {
    CKEDITOR.replace("noidung");

    // Load don vi
    gridDonvi = $("#gridDonvi")
        .dxDataGrid({
            dataSource: arrDonvi,
            keyExpr: "id",
            showRowLines: true,
            showBorders: true,
            columnAutoWidth: true,
            // selection: {
            //     mode: "multiple",
            //     allowSelectAll: true
            // },
            columns: [
                {
                    dataField: "tendonvi",
                    caption: "Tên đơn vị",
                },
                {
                    dataField: "_name",
                    caption: "Địa chỉ",
                },
                {
                    dataField: "sodienthoai",
                    caption: "Điện thoại",
                },
                {
                    dataField: "email",
                    caption: "Email",
                },
            ],
        })
        .dxDataGrid("instance");

    // Load bieu mau

    gridBieumau = $("#gridBieumau")
        .dxDataGrid({
            dataSource: arrBieumau,
            keyExpr: "id",
            showRowLines: true,
            showBorders: true,
            columnAutoWidth: true,
            columns: [
                {
                    dataField: "sohieu",
                    caption: "Số hiệu",
                },
                {
                    dataField: "tenbieumau",
                    caption: "Tên biểu mẫu",
                },
                {
                    caption: "chức năng",
                    cellTemplate: function (container, options) {
                        $("<div>")
                            .dxButton({
                                type: "default",
                                text: "Xem",
                                template: function (e) {
                                    return $('<i class="far fa-eye"></i>')
                                        .text(" Xem")
                                        .css("color", "#ffffff");
                                },
                                onClick: function (e) {
                                    ReviewReport(options.data.id);
                                },
                            })
                            .appendTo(container);
                        $("<div>")
                            .dxButton({
                                type: "danger",
                                text: "Xoá",
                                template: function (e) {
                                    return $('<i class="fa fa-trash"></i>')
                                        .text(" Xoá")
                                        .css("color", "#ffffff");
                                },
                                onClick: function (e) {
                                    delTemplate(options.data.id);
                                },
                            })
                            .appendTo(container);
                    },
                },
            ],
        })
        .dxDataGrid("instance");

    //Load danh sach cac tinh

    $("#chondiaban").dxSelectBox({
        dataSource: "indexTinh",
        displayExpr: "tinh",
        valueExpr: "id",
    });

    $("#kybaocao").dxSelectBox({
        dataSource: "danhsachkybaocao",
        displayExpr: "tenky",
        valueExpr: "id",
    });
    let nam = Ultil.listNam();
    $("#nambaocao").dxSelectBox({
        dataSource: nam,
    });
}
function ReviewReport(id) {
    // Get Infomation Report and show in dialog
    Swal.fire({
        title: "Đang tải dữ liệu",
        text: "Đang tải dữ liệu",
        icon: "info",
        showConfirmButton: false,
    });
    axios
        .get("ShowReportInput/" + id)
        .then((res) => {
            let detailReport = res.data.Detail;
            let date = new Date();
            let parameter = new Map();
            parameter.set("ngay", date.getDate());
            parameter.set("thang", date.getMonth() + 1);
            parameter.set("nam", date.getFullYear());

            parameter.set("province", res.data.unit);
            parameter.set("city", res.data.unit);
            if (CKEDITOR.instances["noidung"]) {
                parameter.set("content", CKEDITOR.instances.noidung.getData());
            } else {
                parameter.set("content", $("#content").text());
            }

            Ultil.ShowReportData(
                "../public/report/xemtruocbieumaubaocao.mrt",
                detailReport, //Data
                parameter,
                "Report",
                true
            );
            Swal.close();
            $("#modelReviewReport").modal("show");
        })
        .catch((err) => {
            $(".loading").modal("hide");
            console.log(err);
        });
}
function delTemplate(id) {
    arrBieumau = arrBieumau.filter((item) => {
        return item.id != id;
    });
    gridBieumau.option("dataSource", arrBieumau);
}

function initEvent() {
    $("#filedongdau").on("change", (e) => {
        let file = $("#filedongdau")[0].files[0];
        let urlFile = URL.createObjectURL(file);
        $("#viewfile").attr("src", urlFile);
    });

    $("#btnchonfiledongdau").on("click", (e) => {
        $("#filedongdau").click();
    });

    $("#btnĐongauphathanh").on("click", (e) => {
        exc_sign_issued();
    });

    $("#btnkypheduyet").on("click", (e) => {
        exc_sign_approved();
    });
    $("#btnKycongvan").on("click", (e) => {
        exc_sign_income();
    });

    $("#btnkyso").on("click", () => {
        $("#modelKyso").modal("show");
    });

    $("#filedinhkem").on("click", function () {
        let file = $("#filedinhkem").data("file");
        window.open("downloadFileDinhkem/" + file);
    });

    $("#btnThoat").on("click", () => {
        localStorage.setItem("Baocao", 0);
        window.location = "viewDanhsachBaocao";
    });

    $("#btnDonvi").on("click", function () {
        // Hien thi cac du lieu de chinh sua
        if (idBaocao == 0) {
            loadDanhsachDiaban();
            $("#modelDonvi").modal("show");
        } else {
            // Load cac du lieu de chinh sua
            loadDanhsachDiaban(true, selectedDiabanEdit);
            $("#modelDonvi").modal("show");
        }
    });
    $("#btnBieumau").on("click", () => {
        // Get list template report
        axios
            .get("indexInputReportCurrent")
            .then((res) => {
                let data = res.data;
                appendBieumau(data);
                if (idBaocao != 0) {
                    checkedBieumau(selectedBieumau);
                }
            })
            .catch((err) => {
                console.log(err);
            });
        $("#modelBieumau").modal("show");
    });

    // Chon don vi
    $("#btnChondonvi").on("click", () => {
        let Donvi = $("input.checkbox-donvi:checked");
        arrDonvi = [];
        for (let index = 0; index < Donvi.length; index++) {
            const element = Donvi[index];
            arrDonvi.push({
                id: $(element).data("id"),
                tendonvi: $(element).data("ten"),
                _name: $(element).data("diachi"),
                sodienthoai: $(element).data("sodienthoai"),
                email: $(element).data("email"),
            });
        }

        gridDonvi.option("dataSource", arrDonvi);
        $("#modelDonvi").modal("toggle");
    });
    $("#btnChonBieumau").on("click", () => {
        arrBieumau = [];
        let Bieumau = $("input.checkbox-bieumau:checked");
        for (let index = 0; index < Bieumau.length; index++) {
            const element = Bieumau[index];
            arrBieumau.push({
                id: $(element).data("id"),
                sohieu: $(element).data("sohieu"),
                tenbieumau: $(element).data("tenbieumau"),
            });
        }
        gridBieumau.option("dataSource", arrBieumau);
        $("#modelBieumau").modal("toggle");
    });
    $("#btnLuu").on("click", () => {
        let kybaocao = $("#kybaocao").dxSelectBox("instance").option("value");
        let sohieu = $("#sokyhieubaocao").val();
        let tieude = $("#tieudebaocao").val();
        let nam = $("#nambaocao").dxSelectBox("instance").option("value");
        let hoanthanh = 0;
        if (Ultil.checkStatusCheckBox("hoanthanh")) {
            hoanthanh = 1;
        }
        let noidung = CKEDITOR.instances.noidung.getData();
        let ngaysaucung = new Date();
        let data = new FormData();
        if (fileEdit != null) {
            data.append("file", `${new Date().getMilliseconds}{/}.${fileEdit}`);
        } else {
            data.append("file", null);
        }
        data.append("kybaocao", kybaocao);
        data.append("sohieu", sohieu);
        data.append("tieude", tieude);
        data.append("nam", nam);
        data.append("hoanthanh", hoanthanh);
        data.append("noidung", noidung);
        data.append("ngaysaucung", moment(ngaysaucung).format("YYYY-MM-DD"));
        data.append("nguoicapnhat", window.idnguoidung);
        data.append("donvinhan", JSON.stringify(arrDonvi));
        data.append("cacbieusolieu", JSON.stringify(arrBieumau));

        let settings = {
            headers: { "content-type": "multipart/form-data" },
        };
        if (idBaocao == 0) {
            axios
                .post("storeBaocao", data, settings)
                .then((res) => {
                    if (res.status == 200) {
                        localStorage.setItem("Baocao", 0);
                        window.location = "viewDanhsachBaocao";
                    } else {
                        Swal.fire(
                            "Không thể lưu",
                            "Đã có lỗi xảy ra vui lòng kiểm tra lại",
                            "error"
                        );
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        } else {
            data.append("id", idBaocao);
            axios
                .post("updateBaocao", data, settings)
                .then((res) => {
                    if (res.status == 200) {
                        window.location = "viewDanhsachBaocao";
                    } else {
                        Swal.fire(
                            "Không thể lưu",
                            "Đã có lỗi xảy ra vui lòng kiểm tra lại",
                            "error"
                        );
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    });
}
function appendListDonvi(data) {
    let html = data.map((element) => {
        return (
            `<tr>
        <td><input class="checkbox-donvi" type="checkbox" data-ten="` +
            element.tendonvi +
            `" data-id="` +
            element.id +
            `" data-sodienthoai="` +
            element.sodienthoai +
            `" data-diachi="` +
            element.phuong +
            `" data-email="` +
            element.email +
            `" /></td>
        <td>` +
            element.tendonvi +
            `</td>
        <td>` +
            element.sodienthoai +
            `</td>
        <td>` +
            element.phuong +
            `</td>
        <td>` +
            element.email +
            `</td>
        </tr>`
        );
    });
    document.getElementById("listDonvi").innerHTML = html;
}

function appendBieumau(data) {
    let html = data.map((item) => {
        return (
            `<tr>
        <td><input class="checkbox-bieumau" data-id="` +
            item.id +
            `" data-sohieu="` +
            item.sohieu +
            `" data-tenbieumau="` +
            item.tenbieumau +
            `" type="checkbox"/></td>
        <td>` +
            item.sohieu +
            `</td>
        <td>` +
            item.tenbieumau +
            `</td>
        </tr>`
        );
    });

    document.getElementById("listBieumau").innerHTML = html;
}

function checkedDonvi(data) {
    data.forEach((item) => {
        $("input.checkbox-donvi[data-id =" + item + "]").prop("checked", true);
    });
}

function checkedBieumau(array) {
    array.forEach((element) => {
        $("input.checkbox-bieumau[data-id =" + element + "]").prop(
            "checked",
            true
        );
    });
}
function loadDanhsachDiaban(checked = false, datacheck = null) {
    axios
        .get("danhsachdonvihanhchinh")
        .then((res) => {
            let data = res.data;
            if (data != null) {
                appendListDonvi(data);
                if (checked == true) {
                    checkedDonvi(datacheck);
                }
            }
        })
        .catch((err) => {
            console.log(err);
        });
}
/*********************************************************
 * Ký số văn bản *
 ********************************************************/
function SignFileCallBack(rv) {
    let message = JSON.parse(rv);
    if (message.Status == 0) {
        fileEdit = message.FileServer;
        $("#viewfile").attr("src", message.FileServer);
    } else {
        Swal.fire(message.Message, "Không thành công", "error");
    }
}

// Đóng dấu phát hành
function exc_sign_issued() {
    let file = $("#filedongdau")[0].files[0];
    var prms = {};
    prms["FileUploadHandler"] =
        "https://api.lihanet.com/FileUploadHandler.aspx";
    prms["SessionId"] = "";
    prms["FileName"] = file.name;
    prms["DocNumber"] = "123/BCY-CTSBMTT";
    prms["IssuedDate"] = new Date();
    var json_prms = JSON.stringify(prms);
    vgca_sign_issued(json_prms, SignFileCallBack);
}
// Ham ky phe duyet
function exc_sign_approved() {
    var prms = {};
    prms["FileUploadHandler"] =
        "https://api.lihanet.com/FileUploadHandler.aspx";
    prms["SessionId"] = "";
    prms["FileName"] = urlFile; //"http://localhost:16227/files/test1.pdf";

    var json_prms = JSON.stringify(prms);
    vgca_sign_approved(json_prms, SignFileCallBack);
}
///Ký số công văn đến
function exc_sign_income() {
    var prms = {};
    var scv = [{ Key: "abc", Value: "abc" }];

    prms["FileUploadHandler"] =
        "https://api.lihanet.com/FileUploadHandler.aspx";
    prms["SessionId"] = "";
    prms["FileName"] = urlFile;
    prms["MetaData"] = scv;

    var json_prms = JSON.stringify(prms);
    vgca_sign_income(json_prms, SignFileCallBack);
}
function exc_appendix(url) {
    var prms = {};
    var scv = [{ Key: "abc", Value: "abc" }];

    prms["FileUploadHandler"] =
        "https://api.lihanet.com/FileUploadHandler.aspx";
    prms["SessionId"] = "";
    prms["FileName"] = url;
    prms["DocNumber"] = "123/BCY-CTSBMTT";
    prms["MetaData"] = scv;

    var json_prms = JSON.stringify(prms);
    vgca_sign_appendix(json_prms, SignFileCallBack);
}

function exc_sign_copy(url) {
    var prms = {};
    var scv = [{ Key: "abc", Value: "abc" }];

    prms["FileUploadHandler"] =
        "https://api.lihanet.com/FileUploadHandler.aspx";
    prms["SessionId"] = "";
    prms["FileName"] = url;
    prms["DocNumber"] = "123/BCY-CTSBMTT";
    prms["MetaData"] = scv;

    var json_prms = JSON.stringify(prms);
    vgca_sign_copy(json_prms, SignFileCallBack);
}

export default { ReviewReport };
