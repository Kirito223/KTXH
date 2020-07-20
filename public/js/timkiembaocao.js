import Ultils from "./Ultil.js";
var dxDataGrid;
$(document).ready(() => {
    if ($("#timkiembaocao").length) {
        loadData();
        initEvent();
    }
});
function loadData() {
    $("#Kybaocao").dxSelectBox({
        dataSource: "danhsachkybaocao",
        displayExpr: "tenky",
        valueExpr: "id"
    });
    $("#Donvigui").dxSelectBox({
        dataSource: "danhsachdonvihanhchinh",
        displayExpr: "tendonvi",
        valueExpr: "id"
    });

    $("#Nambaocao").dxSelectBox({
        dataSource: Ultils.listNam()
    });
    $("#Phongban").dxSelectBox({
        dataSource: "danhsachPhongban",
        displayExpr: "tenphongban",
        valueExpr: "id"
    });

    dxDataGrid = $("#GridBaocao")
        .dxDataGrid({
            dataSource: "indexTimkiemBaocao",
            keyExpr: "id",
            showRowLines: true,
            showBorders: true,
            columnAutoWidth: true,
            paging: {
                pageSize: 10
            },
            columns: [
                {
                    dataField: "sohieu",
                    caption: "Số hiệu"
                },
                {
                    dataField: "tieude",
                    caption: "Tiêu đề"
                },
                {
                    dataField: "tenky",
                    caption: "Kỳ báo cáo"
                },
                {
                    dataField: "ngaycapnhatsaucung",
                    caption: "Ngày cập nhật cuối cùng"
                },
                {
                    dataField: "tendonvi",
                    caption: "Đơn vị"
                },
                {
                    dataField: "Name",
                    caption: "Xem",
                    cellTemplate: function(container, options) {
                        $(
                            '<button class="btn btn-primary btn-sm"><i class="fa fa-folder-open" aria-hidden="true"></i> Xem </button>'
                        )
                            .on("dxclick", function(evt) {
                                evt.stopPropagation();
                                localStorage.setItem(
                                    "idXembaocao",
                                    options.data.id
                                );
                                window.location = "viewXemchitietBaocao";
                            })
                            .appendTo(container);
                    }
                }
            ]
        })
        .dxDataGrid("instance");
}
function initEvent() {
    $("#btnTimkiem").on("click", () => {
        let donvigui = $("#Donvigui")
            .dxSelectBox("instance")
            .option("value");
        let nambaocao = $("#Nambaocao")
            .dxSelectBox("instance")
            .option("value");
        let phongban = $("#Phongban")
            .dxSelectBox("instance")
            .option("value");
        let kybaocao = $("#Kybaocao")
            .dxSelectBox("instance")
            .option("value");
        let tukhoa = $("#Tukhoa").val();

        axios
            .post("TimKiembaocao", {
                donvigui: donvigui,
                nambaocao: nambaocao,
                phongban: phongban,
                kybaocao: kybaocao,
                tukhoa: tukhoa
            })
            .then(res => {
                let data = res.data;
                dxDataGrid.option("dataSource", data);
            })
            .catch(err => {
                console.log(err);
            });
    });
}
