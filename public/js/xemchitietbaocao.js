import thembaocao from "./thembaocaodinhky.js";

$(document).ready(() => {
    if ($("#chitietbaocao").length) {
        loadData();
        initEvent();
    }
});
function loadData() {
    const id = localStorage.getItem("idXembaocao");
    axios
        .get("showBaocao/" + id)
        .then(res => {
            let data = res.data;
            let thongtinchung = data.bieumau;
            let chitiet = data.chitiet;
            $("#kybaocao").text(thongtinchung.tenky);
            $("#sohieu").text(thongtinchung.sohieu);
            $("#tieude").text(thongtinchung.tieude);
            $("#nam").text(thongtinchung.nambaocao);
            $("#donvinhan")
                .dxDataGrid({
                    dataSource: JSON.parse(chitiet.donvinhan),
                    keyExpr: "id",
                    showRowLines: true,
                    showBorders: true,
                    columnAutoWidth: true,
                    columns: [
                        {
                            dataField: "tendonvi",
                            caption: "Tên đơn vị"
                        },
                        {
                            dataField: "_name",
                            caption: "Địa chỉ"
                        },
                        {
                            dataField: "sodienthoai",
                            caption: "Điện thoại"
                        },
                        {
                            dataField: "email",
                            caption: "Email"
                        }
                    ]
                })
                .dxDataGrid("instance");
            $("#bieusolieu")
                .dxDataGrid({
                    dataSource: JSON.parse(chitiet.cacbieusolieu),
                    keyExpr: "id",
                    showRowLines: true,
                    showBorders: true,
                    columnAutoWidth: true,
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
                            caption: "Xem",
                            cellTemplate: function(container, options) {
                                $("<div>")
                                    .dxButton({
                                        type: "default",
                                        text: "Xem",
                                        template: function(e) {
                                            return $(
                                                '<i class="far fa-eye"></i>'
                                            )
                                                .text(" Xem")
                                                .css("color", "#ffffff");
                                        },
                                        onClick: function(e) {
                                            thembaocao.ReviewReport(
                                                options.data.id
                                            );
                                        }
                                    })
                                    .appendTo(container);
                            }
                        }
                    ]
                })
                .dxDataGrid("instance");

            let taptin = JSON.parse(thongtinchung.file);
            if (taptin != null) {
                taptin.forEach(element => {
                    $("#danhsachtaptin").append(
                        `<li><a href="Download/` +
                            element +
                            `">` +
                            element +
                            `</a></li>`
                    );
                });
            } else {
                $("#danhsachtaptin").append(
                    `<li>Không có tập tin đính kèm</li>`
                );
            }

            CKEDITOR.replace("noidung");
            CKEDITOR.on("instanceReady", function(evt) {
                CKEDITOR.instances.noidung.setData(chitiet.noidung);
                CKEDITOR.instances.noidung.setReadOnly(true);
            });
            if (thongtinchung.ngayky != null) {
                $("#ngayky").text(
                    moment(thongtinchung.ngayky).format("DD/MM/YYYY")
                );
            } else {
                $("#ngayky").text("Chưa ký");
            }
            if (thongtinchung.nguoiky != null) {
                $("#nguoiky").text(data.nguoiky);
            } else {
                $("#nguoiky").text("Chưa ký");
            }
        })
        .catch(err => {
            console.log(err);
        });
}
function initEvent() {
    $("#btnTrove").on("click", () => {
        window.location = "viewTimkiembaocao";
    });
}
