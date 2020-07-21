import Ultils from "../js/Ultil.js";
var trangthaiapdung;
var idBieumau = 0;
var chitiet = [];
var dataChiteu;
var fileEdit;

import {
    renderTable,
    checkallSelect,
    selectNode,
    selectedParent,
} from "./treeTable.js";

$(document).ready(() => {
    loadData();
    initEvent();
});

function loadData() {
    axios
        .get("getSelectChitieu")
        .then((res) => {
            dataChiteu = res.data;
            renderTable(dataChiteu);
            $("#tableChitieu").treetable({
                expandable: true,
            });
            idBieumau = Number(localStorage.getItem("idBieumau"));
            if (idBieumau != 0) {
                setTimeout(loadInfoEdit, 500);
            }
        })
        .catch((err) => {
            console.log(err);
        });

    let now = new Date();
    $("#ngayquyetdinh").dxDateBox({
        type: "date",
        value: now,
        displayFormat: "shortdate",
    });
}

//Ham gan cac su kien cho cac control
function initEvent() {
    $("#allSelect").on("click", () => {
        let chk = document.getElementsByClassName("chk-child");
        let checkparent = document.getElementById("allSelect").checked;
        for (let index = 0; index < chk.length; index++) {
            let item = chk[index];
            if (item.checked != checkparent) {
                item.checked = checkparent;
            }
        }
    });

    $("#btnLuu").on("click", () => {
        let file = null;
        file = document.getElementById("file").files[0];

        trangthaiapdung = Ultils.checkStatusCheckBox("trangthaiapdung");
        let ngayQD = $("#ngayquyetdinh").dxDateBox("instance");
        let dateBoxValue = ngayQD.option("value");
        // Lay thong tin du lieu
        let checkedChitieu = document.querySelectorAll(
            "input[class=chk-child]:checked"
        );
        let arrID = [];
        for (let index = 0; index < checkedChitieu.length; index++) {
            arrID.push(Number(checkedChitieu[index].value));
        }

        let time = moment(dateBoxValue).format("YYYY-MM-DD");

        let config = {
            headers: { "content-type": "multipart/form-data" },
        };

        if (idBieumau == 0) {
            let formData = new FormData();
            formData.append("sohieu", $("#sohieu").val());
            formData.append("tenbieumau", $("#tenbieumau").val());
            formData.append("soquyetdinh", $("#soquyetdinh").val());
            formData.append("ngayquyetdinh", time);
            formData.append("mota", $("#mota").val());
            formData.append("trangthaiapdung", trangthaiapdung);
            formData.append("chitieu", JSON.stringify(arrID));
            formData.append("loaibaocao", 1);
            if (file != null) {
                formData.append("file", file);
            }
            axios
                .post("luuBieunauNhaplieuSolieu", formData, config)
                .then((res) => {
                    if (res.status == 200) {
                        window.location = "viewDanhsachBieumauNhaplieu";
                    } else {
                        Swal.fire(
                            "Cảnh báo",
                            "Đã có lỗi xảy ra vui lòng thử lại sau",
                            "warning"
                        );
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        }
        if (idBieumau != 0) {
            // Sua bieu mau
            let formData = new FormData();
            formData.append("donvi", window.idphongban);
            formData.append("taikhoan", window.idnguoidung);
            formData.append("sohieu", $("#sohieu").val());
            formData.append("tenbieumau", $("#tenbieumau").val());
            formData.append("soquyetdinh", $("#soquyetdinh").val());
            formData.append("ngayquyetdinh", time);
            formData.append("mota", $("#mota").val());
            formData.append("trangthaiapdung", trangthaiapdung);
            formData.append("chitieu", JSON.stringify(arrID));
            formData.append("id", idBieumau);
            if (file == undefined) {
                formData.append("file", null);
            } else {
                formData.append("file", file);
            }
            axios
                .post("editBieumauNhaplieu", formData, config)
                .then((res) => {
                    if (res.status == 200) {
                        localStorage.setItem("idBieumau", 0);
                        window.location = "viewDanhsachBieumauNhaplieu";
                    } else {
                        Swal.fire(
                            "Cảnh báo",
                            "Đã có lỗi xảy ra vui lòng thử lại sau",
                            "warning"
                        );
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    });

    $("#btnThoat").on("click", () => {
        localStorage.setItem("idBieumau", 0);
        window.location = "viewDanhsachBieumauNhaplieu";
    });
}
function loadInfoEdit() {
    axios
        .get("showBieumauNhaplieu/" + idBieumau)
        .then((res) => {
            let data = res.data;
            let thongtinchung = data.thongtinchung[0];
            chitiet = data.chitiet;
            $("#nguoitao").text(thongtinchung.tentaikhoan);
            $("#donvi").text(thongtinchung.tendonvi);
            $("#sohieu").val(thongtinchung.sohieu);
            $("#tenbieumau").val(thongtinchung.tenbieumau);
            $("#soquyetdinh").val(thongtinchung.soquyetdinh);
            $("#mota").val(thongtinchung.mota);
            if (thongtinchung.trangthai == 1) {
                $("#trangthaiapdung").prop("checked", true);
            }
            chitiet.map((item) => {
                let element = document.querySelector(
                    "input[value='" + item.chitieu + "']"
                );
                element.checked = true;
            });
            if (thongtinchung.file != null) {
                $("#listFile").append(
                    `<li><a href="#">${thongtinchung.file}</a> <i class="fas fa-trash-alt fa-sm fa-fw"></i></li>`
                );
                fileEdit = thongtinchung.file;
            }
            checkallSelect();
        })
        .catch((err) => {
            console.log(err);
        });
}
