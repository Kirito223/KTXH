$(document).ready(function() {
  $("#treelist_trash").dxTreeList({
   dataSource: {
    load: function(options) {
     return $.ajax({
      url: "getlistchitieutrash",
      dataType: "json",
                // data: { parentIds: options.parentIds }
              });
   }
 },

      // itemsExpr: "children",
      // dataStructure: "tree",
    keyExpr: "id",
  // parentIdExpr: "parent_id",
			 //sap xep
			 sorting: {
			 	mode: "multiple"
			 },
			 columnAutoWidth: true,
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
    selection: {
    	mode: "multiple",
    	recursive: true,
    },
    //loc row
    filterRow: {
    	visible: true
    },
    //co dan cot
    allowColumnResizing: true,
    //thu phóng row
    // autoExpandAll: true,

    columns: [
    { 
    	dataField: "tenchitieu",
    	caption: "Tên chỉ tiêu",
    	validationRules: [{ type: "required" }]
    }, 
    {
    	dataField: "idcha",
    	caption: "Id cha",
        // lookup: {
        //     dataSource:"getlistchitieu",
        //     valueExpr: "tenchitieu",
        //     displayExpr: "tenchitieu",
        // },
        validationRules: [{ type: "required" }]
      },
      {
       dataField: "donvi",
       caption: "Đơn vị",
       validationRules: [{ type: "required" }]
     },  
     ],
    // thêm chi tiêu toolbar
    onToolbarPreparing: function(e) {
      var dataGrid = e.component;
      e.toolbarOptions.items.unshift(
      {
        location: "after",                
        template: function () {
          return $("<i class='fa fa-reply'>").addClass("btn btn-warning btn-sm").text(" Trở lại");                  
        },
        onClick: function(e) {
          window.location = "listchitieu";
        }                
      },  
      {
        location: "after",                
        template: function () {
          return $("<i class='fa fa-info-circle'>").addClass("btn btn-primary btn-sm").text(" Khôi phục");                  
        },
        onClick: function(e) {
          var treeList = $("#treelist_trash").dxTreeList("instance");
          var selectedData = treeList.getSelectedRowKeys("all");
          RestoreChitieulistcheckbox(selectedData);
        }                
      },
        // {
        //     location: "after",                
        //     template: function () {
        //         return $("<i class='fa fa-close'>").addClass("btn btn-danger btn-sm").text(" Xóa vĩnh viễn");                  
        //     },
        //     onClick: function(e) {
        //         var treeList = $("#treelist_trash").dxTreeList("instance");
        //         var selectedData = treeList.getSelectedRowKeys("all");
        //         DelAllChitieulistcheckbox(selectedData);
        //     }                
        // },                   
        );
    },









  });
//khoi phuc list checkbox chi tieu
function RestoreChitieulistcheckbox(data)  
{   
  var treeList = $("#treelist_trash").dxTreeList("instance");
  Swal.fire({
    title: "Khôi phục dữ liệu",
    text: "Bạn có muốn khôi phục dữ liệu không",
    showCancelButton: true,
    cancelButtonText: "Đóng",
    confirmButtonText: "Đồng ý",
    icon: "success"
  }).then(result => {
    if (result.value) {
      for (let index = 0; index < data.length; index++) {
       var ID = data[index];
       axios.post('RestoreChitieulistcheckbox', { id:ID}).then(function (response) {
       });
       Swal.fire({
         title: 'Lưu',
         text: 'Đã lưu thành công',
         icon: 'success',
         confirmButtonText: 'OK'
       })
     }
     treeList.refresh();
   }
 })  
}







//xoa vĩnh viễn list checkbox chi tieu
function DelAllChitieulistcheckbox(data)  
{   
  var treeList = $("#treelist_trash").dxTreeList("instance");
  Swal.fire({
    title: "Xoá dữ liệu",
    text: "Bạn có muốn xoá dữ liệu không",
    showCancelButton: true,
    cancelButtonText: "Đóng",
    confirmButtonText: "Đồng ý",
    icon: "warning"
  }).then(result => {
    if (result.value) {
      for (let index = 0; index < data.length; index++) {
       var ID = data[index];
       axios.post('DelAllChitieulistcheckbox', { id:ID}).then(function (response) {
       });
       Swal.fire({
         title: 'Lưu',
         text: 'Đã lưu thành công',
         icon: 'success',
         confirmButtonText: 'OK'
       })
     }
     treeList.refresh();
   }
 })  
}










});









