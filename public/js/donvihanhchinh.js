var tendonvi,
    donvitructhuoc,
    sobannganh,
    thixa,
    sodienthoai,
    email,
    diachi,
    tinh,
    quan,
    huyen,
    xa,
    mota,
    submit,
    title;

var idDonvi = null;
var huyenSelect = 0;

$(document).ready(function () {
    bindControl();
    $("#open-create-modal").click(function () {
        idDonvi = null;
        $("#modelForCreateDonvihanhchinh").modal("show");
    });

    $("#default-datatable").DataTable({
        ordering: false,
        searching: false,
        paging: false,
        info: false,
        bDestroy: true,
    });

    TreeViewServices("donvihanhchinh");
    bindEvent();
});

function bindControl() {
    tendonvi = document.querySelector("#create-input-tendonvi");
    donvitructhuoc = document.getElementById("create-input-donvihanhchinhcha");
    sobannganh = document.getElementById("create-input-thuoc-sobannganh");
    thixa = document.getElementById("create-input-thuoc-huyen");
    sodienthoai = document.getElementById("create-input-sodienthoai");
    email = document.getElementById("create-input-email");
    diachi = document.getElementById("create-input-diachi");
    tinh = document.getElementById("create-input-tinh");
    huyen = document.getElementById("create-input-huyen");
    xa = document.getElementById("create-input-phuong");
    mota = document.getElementById("create-input-mota");
    submit = document.getElementById("submit-button-for-create");
    title = document.getElementById("modelForCreateDonvihanhchinhLabel");
}

function bindEvent() {
    document.getElementById("button-confirm-delete").onclick = function (e) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "DELETE",
            url: "/donvihanhchinh/" + idDonvi,
            success: function (data) {
                if (data["success"]) {
                    window.location.reload();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(
                    thrownError +
                        "\r\n" +
                        xhr.statusText +
                        "\r\n" +
                        xhr.responseText
                );
            },
        });
    };
    submit.onclick = function (e) {
        e.preventDefault();
        if (idDonvi == null) {
            // Luu du lieu
            let thuoc;
            if (sobannganh.checked) {
                thuoc = sobannganh.value;
            } else {
                thuoc = thixa.value;
            }

            let data = {
                tendonvi: tendonvi.value,
                thuoc: thuoc,
                sodienthoai: sodienthoai.value,
                email: email.value,
                donvihanhchinhcha: donvitructhuoc.value,
                diachi: diachi.value,
                mota: mota.value,
                tinh: tinh.value,
                phuong: xa.value,
            };
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "POST",
                url: "/donvihanhchinh",
                data,
                success: function (data) {
                    window.location.reload();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(
                        thrownError +
                            "\r\n" +
                            xhr.statusText +
                            "\r\n" +
                            xhr.responseText
                    );
                },
            });
        } else {
            let thuoc;
            if (sobannganh.checked) {
                thuoc = sobannganh.value;
            } else {
                thuoc = thixa.value;
            }

            let data = {
                tendonvi: tendonvi.value,
                thuoc: thuoc,
                sodienthoai: sodienthoai.value,
                email: email.value,
                donvihanhchinhcha: donvitructhuoc.value,
                diachi: diachi.value,
                mota: mota.value,
                tinh: tinh.value,
                phuong: xa.value,
            };
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "PUT",
                url: "/donvihanhchinh/" + idDonvi,
                data,
                success: function (data) {
                    window.location.reload();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(
                        thrownError +
                            "\r\n" +
                            xhr.statusText +
                            "\r\n" +
                            xhr.responseText
                    );
                },
            });
        }
    };
}

function themDonvi(e) {
  idDonvi = null;
    let idcha = e.dataset.id;
    donvitructhuoc.value = idcha;
    tendonvi.value = "";
    sodienthoai.value = "";
    email.value = "";
    diachi.value = "";
    tinh.value='none';
    huyen.value = "";
    xa.value = "";
    mota.value = "";
}
function suaDonvi(e) {
    idDonvi = e.dataset.id;
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "GET",
        url: "/donvihanhchinh/getchinhsua/" + idDonvi,
        success: function (data) {
            tendonvi.value = data.tendonvi;
            data.madonvi == null
                ? (donvitructhuoc.value = "none")
                : (donvitructhuoc.value = data.madonvi);
            data.thuoc == "1"
                ? (sobannganh.checked = true)
                : (thixa.checked = true);
            sodienthoai.value = data.sodienthoai;
            email.value = data.email;
            diachi.value = data.diachi;
            tinh.value = data.diaban;
            tinh.dispatchEvent(new Event("change"));
            huyen.value = data.huyen;
            huyenSelect = data.huyen;
            huyen.dispatchEvent(new Event("change"));
            xa.value = data.phuong;
            mota.value = data.mota;
            title.innerText = "Chỉnh sửa đơn vị hành chính";
            submit.innerText = "Sửa";
            $("#modelForCreateDonvihanhchinh").modal("show");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(
                thrownError +
                    "\r\n" +
                    xhr.statusText +
                    "\r\n" +
                    xhr.responseText
            );
        },
    });
}

function xoaDonvi(e) {
    idDonvi = e.dataset.id;
    $("#deleteModal").modal("show");
}

function loadDistrict(id, create = true) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "GET",
        url: "listDistrictWithProvince/" + id,
        success: function (data) {
            if (!$.isEmptyObject(data.error)) {
                printErrorMsg(data.error, "print-error-msg-on-edit");
            } else {
                if (create == true) {
                    $("#create-input-huyen").empty();
                    data.map((item) => {
                        $("#create-input-huyen").append(
                            `<option value=` +
                                item.id +
                                `>` +
                                item._name +
                                `</option>`
                        );
                    });
                } else {
                    $("#huyen").empty();
                    data.map((item) => {
                        $("#huyen").append(
                            `<option value=` +
                                item.id +
                                `>` +
                                item._name +
                                `</option>`
                        );
                    });
                }
                let option = huyen.getElementsByTagName("option");
                option[0].selected = true;
                huyen.dispatchEvent(new Event("change"));
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(
                thrownError +
                    "\r\n" +
                    xhr.statusText +
                    "\r\n" +
                    xhr.responseText
            );
        },
    });
}

function loadPhuong(id, create = true) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "GET",
        url: "listXaphuongwithQuanhuyen/" + id,
        success: function (data) {
            if (!$.isEmptyObject(data.error)) {
                printErrorMsg(data.error, "print-error-msg-on-edit");
            } else {
                if (create == true) {
                    $("#create-input-phuong").empty();
                    data.map((item) => {
                        $("#create-input-phuong").append(
                            `<option value=` +
                                item.id +
                                `>` +
                                item._name +
                                `</option>`
                        );
                    });
                } else {
                    $("#phuong").empty();
                    data.map((item) => {
                        $("#phuong").append(
                            `<option value=` +
                                item.id +
                                `>` +
                                item._name +
                                `</option>`
                        );
                    });
                }
                let option = xa.getElementsByTagName("option");
                option[0].selected = true;
                xa.dispatchEvent(new Event("change"));
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(
                thrownError +
                    "\r\n" +
                    xhr.statusText +
                    "\r\n" +
                    xhr.responseText
            );
        },
    });
}

$(document).ready(function () {
    $("#create-input-tinh").on("change", function () {
        let id = this.value;
        loadDistrict(id);
    });
    $("#create-input-huyen").on("change", function () {
        if (idDonvi == null) {
            let id = this.value;
            loadPhuong(id);
        } else {
            loadPhuong(huyenSelect);
        }
    });
});
