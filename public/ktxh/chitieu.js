$(function() {
    var lookupDataSource = new DevExpress.data.ODataStore({
        url: "getlistchitieu",
        key: "id",
        keyType: "Int32"
        // Other ODataStore options go here
    });
    var treeList = $("#TreeGridContainer")
        .dxTreeList({
            dataSource: lookupDataSource,
            keyExpr: "id",
            parentIdExpr: "idcha",
            showRowLines: true,
            showBorders: true,
            columnAutoWidth: true,
            selection: {
                mode: "multiple",
                recursive: true
            },
            onToolbarPreparing: function(e) {
                var dataGrid = e.component;
                e.toolbarOptions.items.unshift(
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "refresh",
                            onClick: function() {
                                dataGrid
                                    .refresh()
                                    .done(() => {
                                        console.log("done refesh");
                                    })
                                    .fail(() => {
                                        console.log("fail");
                                    });
                            }
                        }
                    },
                    {
                        location: "after",
                        widget: "dxButton",
                        options: {
                            icon: "plus",
                            onClick: function() {
                                console.log("add");
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
                                axios
                                    .post("DelChitieu", {
                                        id: selectedData[0].id
                                    })
                                    .then(res => {
                                        dataGrid.refresh();
                                    })
                                    .catch(err => {});
                            }
                        }
                    }
                );
            },

            columns: [
                {
                    dataField: "idcha",
                    caption: "Cha",
                    visible: false,
                    dataType: "int32",

                    lookup: {
                        dataSource: {
                            store: lookupDataSource
                        },
                        valueExpr: "id",
                        displayExpr: "tenchitieu"
                    },
                    validationRules: [{ type: "required" }]
                },
                {
                    dataField: "tenchitieu",
                    caption: "Tên chỉ tiêu"
                }
            ],
            expandedRowKeys: [1],
            showRowLines: true,
            showBorders: true,
            columnAutoWidth: true
        })
        .dxTreeList("instance");
});
