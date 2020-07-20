var id = null;
var DataTable = null;
$(document).ready(function() {  	
	if (navigator.onLine) {
		DataTable = $('#table-roleUser').DataTable({
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
			"ajax": 'ListUser',
			"columns": [
			{ "data": 'id' },
			{ "data": 'names' }, 
			{ "data": 'email' },
			{ "data": 'name' }, 
			{ "data": 'huyen' },		
			{ "data": "created_at"},
			{
				"mData": 0,
				"bSortable": false,
				"mRender": function(data, type, full) {
					return '<button class="btn btn-info " type="button" onclick="showInforadminUser(' + full.id + ')"><i class="fa fa-paste"></i> Sửa</button>&nbsp'+
                           '<button class="btn btn-danger" type="button" onclick="deladminUser(' + full.id + ');"><i class="fa fa-warning"></i> <span class="bold">Xóa</span></button>';
                    
					
				}
			}
			],
		});
		loadroleadd();
		loadroleedit();
		 loadTinh() ;
		 loadQuanHuyen();
         //id nut them roleUser
         $('#addroleUser').click(function() {
         	addNew();
         	$('#modal-themadminUser').modal('show');       
         });        
         //id nut modal save addadminUser
         $('#saveadminUser').click(function() {
         	AddAdminUser();
         });
         //id nut modal save updateadminUser
         $('#saveadminUser1').click(function() {
         	updateAdminUser();
         });
         $('#saveadminpassword').click(function() {
         	updateAdminPassword();
         });


     } else {
     	Swal.fire({
     		title: 'Không có kết nối Internet!',
     		text: 'Không có kết nối internet vui lòng kiểm tra lại',
     		icon: 'error',
     		confirmButtonText: 'OK'
     	})
     }

     $('#deladminUser').on('click', function() {
     	axios.post('deladminUser', { id: id }).then(function(response) {
     		let data = response.data;
     		if (data == 200) {
     			$('#modal-xoaadminUser').modal('toggle');
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
 });


function loadTinh() {
    axios.get('jsonTinh').then(function (response) {
        let data = response.data;
        if (data != null) {
            let html = data.map(function (item) {
                return '<option value="' + item.id + '">' + item.tinh + '</option>';
            });
            $('#tinhthanh').html('<option value=""></option>' + html);
        }

    }).catch(function (err) {
        console.log(err);
    })
}
function loadQuanHuyen() {
    axios.get('listHuyen').then(function (response) {
        let data = response.data;
        if (data != null) {
            let html = data.map(function (item) {
                return '<option value="' + item.id + '">' + item.huyen + '</option>';
            });
            $('#quanhuyen').html('<option value=""></option>' + html);
        }

    }).catch(function (err) {
        console.log(err);
    })
}



function showInforadminUser(idadminUser) {
	if (idadminUser > 0) {
		id = idadminUser;
		axios.post('getUser', { id: idadminUser }).then(function(response) {
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
	adminUsers = data.adminUser;
	$('#tenuser1').val(data.name);
	$('#email1').val(data.email);
	$('#quyen1').val(data.role);
	$('#password1').val('');
}
async function getInfo(data) {
	await Promise.all([changvalue(data)]).then(
		$('#modal-editadminUser').modal('show')
		);
}



function updateAdminPassword() {	
	let password1 = $('#password1').val();	    
	if (password1 != ""  ) {
		axios.post('updateAdminPassword', {password:password1,id:id}).then(function(response) {
			let success = response.data;
			if (success == 200) {
				Swal.fire({
					title: 'Có lỗi',
					text: 'Lưu không thành công đã có lỗi xảy ra vui lòng thử lại',
					icon: 'error',
					confirmButtonText: 'OK'
				})
			} else {
				
				$('#modal-editadminUser').modal('toggle');
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
	} else {
		Swal.fire({
			title: 'Thông tin không hợp lệ',
			text: 'Thông tin không hợp lệ vui lòng kiểm tra lại',
			icon: 'error',
			confirmButtonText: 'OK'
		})
	}

}






function updateAdminUser() {
	let name1 = $('#tenuser1').val();
	let email1 = $('#email1').val();
	let role1 = $('#quyen1').val();     
	if (name1 != "" && email1 != "" && role1 != "" ) {
		axios.post('updateAdminUser', { name: name1, email: email1,role:role1,id:id}).then(function(response) {
			let success = response.data;			
			if (success == 200) {
				Swal.fire({
					title: 'Có lỗi',
					text: 'Lưu không thành công đã có lỗi xảy ra vui lòng thử lại',
					icon: 'error',
					confirmButtonText: 'OK'
				})
			} else {
				$('#modal-editadminUser').modal('toggle');
				DataTable.ajax.reload();
				Swal.fire({
					title: 'Lưu',
					text: 'Đã lưu thành công',
					icon: 'success',
					confirmButtonText: 'OK'
				});
			}
		}).catch(function(err) {
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
}




function AddAdminUser() {
	if (id == null) {
		let name = $('#tenuser').val();
		let mahuyen = $('#quanhuyen').children("option:selected").val();
		let email = $('#email').val();
		let password = $('#password').val();
		let repassword = $('#repassword').val();
		let role = $('#quyen').children("option:selected").val();   
		if (name != "" && email != "sadasd" && password != "" && role != "" && repassword==password ) {
			axios.post('InsertAdminUser', { name: name,mahuyen:mahuyen, email: email,password:password,role:role}).then(function(response) {
				let success = response.data;
				if (success == 200) {
					$('#modal-themadminUser').modal('toggle');
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
			}).catch(function(err) {
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
		Swal.fire({
			title: 'Lưu',
			text: 'Đã lưu thành công',
			icon: 'success',
			confirmButtonText: 'OK'
		})
	}
}

 //show them roleUser     
 function addNew() {
 	id = null;
 	$('#tenuser').val("");
 	$('#email').val("");
 	$('#password').val("");
 	$('#repassword').val("");
 	$('#role').val("");
 }



 //show and xoa roleUser
 function deladminUser(idadminUser) {
 	$('#modal-xoaadminUser').modal('show');
 	id = idadminUser;
 }


 function loadroleadd() {
 	axios.get('getlistrole').then(function(response) {
 		let data = response.data;
 		if (data != null) {
 			let html = data.map(function(item) {
 				return '<option value="' + item.id + '">' + item.name + '</option>';
 			});
 			$('#quyen').html(html);
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

