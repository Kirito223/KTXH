import Ultil, { initBieumau } from "../js/Ultil.js";

var location,
    nameLocation,
    startYear,
    endYear,
    template,
    typeOfFigures,
    btnView,
    viewExcel;

$(document).ready(() => {
    initControl();
    loadData();
    initEvent();
});
function initControl() {
    location = document.getElementById("location");
    nameLocation = document.getElementById("nameLocation");
    startYear = document.getElementById("startYear");
    endYear = document.getElementById("endYear");
    template = document.getElementById("teamplate");
    btnView = document.getElementById("btnView");
    typeOfFigures = document.getElementById("typeOfFigures");
    viewExcel = document.getElementById("viewExcel");
}
function loadData() {
    initYear();
    initTemplate();
    initTypeOfFigure();
}
function initEvent() {
    location.onchange = (e) => {
        if (e.target.value == 1) {
            getHuyen();
        } else {
            getXa();
        }
    };
    btnView.onclick = function () {
        axios.post("/exportproductionvalue/export", getData()).then((res) => {
            viewExcel.setAttribute(
                "src",
                `https://view.officeapps.live.com/op/embed.aspx?src=${window.location.host}/export/Kehoachsanxuat.xlsx`
            );
        });
    };
}

function getData() {
    return {
        localtion: nameLocation.value,
        startYear: startYear.value,
        endYear: endYear.value,
        template: template.value,
        type: typeOfFigures.value,
    };
}
function initYear() {
    let year = Ultil.listNam();
    let htmlYear = "";

    year.map((item) => {
        htmlYear += `<option value="${item}">${item}</option>`;
    });
    startYear.innerHTML = htmlYear;
    endYear.innerHTML = htmlYear;

    startYear.value = new Date().getFullYear();
    endYear.value = new Date().getFullYear() + 1;
}

async function initTemplate() {
    axios
        .get("danhsachbieumau")
        .then((res) => {
            let html = "";
            res.data.forEach((item) => {
                html += `<option value="${item.id}">${item.tenbieumau}</option>`;
            });
            template.innerHTML =
                `<option value="">------------</option>` + html;
        })
        .catch((err) => {
            console.error(err);
        });
}
async function initTypeOfFigure() {
    axios
        .get("getloaisolieu")
        .then((res) => {
            let html = "";
            res.data.forEach((item) => {
                html += `<option value="${item.id}">${item.tenloaisolieu}</option>`;
            });
            typeOfFigures.innerHTML =
                `<option value="">Chọn loại số liệu</option>` + html;
        })
        .catch((err) => {
            console.error(err);
        });
}
async function getHuyen() {
    axios
        .get("danhsachHuyen")
        .then((res) => {
            let html = "";
            res.data.forEach((item) => {
                html += `<option value="${item.id}">${item.tendonvi}</option>`;
            });
            nameLocation.innerHTML =
                `<option value="">----------</option>` + html;
        })
        .catch((err) => {
            console.error(err);
        });
}
async function getXa() {
    axios
        .get("danhsachXa")
        .then((res) => {
            let html = "";
            res.data.forEach((item) => {
                html += `<option value="${item.id}">${item.tendonvi}</option>`;
            });
            nameLocation.innerHTML =
                `<option value="">----------</option>` + html;
        })
        .catch((err) => {
            console.error(err);
        });
}
