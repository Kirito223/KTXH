var id = null;
var DataTable = null;
$(document).ready(function() {  	
	if (navigator.onLine) {
		DataTable = $('#table-donvi').DataTable({
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
			"serverSide": true,
			"bProcessing": true,
			"ajax": 'listDonVi',
			"columns": [
			{ "data": 'tendonvi' },
			{ "data": 'email' }, 
			{ "data": 'ketoan' },
			{ "data": 'nguoiky' }, 		
			{ "data": "dienthoai"},
			{
				"mData": 0,
				"bSortable": false,
				"mRender": function(data, type, full) {
					return '<button class="btn btn-info " type="button" onclick="showInfordonvi(' + full.id + ')"><i class="fa fa-paste"></i> Sửa</button>&nbsp'+
                           '<button class="btn btn-danger" type="button" onclick="deldonvi(' + full.id + ');"><i class="fa fa-warning"></i> <span class="bold">Xóa</span></button>&nbsp'+
                           '<button class="btn btn-warning" type="button" onclick="resetpass(' + full.id + ');"><i class="fa fa-key"></i> <span class="bold">Mật khẩu mặc định</span></button>';
                    
					
				}
			}
			],
		});
		loadroleadd();
		loadroleedit();
		loadTruonghoc();
         //id nut them roleUser
         $('#adddonvi').click(function() {
         	addNew();
         	$('#modal-themdonvi').modal('show');       
         });        
        
         //id nut modal save updatedonvi
         $('#savedonvi').click(function() {
         	updatedonvi();
         });
        $('#chkxa').click(function () {
            changetaikhoanxa();
        });

     } else {
     	Swal.fire({
     		title: 'Không có kết nối Internet!',
     		text: 'Không có kết nối internet vui lòng kiểm tra lại',
     		icon: 'error',
     		confirmButtonText: 'OK'
     	})
     }

     $('#deldonvi').on('click', function() {
     	axios.post('delDonVi', { id: id }).then(function(response) {
     		let data = response.data;
     		if (data == 200) {
     			$('#modal-xoadonvi').modal('toggle');
     			DataTable.ajax.reload();
     			Swal.fire({
     				title: 'Đã xoá',
     				text: 'Đẫ xoá thành công',
     				icon: 'success',
     				confirmButtonText: 'OK'
     			});
     			id = 0;
     		} else {
     			Swal.fire({
     				title: 'Có lỗi!',
     				text: 'Đã có lối xảy ra! Không thể xoá vui lòng thử lại',
     				icon: 'error',
     				confirmButtonText: 'OK'
     			})
     		}
     	}).catch(function(err) {
     		console.log(err);
     	});
     });
     $('#resetpass').on('click', function() {
     	axios.post('resetAdminPassword', { id: id }).then(function(response) {
     		let data = response.data;
     		if (data == 200) {
     			
     			Swal.fire({
     				title: 'Đã reset',
     				text: 'Đẫ reset thành công',
     				icon: 'success',
     				confirmButtonText: 'OK'
     			});
     			id = 0;
     		} else {
     			Swal.fire({
     				title: 'Có lỗi!',
     				text: 'Đã có lối xảy ra! Không thể xoá vui lòng thử lại',
     				icon: 'error',
     				confirmButtonText: 'OK'
     			})
     		}
     	}).catch(function(err) {
     		console.log(err);
     	});
     });
 });


function changetaikhoanxa()
{
	let taikhoanxa= $('input[name=chkxa]:checked').length;
	
    if(taikhoanxa<=0)
    {
    	loadTruonghoc();
    }
    else
    {
    	loadPhuongXa();
    }
}
function loadPhuongXa() {
    axios.get('listXaAdmin').then(function (response) {
        let data = response.data;
        if (data != null) {
            let html = data.map(function (item) {
                return '<option value="' + item.id + '">' + item.xa + '</option>';
            });
            $('#donvi').html('<option value=""></option>' + html);
        }

    }).catch(function (err) {
        console.log(err);
    })
}
function loadTruonghoc() {
    axios.get('getdsTruongAdmin').then(function (response) {
        let data = response.data;
        if (data != null) {
            let html = data.map(function (item) {
                return '<option value="' +  item.IdTruong + '">' + item.tentruong + '</option>';
            });
            $('#donvi').html('<option value=""></option>' + html);
            
        }

    }).catch(function (err) {
        console.log(err);
    })
}

