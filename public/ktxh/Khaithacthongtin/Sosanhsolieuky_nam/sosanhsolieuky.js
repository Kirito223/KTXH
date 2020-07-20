$(function() {
    var now = new Date();
    $("#datepicker1").datepicker({
        format: "m",
        orientation: "bottom",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        language: 'vi',
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
        language: 'vi',
    });
    $("#datepicker4").datepicker({
        format: "yyyy",
        orientation: "bottom",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        language: 'vi',
    });

    //So lieu dia ban tinh/thanh pho
    $('#tinh').select2();
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


    $('#xa').on('change', function() {
        let xaid = $('#xa').val();
        axios.post('getmadonvi', { id: xaid }).then(function(response) {
            let data = response.data;
            let madonvi = data[0].madonvi;
            // loaddonvihanhchinh(madonvi);	
            // loadsolieutheobieumau(madonvi);	
        });

    });

    //so lieu don vi

    $('#solieudonvi').select2();
    axios.get('getdonvihanhchinh').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tendonvi + '</option>';
            });
            $('#solieudonvi').html('<option value=""></option>' + htmldonvihc);
        }
    })






    //so lieu theo bieu mau
    $('#sosanhsl').select2();
    axios.get('getsolieutheobieumau').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmlbieumau = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenbieumau + '</option>';
            });
            $('#sosanhsl').html('<option value=""></option>' + htmlbieumau);
        }
    })


    $('#sosanhsl').on('change', function() {
        let loaisolieu = $('#sosanhsl').val();
        // loadloaisolieu(loaisolieu);
        // loadkysolieu(loaisolieu);
    });

    //loai so lieu
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




    //so sanh theo ky so lieu
    $('#kysolieu').select2();
    axios.get('getkybaocao').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmlkybc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenky + '</option>';
            });
            $('#kysolieu').html('<option value=""></option>' + htmlkybc);
        }
    });








    $('#checkbox1').change(function() {
        if ($(this).is(":checked")) {
            $("#checkbox2").prop("checked", false);
            document.getElementById("kysl").style.display = "block";
            document.getElementById("thangss").style.display = "block";
            document.getElementById("namss").style.display = "block";
        } else {
            document.getElementById("kysl").style.display = "none";
            document.getElementById("thangss").style.display = "none";
            document.getElementById("namss").style.display = "none";
        }
    });

    $('#checkbox2').change(function() {
        if ($(this).is(":checked")) {
            $("#checkbox1").prop("checked", false);
            document.getElementById("kysl").style.display = "none";
            document.getElementById("thangss").style.display = "none";
            document.getElementById("namss").style.display = "none";
        }
    });









    $('#timkiem').click(function() {
        loadsosanhsolieu();
    });


    function loadsosanhsolieu() {
        document.getElementById("tablegrid").style.display = "block";

        let tinh = $('#tinh').val();
        let huyen = $('#huyen').val();
        let xa = $('#xa').val();
        let solieudonvi = $('#solieudonvi').val();
        let sosanhsl = $('#sosanhsl').val();
        let loaisolieu = $('#loaisolieu').val();

        let kysolieu = $('#kysolieu').val();
        let datepicker1 = $('#datepicker1').val();
        // let datepicker2 = $('#datepicker2').val();
        var datepicker3 = $('#datepicker3').val();
        var datepicker4 = $('#datepicker4').val();

        axios.post('loadsosanhsolieu', { xa: xa, solieudonvi: solieudonvi, sosanhsl: sosanhsl, kysolieu: kysolieu, thang1: datepicker1, nam1: datepicker3, nam2: datepicker4, loaisolieu: loaisolieu }).then(function(response) {
            var data = response.data[0];
            var data1 = response.data[1];





            $("#treelist").dxTreeList({
                dataSource: data,
                //cha con
                // itemsExpr: "children_all",
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
                    showInfo: true,
                },
                //check box
                // selection: {
                // 	mode: "multiple",
                // 	allowSelectAll: false,
                // 	recursive: false,
                // },
                columnFixing: {
                    enabled: true
                },
                //loc row
                filterRow: {
                    visible: true
                },
                //co dan cot
                allowColumnResizing: true,
                //thu phóng row
                // autoExpandAll: true,

                columns: [{
                        dataField: "tenchitieu",
                        caption: "Tên chỉ tiêu",

                    },
                    {
                        fixed: true,
                        fixedPosition: "right",
                        caption: "Đơn vị tính",
                        cellTemplate: function(container, options) {
                            $("<div>")
                                .text(" Đơn vị")
                                .appendTo(container);
                        },
                        width: 100,
                    },
                    {
                        fixed: true,
                        fixedPosition: "right",
                        caption: "Biểu đồ",
                        cellTemplate: function(container, options) {
                            container.addClass("center");
                            $("<div>").dxButton({
                                type: "default",
                                template: function(e) {
                                    return $('<i class="fa fa-bar-chart" aria-hidden="true"></i>');
                                },
                                onClick: function(e) {
                                    // var treeList = $("#treelist").dxTreeList("instance");
                                    // var selectedData = treeList.getSelectedRowsData("all");
                                    $('#bieudo').modal('show');
                                    showbieudo(options);
                                }
                            }).appendTo(container);
                        },
                        width: 100,
                    },

                ],
            });


            if (datepicker3 == '' && datepicker4 == '') {
                return;
            } else {

                var khoan = datepicker4 - datepicker3;

                var max = datepicker4;
                var min = datepicker4 - khoan;
                var years = []

                for (var i = min; i < max; i++) {
                    years.push(i)
                }


                var dataGrid = $('#treelist').dxTreeList('instance');

                for (var i = 0; i < (years.length); i++) {
                    let chitieuyear = years[i];

                    var nam = datepicker3++;
                    var state = dataGrid.state();
                    var columns = dataGrid.option("columns");
                    columns.push({
                        calculateCellValue: function(rowData) {
                            let datepicker3 = $('#datepicker3').val();
                            let datepicker4 = $('#datepicker4').val();
                            if (datepicker3 >= chitieuyear || chitieuyear <= datepicker4) {

                                var lucky = data1.filter(function(number) {
                                    if (number.year == chitieuyear) {
                                        return number;
                                    }
                                });

                                var lucky2 = lucky.filter(function(number2) {
                                    if (number2.chitieu == rowData.id) {
                                        return number2;
                                    }
                                });
                                if (lucky2 != '') {
                                    return lucky2[0].sanluong;
                                }
                                return null;


                            }


                        },
                        caption: nam
                    }, );


                    dataGrid.option("columns", columns);
                    dataGrid.state(state);
                }

            }





        });
    }



    function showbieudo(data) {
        let datepicker3 = $('#datepicker3').val();
        var datepicker4 = $('#datepicker4').val();
        var a = data.data.id;
        var namtu = datepicker3;
        axios.post('getbieudo', { id: a, nam1: datepicker3, nam2: datepicker4 }).then(function(response) {
            var u = response.data;

            let result = u.reduce((re, obj) => {
                let index = re.map(o => o.year).indexOf(obj.year);
                index > -1 ? re[index].sanluong += obj.sanluong : re.push(obj);
                return re;
            }, []);

            $("#chart").dxChart({
                dataSource: result,
                size: {
                    height: 400,
                    width: 550
                },
                legend: {
                    verticalAlignment: "top",
                    horizontalAlignment: "center",
                    itemTextPosition: "right"
                },
                series: [{
                    argumentField: "year",
                    valueField: "sanluong",
                    name: "Sản lượng",
                    type: "bar",
                    color: '#ffaa66'
                }, { valueField: "year", name: "năm" }, ],
                argumentAxis: {
                    label: {
                        format: {
                            type: "decimal"
                        }
                    }
                },
            });
        });

    }












});