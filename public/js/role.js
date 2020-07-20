 
 var id = null;
 var DataTable = null;
 $(document).ready(function() { 
 	
 	if (navigator.onLine) {

    
                $.ajax({
                    type: "GET",
                    url: 'Listrole',
                    contentType: "application/json; charset=utf-8", // content type sent to server
                    dataType: "json", //Expected data format from server
                    processdata: true, //True or False
                    success: function (msg) {//On Successfull API call
                        Succeeded(msg);
                    },
                    error: Failed// When API call fails
                });
            


 		// DataTable = $('#table-role').DataTable({
 		// 	"language": {
 		// 		"lengthMenu": "Hiển thị _MENU_ bản ghi",
 		// 		"zeroRecords": "Không tìm thấy dữ liệu",
 		// 		"info": "Số trang _PAGE_ of _PAGES_",
 		// 		"infoEmpty": "Không có bản ghi",
 		// 		"infoFiltered": "(Lọc _MAX_ bản ghi)",
 		// 		"search": "Tìm kiếm:",
 		// 		"paginate": {
 		// 			"first": "Đầu tiên",
 		// 			"last": "Cuối",
 		// 			"next": "Tiếp theo",
 		// 			"previous": "Quay lại"
 		// 		}
 		// 	},
 		// 	"serverSide": true,
 		// 	"bProcessing": true,
 		// 	ajax: 'Listrole',
 		// 	columns: [
 		// 	{ "data": 'id' },
 		// 	{ "data": 'name' },
 		// 	{ "data": 'description' }, 		
 		// 	{ "data": "created_at"},
 		// 	{
 		// 		"mData": 0,
 		// 		"bSortable": false,
 		// 		"mRender": function(data, type, full) {
   //        return '<button class="btn btn-info " type="button" onclick="showInforole(' + full.id + ')"><i class="fa fa-paste"></i> Sửa</button>&nbsp'+
   //                         '<button class="btn btn-danger" type="button" onclick="delrole(' + full.id + ');"><i class="fa fa-warning"></i> <span class="bold">Xóa</span></button>';
                    
 					
 		// 		}
 		// 	}
 		// 	]
 		// });
         //loadrole();
         //id nut them role
         $('#addrole').click(function() {
           addNew();
           $('#modal-themrole').modal('show');
       });
         //id nut modal save role
         $('#saverole').click(function() {
           AddAndInsertrole();
       });

     } else {
     	Swal.fire({
     		title: 'Không có kết nối Internet!',
     		text: 'Không có kết nối internet vui lòng kiểm tra lại',
     		icon: 'error',
     		confirmButtonText: 'OK'
     	})
     }




     $('#delrole').on('click', function() {
       axios.post('delRolec', { id: id }).then(function(response) {
           let data = response.data;
           if (data == 200) {
               $('#modal-xoarole').modal('toggle');
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
 function Failed(result) {
            alert('Web API call failed: ' + result.status + '' + result.statusText);
        }
function Succeeded(result) {
           // var resultObject = JSON.parse(result);
            var data =result ;//ej.DataManager(resultObject).executeLocal(ej.Query());

            $("#Grid").ejGrid({
                dataSource: data,
                allowPaging: true,
                pageSettings: { pageCount: 3, pageSize: 10 },
                allowSorting: true,
                isResponsive: true,
                scrollSettings:{height:210},
        minWidth:700,
                columns: [
                        { field: "id", headerText: "id", width: 120, textAlign: ej.TextAlign.Right },
                        { field: "names", headerText: "Items", width: 850 },
                        { field: "description", headerText: "Recommendation", width: 600, cssClass: "predictedColumnColor" },
                        { field: "created_at", headerText: "Exclusive_Recommendation", width: 450, cssClass: "predictedColumnColor" },
                        { field: "Rule_Association", headerText: "Rule_Association", width: 450, cssClass: "predictedColumnColor" }
                ]
            });
        }
 function showInforole(idrole) {
   if (idrole > 0) {
       id = idrole;
       axios.post('getrole', { id: idrole }).then(function(response) {
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
   roles = data.role;
   $('#tenrole').val(data.name);
   $('#mota').val(data.description);
   $('#created_at').val(data.created_at);
}
async function getInfo(data) {
   await Promise.all([changvalue(data)]).then(
       $('#modal-themrole').modal('show')
       );
}

function AddAndInsertrole() {
   if (id == null) {
       let tenrole = $('#tenrole').val();
       let description = $('#mota').val();   
       if (tenrole != "" && description != "" ) {
           axios.post('InsertAndUpdateRole', { name: tenrole, description: description }).then(function(response) {
               let success = response.data;
               if (success == 200) {
                   $('#modal-themrole').modal('toggle');
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
       let tenrole = $('#tenrole').val();
       let description = $('#mota').val();   
       if (tenrole != "" && description != "" ) {
           axios.post('InsertAndUpdateRole', { name: tenrole, description: description,id:id }).then(function(response) {
               let data = response.data;
               if (data == 200) {
                   $('#modal-themrole').modal('toggle');
                   DataTable.ajax.reload();
                   Swal.fire({
                       title: 'Lưu',
                       text: 'Đã lưu thành công',
                       icon: 'success',
                       confirmButtonText: 'Ok'
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
       }
   }
}

 //show them role     
 function addNew() {
   id = null;
   $('#tenrole').val("");
   $('#mota').val("");
   $('#created_at').val("");
}



 //show and xoa role
 function delrole(idrole) {
   $('#modal-xoarole').modal('show');
   id = idrole;
}