function showInfordonvi(iddonvi) {
	if (iddonvi > 0) {
		id = iddonvi;
		axios.post('listDonVitable', { id: iddonvi }).then(function(response) {
			let data = response.data;
			if (data == null || data != "") {
				getInfo(data);
			} else {
				alert("đã xảy ra lỗi vui lòng thử lại")
			}
		}).catch(function(err) {
			console.log(err);
		})
	}
}

function changvalue(data) {
	
	$('#donvi').val(data.donvi);
	$('#chucdanh1').val(data.chucdanh1);
	$('#ma_qhns').val(data.ma_qhns);
	$('#dienthoai').val(data.dienthoai);
	$('#chucdanh2').val(data.chucdanh2);
	$('#ketoan').val(data.ketoan);
	$('#truongphong').val(data.truongphong);
	$('#chucdanh_qd2').val(data.chucdanh_qd2);
	$('#tennguoidung').val(data.tennguoidung);
	$('#nguoiky').val(data.nguoiky);
	$('#truongphongtc').val(data.truongphongtc);
	$('#chucdanh_qd').val(data.chucdanh_qd);
	$('#nguoiky_qd').val(data.nguoiky_qd);
	$('#loaitaikhoan').val(data.loaitaikhoan);
	$('#tenuser').val(data.tenuser);
	$('#email').val(data.email);
	$('#quyen').val(data.loaitaikhoan).change();
	

}
async function getInfo(data) {
	await Promise.all([changvalue(data)]).then(
		$('#modal-themdonvi').modal('show')
		);
}



