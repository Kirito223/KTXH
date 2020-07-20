var id = null;
var DataTable = null;

$(document).ready(function () {

    if (navigator.onLine) {
        DataTable = $('#table-lophoc').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Số trang _PAGE_ of _PAGES_",
                "infoEmpty": "Không có bản ghi",
                "infoFiltered": "(Lọc _MAX_ bản ghi)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu tiên",
                    "last": "Cuối",
                    "next": "Tiếp theo",
                    "previous": "Quay lại"
                }
            },
            //"dataType": "json",
            "serverSide": true,
            "bProcessing": true,
            "ajax": "dscauhinh",
            "columns": [
                { "data": "mucluong" },
                { "data": "tien_dodunght" },
                { "data": "muchuongdantocthieuso" },
                { "data": "hotrochiphi" },
                { "data": "hocphi" },
                {
                    "mData": 0,
                    "bSortable": false,
                    "mRender": function (data, type, full) {
                        return moment(full.tungay).format("DD/MM/YYYY");
                    }
                },
                {
                    "mData": 0,
                    "bSortable": false,
                    "mRender": function (data, type, full) {
                        return moment(full.denngay).format("DD/MM/YYYY");
                    }
                },
                 {
                    "mData": 0,
                    "bSortable": false,
                    "mRender": function (data, type, full) {
                        return '<button class="btn btn-info " type="button" onclick="showInfoLoophoc(' + full.id + ')"><i class="fa fa-paste"></i> Sửa</button>&nbsp'+
                           '<button class="btn btn-danger" type="button" onclick="delLophoc(' + full.id + ')"><i class="fa fa-warning"></i> <span class="bold">Xóa</span></button>';
                    
                       }
                }
            ],

        });

        $("#tungay").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        $("#denngay").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        
       
        $('#addnew').click(function () {
            addNew();
            $('#modal-lophoc').modal('show');
        });
        $('#saveLop').click(function () {
            AddAndInsertLophoc();
        })
    } else {
        Swal.fire({
            title: 'Không có kết nối Internet!',
            text: 'Không có kết nối internet vui lòng kiểm tra lại',
            icon: 'error',
            confirmButtonText: 'OK'
        })
    }

});


function delLophoc(idLop) {

    $('#modal-xoalop').modal('show');
    $('#delLop').click(function () {
        axios.post('delCauhinh', { id: idLop }).then(function (response) {
            let data = response.data;
            if (data == 200) {
                $('#modal-xoalop').modal('toggle');
                DataTable.ajax.reload();
                Swal.fire({
                    title: 'Đã xoá',
                    text: 'Đẫ xoá thành công',
                    icon: 'success',
                    confirmButtonText: 'OK'
                })
            } else {
                Swal.fire({
                    title: 'Có lỗi!',
                    text: 'Đã có lối xảy ra! Không thể xoá vui lòng thử lại',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }

        }).catch(function (err) {
            console.log(err);
        })
    })

}

function addNew() {
    id = null;
    $('#mucluong').val("149000");
    $('#ddht').val("100000");
    $('#dtts').val("0.3");
    $('#hocphi').val("5000000");
    $('#tungay').val("1/1/2020");
    $('#denngay').val("31/12/2021");
    $('#hthocphi').val("100000");
    
}
 
function AddAndInsertLophoc() {
    if (id == null) {
        let mucluong = $('#mucluong').val();
        let tien_dodunght = $('#ddht').val();
        let muchuongdantocthieuso = $('#dtts').val();
        let hotrochiphi = $('#hthocphi').val();
        let hocphi = $('#hocphi').val();
        let tungay = $('#tungay').val();
        let valtungay = moment(tungay,"DD/MM/YYYY").format("YYYY-MM-DD");
        let denngay = $('#denngay').val();
         let valdenngay = moment(denngay,"DD/MM/YYYY").format("YYYY-MM-DD");
        if (mucluong != "" && hocphi != "") {
            axios.post('InsertAndUpdateCauHinh', { mucluong: mucluong, tien_dodunght: tien_dodunght,
             muchuongdantocthieuso: muchuongdantocthieuso, hotrochiphi: hotrochiphi,
             hocphi: hocphi, tungay: valtungay,denngay:valdenngay }).then(function (response) {
                let data = response.data;
                if (data == 200) {
                    $('#modal-lophoc').modal('toggle');
                    DataTable.ajax.reload();
                    Swal.fire({
                        title: 'Lưu',
                        text: 'Đã lưu thành công',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                } else {
                    Swal.fire({
                        title: 'Có lỗi',
                        text: 'Lưu không thành công đã có lỗi xảy ra vui lòng thử lại',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }
            }).catch(function (err) {
                console.log(err);
            })
        } else {
            Swal.fire({
                title: 'Thông tin không hợp lệ',
                text: 'Thông tin không hợp lệ vui lòng kiểm tra lại',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        }
    } else {
        let mucluong = $('#mucluong').val();
        let tien_dodunght = $('#ddht').val();
        let muchuongdantocthieuso = $('#dtts').val();
        let hotrochiphi = $('#hthocphi').val();
        let hocphi = $('#hocphi').val();
        let tungay = $('#tungay').val();
        let valtungay = moment(tungay,"DD/MM/YYYY").format("YYYY-MM-DD");
        let denngay = $('#denngay').val();
         let valdenngay = moment(denngay,"DD/MM/YYYY").format("YYYY-MM-DD");
        if (mucluong != "" && hocphi != "") {
            axios.post('InsertAndUpdateCauHinh', { mucluong: mucluong, tien_dodunght: tien_dodunght,
             muchuongdantocthieuso: muchuongdantocthieuso, hotrochiphi: hotrochiphi,
             hocphi: hocphi, tungay: valtungay,denngay:valdenngay,id:id }).then(function (response) {
                let data = response.data;
                if (data == 200) {
                    $('#modal-lophoc').modal('toggle');
                    DataTable.ajax.reload();
                    Swal.fire({
                        title: 'Lưu',
                        text: 'Đã lưu thành công',
                        icon: 'success',
                        confirmButtonText: 'Cool'
                    })
                } else {
                    Swal.fire({
                        title: 'Có lỗi',
                        text: 'Lưu không thành công đã có lỗi xảy ra vui lòng thử lại',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }
            }).catch(function (err) {
                console.log(err);
            })
        }
    }
}

function showInfoLoophoc(idLop) {
    if (idLop > 0) {
        id = idLop;
        axios.get('cauhinh/'+ idLop ).then(function (response) {
            let data = response.data;
            if (data == null || data != "") {
                getInfo(data);
            } else {
                alert("đã xảy ra lỗi vui lòng thử lại")
            }
        }).catch(function (err) {
            console.log(err);
        })
    }
}

async function getInfo(data) {

    await Promise.all([changvalue(data)]).then(
        $('#modal-lophoc').modal('show')
    );
}



function changvalue(data) {
    id = data.id;
     $('#mucluong').val(data.mucluong);
    $('#ddht').val(data.tien_dodunght);
    $('#dtts').val(data.muchuongdantocthieuso);
    $('#hocphi').val(data.hocphi);
    let valtungay = moment(data.tungay,"YYYY-MM-DD").format("DD/MM/YYYY");
    let valdenngay = moment(data.denngay,"YYYY-MM-DD").format("DD/MM/YYYY");
    $('#tungay').val(valtungay);
    $('#denngay').val(valdenngay);
    $('#hthocphi').val(data.hotrochiphi);


}