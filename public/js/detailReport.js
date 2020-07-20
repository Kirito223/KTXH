import thembaocaodinhky from "./thembaocaodinhky.js";
$(document).ready(() => {
    initEvent();
});

function initEvent() {
    let btnView = document.getElementsByClassName("btn-view");
    for (let index = 0; index < btnView.length; index++) {
        $(btnView[index]).on("click", () => {
            let id = $(btnView[index]).data("id");
            thembaocaodinhky.ReviewReport(id);
        });
    }

    $("#btnTrove").on("click", () => {
        window.location = "viewDuyetbaocao";
    });
}
