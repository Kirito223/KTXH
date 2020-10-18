$(document).ready(function() {

    $("#nam").datepicker({
        format: "yyyy",
        orientation: "bottom",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        language: 'vi',
    });


    $('#tinh').select2({ width: '100%' });
    axios.get('getlisttinh').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmltinh = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tinh + '</option>';
            });
            $('#tinh').html('<option value=""></option>' + htmltinh);
        }
    });

    //so lieu dia ban huyen
    $('#tinh').on('change', function() {
        let tinhid = $('#tinh').val();
        loadhuyen(tinhid);
    });

    function loadhuyen(id) {
        $('#huyen').select2();
        axios.post('getlisthuyen', { matinh: id }).then(function(response) {
            let data1 = response.data;
            if (data1 != null) {
                let htmlhuyen = data1.map(function(item) {
                    return '<option value="' + item.id + '">' + item.huyen + '</option>';
                });
                $('#huyen').html('<option value=""></option>' + htmlhuyen);
            }
        });
    }

    //so lieu dia ban xa
    $('#huyen').on('change', function() {
        let huyenid = $('#huyen').val();
        loadxa(huyenid);
    });

    function loadxa(id) {
        $('#xa').select2();
        axios.post('getlistxa', { mahuyen: id }).then(function(response) {
            let data1 = response.data;
            if (data1 != null) {
                let htmlxa = data1.map(function(item) {
                    return '<option value="' + item.id + '">' + item.xa + '</option>';
                });
                $('#xa').html('<option value=""></option>' + htmlxa);
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

    $('#donvibaocao').select2({ width: '100%' });
    axios.get('getdonvihanhchinh').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tendonvi + '</option>';
            });
            $('#donvibaocao').html('<option value=""></option>' + htmldonvihc);
        }
    })

    $('#kysolieu').select2({ width: '100%' });
    axios.get('getkybaocao').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenky + '</option>';
            });
            $('#kysolieu').html('<option value=""></option>' + htmldonvihc);
        }
    })

    $('#loaisolieu').select2();
    axios.get('getloaisolieu').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmlloaisl = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenloaisolieu + '</option>';
            });
            $('#loaisolieu').html('<option value=""></option>' + htmlloaisl);
        }
    });

    $('#chitieu').select2({ width: '100%' });
    axios.get('getlistchitieudubaosl').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenchitieu + '</option>';
            });
            $('#chitieu').html('<option value=""></option>' + htmldonvihc);
        }
    })


    $("#checkbox1").change(function () {
        if ($(this).is(":checked")) {
            $("#checkbox2").prop("checked", false);
        }
    });

    $("#checkbox2").change(function () {
        if ($(this).is(":checked")) {
            $("#checkbox1").prop("checked", false);
        }
    });


    $("#timkiem").click(function () {
        loaddubaosolieu();
    });


    function loaddubaosolieu(){
        document.getElementById("tablegrid").style.display = "block";

        var tinh = $("#tinh").val();
        var huyen = $("#huyen").val();
        var xa = $("#xa").val();
        var donvibaocao = $("#donvibaocao").val();
        var kysolieu = $("#kysolieu").val();
        var nam = $("#nam").val();
        var loaisolieu = $("#loaisolieu").val();
        var chitieu = $("#chitieu").val();

        axios.post("loaddubaosolieu", {
            tinh:tinh,
            huyen:huyen,
            xa: xa,
            donvibaocao: donvibaocao,
            nam: nam,
            kysolieu: kysolieu,
            chitieu: chitieu,
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



            //     var datass = [];
            //     var datact = data.filter(function(number) {
            //      var element = number.id;  
            //      var da = datachitieu.filter(function(number2) {
            //         if(number2.id == element){
            //             return number2 ;                                 
            //         }                    
            //     }); 
            //      if(da != ""){
            //         datass.push(da[0]);    
            //     }
            // });



        }




        $("#treelist").dxTreeList({
            dataSource: datachitieu,
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
                    //  mode: "multiple",
                    //  allowSelectAll: false,
                    //  recursive: false,
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
                        // width:800,
                    },
                    {
                        fixed: true,
                        fixedPosition: "right",
                        caption: "Đơn vị tính",
                        cellTemplate: function (container, options) {
                            if(options.row.data.tbl_donvitinh == null){
                                $("<div>").text("").appendTo(container);
                            }else{
                             $("<div>").text(options.row.data.tbl_donvitinh.tendonvi).appendTo(container);
                         }
                         
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

                               
                                showbieudo(options,datachitietsolieutheobieu,years);
                            },
                        })
                        .appendTo(container);
                    },
                    width: 100,
                },
                ],
            });


                // var max = datass;
                // var min = nam;
                // var years = [];

                // for (var i = min; i <= max; i++) {
                //     years.push(i);
                // }

                var data2 = [];
			if(datas != undefined){
				datas.filter(function(item) {
                    var i = data2.findIndex(x => x.id == item.tbl_solieutheobieu.namnhap);
                    if (i <= -1) {
                        data2.push({ id: item.chitieu,namnhap:item.tbl_solieutheobieu.namnhap});
                    }
                    return null;
                });
			}
			
                

                var data3 = [];
			if(data2.length >0){
				 data2.filter(function(item) {
                    var i = data3.findIndex(x => x.namnhap == item.namnhap);
                    if (i <= -1) {
                        data3.push({ id: item.id,namnhap:item.namnhap});
                    }
                    return null;
                });
  				data3.sort(function(a, b) {
                    return a.namnhap + b.namnhap;
                });

                var nammax = data3[0].namnhap;
                var nammin = nammax-5;
			}
               
              

                var years = [];

                for (var i = nammin; i <= nammax; i++) {
                    years.push(i);
                }









                var dataGrid = $("#treelist").dxTreeList("instance");
                for (var i = 0; i < years.length; i++) {
                    let chitieuyear = years[i];
                    if(chitieuyear == nammax){
                        var nam = 'Giá trị dự báo năm '+ nammax;
                    }else{
                        var nam = nammin++;
                    }

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
                                    return number.sanluong;
                                }
                            });

                            if(lucky == ''){
                                return null;
                            }else{

                                var data1 = lucky.map(item => ({ id: item.chitieu, sanluong: item.sanluong,namnhap:item.tbl_solieutheobieu.namnhap,tenchitieu:item.tbl_chitieu.tenchitieu}));

                                let result = data1.reduce((re, obj) => {
                                    let index = re.map((o) => o.namnhap).indexOf(obj.namnhap);
                                     if(index>-1){
		 re[index].sanluong+= Number(obj.sanluong);
		  }else{
			  obj.sanluong = Number(obj.sanluong);
			  re.push(obj);
	   }
                                    return re;
                                }, []);

                                return result[0].sanluong;
                            }

                        },
                        caption: nam,
                    });

                    dataGrid.option("columns", columns);
                    dataGrid.state(state);
                }
                



            });


}






function showbieudo(data,datachitietsolieutheobieu,years) {

	if(datachitietsolieutheobieu != undefined){
	 var a = data.data.id;

    var lucky = datachitietsolieutheobieu.filter(function(number) {
        if (number.chitieu == a) {
            return number;
        }
    });

    var data1 = lucky.map(item => ({ id: item.chitieu, sanluong: item.sanluong,namnhap:item.tbl_solieutheobieu.namnhap,tenchitieu:item.tbl_chitieu.tenchitieu}));

    var data2 = years.map(function(val, index){ 
        return {key:index, namnhap:val,sanluong:0}; 
    });
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
        // 
        valueAxis: {
            label: {
                customizeText: function () {
                    var str = this.valueText;
                    str = str.replace(/M/g," Triệu"); 
                    str = str.replace(/B/g," Tỷ"); 
                    str = str.replace(/K/g," Nghìn"); 
                    str = str.trim();
                    return str;
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
                format: {
                    type: "decimal",
                },
            },
        },
    });
		 $("#bieudo").modal("show");
	}else{
	Swal.fire("Chưa có dữ liệu thống kê", "Chưa có dữ liệu", "info");
	}
	
   

}
















})