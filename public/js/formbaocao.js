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
    idBaocao = Number(localStorage.getItem("idbaocao"));
    if (idBaocao != 0) {
        setTimeout(loadInfoEdit, 500);
    }

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
            checkallSelect();
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

        if (idBaocao == 0) {
            axios
                .post("LuuBieumaubaocao", {
                    donvi: window.idphongban,
                    taikhoan: window.idnguoidung,
                    sohieu: $("#sohieu").val(),
                    tenbieumau: $("#tenbieumau").val(),
                    soquyetdinh: $("#soquyetdinh").val(),
                    ngayquyetdinh: time,
                    mota: $("#mota").val(),
                    trangthaiapdung: trangthaiapdung,
                    trangthaisudung: 0,
                    kybaocao: kybaocaosudungbieumau,
                    loaisolieu: loaisolieu,
                    chitieu: JSON.stringify(arrID),
                    loaibaocao: 1,
                })
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
            axios
                .post("SuaBieumaubaocao", {
                    donvi: window.idphongban,
                    taikhoan: window.idnguoidung,
                    sohieu: $("#sohieu").val(),
                    tenbieumau: $("#tenbieumau").val(),
                    soquyetdinh: $("#soquyetdinh").val(),
                    ngayquyetdinh: time,
                    mota: $("#mota").val(),
                    trangthaiapdung: trangthaiapdung,
                    trangthaisudung: 0,
                    kybaocao: kybaocaosudungbieumau,
                    loaisolieu: loaisolieu,
                    chitieu: JSON.stringify(arrID),
                    loaibaocao: 1,
                    id: idBaocao,
                })
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
