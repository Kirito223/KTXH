var cungky=0;
$(function () {
    var now = new Date();
    $("#datepicker1").datepicker({
        format: "m",
        orientation: "bottom",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        language: "vi",
    });
    // $("#datepicker2").datepicker({
    // 	format: "m",
    // 	orientation: "bottom",
    // 	viewMode: "months",
    // 	minViewMode: "months",
    // 	autoclose: true,
    // 	language: 'vi',
    // });
    $("#datepicker3").datepicker({
        format: "yyyy",
        orientation: "bottom",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        language: "vi",
    });
    $("#datepicker4").datepicker({
        format: "yyyy",
        orientation: "bottom",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        language: "vi",
    });

    //So lieu dia ban tinh/thanh pho
    $("#tinh").select2();
    axios.get("getlisttinh").then(function (response) {
        let data = response.data;
        if (data != null) {
            let htmltinh = data.map(function (item) {
                return (
                    '<option value="' + item.id + '">' + item.tinh + "</option>"
                    );
            });
            $("#tinh").html('<option value=""></option>' + htmltinh);
        }
    });

    //so lieu dia ban huyen
    $("#tinh").on("change", function () {
        let tinhid = $("#tinh").val();
        loadhuyen(tinhid);
    });

    function loadhuyen(id) {
        $("#huyen").select2();
        axios.post("getlisthuyen", { matinh: id }).then(function (response) {
            let data1 = response.data;
            if (data1 != null) {
                let htmlhuyen = data1.map(function (item) {
                    return (
                        '<option value="' +
                        item.id +
                        '">' +
                        item.huyen +
                        "</option>"
                        );
                });
                $("#huyen").html('<option value=""></option>' + htmlhuyen);
            }
        });
    }

    //so lieu dia ban xa
    $("#huyen").on("change", function () {
        let huyenid = $("#huyen").val();
        loadxa(huyenid);
    });

    function loadxa(id) {
        $("#xa").select2();
        axios.post("getlistxa", { mahuyen: id }).then(function (response) {
            let data1 = response.data;
            if (data1 != null) {
                let htmlxa = data1.map(function (item) {
                    return (
                        '<option value="' +
                        item.id +
                        '">' +
                        item.xa +
                        "</option>"
                        );
                });
                $("#xa").html('<option value=""></option>' + htmlxa);
            }
        });
    }

    $("#xa").on("change", function () {
        let xaid = $("#tinh").val();
        axios.post("getmadonvi", { id: xaid }).then(function (response) {
            let data = response.data;
            let madonvi = data[0].madonvi;
            // loaddonvihanhchinh(madonvi);
            // loadsolieutheobieumau(madonvi);
        });
    });

    //so lieu don vi

    $("#solieudonvi").select2();
    axios.get("getdonvihanhchinh").then(function (response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function (item) {
                return (
                    '<option value="' +
                    item.id +
                    '">' +
                    item.tendonvi +
                    "</option>"
                    );
            });
            $("#solieudonvi").html('<option value=""></option>' + htmldonvihc);
        }
    });

    //so lieu theo bieu mau
    $("#sosanhsl").select2();
    axios.get("danhsachbieumau").then(function (response) {
        let data = response.data;
        if (data != null) {
            let htmlbieumau = data.map(function (item) {
                return (
                    '<option value="' + item.id +'">' + item.tenbieumau +'  -   ' + item.sohieu + "</option>"
                    );
            });
            $("#sosanhsl").html('<option value=""></option>' + htmlbieumau);
        }
    });

    $("#sosanhsl").on("change", function () {
        let loaisolieu = $("#sosanhsl").val();
        // loadloaisolieu(loaisolieu);
        // loadkysolieu(loaisolieu);
    });

    //loai so lieu
    $("#loaisolieu").select2();
    axios.get("getloaisolieu").then(function (response) {
        let data = response.data;
        if (data != null) {
            let htmlloaisl = data.map(function (item) {
                return (
                    '<option value="' +
                    item.id +
                    '">' +
                    item.tenloaisolieu +
                    "</option>"
                    );
            });
            $("#loaisolieu").html('<option value=""></option>' + htmlloaisl);
        }
    });

    //so sanh theo ky so lieu
    $("#kysolieu").select2();
    axios.get("getkybaocao").then(function (response) {
        let data = response.data;
        if (data != null) {
            let htmlkybc = data.map(function (item) {
                return (
                    '<option value="' +
                    item.id +
                    '">' +
                    item.tenky +
                    "</option>"
                    );
            });
            $("#kysolieu").html('<option value=""></option>' + htmlkybc);
        }
    });

    $("#checkbox1").change(function () {
        if ($(this).is(":checked")) {
            $("#checkbox2").prop("checked", false);
            document.getElementById("kysl").style.display = "block";
            document.getElementById("thangss").style.display = "block";
            document.getElementById("namss").style.display = "block";
			cungky=0;
        } else {
            document.getElementById("kysl").style.display = "none";
            document.getElementById("thangss").style.display = "none";
            document.getElementById("namss").style.display = "none";
			cungky=1;
            //clear();
        }
    });

    $("#checkbox2").change(function () {
        if ($(this).is(":checked")) {
            $("#checkbox1").prop("checked", false);
            document.getElementById("kysl").style.display = "none";
            document.getElementById("thangss").style.display = "none";
            document.getElementById("namss").style.display = "none";
            //clear();
        }
    });

    function clear(){
        var tinh = $("#tinh").val('');
        var huyen = $("#huyen").val('');
        var xa = $("#xa").val('');
        var solieudonvi = $("#solieudonvi").val('');
        var sosanhsl = $("#sosanhsl").val('');
        var loaisolieu = $("#loaisolieu").val('');

        var kysolieu = $("#kysolieu").val('');
        var datepicker1 = $("#datepicker1").val('');
        // let datepicker2 = $('#datepicker2').val('');
        var datepicker3 = $("#datepicker3").val('');
        var datepicker4 = $("#datepicker4").val('');
    }

    $("#timkiem").click(function () {
        loadsosanhsolieu();
    });

    function loadsosanhsolieu() {
        document.getElementById("tablegrid").style.display = "block";

        var tinh = $("#tinh").val();
        var huyen = $("#huyen").val();
        var xa = $("#xa").val();
        var solieudonvi = $("#solieudonvi").val();
        var sosanhsl = $("#sosanhsl").val();
        var loaisolieu = $("#loaisolieu").val();

        var kysolieu = $("#kysolieu").val();
        var datepicker1 = $("#datepicker1").val();
        // let datepicker2 = $('#datepicker2').val();
        var datepicker3 = $("#datepicker3").val();
        var datepicker4 = $("#datepicker4").val();

        axios.post("loadsosanhsolieu", {
            tinh:tinh,
            huyen:huyen,
            xa: xa,
            solieudonvi: solieudonvi,
            sosanhsl: sosanhsl,
            kysolieu: kysolieu,
            thang1: datepicker1,
            nam1: datepicker3,
            nam2: datepicker4,
			sosanhky:cungky,
			cungky:cungky,
            loaisolieu: loaisolieu,
        })
        .then(function (response) {
            var datachitieu = response.data[0];
            var datachitietsolieutheobieu = response.data[1];
            if(datachitieu != null && datachitietsolieutheobieu == null){
                var datass = response.data[0];
            }else{
                var datas = response.data[1];
                var data = [];
                datas.filter(function(item) {
                    var i = data.findIndex(x => x.id == item.chitieu);
                    if (i <= -1) {
                        data.push({ id: item.chitieu});
                    }
                    return null;
                });



                var datass = [];
                var datact = data.filter(function(number) {
                 var element = number.id;  
                 var da = datachitieu.filter(function(number2) {
                    if(number2.id == element){
                        return number2 ;                                 
                    }
                }); 
					if(da[0] != undefined){
						datass.push(da[0]);
					}
                                   
             });   



            }

            $("#treelist").dxTreeList({
                dataSource: datass,
                    //cha con
                    // itemsExpr: "children_all",
                    // dataStructure: "tree",
                    keyExpr: "id",
                    parentIdExpr: "idcha",
                    // hasItemsExpr:"children_all",
                    //sap xep
                    sorting: {
                        mode: "multiple",
                    },
                    columnAutoWidth: true,
                    // wordWrapEnabled: true,
                    showRowLines: true,
                    //phan trang
                    showBorders: true,
                    scrolling: {
                        mode: "standard",
                    },
                    paging: {
                        enabled: true,
                        pageSize: 10,
                    },
                    pager: {
                        showPageSizeSelector: true,
                        allowedPageSizes: [5, 10, 20 ,50],
                        showInfo: true,
                    },
                    //check box
                    // selection: {
                    // 	mode: "multiple",
                    // 	allowSelectAll: false,
                    // 	recursive: false,
                    // },
                    columnFixing: {
                        enabled: true,
                    },
                    //loc row
                    filterRow: {
                        visible: true,
                    },
                    //co dan cot
                    allowColumnResizing: true,
                    //thu phóng row
                    // autoExpandAll: true,

                    columns: [
                    {
                        dataField: "tenchitieu",
                        caption: "Tên chỉ tiêu",

                    },
                    {
                        fixed: true,
                        fixedPosition: "right",
                        caption: "Đơn vị tính",
                        cellTemplate: function (container, options) {
                            $("<div>").text(options.row.data.tendonvi).appendTo(container);
                        },
                        width: 100,
                    },
                    {
                        fixed: true,
                        fixedPosition: "right",
                        caption: "Biểu đồ",
                        cellTemplate: function (container, options) {
                            container.addClass("center");
                            $("<div>")
                            .dxButton({
                                type: "default",
                                template: function (e) {
                                    return $(
                                        '<i class="fa fa-bar-chart" aria-hidden="true"></i>'
                                        );
                                },
                                onClick: function (e) {

                                    $("#bieudo").modal("show");
                                    showbieudo(options,datachitietsolieutheobieu);
                                },
                            })
                            .appendTo(container);
                        },
                        width: 100,
                    },
                    ],
                });

            if (datepicker3 == "" && datepicker4 == "") {
                return;
            } else {
                var max = datepicker4;
                var min = datepicker3;
                var years = [];

                for (var i = min; i <= max; i++) {
                    years.push(i);
                }

                var dataGrid = $("#treelist").dxTreeList("instance");

                for (var i = 0; i < years.length; i++) {
                    let chitieuyear = years[i];

                    var nam = datepicker3++;
                    var state = dataGrid.state();
                    var columns = dataGrid.option("columns");
                    columns.push({
                        calculateCellValue: function (rowData) {
                            var idchitieu = rowData.id;
                            var datasl = datachitietsolieutheobieu;

                            var lucky = datasl.filter(function(number) {
                                var namnhapsl = number.tbl_solieutheobieu.namnhap.toString();
                                var chitieunam = chitieuyear.toString();
                                if (number.chitieu == idchitieu && chitieunam == namnhapsl) {
                                    return Number(number.sanluong);
                                }
                            });

                            if(lucky == ''){
                                return null;
                            }else{
								if(lucky.length == 1){
									 return Number(lucky[0].sanluong);
								   }else{
								   let sum = 0;
									   lucky.forEach(item=>{
									   sum= Number(item.sanluong);
									   });
									   return sum;
								   }
                               
                            }
                            
                        },
                        caption: nam,
                    });

                    dataGrid.option("columns", columns);
                    dataGrid.state(state);
                }
            }
        });
}