function updatedonvi() {	

	let loaitaikhoan = $('#quyen').val();  
	let donvi =$('#donvi').children("option:selected").text();
	
	let chucdanh1 =$('#chucdanh1').val(); 
	let ma_qhns =$('#ma_qhns').val(); 
	let dienthoai =$('#dienthoai').val(); 
	let chucdanh2 =$('#chucdanh2').val(); 
	let ketoan =$('#ketoan').val(); 
	let truongphong =$('#truongphong').val(); 
	let chucdanh_qd2 =$('#chucdanh_qd2').val(); 
	let tennguoidung =$('#tennguoidung').val(); 
	let nguoiky =$('#nguoiky').val(); 
	let truongphongtc =$('#truongphongtc').val(); 
	let chucdanh_qd =$('#chucdanh_qd').val(); 
	let nguoiky_qd =$('#nguoiky_qd').val(); 
	let password =$('#password').val(); 
	let matruong=0;
	let maxa=0;
	let madonvi =$('#donvi').children("option:selected").val();
	let taikhoanxa= $('input[name=chkxa]:checked').length;
	
    if(taikhoanxa<=0)
    {
    	matruong=madonvi;
    }
    else
    {
    	maxa=madonvi;
    }
	
	let email =$('#email').val(); 
	 if (id == null) {
		axios.post('InsertAndUpdateDonVi', {donvi:donvi,chucdanh1:chucdanh1,ma_qhns:ma_qhns,dienthoai:dienthoai,chucdanh2:chucdanh2,
			ketoan:ketoan,truongphong:truongphong,chucdanh_qd2:chucdanh_qd2,tennguoidung:tennguoidung,nguoiky:nguoiky,truongphongtc:truongphongtc,
			chucdanh_qd:chucdanh_qd,loaitaikhoan:loaitaikhoan,nguoiky_qd:nguoiky_qd,email:email,password:password,maxa:maxa,matruong:matruong,id:id}).then(function(response) {
			let success = response.data;
			if (success == 500) {
				Swal.fire({
					title: 'Có lỗi',
					text: 'Lưu không thành công đã có lỗi xảy ra vui lòng thử lại',
					icon: 'error',
					confirmButtonText: 'OK'
				})
			} else {
				
				$('#modal-themdonvi').modal('toggle');
				DataTable.ajax.reload();
				Swal.fire({
					title: 'Lưu',
					text: 'Đã lưu thành công',
					icon: 'success',
					confirmButtonText: 'OK'
				})
			}
		}).catch(function(err) {
			console.log(err);
		})
	} else 
	{
		axios.post('InsertAndUpdateDonVi', {donvi:donvi,chucdanh1:chucdanh1,ma_qhns:ma_qhns,dienthoai:dienthoai,chucdanh2:chucdanh2,
			ketoan:ketoan,truongphong:truongphong,chucdanh_qd2:chucdanh_qd2,tennguoidung:tennguoidung,nguoiky:nguoiky,truongphongtc:truongphongtc,
			chucdanh_qd:chucdanh_qd,loaitaikhoan:loaitaikhoan,nguoiky_qd:nguoiky_qd,email:email}).then(function(response) {
			let success = response.data;
			if (success == 500) {
				Swal.fire({
					title: 'Có lỗi',
					text: 'Lưu không thành công đã có lỗi xảy ra vui lòng thử lại',
					icon: 'error',
					confirmButtonText: 'OK'
				})
			} else {
				
				$('#modal-themdonvi').modal('toggle');
				DataTable.ajax.reload();
				Swal.fire({
					title: 'Lưu',
					text: 'Đã lưu thành công',
					icon: 'success',
					confirmButtonText: 'OK'
				})
			}
		}).catch(function(err) {
			console.log(err);
		})
	}

}









 //show them roleUser     
 function addNew() {
 	id = null;
 	$('#donvi').val('');
	$('#chucdanh1').val('Chức danh 1');
	$('#ma_qhns').val('NS.0001');
	$('#dienthoai').val('0901940369');
	$('#chucdanh2').val('Chức danh 2');
	$('#ketoan').val('Tên kế toán');
	$('#truongphong').val('Tên trưởng phòng');
	$('#chucdanh_qd2').val('Chức danh quyết định');
	$('#tennguoidung').val('Tên người dùng');
	$('#nguoiky').val('Người ký');
	$('#truongphongtc').val('Trưởng phòng tài chính');
	$('#chucdanh_qd').val('Chức danh quyết định 1');
	$('#nguoiky_qd').val('Người ký quyết định 1');
	$('#loaitaikhoan').val('');
	$('#password').val('abc@123');
	$('#email').val('abc@gmail.com');

 }



 //show and xoa roleUser
 function deldonvi(iddonvi) {
 	$('#modal-xoadonvi').modal('show');
 	id = iddonvi;
 }


 function loadroleadd() {
 	axios.get('getlistrole').then(function(response) {
 		let data = response.data;
 		if (data != null) {
 			let html = data.map(function(item) {
 				return '<option value="' + item.id + '">' + item.name + '</option>';
 			});
 			$('#quyen').html(html).multiselect({includeSelectAllOption: true,buttonWidth: '100%',});
 		}
 	}).catch(function(err) {
 		console.log(err);
 	})
 }
 function loadroleedit() {
 	axios.get('getlistrole').then(function(response) {
 		let data = response.data;
 		if (data != null) {
 			let html = data.map(function(item) {
 				return '<option value="' + item.id + '">' + item.name + '</option>';
 			});
 			$('#quyen1').html('item.name'+html).multiselect({includeSelectAllOption: true,buttonWidth: '100%',});
 		}
 	}).catch(function(err) {
 		console.log(err);
 	})
 }

