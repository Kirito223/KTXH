$(document).ready(function() {

    $("#solieucuanam").datepicker({
        format: "yyyy",
        orientation: "bottom",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        language: 'vi',
    });

    $("#chonthang").datepicker({
        format: "m",
        orientation: "bottom",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        language: 'vi',
    });



    //So lieu dia ban tinh/thanh pho

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


    $('#xa').on('change', function() {
        let xaid = $('#xa').val();
        axios.post('getmadonvi', { id: xaid }).then(function(response) {
            let data = response.data;
            let madonvi = data[0].madonvi;
        });

    });


    $('#donvithuthap').select2({ width: '100%' });
    axios.get('getdonvihanhchinh').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tendonvi + '</option>';
            });
            $('#donvithuthap').html('<option value=""></option>' + htmldonvihc);
        }
    })


    $('#solieucuaky').select2({ width: '100%' });
    axios.get('getkybaocao').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenky + '</option>';
            });
            $('#solieucuaky').html('<option value=""></option>' + htmldonvihc);
        }
    })

    $('#solieubieumau').select2({ width: '100%' });
    axios.get('getlistbieumau').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenbieumau + '</option>';
            });
            $('#solieubieumau').html('<option value=""></option>' + htmldonvihc);
        }
    })


    $('#timkiem').click(function() {
        loadsolieutheobieumau();
    });

    function loadsolieutheobieumau() {
        document.getElementById("tablegrid").style.display = "block";
        let tinh = $('#tinh').val();
        let huyen = $('#huyen').val();
        let xa = $('#xa').val();
        let donvithuthap = $('#donvithuthap').val();
        let solieucuaky = $('#solieucuaky').val();
        let solieucuanam = $('#solieucuanam').val();
        let chonthang = $('#chonthang').val();
        let solieubieumau = $('#solieubieumau').val();

        axios.post('loadsolieutheobieumau', { tinh: tinh, huyen: huyen, xa: xa, donvithuthap: donvithuthap, solieucuaky: solieucuaky, solieucuanam: solieucuanam, chonthang: chonthang, solieubieumau: solieubieumau }).then(function(response) {
            var data = response.data[0];
            var data1 = response.data[1];
            var data2 = response.data[2];



            if (data == '') {
                Swal.fire({
                    title: 'Có lỗi!',
                    text: 'Không có dữ liệu! Vui lòng chọn và thử lại',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }

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
                //  mode: "multiple",
                //  allowSelectAll: false,
                //  recursive: false,
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
                        .text(" Người")
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
                                $('#sosanhtheobieumau').modal('show');
                                showbieudo(options,data1,data2);
                            }
                        }).appendTo(container);
                    },
                    width: 100,
                },

                ],
            });





            var dataGrid = $('#treelist').dxTreeList('instance');
            var state = dataGrid.state();
            var columns = dataGrid.option("columns");
            columns.push({
                calculateCellValue: function(rowData) {
                    var lucky = data1.filter(function(number) {
                        if (rowData.id == number.chitieu) {
                            return number;
                        }
                    });



                    if(lucky != '' ){
                        return lucky[0].count;
                    }
                    return null;

                },
                caption: data1[0].tenloaisolieu
            }, );
            dataGrid.option("columns", columns);
            dataGrid.state(state);









            

            var dataGrid2 = $('#treelist').dxTreeList('instance');
            var state2 = dataGrid2.state();
            var columns2 = dataGrid2.option("columns");
            columns2.push({
                calculateCellValue: function(rowData) {
                    var lucky2 = data2.filter(function(number) {
                        if (rowData.id == number.chitieu) {
                            return number;
                        }
                    });



                    if(lucky2 != '' ){
                        return lucky2[0].count;
                    }
                    return null;

                },
                caption: data2[0].tenloaisolieu
            }, );
            dataGrid2.option("columns", columns2);
            dataGrid2.state(state2);








        })






    }








function showbieudo(data, data1,data2) {
    var datas = data.data;
    if (datas == '') {
        Swal.fire({
            title: 'Có lỗi!',
            text: 'Không có dữ liệu! Vui lòng chọn và thử lại',
            icon: 'error',
            confirmButtonText: 'OK'
        })
    } else {

        var bieudo = data1.filter(function(number) {
            if (datas.id == number.chitieu) {
                return number;
            }
        });

        var bieudo2 = data2.filter(function(number) {
            if (datas.id == number.chitieu) {
                return number;
            }
        });
        var char = bieudo.concat(bieudo2);




        $("#chart").dxChart({
            dataSource: char,
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
                argumentField: "tenloaisolieu",
                valueField: "count",
                type: "bar",
                color: '#ffaa66'
            },

            ],
            argumentAxis: {
                label: {
                    format: {
                        type: "decimal"
                    }
                }
            },
        });
    }

}









})