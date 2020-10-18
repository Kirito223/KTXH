import Ultil from "./Ultil.js";

var kybaocao,
    sokyhieubaocao,
    tieudebaocao,
    nambaocao,
    hoanthanh,
    gridDonvi,
    gridBieumau,
    filedinhkem,
    btnDuyetbaocao,
    noidung,
    btnThoat;
var idBaocao;
$(document).ready(() => {
    initControl();
    loadData();
    initEvent();
});

function initEvent() {
    btnThoat.onclick = (e) => {
        window.history.back();
    };

    btnDuyetbaocao.onclick = function () {
        Swal.fire({
            title: "Bạn muốn duyệt báo cáo này không?",
            text: "Duyệt báo cáo",
            icon: "warning",
            showConfirmButton: true,
            confirmButtonText: "Đồng ý",
            showCancelButton: true,
            cancelButtonText: "Đóng",
            preConfirm: () => {
                axios
                    .get("Duyet/" + idBaocao)
                    .then((res) => {
                        res = res.data;
                        if (res["Code"] == 200) {
                            window.history.back();
                        } else {
                            Swal.fire(res["Message"], "Lỗi", "error");
                        }
                    })
                    .catch((err) => {
                        console.error(err);
                    });
            },
        });
    };
}

function loadData() {
    axios
        .get("thongtinchitiet")
        .then((res) => {
            let baocao = res.data;
            let bieumau = baocao.bieumau;
            kybaocao.value = bieumau.tenky;
            sokyhieubaocao.value = bieumau.sohieu;
            tieudebaocao.value = bieumau.tieude;
            nambaocao.value = bieumau.nambaocao;
            if (bieumau.trangthai == 1) {
                hoanthanh.prop("checked", true);
            }
            if (baocao.nguoiky != null) {
                btnDuyetbaocao.disabled = true;
            }
            let chitiet = baocao.chitiet;
            // Render don vi
            let donvi = JSON.parse(chitiet.donvinhan);
            let sott = 1;
            let htmlDonvi = "";
            donvi.forEach((item) => {
                htmlDonvi += `<tr>
                <td>${sott}</td>
                <td>${item.tendonvi}</td>
                <td>${item.sodienthoai}</td>
                <td>${item.email}</td>
                </tr>`;
                sott++;
            });
            gridDonvi.innerHTML = htmlDonvi;
            // Render bieu mau

            let bieumaus = JSON.parse(chitiet.cacbieusolieu);
            let htmlBieumau = "";
            sott = 1;
            bieumaus.forEach((item) => {
                let tr = document.createElement("tr");
                let tdstt = document.createElement("td");
                let textstt = document.createTextNode(sott);
                let tdsohieu = document.createElement("td");
                let lblsohieu = document.createTextNode(item.sohieu);
                let tdten = document.createElement("td");
                let lblten = document.createTextNode(item.tenbieumau);
                let tdbutton = document.createElement("td");
                let button = document.createElement("button");
                button.setAttribute("class", "btn btn-default btn-sm");
                let icon = document.createElement("i");
                icon.setAttribute("class", "fa fa-eye");

                tdstt.appendChild(textstt);
                tdsohieu.appendChild(lblsohieu);
                tdten.appendChild(lblten);
                tdbutton.appendChild(button);
                button.appendChild(icon);
                button.onclick = function () {
                    ReviewReport(item.id);
                };

                tr.appendChild(tdstt);
                tr.appendChild(tdsohieu);
                tr.appendChild(tdten);
                tr.appendChild(tdbutton);

                gridBieumau.appendChild(tr);
                sott++;
            });
            // gridBieumau.innerHTML = htmlBieumau;
            filedinhkem.innerText = bieumau.file;
            filedinhkem.dataset.file = bieumau.file;
            filedinhkem.onclick = function () {
                let file = $("#filedinhkem").data("file");
                window.open("downloadFileDinhkem/" + file);
            };
            noidung.innerHTML = chitiet.noidung;
            idBaocao = chitiet.baocao;
        })
        .catch((err) => {
            console.error(err);
        });
}

function initControl() {
    btnThoat = document.getElementById("btnThoat");
    btnDuyetbaocao = document.getElementById("btnDuyetbaocao");
    kybaocao = document.getElementById("kybaocao");
    sokyhieubaocao = document.getElementById("sokyhieubaocao");
    tieudebaocao = document.getElementById("tieudebaocao");
    nambaocao = document.getElementById("nambaocao");
    hoanthanh = document.getElementById("hoanthanh");
    gridDonvi = document.getElementById("gridDonvi");
    gridBieumau = document.getElementById("gridBieumau");
    filedinhkem = document.getElementById("filedinhkem");
    noidung = document.getElementById("noidung");
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
                "../report/xemtruocbieumaubaocao.mrt",
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
