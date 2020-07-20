$(function() {
    let bieumauso = $("#bieumauso").val();

    axios
        .post("/loadtableinfobieumau", {
            id: bieumauso
        })
        .then(function(response) {
            var datas = response.data;

            if (datas == "") {
                Swal.fire({
                    title: "Có lỗi!",
                    text: "Đã có lối xảy ra! Vui lòng kiểm tra và thử lại",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
            $("#treelist").dxTreeList({
                dataSource: datas,
                //cha con
                // itemsExpr: "children",
                // dataStructure: "tree",
                keyExpr: "id",
                parentIdExpr: "parent_id",
                // hasItemsExpr:"children_all",
                //sap xep
                sorting: {
                    mode: "multiple"
                },
                columnAutoWidth: true,
                // wordWrapEnabled: true,
                showRowLines: true,
                //phan trang
                showBorders: true,
                scrolling: {
                    mode: "standard"
                },
                paging: {
                    enabled: true,
                    pageSize: 10
                },
                pager: {
                    showPageSizeSelector: true,
                    allowedPageSizes: [5, 10, 20],
                    showInfo: true
                },
                //check box
                // selection: {
                // 	mode: "multiple",
                // 	recursive: true,
                // },
                //loc row
                filterRow: {
                    visible: true
                },
                //co dan cot
                allowColumnResizing: true,
                //thu phóng row
                // autoExpandAll: true,
                columns: [
                    {
                        dataField: "machitieu",
                        caption: "Mã chỉ tiêu"
                        // visible :false,
                    },
                    {
                        dataField: "tenchitieu",
                        caption: "Tên chỉ tiêu"
                    },
                    {
                        dataField: "KHYearPercent",
                        caption: "So KH năm (%)",
                        validationRules: [{ type: "required" }]
                    },
                    {
                        dataField: "KHdate",
                        caption: "TH kỳ",
                        validationRules: [{ type: "required" }]
                    },

                    {
                        dataField: "KHdate",
                        caption: "KH kỳ",
                        validationRules: [{ type: "required" }]
                    },
                    {
                        dataField: "Quanity",
                        caption: "SL ước",
                        validationRules: [{ type: "required" }]
                    },
                    {
                        dataField: "Quanityreal",
                        caption: "Số lượng chính thức",
                        validationRules: [{ type: "required" }]
                    },
                    {
                        dataField: "Compare",
                        caption: "So kỳ trước (+/-)",
                        validationRules: [{ type: "required" }]
                    },
                    {
                        dataField: "day",
                        caption: "Số liệu theo ngày",
                        validationRules: [{ type: "required" }]
                    },
                    {
                        dataField: "KHYear",
                        caption: "KH năm",
                        validationRules: [{ type: "required" }]
                    },
                    {
                        dataField: "donvitinh",
                        caption: "Đơn vị tính",
                        validationRules: [{ type: "required" }]
                    }
                ],
                expandedRowKeys: [2]
            });
        });

    $("#trolai").click(function() {
        window.location = "listdanhsachbieumau";
    });
});
