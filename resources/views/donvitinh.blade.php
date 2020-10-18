@extends('master')
@section('title','Đơn Vị Tính')
@section('content')

<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/donvitinh" id="donvitinhForm" style="margin-bottom : 10px;">
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
                        <h4 class="widget-title">Danh Sách Các Đơn Vị Tính</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade" id="modelForCreateDonvitinh" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForCreateDonvitinhLabel">Tạo Mới Đơn Vị Tính
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createDonvitinhForm">
                                            <div class="form-group">
                                                <label for="create-input-maso">Mã số</label>
                                                <input type="text" name="maso" class="form-control"
                                                    id="create-input-maso" placeholder="Nhập mã số">
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-tendonvi">Tên đơn vị</label>
                                                <input type="text" name="tendonvi" class="form-control"
                                                    id="create-input-tendonvi" placeholder="Nhập tên đơn vị tính">
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-mota">Mô Tả</label>
                                                <input type="text" name="mota" class="form-control"
                                                    id="create-input-mota" placeholder="Nhập mô tả">
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="apdung" class="form-check-input"
                                                    id="create-input-apdung" value="1">
                                                <label class="form-check-label" for="create-input-mota">Áp dụng</label>
                                            </div>
                                        </form>
                                        <div class="alert alert-danger print-error-msg-on-create" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success mw-md" id="submit-button-for-create">Tạo
                                            mới</button>
                                        <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">
                                            Hủy bỏ
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modelForEditDonvitinh" tabindex="-1" role="dialog"
                            aria-labelledby="editModalDonvitinh" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForEditDonvitinhLabel">Chỉnh Sửa Đơn Vị Tính
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="editDonvitinhForm">
                                            <input type="hidden" name="id" id="EditInputId">
                                            <div class="form-group">
                                                <label for="edit-input-maso">Mã số</label>
                                                <input type="text" name="maso" class="form-control" id="edit-input-maso"
                                                    placeholder="Nhập tên mã số">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-input-tendonvi">Tên Đơn Vị</label>
                                                <input type="text" name="tendonvi" class="form-control"
                                                    id="edit-input-tendonvi" placeholder="Nhập tên đơn vị">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-input-mota">Mô Tả</label>
                                                <input type="text" name="mota" class="form-control" id="edit-input-mota"
                                                    placeholder="Nhập mô tả">
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
                                        <h5 class="modal-title" id="deleteModalLabel">Xóa Đơn Vị Tính</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa đơn vị tính này?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            id="button-confirm-delete">Xóa</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">Thứ Tự</th>
                                        <th style="width: 15%">Mã số</th>
                                        <th style="width: 15%">Tên đơn vị tính</th>
                                        <th style="width: 32%">Mô tả đơn vị tính</th>
                                        <th style="width: 8%">Áp Dụng</th>
                                        <th style="width: 20%">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-donvitinh">
                                    <form method="POST" action="/donvitinh" id="donvitinhForm"
                                        style="margin-bottom : 10px;">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="row" style="display:flex; justify-content: flex-start">
                                            <button type="submit" class="btn mw-md btn-primary m-xs">Lưu thay
                                                đổi</button>
                                            <div style="margin-left: auto">
                                                <button type="button" class="btn mw-md btn-success m-xs"
                                                    id="show-create-modal">
                                                    Tạo Mới</button>
                                            </div>
                                        </div>
                                        @if(count($donvitinhs) > 0)
                                        @foreach($donvitinhs as $donvitinh)
                                        <tr id="donvitinh{{ $donvitinh->id }}" class="row-donvitinh">
                                            <td scope="row">
                                                <button type="button" class="btn btn-xs btn-primary button-move-down">
                                                    <i class="zmdi zmdi-long-arrow-down zmdi-hc-2x"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-danger button-move-up">
                                                    <i class="zmdi zmdi-long-arrow-up zmdi-hc-2x"></i>
                                                </button>
                                            </td>
                                            <td style="vertical-align: middle">{{ $donvitinh->maso }}</td>
                                            <td style="vertical-align: middle">{{ $donvitinh->tendonvi }}</td>
                                            <td style="vertical-align: middle">{{ $donvitinh->mota }}</td>
                                            <td style="vertical-align: middle">
                                                <div class="checkbox checkbox-primary">
                                                    <input type="checkbox" value="{{ $donvitinh->id }}"
                                                        id="apdungCheckbox{{ $donvitinh->id }}" class="apdung-checkbox"
                                                        {{$donvitinh->apdung == 1 ? 'checked' : ''}} />
                                                    <label for="apdungCheckbox{{ $donvitinh->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                    style="margin: 2px" id="edit-btn-{{ $donvitinh -> id }}">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm button-delete"
                                                    style="margin: 2px" id="delete-btn-{{ $donvitinh -> id }}">
                                                    <i class="fa fa-close"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        <input type="hidden" name="apdung" id="apdungArray" />
                                        <input type="hidden" name="thutu" id="ordersArray" />
                                    </form>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
</section>

<script type="text/javascript" src="{{ URL::asset('js/SimpleModelService.js') }}"></script>
<script>
    $(document).ready(function(){
	$("#show-create-modal").click(function(){
		$("#modelForCreateDonvitinh").modal("show");
	});
});	
	
let modelServices = simpleModelService('/donvitinh');

modelServices.generateUpAndDownButtonsEvents("button-move-up", "button-move-down", "tbody-donvitinh", "row-donvitinh");

modelServices.setCheckedItems("apdung-checkbox");

modelServices.generateCheckboxesEvent("apdung-checkbox");

modelServices.generateDeleteButtonsEvents("button-delete", "button-confirm-delete", "deleteModal");

modelServices.generateEditButtonsEvent("edit-btn","confirm-edit-btn", "modelForEditDonvitinh" , "edit-input-maso", "edit-input-tendonvi", "edit-input-mota",
    "edit-input-apdung");
modelServices.generateSubmitButtonForCreateEvent("submit-button-for-create", "create-input-maso", "create-input-tendonvi", "create-input-mota",
    "create-input-apdung");
</script>
@endsection