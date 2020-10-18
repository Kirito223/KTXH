@extends('master')
@section('title','Cập nhật phòng ban')
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
                        <h4 class="widget-title">Cập Nhật Danh Sách Phòng Ban</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade" id="model-for-create-phongban" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForCreatePhongbanLabel">Tạo Mới Phòng Ban
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createPhongBanForm">
                                            <div class="form-group">
                                                <label for="create-input-tenphongban">Tên Phòng Ban</label>
                                                <input type="text" name="tenphongban" class="form-control"
                                                    id="create-input-tenphongban" placeholder="Nhập tên Phòng Ban">
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-mota">Mô Tả</label>
                                                <input type="text" name="mota" class="form-control"
                                                    id="create-input-mota" placeholder="Nhập mô tả">
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-madonvi">Thuộc Đơn Vị</label>
                                                <input disabled type="text" value="{{ $donvihanhchinh->tendonvi }}"
                                                    class="form-control" placeholder="Nhập đơn vị">
                                            </div>
                                            <input id="create-input-madonvi" type="hidden" name="madonvi"
                                                value="{{ $donvihanhchinh->id }}" />
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

                        <div class="modal fade" id="model-for-edit-phongban" tabindex="-1" role="dialog"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForEditLoaisolieuLabel">Chỉnh Sửa Phòng Ban
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="editLoaisolieuForm">
                                            <div class="form-group">
                                                <label for="edit-input-tenphongban">Tên Phòng Ban</label>
                                                <input type="text" name="tenphongban" class="form-control"
                                                    id="edit-input-tenphongban" placeholder="Nhập tên Phòng Ban">
                                            </div>
                                            <input type="hidden" id="edit-input-2">
                                            <div class="form-group">
                                                <label for="edit-input-mota">Mô Tả</label>
                                                <input type="text" name="mota" class="form-control" id="edit-input-mota"
                                                    placeholder="Nhập mô tả">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-input-madonvi">Thuộc Đơn Vị</label>
                                                <input disabled type="text" value="{{ $donvihanhchinh->tendonvi }}"
                                                    class="form-control" placeholder="Nhập đơn vị">
                                            </div>
                                            <input id="edit-input-madonvi" type="hidden" name="madonvi"
                                                value="{{ $donvihanhchinh->id }}" />
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
                                        <h5 class="modal-title" id="deleteModalLabel">Xóa Phòng Ban</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa phòng ban này?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            id="confirm-delete-btn">Xóa</button>
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
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->mota }}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">Tên Phòng Ban</th>
                                        <th style="width: 30%">Thuộc đơn vị</th>
                                        <th style="width: 20%">Mô Tả</th>
                                        <th style="width: 20%">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-phongban">
                                    <form method="POST" action="/loaisolieu" id="loaisolieuForm"
                                        style="margin-bottom : 10px;">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="row" style="display:flex; justify-content: flex-start">
                                            <a href="/donvihanhchinh" class="btn mw-md btn-primary m-xs"
                                                style="display:flex; padding: 3px 16px"><i
                                                    class="zmdi zmdi-arrow-left zmdi-hc-2x"></i>
                                                <div style="padding: 5px; margin-left: 5px">Quay Lại</div>
                                            </a>
                                            <div style="margin-left: auto">
                                                <button type="button" class="btn mw-md btn-success m-xs"
                                                    id="show-create-modal">
                                                    Tạo Mới</button>
                                            </div>
                                        </div>
                                        @if(count($phongbans) > 0)
                                        @foreach($phongbans as $phongban)
                                        <tr id="phongban{{ $phongban->id }}" class="row-phongban">
                                            <td style="vertical-align: middle">{{ $phongban->tenphongban }}</td>
                                            <td style="vertical-align: middle">{{ $phongban->donvihanhchinh->tendonvi }}
                                            </td>
                                            <td style="vertical-align: middle">{{ $phongban->mota }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                    style="margin: 2px" id="edit-btn-{{ $phongban->id }}">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    style="margin: 2px" id="delete-btn-{{ $phongban->id }}">
                                                    <i class="fa fa-close"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </form>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
</section>


<script type="text/javascript" src="{{ URL::asset('js/SimpleModelServiceWithRedirect.js') }}"></script>
<script>
    let modelServices = simpleModelServiceWithRedirect('/phongban', "donvihanhchinh/" + document.getElementById('create-input-madonvi')
    .value + "/editphongban");
$("#show-create-modal").click(function(){
	$("#model-for-create-phongban").modal("show");
})

modelServices.generateSubmitButtonForCreateEvent("submit-button-for-create", "model-for-create-phongban" ,"create-input-tenphongban", "create-input-mota",
    "create-input-madonvi");
modelServices.generateEditButtonsEvent("edit-btn", "confirm-edit-btn", "model-for-edit-phongban" ,"edit-input-tenphongban", "edit-input-2", "edit-input-mota", "edit-input-madonvi");
modelServices.generateDeleteButtonsEvents("delete-btn", "confirm-delete-btn", "deleteModal");
</script>


@endsection