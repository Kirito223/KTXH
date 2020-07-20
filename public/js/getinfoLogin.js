window.madonvi = $("#madonvi").val();
window.idphongban;
window.idnguoidung;
$(document).ready(() => {
    initInfo();
    loadInfoAccount();
});
function initInfo() {
    localStorage.setItem("phongban", $("#tendonvi").val());
    localStorage.setItem("tentaikhoan", $("#nameaccount").val());
    localStorage.setItem("idphongban", $("#madonvi").val());
    localStorage.setItem("idnguoidung", $("#userid").val());
}
function loadInfoAccount() {
    const phongban = localStorage.phongban;
    const tentaikhoan = localStorage.tentaikhoan;
    window.idphongban = localStorage.idphongban;
    window.idnguoidung = localStorage.idnguoidung;
    $("#nguoitao").text(tentaikhoan);
    $("#donvi").text(phongban);
}
export default {
    loadInfoAccount
};
