$(function() {
    var now = new Date();
    $("#datepicker1").datepicker({
        autoclose: true,
        language: "vi"
    });
    $("#datepicker2").datepicker({
        autoclose: true,
        language: "vi"
    });
    $("#datepicker3").datepicker({
        autoclose: true,
        language: "vi"
    });
    $("#datepicker4").datepicker({
        autoclose: true,
        language: "vi"
    });

    //ten bieu mau
    $("#tenbieumau").select2();
    axios
        .get("getlistbieumau")
        .then(function(response) {
            let data = response.data;
            if (data != null) {
                let htmltenbieumau = data.map(function(item) {
                    return (
                        '<option value="' +
                        item.id +
                        '">' +
                        item.tenbieumau +
                        "</option>"
                    );
                });
                $("#tenbieumau").html(
                    '<option value=""></option>' + htmltenbieumau
                );
            }
        })
        .catch(function(err) {
            console.log(err);
        });

    //co quan dao tao
    $("#coquandaotao").select2();
    axios
        .get("getlistdonvihanhchinh")
        .then(function(response) {
            let data = response.data;
            if (data != null) {
                let htmlcoquan = data.map(function(item) {
                    return (
                        '<option value="' +
                        item.id +
                        '">' +
                        item.tendonvi +
                        "</option>"
                    );
                });
                $("#coquandaotao").html(
                    '<option value=""></option>' + htmlcoquan
                );
            }
        })
        .catch(function(err) {
            console.log(err);
        });

    $("#timkiem").click(function() {
        loadtable();
    });

    //loc table
    function loadtable() {
        document.getElementById("tablegrid").style.display = "block";

        let ngayquyetdinhtu = $("#datepicker1").val();
        let ngayquyetdinhden = $("#datepicker2").val();

        let ngaytaotu = $("#datepicker3").val();
        let ngaytaoden = $("#datepicker4").val();

        let tenbieumau = $("#tenbieumau").val();
        let bieumauso = $("#bieumauso").val();

        let coquandaotao = $("#coquandaotao").val();
        let quyetdinhso = $("#quyetdinhso").val();
        let loaibieumau = $("#loaibieumau").val();

        var data = axios
            .post("loadlistbieumau", {
                ngayquyetdinh1: ngayquyetdinhtu,
                ngayquyetdinh2: ngayquyetdinhden,
                ngaytao1: ngaytaotu,
                ngaytao2: ngaytaoden,
                tenbieumau: tenbieumau,
                bieumauso: bieumauso,
                coquandaotao: coquandaotao,
                quyetdinhso: quyetdinhso,
                loaibieumau: loaibieumau
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
                //table
                $("#gridContainer").dxDataGrid({
                    dataSource: datas,
                    //phan trang
                    showBorders: true,
                    paging: {
                        pageSize: 4
                    },
                    pager: {
                        showPageSizeSelector: true,
                        allowedPageSizes: [5, 10, 20],
                        showInfo: true
                    },
                    //xap xep
                    sorting: {
                        mode: "multiple"
                    },
                    //loc du lieu
                    filterRow: {
                        visible: true,
                        applyFilter: "auto"
                    },
                    searchPanel: {
                        visible: true,
                        width: 240,
                        placeholder: "Tìm kiếm"
                    },
                    headerFilter: {
                        visible: true
                    },
                    //chon row
                    // selection: {
                    // 	mode: "single"
                    // },
                    //co dan cot
                    allowColumnResizing: true,
                    columns: [
                        {
                            caption: "Xem",
                            allowEditing: false,
                            cellTemplate: function(container, options) {
                                container.addClass("center");
                                $("<div>")
                                    .dxButton({
                                        icon: "search",
                                        onClick: function(e) {
                                            let data = options.data;
                                            infobieumau(data);
                                        }
                                    })
                                    .appendTo(container);
                            },
                            width: 50
                        },
                        {
                            dataField: "sohieu",
                            caption: "Biểu mẫu số",
                            validationRules: [{ type: "required" }]
                        },
                        {
                            dataField: "tenbieumau",
                            caption: "Tên biểu mẫu",
                            validationRules: [{ type: "required" }]
                        },
                        {
                            dataField: "soquyetdinh",
                            caption: "Quyết định số",
                            validationRules: [{ type: "required" }]
                        },
                        {
                            dataField: "ngayquyetdinh",
                            caption: "Ngày quyết định",
                            validationRules: [{ type: "required" }]
                        },
                        {
                            dataField: "created_at",
                            caption: "Ngày tạo",
                            validationRules: [{ type: "required" }]
                        },
                        {
                            dataField: "tendangnhap",
                            caption: "Người tạo",
                            validationRules: [{ type: "required" }]
                        },
                        {
                            dataField: "tendonvi",
                            caption: "Cơ quan",
                            validationRules: [{ type: "required" }]
                        }
                    ]
                });
            });
    }

    function infobieumau(data) {
        var data1 = data; // ban chay coi duoc chua
        window.location.href = "/" + "loadchitietbieumau/" + data1.id;
    }
});
