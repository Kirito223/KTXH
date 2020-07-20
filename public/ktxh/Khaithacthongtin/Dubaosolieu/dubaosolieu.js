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


    $('#xa').on('change', function() {
        let xaid = $('#xa').val();
        axios.post('getmadonvi', { id: xaid }).then(function(response) {
            let data = response.data;
            let madonvi = data[0].madonvi;
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
    axios.get('getlistchitieu').then(function(response) {
        let data = response.data;
        if (data != null) {
            let htmldonvihc = data.map(function(item) {
                return '<option value="' + item.id + '">' + item.tenchitieu + '</option>';
            });
            $('#chitieu').html('<option value=""></option>' + htmldonvihc);
        }
    })






















})