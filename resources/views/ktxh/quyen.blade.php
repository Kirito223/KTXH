@extends('master')
@section('title','Quyền')
@section('content')


<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            @csrf
            {{ method_field('PUT') }}
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
                    <h4 class="widget-title">Danh Sách Quyền</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <div class="modal fade in" id="modelForCreateQuyen" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header row" style="padding: 5px">
                                    <h2 class="modal-title" id="modelForCreateNhomquyenLabel">Tạo Mới Quyền
                                    </h2>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @csrf
                                <div class="modal-body">
                                    <form id="createNhomquyenForm">
                                        <div class="form-group row">
                                            <label for="create-input-tenquyen" class="col-sm-3 col-form-label">
                                                Tên quyền
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="tenquyen" class="form-control create-input"
                                                    id="create-input-tenquyen" placeholder="Nhập tên quyền">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="create-input-mota" class="col-sm-3 col-form-label">Mô tả</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="mota" class="form-control create-input"
                                                    id="create-input-mota" placeholder="Nhập mô tả">
                                            </div>
                                        </div>
                                        <fieldset class="form-group">
                                            <div class="row">
                                                <legend class="col-form-label col-sm-3 pt-0">Route</legend>
                                            </div>
                                            <div class="row"
                                                style="max-height: 180px; overflow-y:auto; border: 1px solid black; padding: 5px">
                                                <div class="col-sm-9 col-offset-3">
                                                    @if(count($routes) > 0)
                                                    @foreach($routes as $route)
                                                    <div class="form-check">
                                                        <input class="form-check-input create-input" type="checkbox"
                                                            name="route" id="create-input-route-{{ $route->id }}"
                                                            value="{{ $route-> id }}">
                                                        <label class="form-check-label"
                                                            for="create-input-route-{{ $route->id }}">
                                                            {{ $route->route }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </fieldset>
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
                    <div class="modal fade in" id="modelForEditQuyen" tabindex="-1" role="dialog"
                        aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header row" style="padding: 5px">
                                    <h2 class="modal-title" id="modelForEditQuyenLabel">Chỉnh Sửa Quyền
                                    </h2>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="edit-input-tenquyen" class="col-sm-3 col-form-label">
                                            Tên quyền
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="tenquyen" class="form-control edit-input"
                                                id="edit-input-tenquyen" placeholder="Nhập tên quyền">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="edit-input-mota" class="col-sm-3 col-form-label">Mô tả</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="mota" class="form-control edit-input"
                                                id="edit-input-mota" placeholder="Nhập mô tả">
                                        </div>
                                    </div>
                                    <fieldset class="form-group">
                                        <div class="row">
                                            <legend class="col-form-label col-sm-3 pt-0">Route</legend>
                                        </div>
                                        <div class="row"
                                            style="max-height: 180px; overflow-y:auto; border: 1px solid black; padding: 5px">
                                            <div class="col-sm-9 col-offset-3">
                                                @if(count($routes) > 0)
                                                @foreach($routes as $route)
                                                <div class="form-check">
                                                    <input class="form-check-input edit-input" type="checkbox"
                                                        name="route" id="edit-input-route-{{ $route->id }}"
                                                        value="{{ $route->id }}">
                                                    <label class="form-check-label"
                                                        for="edit-input-route-{{ $route->id }}">
                                                        {{ $route->route }}
                                                    </label>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="alert alert-danger print-error-msg-on-edit" style="display:none">
                                        <ul></ul>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary mw-md"
                                        id="submit-button-for-edit">Chỉnh
                                        sửa</button>
                                    <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">Hủy
                                        bỏ</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade in" id="deleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" id="deleteModalLabel">Xóa Quyền</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa quyền này?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            id="submit-button-for-delete">Xóa</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="table-responsive">
                        <table class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="width: 20%">Tên quyền</th>
                                    <th style="width: 30%">Mô tả</th>
                                    <th class="hidden-item">Route Id</th>
                                    <th style="width: 30%">Route</th>
                                    <th style="width: 20%">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-nhomquyen">
                                <div class="row" style="display:flex; justify-content: flex-start">
									<button type="button" class="btn btn-primary" id="update-route-btn">Cập Nhật Route</button>
                                    <div style="margin-left: auto">
                                        <button type="button" class="btn mw-md btn-success m-xs" id="show-create-modal">
                                            Tạo Mới
                                        </button>
                                    </div>
                                </div>
                                @if(count($quyens) > 0)
                                @foreach($quyens as $quyen)
                                <tr id="quyen-row-{{ $quyen->id }}" class="quyen-row">
                                    <td style="vertical-align: middle">{{ $quyen->tenquyen }}</td>
                                    <td style="vertical-align: middle">{{ $quyen->mota }}</td>
                                    <td class="hidden-item route-id">
                                        <ul>
                                            @if(count($quyen->routes) > 0)
                                            @foreach($quyen->routes as $route)
                                            <li>{{ $route->id }}</li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </td>
                                    <td style="vertical-align: middle">
                                        <ul>
                                            @if(count($quyen->routes) > 0)
                                            @foreach($quyen->routes as $route)
                                            <li>- {{ $route->route }} </li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </td>
                                    <td style="vertical-align: middle">
                                        <button type="button" class="btn btn-primary btn-sm edit-button"
                                            style="margin: 2px" id="edit-btn-{{ $quyen->id }}">
                                            <i class="fa fa-edit"></i> Sửa
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-button"
                                            style="margin: 2px" id="delete-btn-{{ $quyen -> id }}">
                                            <i class="fa fa-close"></i> Xóa
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
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

    $('#show-create-modal').click(function() {
        $('#modelForCreateQuyen').modal('show');
    });
});

