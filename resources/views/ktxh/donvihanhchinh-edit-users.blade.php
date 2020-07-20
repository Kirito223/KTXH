@extends('master')
@section('title','Cập nhật người dùng')
@section('content')
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/loaisolieu" id="loaisolieuForm" style="margin-bottom : 10px;">
                <div class="widget">
                    <header class="widget-header">
                        @if(!empty(Session::get('success')))
                        <div class="alert alert-success">
                            <p class="text-success">{{ Session::get('success') }}</p>
                        </div>
                        @endif
                        @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <div class="alert alert-danger">
                            <p class="text-danger">{{ $error }}</p>
                        </div>
                        @endforeach
                        @endif
                        @if(!empty(Session::get('error')))
                        <div class="alert alert-danger">
                            <p class='text-danger'>{{ Session::get('error') }}</p>
                        </div>
                        @endif
                        <h4 class="widget-title">Thông Tin Đơn Vị</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade" id="modelForCreateLoaisolieu" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForCreateLoaisolieuLabel">Tạo Mới Loại Số Liệu
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createLoaisolieuForm">
                                            <div class="form-group">
                                                <label for="create-input-tenloaisolieu">Tên Loại Số Liệu</label>
                                                <input type="text" name="tenloaisolieu" class="form-control"
                                                    id="create-input-tenloaisolieu" placeholder="Nhập tên loại số liệu">
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-mota">Mô Tả</label>
                                                <input type="text" name="mota" class="form-control"
                                                    id="create-input-mota" placeholder="Nhập mô tả">
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-cachtinh">Cách Tính</label>
                                                <input type="text" name="cachtinh" class="form-control"
                                                    id="create-input-cachtinh" placeholder="Nhập cách tính">
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="apdung" class="form-check-input"
                                                    id="create-input-apdung" value="1">
                                                <label class="form-check-label" for="create-input-apdung">Áp
                                                    dụng</label>
                                            </div>
                                        </form>
                                        <div class="alert alert-danger print-error-msg-on-create" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success mw-md" id="submit-button-for-create">Tạo
                                            mới</button>
                                        <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">Hủy
                                            bỏ</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modelForEditLoaisolieu" tabindex="-1" role="dialog"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForEditLoaisolieuLabel">Chỉnh Sửa Loại Số Liệu
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="editLoaisolieuForm">
                                            <input type="hidden" name="id" id="EditInputId">
                                            <div class="form-group">
                                                <label for="edit-input-tenloaisolieu">Tên Loại Số Liệu</label>
                                                <input type="text" name="tenloaisolieu" class="form-control"
                                                    id="edit-input-tenloaisolieu" placeholder="Nhập tên loại số liệu">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-input-mota">Mô Tả</label>
                                                <input type="text" name="mota" class="form-control" id="edit-input-mota"
                                                    placeholder="Nhập mô tả">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-input-cachtinh">Cách Tính</label>
                                                <input type="text" name="cachtinh" class="form-control"
                                                    id="edit-input-cachtinh" placeholder="Nhập cách tính">
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="apdung" class="form-check-input"
                                                    id="edit-input-apdung" value="1">
                                                <label class="form-check-label" for="edit-input-apdung">Áp dụng</label>
                                            </div>
                                        </form>
                                        <div class="alert alert-danger print-error-msg-on-edit" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success mw-md" id="confirm-edit-btn">Chỉnh sửa</button>
                                        <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">Hủy
                                            bỏ</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal" id="deleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Xóa Loại Số Liệu</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa người dùng khỏi phòng ban này?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            id="button-confirm-delete">Xóa</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Tên đơn vị:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->tendonvi }}
                            </div>
							<input type="text" id="iddonvi" value="{{ $donvihanhchinh->id }}" hidden="">
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Thuộc:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->thuoc == 1 ? 'Sở ban ngành' : 'Thị xã/TP/Huyện' }}
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Số điện thoại:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->sodienthoai }}
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Địa chỉ email:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->email }}
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Địa chỉ liên hệ:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->diachi }}
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Mô tả:</b>
                                <a href="/donvihanhchinh" class="btn mw-md btn-primary m-xs" style="display:flex; padding: 3px 16px"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i><div style="padding: 5px; margin-left: 5px">Quay Lại</div></a>
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->mota }}
                            </div>
                        </div>
                        <hr class="widget-separator">
                        <h4 class="widget-title" style="margin-top: 10px">Cập Nhật Người Dùng</h4>
						<input class="hidden-item" value="{{ $donvihanhchinh->id }}" id="input-id-donvihanhchinh">
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Chọn Phòng Ban</b>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <select class="form-control" id="select-phongban">
                                        <option value="" selected>----Chọn phòng ban----</option>
										<option value="none">Không thuộc phòng ban</option>
                                        @if(count($phongbans))
                                        @foreach($phongbans as $phongban)
                                        <option value=" {{ $phongban->id }}">{{ $phongban->tenphongban }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Chọn Người Dùng</b>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <select multiple class="form-control" id="list-users" size="8">
                                        @if(count($unassignedUsers))
                                        @foreach($unassignedUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->tendangnhap }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button type="button" id="add-user-button"
                                    class="btn btn-success btn-md detail-create-btn">
                                    <i class="fa fa-plus fa-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">Tài khoản đăng nhập</th>
                                        <th style="width: 30%">Tên hiển thị người dùng</th>
                                        <th style="width: 20%">Thuộc phòng ban</th>
                                        <th style="width: 20%">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-users">
                                    <tr class="row-user hidden-item" id="example-row-user">
                                        <td style="vertical-align: middle">123</td>
                                        <td style="vertical-align: middle">456</td>
                                        <td style="vertical-align: middle">789</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                <i class="fa fa-close"></i> Xóa
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        	</div>
		</div>
</section>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

let currentPhongban = null;
let idDonvi = document.getElementById("input-id-donvihanhchinh").value;
let selectPhongban = document.getElementById('select-phongban');
selectPhongban.addEventListener('change', function(e) {
    let id = this.value;
	
    e.preventDefault();
    setCurrentPhongban(id);
	if(id == "") {
		clearTableBody();
	} else {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'GET',
        url: '/getusersthuocphongban/' + id,
		data: {
			idDonvi
		},
        success: function(data) {
            if (!$.isEmptyObject(data.error)) {
                console.log(data.error);
            } else {
                handleUserData(data);
                console.log(data.success);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
	}
})

function generateUserRow(inputData) {
    let cloneUserRow = document.getElementById('example-row-user').cloneNode(true);
    cloneUserRow.classList.remove('hidden-item');
    for (let i = 0; i < inputData.length; i++) {
        if (i == inputData.length - 1) {
            cloneUserRow.children[i].querySelector('.delete-btn').id = 'delete-btn-' + inputData[i];
            continue;
        }
        cloneUserRow.children[i].innerHTML = inputData[i];
    }


    return cloneUserRow;
}


function appendRowtoTable(...inputData) {
    let cloneUserRow = generateUserRow(inputData);
    let bodyUsersTable = document.getElementById('tbody-users');
    bodyUsersTable.appendChild(cloneUserRow);
}

function clearTableBody() {
    let bodyUsersTable = document.getElementById('tbody-users');
    for (let i = bodyUsersTable.children.length - 1; i > 0; i--) {
        bodyUsersTable.removeChild(bodyUsersTable.children[i]);
    }
}

function handleUserData(data) {
    let usersArr = data.success;
    clearTableBody();
    if(usersArr.length > 0){
        for (let i = 0; i < usersArr.length; i++) {
            appendRowtoTable(usersArr[i].tendangnhap, usersArr[i].tentaikhoan, usersArr[i].phongban, usersArr[i].id);
        }
        generateDeleteButtonsEvents('delete-btn');
    }
}

function setCurrentPhongban(id) {
    currentPhongban = id
}

let addUserButton = document.getElementById('add-user-button');
addUserButton.addEventListener('click', function(e) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '/addusersvaophongban',
        data: {
            phongban: currentPhongban,
            usersid: getSelectedUsers(),
			idDonvi
        },
        success: function(data) {
            if (!$.isEmptyObject(data.error)) {
                printErrorMsg(data.error, "print-error-msg-on-edit");
            } else {
                handleUserData(data);
                setNewValuesForSelectInput(data.usernotinphongban);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

function getSelectedUsers() {
    let userSelect = document.getElementById('list-users');
    let selected = [];
    for (let i = 0; i < userSelect.length; i++) {
        if (userSelect.options[i].selected) selected.push(userSelect.options[i].value);
    }
    return selected;
}

function setNewValuesForSelectInput(userInput) {
    let userSelect = document.getElementById('list-users');
    userSelect.options.length = 0;
    for (let i = 0; i < userInput.length; i++) {
        let option = document.createElement("option");
        option.value = userInput[i].id;
        option.text = userInput[i].tendangnhap;
        userSelect.add(option);
    }
}

function generateDeleteButtonsEvents(btnDeleteClassName) {
    var deleteButtons = document.getElementsByClassName(btnDeleteClassName);
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', function(e) {
            e.preventDefault();
            let idArr = this.id.split('-');
            let id = idArr[idArr.length - 1];
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/removeuserkhoiphongban',
                data: {
                    phongban: currentPhongban,
                    userid: id,
					idDonvi,
					"_method": 'DELETE'
                },
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error, "print-error-msg-on-edit");
                    } else {
                        console.log(data.success);
                        console.log(data.usernotinphongban)
                        handleUserData(data);
                        setNewValuesForSelectInput(data.usernotinphongban);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });

        })
    }
}
</script>
@endsection