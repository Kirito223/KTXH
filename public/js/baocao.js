var treeList;

$(document).ready(() => {
    loadData();
    initEvent();
});

function loadData() {
    treeList = $("#GridBaocao")
        .dxDataGrid({
            dataSource: "Bieumaubaocaoindex",
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
                    dataField: "tenbieumau",
                    caption: "Tên biểu mẫu",
                },
                {
                    dataField: "file",
                    caption: "File quyết định",
                    cellTemplate: function (element, info) {
                        element
                            .append(
                                '<a href="http://ctktxh.lihanet.com/upload/' +
                                    info.text +
                                    '">' +
                                    info.text +
                                    "</a>"
                            )
                            .css("color", "red");
                    },
                },
                {
                    dataField: "tentaikhoan",
                    caption: "Người cập nhật",
                },
                {
                    dataField: "trangthai",
                    caption: "Áp dụng",
                    cellTemplate: function (element, info) {
                        if (info.text == 1) {
                            element
                                .append("<span>Đã áp dụng </span>")
                                .css("color", "green");
                        } else {
                            element
                                .append("<span>Chưa áp dụng </span>")
                                .css("color", "blue");
                        }
                    },
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
                                localStorage.setItem("idbaocao", 0);
                                window.location = "formthembaocao";
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
                                        "idbaocao",
                                        selected[0].id
                                    );
                                    window.location = "editBieumauBaocao";
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
                                    text: "Bạn có muốn xoá dữ liệu không",
                                    icon: "warning",
                                    showCancelButton: true,
                                    cancelButtonText: "Không",
                                    confirmButtonText: "Có",
                                }).then((result) => {
                                    if (result.value) {
                                        axios
                                            .post("destroyBieumauBaocao", {
                                                bieumau: JSON.stringify(
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
                                });
                            },
                        },
                    },
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "check",
                            onClick: function () {
                                var selectedData = treeList.getSelectedRowsData();

                                if (selectedData.length > 1) {
                                    Swal.fire(
                                        "Chọn mẫu",
                                        "Vui lòng chọn 1 mẫu muốn sử dụng",
                                        "success"
                                    );
                                } else if (
                                    selectedData.length == 1 &&
                                    selectedData != 0
                                ) {
                                    Swal.fire({
                                        title: "Áp dụng mẫu",
                                        text:
                                            "Bạn có muốn áp dụng mẫu " +
                                            selectedData[0].tenbieumau +
                                            " không?",
                                        icon: "warning",
                                        confirmButtonText: "Áp dụng",
                                        confirmButtonColor: "#3085d6",
                                        showCancelButton: true,
                                        cancelButtonColor: "d33",
                                        cancelButtonText: "Không",
                                    }).then((result) => {
                                        if (result.value) {
                                            axios
                                                .get(
                                                    "ApplyBieumauBaocao/" +
                                                        selectedData[0].id
                                                )
                                                .then((res) => {
                                                    if (res.data == 200) {
                                                        Swal.fire(
                                                            "Đã áp dụng",
                                                            "Bạn đã áp dụng thành công mẫu ",
                                                            "success"
                                                        );
                                                        treeList.refresh();
                                                    }
                                                })
                                                .catch((err) => {
                                                    console.log(err);
                                                });
                                        }
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
function initEvent() {}
