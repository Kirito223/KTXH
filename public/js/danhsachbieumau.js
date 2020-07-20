var gridData;
$(document).ready(() => {
    loadData();
});

function loadData() {
    gridData = $("#GridBieumau")
        .dxDataGrid({
            dataSource: "Nhaplieuindex",
            keyExpr: "id",
            showRowLines: true,
            showBorders: true,
            columnAutoWidth: true,
            selection: {
                mode: "multiple",
                allowSelectAll: false
            },
            columns: [
                {
                    dataField: "sohieu",
                    caption: "Số hiệu"
                },
                {
                    dataField: "tenbieumau",
                    caption: "Tên biểu mẫu"
                },
                {
                    dataField: "tentaikhoan",
                    caption: "Người cập nhật"
                },
                {
                    dataField: "created_at",
                    caption: "Ngày tạo",
                   customizeText: function(cellInfo) {
                     return   moment(cellInfo.value).format(
                            "DD/MM/YYYY"
                        )
                    }
                }, 
                {
                    dataField: "namnhap",
                    caption: "Năm",
                  
                }
            ],
            onToolbarPreparing: function(e) {
                var dataGrid = e.component;
                e.toolbarOptions.items.unshift(
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "plus",
                            onClick: function() {
								localStorage.setItem(
                                        "idInputReport",
                                        0
                                    );
                                window.location = "viewNhaplieuBaocao";
                            }
                        }
                    },
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "edit",
                            onClick: function() {
                                let selected = gridData.getSelectedRowsData(
                                    "all"
                                );
                                if (selected.length > 1) {
                                    Swal.fire(
                                        "Chọn một đối tượng để chỉnh sửa",
                                        "Chọn đối tượng chỉnh sửa",
                                        "warning"
                                    );
                                } else {
                                    let idBieumau = selected[0].id;
                                    localStorage.setItem(
                                        "idInputReport",
                                        idBieumau
                                    );
                                    window.location = "viewNhaplieuBaocao";
                                }
                            }
                        }
                    },
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "trash",
                            onClick: function() {
                                var selectedData = dataGrid.getSelectedRowsData();

                                Swal.fire({
                                    title: "Xoá dữ liệu",
                                    text:
                                        "Bạn có muốn xoá các biểu mẫu hay không",
                                    confirmButtonColor: "#eb090d",
                                    confirmButtonText: "Có xoá",
                                    cancelButtonColor: "#141ede",
                                    cancelButtonText: "Thoát",
                                    showCancelButton: true
                                }).then(result => {
                                    if (result.value) {
                                        axios
                                            .post("xoaNhaplieuBaocao", {
                                                bieumau: JSON.stringify(
                                                    selectedData
                                                )
                                            })
                                            .then(res => {
                                                if (res.status == 200) {
                                                    dataGrid.refresh();
                                                }
                                            })
                                            .catch(err => {
                                                console.log(err);
                                            });
                                    }
                                });
                            }
                        }
                    }
                );
            }
        })
        .dxDataGrid("instance");
}
