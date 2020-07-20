$(document).ready(function() {
    $("#treelist").dxTreeList({
        dataSource: {
            load: function(options) {
                return $.ajax({
                    url: "getlistchitieu",
                    dataType: "json"
                    // data: { parentIds: options.parentIds }
                });
            }
        },
        keyExpr: "id",
        parentIdExpr: "idcha",
        autoExpandAll: true,
        paging: {
            enabled: true,
            pageSize: 100
        },
        pager: {
            showPageSizeSelector: true,
            allowedPageSizes: [50, 100, 150],
            showInfo: true
        },
        // editing: {
        // mode: "form",
        // allowUpdating: true,
        // allowDeleting: true,
        // allowAdding: true,
        // form: {
        //     items: [
        //     {
        //         itemType: "group",
        //         items: ["tenchitieu", "donvi"],
        //     },
        //     {
        //         itemType: "group",
        //         items: ["idcha"],

        //     },
        //     ]
        // },
        // toolbarItems: [{
        //     toolbar: 'bottom',
        //     location: 'after',
        //     widget: "dxButton",
        //     options: {
        //         text: "Cancel",
        //         onClick: function () {
        //             $("#treelist").dxTreeList("cancelEditData");
        //         }
        //     }
        // }]
        // },

        //sap xep
        sorting: {
            mode: "multiple"
        },
        columnAutoWidth: true,
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
        selection: {
            mode: "multiple",
            recursive: true
        },
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
                dataField: "tenchitieu",
                caption: "Tên chỉ tiêu",
                validationRules: [{ type: "required" }]
            },
            {
                dataField: "tendonvi",
                caption: "Đơn vị tính",
                validationRules: [{ type: "required" }]
            },
            {
                allowEditing: false,
                cellTemplate: function(container, options) {
                    container.addClass("center");
                    $("<div>")
                        .dxButton({
                            type: "default",
                            text: "Thêm con",
                            template: function(e) {
                                return $("<i class='fa fa-plus'>")
                                    .text(" Thêm con")
                                    .css("color", "#ffffff");
                            },
                            onClick: function(e) {
                                showthemcon(options);
                            }
                        })
                        .appendTo(container);

                    $("<div>")
                        .dxButton({
                            type: "success",
                            text: "Sửa",
                            template: function(e) {
                                return $("<i class='fa fa-edit'>")
                                    .text(" Sửa")
                                    .css("color", "#ffffff");
                            },
                            onClick: function(e) {
                                showsua(options);
                            }
                        })
                        .appendTo(container);

                    $(" <div>")
                        .dxButton({
                            type: "danger",
                            text: "Refresh",
                            template: function(e) {
                                return $("<i class='fa fa-close'>")
                                    .text(" Xóa")
                                    .css("color", "#ffffff");
                            },
                            //xóa row
                            onClick: function(e) {
                                var dataGrid = $("#treelist").dxTreeList(
                                    "instance"
                                );
                                var xoa = options.data;
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
                                            .post("DelChitieu", { id: xoa.id })
                                            .then(function(response) {
                                                if (response.status == 200) {
													
                                                    Swal.fire({
                                                        title: "Lưu",
                                                        text:
                                                            "Đã lưu thành công",
                                                        icon: "success",
                                                        confirmButtonText: "OK"
                                                    });
													dataGrid.refresh();
                                                } else {
                                                    Swal.fire({
                                                        title: "Có lỗi!",
                                                        text:
                                                            "Đã có lối xảy ra! Vui lòng kiểm tra và thử lại",
                                                        icon: "error",
                                                        confirmButtonText: "OK"
                                                    });
                                                }
                                            });
                                        dataGrid.refresh();
                                    }
                                });
                            }
                        })
                        .appendTo(container);
                },
                width: 300
            }
        ],
        // thêm chi tiêu toolbar
        onToolbarPreparing: function(e) {
            var dataGrid = e.component;
            e.toolbarOptions.items.unshift(
                {
                    location: "after",
                    template: function() {
                        return $("<i class='fa fa-plus'>")
                            .addClass("btn btn-primary btn-sm")
                            .text(" Thêm chỉ tiêu");
                    },
                    onClick: function(e) {
                        showthemchitieu();
                        // var them = $('#treelist').dxTreeList('instance').addRow();
                    }
                },
                {
                    location: "after",
                    template: function() {
                        return $("<i class='fa fa-close'>")
                            .addClass("btn btn-danger btn-sm")
                            .text(" Xóa nhiều");
                    },
                    onClick: function(e) {
                        var treeList = $("#treelist").dxTreeList("instance");
                        var selectedData = treeList.getSelectedRowKeys("all");
                        DelChitieulistcheckbox(selectedData);
                        // var them = $('#treelist').dxTreeList('instance').addRow();
                    }
                },
                {
                    location: "after",
                    template: function() {
                        return $("<i class='fa fa-trash'>")
                            .addClass("btn btn-warning btn-sm")
                            .text(" Thùng rác");
                    },
                    onClick: function(e) {
                        window.location = "listchitieutrash";
                    }
                }
            );
        }
    });

    $("#luucon").click(function() {
        addthemcon();
    });
    loadidcha();
    loaddonvitinh();
    $("#luusua").click(function() {
        addsua();
    });
    $("#luuthem").click(function() {
        addthemchitieu();
    });

    //them chi tieu con
    function showthemcon(data) {
        //loadidcha();
        loadthemcon(data);

        //loadidcha(data);
        $("#chitieuthemcon").modal("show");
    }

    function loaddonvitinh() {
        axios.get("getlistdonvitinh").then(function(response) {
            let data = response.data;

            let html = data.map(function(item) {
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
            $("#donvi").html('<option value=""></option>' + html);
            $("#donvisua").html('<option value=""></option>' + html);
            $("#donvithem").html('<option value=""></option>' + html);
        });
    }
    function loadthemcon(data) {
        var data = data.data;
        $("#idcha")
            .val(data.id)
            .change();
        // $('#idcha').html('<option value="' + data.id + '">' + data.tenchitieu + '</option>');
        // $('#tenchitieu').val(data.tenchitieu);
        // $('#donvi').val(data.donvi);
    }

    function loadidcha(data) {
        axios.get("getlistchitieu").then(function(response) {
            let data1 = response.data;
            var datas = data1.filter(function(data1) {
                return data1.idcha === null;
            });
            let html = datas.map(function(item) {
                return (
                    '<option value="' +
                    item.id +
                    '">' +
                    item.tenchitieu +
                    " (" +
                    item.id +
                    ")" +
                    "</option>"
                );
            });
            var idcha = data.data;
            if (idcha.idcha == null) {
                $("#idcha").html(
                    '<option value="' +
                        idcha.id +
                        '">' +
                        idcha.tenchitieu +
                        " (" +
                        idcha.id +
                        ")" +
                        "</option>" +
                        html
                );
            } else {
                $("#idcha").html(
                    '<option value="' +
                        idcha.id +
                        '">' +
                        idcha.tenchitieu +
                        " (" +
                        idcha.id +
                        ")" +
                        "</option>" +
                        html
                );
            }
        });
    }

    function addthemcon() {
        var dataGrid = $("#treelist").dxTreeList("instance");
        let tenchitieu = $("#tenchitieu").val();
        let idcha = $("#idcha")
            .children("option:selected")
            .val();
        let donvi = $("#donvi")
            .children("option:selected")
            .val();
        axios
            .post("InsertChitieuCon", {
                tenchitieu: tenchitieu,
                idcha: idcha,
                donvi: donvi
            })
            .then(function(response) {
                if (response.status == 200) {
					$("#chitieuthemcon").modal("toggle");
					dataGrid.refresh();
					
                    //window.location.reload();
                } else {
                    Swal.fire({
                        title: "Có lỗi!",
                        text: "Đã có lối xảy ra! Vui lòng kiểm tra và thử lại",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
    }

    //sua chi tieu
    function showsua(data) {
        //loadidcha();
        loadsua(data);

        //loadidchasua(data);
        $("#chitieusua").modal("show");
    }
    function loadidcha() {
        axios.get("getlistchitieu").then(function(response) {
            let data = response.data;

            let html = data.map(function(item) {
                return (
                    '<option value="' +
                    item.id +
                    '">' +
                    item.tenchitieu +
                    " (" +
                    item.id +
                    ")" +
                    "</option>"
                );
            });
            $("#idchasua").html('<option value=""></option>' + html);
            $("#idcha").html('<option value=""></option>' + html);
        });
    }
    function loadsua(data) {
        var data = data.data;
        $("#tenchitieusua").val(data.tenchitieu);
        $("#donvisua")
            .val(data.donvitinh)
            .change();
        $("#idchasua")
            .val(data.idcha)
            .change();
        $("#idsua").val(data.id);
    }

    function loadidchasua(data) {
        axios.get("getlistchitieu").then(function(response) {
            let data1 = response.data;
            var datas = data1.filter(function(data1) {
                return data1.parent_id === null;
            });
            let html = datas.map(function(item) {
                return (
                    '<option value="' +
                    item.id +
                    '">' +
                    item.tenchitieu +
                    " (" +
                    item.id +
                    ")" +
                    "</option>"
                );
            });
            var id = data.row.node.parent.data;
            var idcha = data.data;
            if (idcha.parent_id == null) {
                $("#idchasua").html('<option value=""></option>' + html);
            } else {
                $("#idchasua").html(
                    '<option value="' +
                        id.id +
                        '">' +
                        id.tenchitieu +
                        " (" +
                        id.id +
                        ")" +
                        "</option>" +
                        html
                );
            }
        });
    }

    function addsua() {
        var dataGrid = $("#treelist").dxTreeList("instance");
        let Tenchitieu = $("#tenchitieusua").val();
        let Idcha = $("#idchasua")
            .children("option:selected")
            .val();
        let Donvi = $("#donvisua").val();
        let ID = $("#idsua").val();
        axios
            .post("UpdateChitieu", {
                id: ID,
                tenchitieu: Tenchitieu,
                idcha: Idcha,
                donvi: Donvi
            })
            .then(function(response) {
                if (response.status == 200) {
                    $("#chitieusua").modal("toggle");
                    dataGrid.refresh();
                    loadidcha();
                } else {
                    Swal.fire({
                        title: "Có lỗi!",
                        text: "Đã có lối xảy ra! Vui lòng kiểm tra và thử lại",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
    }

    //them moi chi tieu
    function showthemchitieu() {
        $("#chitieuthem").modal("show");
    }
    function addthemchitieu() {
        var dataGrid = $("#treelist").dxTreeList("instance");
        let Tenchitieu = $("#tenchitieuthem").val();
        let Donvi = $("#donvithem").val();
        axios
            .post("InsertChitieu", { tenchitieu: Tenchitieu, donvi: Donvi })
            .then(function(response) {
                if (response.status == 200) {
                    $("#chitieuthem").modal("toggle");
                    dataGrid.refresh();
                    loadidcha();
                } else {
                    Swal.fire({
                        title: "Có lỗi!",
                        text: "Đã có lối xảy ra! Vui lòng kiểm tra và thử lại",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
    }

    //xoa list checkbox chi tieu
    function DelChitieulistcheckbox(data) {
        var dataGrid = $("#treelist").dxTreeList("instance");
        Swal.fire({
            title: "Bạn muốn xoá chỉ tiêu này không",
            text: "Xoá chỉ tiêu",
            showCancelButton: true,
            cancelButtonText: "Đóng",
            confirmButtonText: "Đồng ý",
            icon: "warning"
        }).then(result => {
            if (result.value) {
                for (let index = 0; index < data.length; index++) {
                    var ID = data[index];
                    axios
                        .post("DelChitieulistcheckbox", { id: ID })
                        .then(function(response) {});
                }
                dataGrid.refresh();
                loadidcha();
            }
        });
    }
});
