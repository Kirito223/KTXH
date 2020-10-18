@extends('master')
@section('title','Kỳ Báo Cáo')
@section('content')

<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/kybaocao" id="kybaocaoForm" style="margin-bottom : 10px;">
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
                        <h4 class="widget-title">Danh Sách Các Kỳ Báo Cáo</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade" id="modelForCreateKybaocao" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForCreateKybaocaoLabel">Tạo Mới Kỳ Báo Cáo
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createKybaocaoForm">
                                            <div class="form-group">
                                                <label for="create-input-tenky">Tên Kỳ Báo Cáo</label>
                                                <input type="text" name="tenky" class="form-control"
                                                    id="create-input-tenky" placeholder="Nhập tên kỳ báo cáo">
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-mota">Mô Tả </label>
                                                <input type="text" name="mota" class="form-control"
                                                    id="create-input-mota" placeholder="Nhập mô tả kỳ báo cáo">
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

                        <div class="modal fade" id="modelForEditKybaocao" tabindex="-1" role="dialog"
                            aria-labelledby="editModalKybaocao" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForEditKybaocaoLabel">Chỉnh Sửa Kỳ Báo Cáo
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="editKybaocaoForm">
                                            <input type="hidden" name="id" id="EditInputId">
                                            <div class="form-group">
                                                <label for="edit-input-tenky">Tên Kỳ Báo Cáo</label>
                                                <input type="text" name="tenky" class="form-control"
                                                    id="edit-input-tenky" placeholder="Nhập tên kỳ báo cáo">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-input-mota">Mô Tả</label>
                                                <input type="text" name="mota" class="form-control" id="edit-input-mota"
                                                    placeholder="Nhập mô tả">
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
                                        <h5 class="modal-title" id="deleteModalLabel">Xóa Kỳ Báo Cáo</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn kỳ báo cáo này?
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
                                        <th style="width: 30%">Tên Kỳ Báo Cáo</th>
                                        <th style="width: 40%">Mô Tả</th>
                                        <th style="width: 20%">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-kybaocao">
                                    <form method="POST" action="/kybaocao" id="kybaocaoForm"
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
                                        @if(count($kybaocaos) > 0)
                                        @foreach($kybaocaos as $kybaocao)
                                        <tr id="kybaocao{{ $kybaocao->id }}" class="row-kybaocao">
                                            <td scope="row" class="">
                                                <button type="button" class="btn btn-xs btn-primary button-move-down">
                                                    <i class="zmdi zmdi-long-arrow-down zmdi-hc-2x"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-danger button-move-up">
                                                    <i class="zmdi zmdi-long-arrow-up zmdi-hc-2x"></i>
                                                </button>
                                            </td>
                                            <td style="vertical-align: middle">{{ $kybaocao->tenky }}</td>
                                            <td style="vertical-align: middle">{{ $kybaocao->mota }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                    style="margin: 2px" id="edit-btn-{{ $kybaocao->id }}">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm button-delete"
                                                    style="margin: 2px" id="delete-btn-{{ $kybaocao -> id }}">
                                                    <i class="fa fa-close"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        <!-- <input type="hidden" name="apdung" id="apdungArray" /> -->
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
		$("#modelForCreateKybaocao").modal("show");
	});
});	

let modelServices = simpleModelService('/kybaocao');

modelServices.generateUpAndDownButtonsEvents("button-move-up", "button-move-down", "tbody-kybaocao", "row-kybaocao");

modelServices.generateDeleteButtonsEvents("button-delete", "button-confirm-delete", "deleteModal");

modelServices.generateEditButtonsEvent("edit-btn","confirm-edit-btn", "modelForEditKybaocao" , "edit-input-tenky", "edit-input-mota");
modelServices.generateSubmitButtonForCreateEvent("submit-button-for-create", "create-input-tenky", "create-input-mota");
</script>


@endsection