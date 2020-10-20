$(document).ready(function () {
    var arrChitieu = [];
    var btnUpdate,
        btnDelete,
        name,
        unit,
        parent,
        btnCancel,
        code,
        btnSave,
        form;
    var idEdit = null;
    btnUpdate = document.getElementById("btnUpdate");
    btnDelete = document.getElementById("btnDelete");
    name = document.getElementById("name");
    unit = document.getElementById("unit");
    parent = document.getElementById("parent");
    btnCancel = document.getElementById("btnCancel");
    code = document.getElementById("code");
    btnSave = document.getElementById("btnSave");
    form = document.getElementById("form");

    $("#treelist").dxTreeList({
        dataSource: {
            load: function (options) {
                return $.ajax({
                    url: "getlistchitieu",
                    dataType: "json",
                    // data: { parentIds: options.parentIds }
                });
            },
        },
        keyExpr: "id",
        parentIdExpr: "idcha",
        autoExpandAll: true,
        paging: {
            enabled: true,
            pageSize: 100,
        },
        pager: {
            showPageSizeSelector: true,
            allowedPageSizes: [50, 100, 150],
            showInfo: true,
        },
        sorting: {
            mode: "multiple",
        },
        columnAutoWidth: true,
        showRowLines: true,
        //phan trang
        showBorders: true,
        scrolling: {
            mode: "standard",
        },
        paging: {
            enabled: true,
            pageSize: 10,
        },
        pager: {
            showPageSizeSelector: true,
            allowedPageSizes: [5, 10, 20],
            showInfo: true,
        },
        //check box
        selection: {
            mode: "multiple",
            recursive: false,
        },
        //loc row
        filterRow: {
            visible: true,
        },
        //co dan cot
        allowColumnResizing: true,
        //thu phóng row
        // autoExpandAll: true,

        columns: [
            {
                dataField: "tenchitieu",
                caption: "Tên chỉ tiêu",
                validationRules: [{ type: "required" }],
            },
            {
                dataField: "tendonvi",
                caption: "Đơn vị tính",
                validationRules: [{ type: "required" }],
            },
            {
                cellTemplate: function (element, info) {
                    element.addClass("center");
                    $("<div>")
                        .dxButton({
                            type: "default",
                            text: "Sửa",
                            template: function (e) {
                                return $("<i class='fas fa-edit'>")
                                    .text(" Sửa")
                                    .css("color", "#ffffff");
                            },
                            onClick: function (e) {
                                showEdit(info.data.id);
                            },
                        })
                        .appendTo(element);
                },
            },
        ],
    });

    code.oninput = function () {
        btnCancel.disabled = false;
    };

    btnSave.onclick = function () {
        add();
    };

    btnCancel.onclick = function (e) {
        e.preventDefault();
        idEdit = null;
        btnSave.disabled = false;
        btnUpdate.disabled = true;
        btnCancel.disabled = true;
        form.reset();
    };

    btnUpdate.onclick = function (e) {
        update();
    };
    btnDelete.onclick = function (e) {
        e.preventDefault();
        del();
    };

    loaddonvitinh();

    function showEdit(id) {
        let index = arrChitieu.findIndex((x) => x.id == id);
        idEdit = id;
        name.value = arrChitieu[index].tenchitieu;
        code.value = arrChitieu[index].machitieu;
        unit.value = arrChitieu[index].donvitinh;
        parent.value = arrChitieu[index].idcha;
        btnCancel.disabled = false;
        btnSave.disabled = true;
        btnUpdate.disabled = false;
    }

    async function update() {
        let evaluation = getData();
        await axios
            .post("UpdateChitieu/" + idEdit, evaluation)
            .then(function (response) {
                if (response.status == 200) {
                    window.location.reload();
                } else {
                    Swal.fire({
                        title: "Có lỗi!",
                        text: "Đã có lối xảy ra! Vui lòng kiểm tra và thử lại",
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                }
            });
    }
    async function del() {
        let arrDel = $("#treelist")
            .dxTreeList("instance")
            .getSelectedRowsData();
        if (arrDel.length > 0) {
            await axios
                .post("DelChitieu", { data: JSON.stringify(arrDel) })
                .then(function (response) {
                    if (response.status == 200) {
                        window.location.reload();
                    } else {
                        Swal.fire({
                            title: "Có lỗi!",
                            text:
                                "Đã có lối xảy ra! Vui lòng kiểm tra và thử lại",
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    }
                });
        } else {
            Swal.fire({
                title: "Chưa chọn chỉ tiêu",
                text: "Vui lòng chọn ít nhất 1 chỉ tiêu muốn xóa",
                icon: "warning",
                confirmButtonText: "OK",
            });
        }
    }
    async function add() {
        let evaluation = getData();
        await axios.post("InsertChitieu", evaluation).then(function (response) {
            if (response.status == 200) {
                window.location.reload();
            } else {
                Swal.fire({
                    title: "Có lỗi!",
                    text: "Đã có lối xảy ra! Vui lòng kiểm tra và thử lại",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            }
        });
    }

    function getData() {
        return {
            machitieu: code.value,
            tenchitieu: name.value,
            donvi: unit.value,
            idcha: parent.value,
        };
    }

    function loaddonvitinh() {
        axios.get("getlistdonvitinh").then(function (response) {
            let data = response.data;
            let html = data.map(function (item) {
                return (
                    '<option value="' +
                    item.id +
                    '">' +
                    item.tendonvi +
                    " (" +
                    item.maso +
                    ")" +
                    "</option>"
                );
            });
            unit.innerHTML = '<option value=""></option>' + html;
        });
    }
    loadParent();
    async function loadParent() {
        await axios.get("getlistchitieu").then(function (response) {
            let html;
            response.data.forEach((item) => {
                arrChitieu.push(item);
                html +=
                    '<option value="' +
                    item.id +
                    '">' +
                    item.tenchitieu +
                    " (" +
                    item.id +
                    ")" +
                    "</option>";
            });
            parent.innerHTML = '<option value=""></option>' + html;
            +html;
        });
    }
});
