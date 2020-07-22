import Ultils from "../js/Ultil.js";
import {
    renderTable,
    checkallSelect,
    selectNode,
    selectedParent,
} from "./treeTable.js";
var trangthaiapdung;
var kybaocaosudungbieumau;
var loaisolieu = 0;
var idBaocao = 0;
var chitiet = [];
var treeList;
var dataChiteu;
$(document).ready(() => {
    loadData();
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

            idBaocao = Number(localStorage.getItem("idbaocao"));
            if (idBaocao != 0) {
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
    initEvent();
    loadKybaocao();
}

function loadInfoEdit() {
    axios
        .get("ChitietBieumaubaocao/" + idBaocao)
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
                $("#trangthaisudung").text("Đang sử dụng");
                $("#trangthaisudung").css("color", "green");
            }
            let chkKybaocao = $(
                "input.checkbox-kybaocao[data-id=" +
                    thongtinchung.kybaocao +
                    "]"
            );
            chkKybaocao.prop("checked", true);
            if (thongtinchung.loaisolieu == 1) {
                $("#checkbox-solieuchinhthuc").prop("checked", true);
            }
            chitiet.map((item) => {
                let element = document.querySelector(
                    "input[value='" + item.chitieu + "']"
                );
                element.checked = true;
            });
            if (thongtinchung.file != null) {
                $("#listFile").append(
                    `<li><a href="downloadFileQD/${thongtinchung.file}" target="blank">${thongtinchung.file}</a> <i class="fas fa-trash-alt fa-sm fa-fw" id="btnDelFile"></i></li>`
                );
            }
            checkallSelect();
            $("#btnDelFile").on("click", (e) => {
                axios
                    .get("delFileQuyetdinh/" + idBaocao)
                    .then((res) => {
                        let data = res.data;
                        if (data.code == 200) {
                            $("#listFile").empty();
                        }
                    })
                    .catch((err) => {
                        console.error(err);
                    });
            });
        })
        .catch((err) => {
            console.log(err);
        });
}

function loadKybaocao() {
    axios
        .get("danhsachkybaocao")
        .then((res) => {
            let data = res.data;
            data.forEach((element) => {
                $("#danhsachkybaocao").append(
                    `<div">
                <input name="kybaocao" type="radio" class="checkbox-kybaocao" data-id="` +
                        element.id +
                        `"><label
                  >` +
                        element.tenky +
                        `
                </label>
            </div>`
                );
            });
        })
        .catch((err) => {
            console.log("err", err);
        });
}

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

    $("#btnThoat").on("click", () => {
        localStorage.setItem("idbaocao", 0);
        window.location = "viewQuanlyBieumaubaocao";
    });
    $("#btnLuu").on("click", () => {
        let file = null;
        file = document.getElementById("file").files[0];

        trangthaiapdung = Ultils.checkStatusCheckBox("trangthaiapdung");

        let kybaocao = $("input.checkbox-kybaocao:checked");
        kybaocaosudungbieumau = $(kybaocao).data("id");

        if (Ultils.checkStatusCheckBox("checkbox-solieuchinhthuc")) {
            loaisolieu = 1;
        }
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
        if (idBaocao == 0) {
            let formData = new FormData();
            formData.append("donvi", window.idphongban);
            formData.append("taikhoan", window.idnguoidung);
            formData.append("sohieu", $("#sohieu").val());
            formData.append("tenbieumau", $("#tenbieumau").val());
            formData.append("soquyetdinh", $("#soquyetdinh").val());
            formData.append("ngayquyetdinh", time);
            formData.append("mota", $("#mota").val());
            formData.append("trangthaiapdung", trangthaiapdung);
            formData.append("trangthaisudung", 0);
            formData.append("kybaocao", kybaocaosudungbieumau);
            formData.append("loaisolieu", loaisolieu);
            formData.append("chitieu", JSON.stringify(arrID));
            formData.append("loaibaocao", 1);
            if (file != null) {
                formData.append("file", file);
            }
            axios
                .post("LuuBieumaubaocao", formData, config)
                .then((res) => {
                    if (res.status == 200) {
                        window.location = "viewQuanlyBieumaubaocao";
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
        if (idBaocao != 0) {
            let formData = new FormData();
            formData.append("donvi", window.idphongban);
            formData.append("taikhoan", window.idnguoidung);
            formData.append("sohieu", $("#sohieu").val());
            formData.append("tenbieumau", $("#tenbieumau").val());
            formData.append("soquyetdinh", $("#soquyetdinh").val());
            formData.append("ngayquyetdinh", time);
            formData.append("mota", $("#mota").val());
            formData.append("trangthaiapdung", trangthaiapdung);
            formData.append("trangthaisudung", 0);
            formData.append("kybaocao", kybaocaosudungbieumau);
            formData.append("loaisolieu", loaisolieu);
            formData.append("chitieu", JSON.stringify(arrID));
            formData.append("loaibaocao", 1);
            formData.append("id", idBaocao);
            if (file == undefined) {
                formData.append("file", null);
            } else {
                formData.append("file", file);
            }
            axios
                .post("SuaBieumaubaocao", formData, config)
                .then((res) => {
                    if (res.status == 200) {
                        localStorage.setItem("idbaocao", 0);
                        window.location = "viewQuanlyBieumaubaocao";
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
}
