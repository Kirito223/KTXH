var treeList;

$(document).ready(() => {
    loadData();
    initEvent();
});

function loadData() {
    treeList = $("#gridBieumau")
        .dxDataGrid({
            dataSource: "indexNhaplieuBieumau",
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
                    dataField: "tenloaisolieu",
                    caption: "Loại số liệu"
                },
				{
                    dataField: "namnhap",
                    caption: "Năm nhập", 
				
                },
                {
                    dataField: "tentaikhoan",
                    caption: "Người cập nhật"
                },
                
                {
                    dataField: "created_at",
                    caption: "Ngày nhập",
                    customizeText: function(cellInfo) {
                     return   moment(cellInfo.value).format(
                            "DD/MM/YYYY"
                        )
                    }
                   
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
                                localStorage.setItem("idBieunhap", 0);
                                window.location = "viewNhaplieuBieumau";
                            }
                        }
                    },
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "edit",
                            onClick: function() {
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
                                        "idBieunhap",
                                        selected[0].id
                                    );
                                    window.location = "viewNhaplieuBieumau";
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
                                var selectedData = treeList.getSelectedRowsData();
                                Swal.fire({
                                    title: "Xoá dữ liệu",
                                    text: "Bạn có muốn xoá dữ liệu không",
                                    showCancelButton: true,
                                    cancelButtonText: "Đóng",
                                    confirmButtonText: "Đồng ý",
                                    icon: "warning"
                                }).then(result => {
                                    if (result.value) {
                                        axios
                                            .post("DelBieumau", {
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
function initEvent() {}
