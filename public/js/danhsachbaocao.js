var treeList;

$(document).ready(() => {
    if ($("#danhsachbaocao").length) {
        loadData();
    }
});

function loadData() {
    treeList = $("#GridBaocao")
        .dxDataGrid({
            dataSource: "indexDanhsachBaocao",
            keyExpr: "id",
            showRowLines: true,
            showBorders: true,
            columnAutoWidth: true,
            selection: {
                mode: "multiple",
                allowSelectAll: false,
            },
            columns: [
                {
                    dataField: "sohieu",
                    caption: "Số hiệu",
                },
                {
                    dataField: "tieude",
                    caption: "Tiêu đề",
                },
                {
                    dataField: "tenky",
                    caption: "Kỳ báo cáo",
                },
                {
                    dataField: "ngaycapnhatsaucung",
                    caption: "Kỳ báo cáo",
                },
                {
                    dataField: "tentaikhoan",
                    caption: "Người cập nhật",
                },
                {
                    dataField: "gui",
                    caption: "Gửi",
                    cellTemplate: function (container, options) {
                        if (options.data.gui == 0) {
                            $(
                                "<span style='color:green'>Chưa gửi</span>"
                            ).appendTo(container);
                        }
                        if (options.data.gui == 1) {
                            $(
                                "<span style='color:blue'>Đã gửi</span>"
                            ).appendTo(container);
                        }
                    },
                },
                {
                    allowEditing: false,
                    cellTemplate: function (container, options) {
                        container.addClass("center");

                        $("<div>")
                            .dxButton({
                                type: "success",
                                text: "Xem",
                                template: function (e) {
                                    return $("<i class='far fa-eye'>")
                                        .text(" Xem")
                                        .css("color", "#ffffff");
                                },
                                onClick: function (e) {
                                    window.location =
                                        "xemchitietbaocao/" + options.data.id;
                                },
                            })
                            .appendTo(container);
                    },
                    width: 300,
                },
            ],
            onToolbarPreparing: function (e) {
                var dataGrid = e.component;
                e.toolbarOptions.items.unshift(
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "plus",
                            onClick: function () {
                                localStorage.setItem("Baocao", 0);
                                localStorage.setItem("view", 0);
                                window.location = "viewThongtinBaocao";
                            },
                        },
                    },
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "edit",
                            onClick: function () {
                                let selected = treeList.getSelectedRowsData(
                                    "all"
                                );
                                if (selected.length > 1) {
                                    Swal.fire(
                                        "Cảnh báo",
                                        "Chỉ được chỉnh sửa 1 mục",
                                        "warning"
                                    );
                                } else {
                                    localStorage.setItem(
                                        "Baocao",
                                        selected[0].id
                                    );
                                    localStorage.setItem("view", 0);
                                    window.location = "viewThongtinBaocao";
                                }
                            },
                        },
                    },
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "trash",
                            onClick: function () {
                                var selectedData = treeList.getSelectedRowsData();

                                Swal.fire({
                                    title: "Xoá dữ liệu",
                                    text: "Bạn muốn xoá dữ liệu",
                                    icon: "question",
                                    cancelButtonText: "Không",
                                    showCancelButton: true,
                                    confirmButtonText: "Xoá",
                                })
                                    .then((result) => {
                                        if (result.value) {
                                            axios
                                                .post("deleteBaocao", {
                                                    baocao: JSON.stringify(
                                                        selectedData
                                                    ),
                                                })
                                                .then((res) => {
                                                    if (res.status == 200) {
                                                        dataGrid.refresh();
                                                    }
                                                })
                                                .catch((err) => {
                                                    console.log(err);
                                                });
                                        }
                                    })
                                    .catch((err) => {
                                        console.log(err);
                                    });
                            },
                        },
                    },
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "message",
                            onClick: function () {
                                var selectedData = treeList.getSelectedRowsData();
                                if (selectedData[0].gui == 1) {
                                    Swal.fire(
                                        "Báo cáo đã được gửi không thể gửi lại",
                                        "Báo cáo đã được gửi đi",
                                        "info"
                                    );
                                } else {
                                    axios
                                        .get("sendBaocao/" + selectedData[0].id)
                                        .then((res) => {
                                            if (res.status == 200) {
                                                Swal.fire(
                                                    "Đã gửi",
                                                    "Bạn đã gửi thành công",
                                                    "success"
                                                );
                                                dataGrid.refresh();
                                            }
                                        })
                                        .catch((err) => {
                                            console.log(err);
                                        });
                                }
                            },
                        },
                    }
                );
            },
        })
        .dxDataGrid("instance");
}
