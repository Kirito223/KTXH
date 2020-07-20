@extends('master')
@section('title','Kế hoạch ktxh')
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
                        <h4 class="widget-title">Danh sách kế hoạch phát triển KTXH</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade in" id="modelForCreateKehoach" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
									
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForCreateKehoachLabel">Tạo mới kế hoạch
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createKehoachForm">
                                            <div class="form-group">
                                                <label for="create-input-tenkehoach">Tên kế hoạch</label>
                                                <input type="text" name="tenkehoach" class="form-control"
                                                    id="create-input-tenkehoach" placeholder="Nhập tên kế hoạch">
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-namthuchien">Năm thực hiện</label>
                                                <input type="text" name="namthuchien" class="form-control"
                                                    id="create-input-namthuchien" placeholder="Nhập năm thực hiện">
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
                        <div class="modal fade in" id="modelForEditKehoach" tabindex="-1" role="dialog"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForEditKehoach">Chỉnh sửa kế hoạch
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="editKehoachForm">
                                            <input type="hidden" name="id" id="EditInputId">
                                            <div class="form-group">
                                                <label for="edit-input-tenkehoach">Tên kế hoạch</label>
                                                <input type="text" name="tenkehoach" class="form-control"
                                                    id="edit-input-tenkehoach" placeholder="Nhập tên kế hoạch">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-input-namthuchien">Năm thực hiện</label>
                                                <input type="text" name="namthuchien" class="form-control" id="edit-input-namthuchien"
                                                    placeholder="Nhập năm thực hiện">
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

                        <div class="modal fade in" id="deleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Xóa kế hoạch</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa kế hoạch này?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            id="button-confirm-delete">Xóa</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table no-footer"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 60%">Tên kế hoạch</th>
                                        <th style="width: 20%">Năm thực hiện</th>
                                        <th style="width: 20%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <form method="POST" action="/loaisolieu" id="loaisolieuForm"
                                        style="margin-bottom : 10px;">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="row" style="display:flex; justify-content: flex-start">
                                            <div style="margin-left: auto">
                                                <button type="button" class="btn mw-md btn-success m-xs" id="show-create-modal">
                                                    Tạo Mới</button>
                                            </div>
                                        </div>
                                        @if(count($kehoachktxhxas) > 0)
                                        @foreach($kehoachktxhxas as $kehoachktxhxa)
                                        <tr id="kehoachktxhxa{{ $kehoachktxhxa->id }}" class="row-kehoachktxhxa">
                                            <!-- <td scope="row">
                                                <button type="button" class="btn btn-xs btn-primary button-move-down">
                                                    <i class="zmdi zmdi-long-arrow-down zmdi-hc-2x"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-danger button-move-up">
                                                    <i class="zmdi zmdi-long-arrow-up zmdi-hc-2x"></i>
                                                </button>
                                            </td> -->
                                            <td style="vertical-align: middle"><a href="/kehoachktxhxa/{{ $kehoachktxhxa->id }}/details">{{ $kehoachktxhxa->tenkehoach }}</a></td>
                                            <td style="vertical-align: middle">{{ $kehoachktxhxa->namthuchien }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                    style="margin: 2px" id="edit-btn-{{ $kehoachktxhxa->id }}">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    style="margin: 2px" id="delete-btn-{{ $kehoachktxhxa->id }}">
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

<script type="text/javascript" src="{{ URL::asset('js/kehoachktxhxa.js') }}"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#show-create-modal").click(function(e) {
        e.preventDefault();
        $("#modelForCreateKehoach").modal('show');
    });
	
});
let modelServices = simpleModelService('/kehoachktxhxa');

modelServices.generateEditButtonsEvent('edit-btn', 'confirm-edit-btn', 'modelForEditKehoach', 'edit-input-tenkehoach', 'edit-input-namthuchien');
modelServices.generateSubmitButtonForCreateEvent('submit-button-for-create', 'create-input-tenkehoach', 'create-input-namthuchien');
modelServices.generateDeleteButtonsEvents('delete-btn', 'button-confirm-delete', 'deleteModal');


</script>
@endsection