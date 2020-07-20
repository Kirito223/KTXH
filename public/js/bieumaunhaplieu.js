import Ultils from "../js/Ultil.js";
var trangthaiapdung;
var idBieumau = 0;
var chitiet = [];
var dataChiteu;
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
    idBieumau = Number(localStorage.getItem("idBieumau"));
    if (idBieumau != 0) {
        setTimeout(loadInfoEdit, 500);
    }
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
        if (idBieumau == 0) {
            axios
                .post("luuBieunauNhaplieuSolieu", {
                    sohieu: $("#sohieu").val(),
                    tenbieumau: $("#tenbieumau").val(),
                    soquyetdinh: $("#soquyetdinh").val(),
                    ngayquyetdinh: time,
                    mota: $("#mota").val(),
                    trangthaiapdung: trangthaiapdung,
                    chitieu: JSON.stringify(arrID),
                    loaibaocao: 1,
                })
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
            axios
                .post("editBieumauNhaplieu", {
                    donvi: window.idphongban,
                    taikhoan: window.idnguoidung,
                    sohieu: $("#sohieu").val(),
                    tenbieumau: $("#tenbieumau").val(),
                    soquyetdinh: $("#soquyetdinh").val(),
                    ngayquyetdinh: time,
                    mota: $("#mota").val(),
                    trangthaiapdung: trangthaiapdung,
                    chitieu: JSON.stringify(arrID),
                    id: idBieumau,
                })
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
            checkallSelect();
        })
        .catch((err) => {
            console.log(err);
        });
}
