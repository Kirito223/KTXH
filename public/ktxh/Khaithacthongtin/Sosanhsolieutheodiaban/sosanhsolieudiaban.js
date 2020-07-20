$(document).ready(function() {


    $("#namsosanh").datepicker({
        format: "yyyy",
        orientation: "bottom",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        language: 'vi',
    });

    $("#thangsosanh").datepicker({
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


    $('#sosanhduatheochitieu').select2({ width: '100%' });
    axios.get('getlistchitieu').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenchitieu + '</option>';
            });
            $('#sosanhduatheochitieu').html('<option value=""></option>' + htmldonvihc);
        }
    })

    $('#sosanhsolieudonvi').select2({ width: '100%' });
    axios.get('getdonvihanhchinh').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tendonvi + '</option>';
            });
            $('#sosanhsolieudonvi').html('<option value=""></option>' + htmldonvihc);
        }
    })

    $('#sosanhduatheobieumau').select2({ width: '100%' });
    axios.get('getlistbieumau').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenbieumau + '</option>';
            });
            $('#sosanhduatheobieumau').html('<option value=""></option>' + htmldonvihc);
        }
    })


    $('#duatrenloaisolieu').select2({ width: '100%' });
    axios.get('getloaisolieu').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenloaisolieu + '</option>';
            });
            $('#duatrenloaisolieu').html('<option value=""></option>' + htmldonvihc);
        }
    })


    $('#sosanhtheokysolieu').select2({ width: '100%' });
    axios.get('getkybaocao').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenky + '</option>';
            });
            $('#sosanhtheokysolieu').html('<option value=""></option>' + htmldonvihc);
        }
    })








    $('#checkbox1').change(function() {
        if ($(this).is(":checked")) {
            $("#checkbox2").prop("checked", false);
            document.getElementById("sssldv").style.display = "block";
            document.getElementById("ssdtbm").style.display = "block";
            document.getElementById("timkiemdiaban").style.display = "block";

            document.getElementById("slcdb").style.display = "block";
            document.getElementById("ssdtct").style.display = "none";
            document.getElementById("tss").style.display = "none";
            document.getElementById("timkiem").style.display = "none";
            document.getElementById("timkiemdonvi").style.display = "none";
        } else {
            document.getElementById("sssldv").style.display = "none";
            document.getElementById("ssdtbm").style.display = "none";
            document.getElementById("timkiemdiaban").style.display = "none";
            document.getElementById("timkiem").style.display = "block";
            document.getElementById("slcdb").style.display = "none";
            document.getElementById("ssdtct").style.display = "none";
            document.getElementById("tss").style.display = "none";
        }
    });

    $('#checkbox2').change(function() {
        if ($(this).is(":checked")) {
            $("#checkbox1").prop("checked", false);
            document.getElementById("sssldv").style.display = "none";
            document.getElementById("ssdtbm").style.display = "none";
            document.getElementById("timkiem").style.display = "none";
            document.getElementById("timkiemdiaban").style.display = "none";

            document.getElementById("slcdb").style.display = "block";
            document.getElementById("ssdtct").style.display = "block";
            document.getElementById("tss").style.display = "block";
            document.getElementById("timkiemdonvi").style.display = "block";
        } else {
            document.getElementById("slcdb").style.display = "none";
            document.getElementById("ssdtct").style.display = "none";
            document.getElementById("tss").style.display = "none";
            document.getElementById("timkiemdonvi").style.display = "none";
            document.getElementById("timkiem").style.display = "block";

        }
    });






    $('#timkiem').click(function() {

        Swal.fire({
            title: 'Có lỗi!',
            text: 'Không có dữ liệu! Vui lòng chọn và thử lại',
            icon: 'error',
            confirmButtonText: 'OK'
        })

    });

    $('#timkiemdiaban').click(function() {
        loadsosanhsolieutheodiaban();
    });

    $('#timkiemdonvi').click(function() {
        loadsosanhsolieutheodonvi();
    });

    function loadsosanhsolieutheodiaban() {
        document.getElementById("tablegrid").style.display = "block";
        document.getElementById("tablegrid1").style.display = "none";
        let tinh = $('#tinh').val();
        let huyen = $('#huyen').val();
        let xa = $('#xa').val();
        let sosanhsolieudonvi = $('#sosanhsolieudonvi').val();
        let sosanhduatheobieumau = $('#sosanhduatheobieumau').val();
        let duatrenloaisolieu = $('#duatrenloaisolieu').val();
        let sosanhtheokysolieu = $('#sosanhtheokysolieu').val();
        let namsosanh = $('#namsosanh').val();

        var tentinh = $("#tinh option:selected").text();
        var tenhuyen = $("#huyen option:selected").text();

        axios.post('loadsosanhsolieudiaban', { sosanhsolieudonvi: sosanhsolieudonvi, sosanhduatheobieumau: sosanhduatheobieumau, duatrenloaisolieu: duatrenloaisolieu, sosanhtheokysolieu: sosanhtheokysolieu, namsosanh: namsosanh, tinh: tinh, huyen: huyen, xa: xa }).then(function(response) {
            var data = response.data[0];
            var sltinh = response.data[1];
            var slhuyen = response.data[2];
            if (data == '') {
                Swal.fire({
                    title: 'Có lỗi!',
                    text: 'Không có dữ liệu! Vui lòng chọn và thử lại',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }


            $("#treelistdiaban").dxTreeList({
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
                            if (options.data.parent_id == null) {
                                $("<div>")
                                    .text(" Người")
                                    .appendTo(container);
                            }

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
                                    if (tinh == '' || huyen == '') {
                                        $('#bieudodiaban').modal('hide');
                                    } else {
                                        $('#bieudodiaban').modal('show');
                                    }

                                    showbieudodiaban(data, sltinh, slhuyen, tentinh, tenhuyen);
                                }
                            }).appendTo(container);
                        },
                        width: 100,
                    },

                ],
            });

            var dataGrid = $('#treelistdiaban').dxTreeList('instance');
            if (sltinh != '') {
                var state = dataGrid.state();
                var columns = dataGrid.option("columns");
                columns.push({
                    calculateCellValue: function(rowData) {
                        if (rowData.parent_id == null) {
                            return sltinh[0].matinh;
                        }
                    },
                    caption: tentinh
                }, );
                dataGrid.option("columns", columns);
                dataGrid.state(state);
            }

            if (slhuyen != '') {
                var state = dataGrid.state();
                var columns = dataGrid.option("columns");
                columns.push({
                    calculateCellValue: function(rowData) {
                        if (rowData.parent_id == null) {
                            return slhuyen[0].mahuyen;
                        }
                    },
                    caption: tenhuyen
                }, );
                dataGrid.option("columns", columns);
                dataGrid.state(state);
            }
        });
    }



    function showbieudodiaban(data, tinh, huyen, tentinh, tenhuyen) {
        if (tinh == '' || huyen == '') {
            Swal.fire({
                title: 'Có lỗi!',
                text: 'Không có dữ liệu! Vui lòng chọn và thử lại',
                icon: 'error',
                confirmButtonText: 'OK'
            })

        } else {
            var chart = tinh.concat(huyen);
            $("#chart").dxChart({
                dataSource: chart,
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
                        argumentField: "matinh",
                        valueField: "matinh",
                        name: tentinh,
                        type: "bar",
                        color: '#ffaa66'
                    },
                    { valueField: "mahuyen", argumentField: "mahuyen", name: tenhuyen, type: "bar", }
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




    function loadsosanhsolieutheodonvi() {
        document.getElementById("tablegrid1").style.display = "block";
        document.getElementById("tablegrid").style.display = "none";
        let tinh = $('#tinh').val();
        let huyen = $('#huyen').val();
        let xa = $('#xa').val();
        let sosanhduatheochitieu = $('#sosanhduatheochitieu').val();
        let duatrenloaisolieu = $('#duatrenloaisolieu').val();
        let sosanhtheokysolieu = $('#sosanhtheokysolieu').val();
        let thangsosanh = $('#thangsosanh').val();
        let namsosanh = $('#namsosanh').val();


        axios.post('loadsosanhsolieudonvi', { tinh: tinh, huyen: huyen, xa: xa, sosanhduatheochitieu: sosanhduatheochitieu, duatrenloaisolieu: duatrenloaisolieu, sosanhtheokysolieu: sosanhtheokysolieu, thangsosanh: thangsosanh, namsosanh: namsosanh }).then(function(response) {
            var data = response.data[0];
            var data1 = response.data[1];
            var data2 = response.data[2];

            var resArr = [];
            data1.filter(function(item) {
                var i = resArr.findIndex(x => x.name == item.tendonvi);
                if (i <= -1) {
                    resArr.push({ id: item.id, name: item.tendonvi, madonvi: item.madonvi });
                }
                return null;
            });



            if (data == '') {
                Swal.fire({
                    title: 'Có lỗi!',
                    text: 'Không có dữ liệu! Vui lòng chọn và thử lại',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }

            $("#treelistdonvi").dxTreeList({
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
                            if (options.data.parent_id == null) {
                                $("<div>")
                                    .text(" Cở sở")
                                    .appendTo(container);
                            }

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
                                    $('#bieudodonvi').modal('show');
                                    showbieudodonvi(resArr, options);
                                }
                            }).appendTo(container);
                        },
                        width: 100,
                    },

                ],
            });




            for (let index = 0; index < resArr.length; index++) {
                const element = resArr[index].name;
                const elementid = resArr[index].madonvi;


                var dataGrid = $('#treelistdonvi').dxTreeList('instance');
                if (resArr != '') {
                    var state = dataGrid.state();
                    var columns = dataGrid.option("columns");
                    columns.push({
                        calculateCellValue: function(rowData) {

                            var lucky = data2.filter(function(number) {
                                if (number.chitieu == rowData.id && number.madonvi == elementid) {
                                    return number;
                                }
                            });
                            if (lucky != '') {
                                return lucky.length;
                            }
                            return null;


                        },
                        caption: element
                    }, );
                    dataGrid.option("columns", columns);
                    dataGrid.state(state);
                }

            }
        });
    }




    function showbieudodonvi(data, options) {
        var op = options.data.id;
        var da = data;
        if (data == '') {
            Swal.fire({
                title: 'Có lỗi!',
                text: 'Không có dữ liệu! Vui lòng chọn và thử lại',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        } else {

            axios.post('getbieudodonvi', { idchitieu: op }).then(function(response) {
                var data = response.data;

                var ss = [];
                for (let index = 0; index < da.length; index++) {
                    var element = da[index].madonvi;
                    var lucky = data.filter(function(number) {
                        if (number.madonvi == element) {
                            return number;

                        }

                    });
                    ss.push(lucky);
                }

                var resArr = [];
                ss.filter(function(item) {
                    var it = item.length;
                    da.filter(function(tt) {
                        var ten = tt.name;
                        var ma = tt.madonvi;
                        item.filter(function(items) {
                            if (items.madonvi == ma) {
                                resArr.push({ id: items.chitieu, madonvi: it, tendonvi: ten });
                            }

                        });
                    });
                });


                const key = 'madonvi';
                const arrayUniqueByKey = [...new Map(resArr.map(item => [item[key], item])).values()];
                for (let index = 0; index < arrayUniqueByKey.length; index++) {
                    const element = arrayUniqueByKey[index].tendonvi;










                    $("#chart1").dxChart({
                        dataSource: arrayUniqueByKey,
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
                                argumentField: 'tendonvi',
                                valueField: 'madonvi',
                                // name: element,
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

            });

        }

    }

















})