var quyenItemRows = [...document.getElementsByClassName('quyen-row')];

function generateSubmitButtonForCreateEvent(submitButtonForCreateId, inputsClassName) {
    var elementsArr = [...document.getElementsByClassName(inputsClassName)];
    let submitButtonForCreate = document.getElementById(submitButtonForCreateId);
    submitButtonForCreate.addEventListener('click', function(e) {
        e.preventDefault();
        let data = {};
        for (let i = 0; i < elementsArr.length; i++) {
            let inputType = elementsArr[i].getAttribute('type');
            let inputName = elementsArr[i].getAttribute('name');
            switch (inputName) {
                case 'route':
                    if (elementsArr[i].checked) {
                        data[inputName] = inputName in data ? [...data[inputName], elementsArr[i].value] : [
                            elementsArr[i].value
                        ];
                    }
                    break;
                default:
                    data[inputName] = elementsArr[i].value;
            }
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/quyen',
            data,
            success: function(data) {
                if (!$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error, "print-error-msg-on-create");
                } else {
                    console.log(data.success);
                    window.location.href = '/quyen';
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
}

function printErrorMsg(msg, printErrorMessageClassName) {

    $("." + printErrorMessageClassName).find("ul").html('');
    $("." + printErrorMessageClassName).css('display', 'block');
    $.each(msg, function(key, value) {
        $("." + printErrorMessageClassName).find("ul").append('<li>' + value + '</li>');
    });
}

function generateEditButtonsEvent(editBtnClassName, confirmEditButtonId, modalId, inputsClassName) {
    var elementsArr = [...document.getElementsByClassName(inputsClassName)];
    let editButtons = document.getElementsByClassName(editBtnClassName);
    let id;
    for (let i = 0; i < editButtons.length; i++) {
        editButtons[i].addEventListener('click', function(e) {
            e.preventDefault();
            $('#' + modalId).modal('show');
            let idArr = this.id.split('-');
            id = idArr[idArr.length - 1];
            let parentRow = this.closest('tr');
            let arrIdOfRoute = [...parentRow.querySelector('.route-id').querySelectorAll('li')].map(function(
                item) {
                return item.innerText;
            });
            console.log(arrIdOfRoute);
            let ignoreVal = 0;
            for (let i = 0; i < elementsArr.length; i++) {
                let inputType = elementsArr[i].getAttribute('type');
                let inputName = elementsArr[i].getAttribute('name');
                switch (inputName) {
                    case 'route':
                        elementsArr[i].checked = arrIdOfRoute.includes(elementsArr[i].value);
                        break;
                    default:
                        elementsArr[i].value = parentRow.children[i].innerText;
                        break;
                }
            }
        })
    }
    let confirmEditButton = document.getElementById(confirmEditButtonId);
    confirmEditButton.addEventListener("click", function(e) {
        e.preventDefault();
        let data = {};
        for (let i = 0; i < elementsArr.length; i++) {
            let inputType = elementsArr[i].getAttribute('type');
            let inputName = elementsArr[i].getAttribute('name');
            switch (inputName) {
                case 'route':
                    if (elementsArr[i].checked) {
                        data[inputName] = inputName in data ? [...data[inputName], elementsArr[i].value] : [
                            elementsArr[i].value
                        ];
                    }
                    break;
                default:
                    data[inputName] = elementsArr[i].value;
            }
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/quyen/' + id,
            data: {
				_method: 'PUT',
				...data
			},
            success: function(data) {
                if (!$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error, "print-error-msg-on-edit");
                } else {
                    console.log(data.success);
                    window.location.href = '/quyen';
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
}

function generateDeleteButtonsEvents(btnDeleteClassName, btnConfirmDeleteId, modalId) {
    var deleteButtons = [...document.getElementsByClassName(btnDeleteClassName)];
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', function(e) {
            e.preventDefault();
            let idArr = this.id.split('-');
            let id = idArr[idArr.length - 1];
            $('#' + modalId).modal('show');
            var confirmDeleteButton = document.getElementById(btnConfirmDeleteId);
            confirmDeleteButton.addEventListener('click', function sendDeleteInfoAjax(e) {
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '/quyen/' + id,
					data: {
						_method: 'DELETE'
					},
                    success: function(data) {
                        if (!$.isEmptyObject(data.error)) {
                            alert(data.error);
                        } else {
                            window.location.href = '/quyen';
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr
                            .responseText);
                    }
                });
                confirmDeleteButton.removeEventListener("click", sendDeleteInfoAjax);
            })
        })
    }
}
	
document.getElementById('update-route-btn').addEventListener('click', function(e) {
    e.preventDefault();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: "/updateroute",
        success: function(data) {
            if (!$.isEmptyObject(data.error)) {
                printErrorMsg(data.error, "print-error-msg-on-edit");
            } else {
                console.log(data.success);
                window.location.href = '/quyen';
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
})


generateSubmitButtonForCreateEvent("submit-button-for-create", "create-input");
generateEditButtonsEvent("edit-button", "submit-button-for-edit", "modelForEditQuyen", "edit-input");
generateDeleteButtonsEvents("delete-button", "submit-button-for-delete", "deleteModal");
</script>
@endsection