function showbieudo(data,datachitietsolieutheobieu) {
    var a = data.data.id;


    var data2 = data.component._options.columns;


    var lucky = datachitietsolieutheobieu.filter(function(number) {
        if (number.chitieu == a) {
            return number;
        }
    });

    var data1 = lucky.map(item => (
		{ id: item.id, 
		 sanluong: item.sanluong,namnhap:item.tbl_solieutheobieu.namnhap,tenchitieu:item.tbl_chitieu.tenchitieu,
		}
	));

    var data0 = data1.concat(data2);
    let result = data0.reduce((re, obj) => {
        let index = re.map((o) => o.namnhap).indexOf(obj.namnhap);
         if(index>-1){
		 re[index].sanluong+= Number(obj.sanluong);
		  }else{
			  obj.sanluong = Number(obj.sanluong);
			  re.push(obj);
	   }
        return re;
    }, []);

    var titlebd = 'Chỉ tiêu : ' + data.data.tenchitieu;
    $("#title").text(titlebd);


    $("#chart").dxChart({
        dataSource: result,
        commonSeriesSettings: {
            argumentField: "state",
            type: "bar",
            hoverMode: "allArgumentPoints",
            selectionMode: "allArgumentPoints",
            label: {
                visible: true,
                format: {
                    type: "fixedPoint",
                    precision: 0
                }
            }
        },
        size: {
            height: 400,
            width: 550,
        },
        legend: {
            verticalAlignment: "top",
            horizontalAlignment: "center",
            itemTextPosition: "right",
        },
        series: [
        {
            argumentField: 'namnhap',
            valueField: "sanluong",
            name: "Sản lượng",
            type: "bar",
            color: "#ffaa66",
        },
        ],
        // valueAxis: {
        //     title: {
        //         text: "Triệu đồng"
        //     },
        //     position: "left"
        // },
        valueAxis: {
            label: {
                customizeText: function () {
                   var str = this.valueText;
                   str = str.replace(/M/g," Triệu"); 
                   str = str.replace(/B/g," Tỷ"); 
                   str = str.replace(/K/g," Nghìn"); 
                   str = str.trim();
                   if(data1[0].donvitinh == null){
                    return str
                }else{
                    return str;
                }

            }
        },
    },

        // title: titlebd,
        legend: {
            verticalAlignment: "top",
            horizontalAlignment: "center"
        },
        argumentAxis: {
            label: {
                format: { year: "digit" }
            },
        },
    });

}









});
