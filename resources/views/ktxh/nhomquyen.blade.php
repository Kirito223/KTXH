@extends('master')
@section('title','Nhóm Quyền')
@section('content')
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/nhomquyen" style="margin-bottom : 10px;">
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
                        <h4 class="widget-title">Danh Sách Nhóm Quyền</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade in" id="modelForCreateNhomquyen" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForCreateNhomquyenLabel">Tạo Mới Nhóm Quyền
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createNhomquyenForm">
                                            <div class="form-group row">
                                                <label for="create-input-tennhomquyen" class="col-sm-3 col-form-label">
                                                    Tên nhóm quyền
                                                </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="tennhomquyen" class="form-control"
                                                        id="create-input-tennhomquyen"
                                                        placeholder="Nhập tên nhóm quyền">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="create-input-mota" class="col-sm-3 col-form-label">Mô
                                                    tả</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="mota" class="form-control"
                                                        id="create-input-mota" placeholder="Nhập mô tả">
                                                </div>
                                            </div>
                                            <fieldset class="form-group">
                                                <div class="row">
                                                    <legend class="col-form-label col-sm-3 pt-0">Quyền</legend>
                                                    <div class="col-sm-9">
                                                        @if(count($quyens) > 0)
                                                        @foreach($quyens as $quyen)
                                                        <div class="form-check">
                                                            <input class="form-check-input create-input-quyen"
                                                                type="checkbox" name="quyen"
                                                                id="create-input-quyen-{{ $quyen->id }}"
                                                                value="{{ $quyen-> id }}">
                                                            <label class="form-check-label"
                                                                for="create-input-quyen-{{ $quyen->id }}">
                                                                {{ $quyen->mota }}
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="form-check">
                                                <input type="checkbox" name="kichhoat" class="form-check-input"
                                                    id="create-input-kichhoat" value="1">
                                                <label class="form-check-label" for="create-input-kichhoat">Kích
                                                    hoạt</label>
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

                        <div class="modal fade in" id="modelForEditNhomquyen" tabindex="-1" role="dialog"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForEditNhomquyenLabel">Chỉnh Sửa Nhóm Quyền
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="edit-input-tennhomquyen" class="col-sm-3 col-form-label">
                                                Tên nhóm quyền
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="tennhomquyen" class="form-control"
                                                    id="edit-input-tennhomquyen" placeholder="Nhập tên nhóm quyền">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="edit-input-mota" class="col-sm-3 col-form-label">Mô tả</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="mota" class="form-control" id="edit-input-mota"
                                                    placeholder="Nhập mô tả">
                                            </div>
                                        </div>
                                        <fieldset class="form-group">
                                            <div class="row">
                                                <legend class="col-form-label col-sm-3 pt-0">Quyền</legend>
                                                <div class="col-sm-9">
                                                    @if(count($quyens) > 0)
                                                    @foreach($quyens as $quyen)
                                                    <div class="form-check">
                                                        <input class="form-check-input edit-input-quyen" type="checkbox"
                                                            name="quyen" id="edit-input-quyen-{{ $quyen->id }}"
                                                            value="{{ $quyen-> id }}">
                                                        <label class="form-check-label"
                                                            for="edit-input-quyen-{{ $quyen->id }}">
                                                            {{ $quyen->mota }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="form-check">
                                            <input type="checkbox" name="kichhoat" class="form-check-input"
                                                id="edit-input-kichhoat" value="1">
                                            <label class="form-check-label" for="edit-input-kichhoat">Kích
                                                hoạt</label>
                                        </div>
                                        <div class="alert alert-danger print-error-msg-on-edit" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success mw-md" id="confirm-edit-btn">Chỉnh
                                            sửa</button>
                                        <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">Hủy
                                            bỏ</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade in" id="deleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" id="deleteModalLabel">Xóa Nhóm Quyền</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa nhóm quyền này?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            id="button-confirm-delete">Xóa</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="kichhoat" id="kichhoatArray" />

                        <div class="table-responsive">
                            <table class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Tên nhóm quyền</th>
                                        <th style="width: 20%">Mô tả</th>
                                        <th class="hidden-item">Quyền Id</th>
                                        <th style="width: 30%">Quyền</th>
                                        <th style="width: 10%; text-align: center">Kích hoạt</th>
                                        <th style="width: 20%">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-nhomquyen">
                                    <div class="row" style="display:flex; justify-content: flex-start">
                                        <button type="submit" class="btn mw-md btn-primary m-xs">Lưu thay
                                            đổi</button>
                                        <div style="margin-left: auto">
                                            <button type="button" class="btn mw-md btn-success m-xs"
                                                id="show-create-modal">
                                                Tạo Mới
                                            </button>
                                        </div>
                                    </div>
                                    @if(count($nhomquyens) > 0)
                                    @foreach($nhomquyens as $nhomquyen)
                                    <tr id="nhomquyen-{{ $nhomquyen->id }}" class="row-nhomquyen">
                                        <td style="vertical-align: middle">{{ $nhomquyen->tennhomquyen }}</td>
                                        <td style="vertical-align: middle">{{ $nhomquyen->mota }}</td>
                                        <td class="hidden-item">
                                            <ul>
                                                @if(count($nhomquyen->quyens) > 0)
                                                @foreach($nhomquyen->quyens as $quyen)
                                                <li>{{ $quyen->id }}</li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <ul>
                                                @if(count($nhomquyen->quyens) > 0)
                                                @foreach($nhomquyen->quyens as $quyen)
                                                <li>- {{ $quyen->mota }} </li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </td>
                                        <td style="vertical-align: middle; text-align: center">
                                            <div class="checkbox checkbox-primary" style="vertical-align: middle">
                                                <input type="checkbox" value="{{ $nhomquyen->id }}"
                                                    id="nhomquyenCheckbox{{ $nhomquyen->id }}"
                                                    class="nhomquyen-checkbox"
                                                    {{$nhomquyen->kichhoat == 1 ? 'checked' : ''}} />
                                                <label for="nhomquyenCheckbox{{ $nhomquyen->id }}"></label>
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                style="margin: 2px" id="edit-btn-{{ $nhomquyen -> id }}">
                                                <i class="fa fa-edit"></i> Sửa
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                style="margin: 2px" id="delete-btn-{{ $nhomquyen -> id }}">
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
            </form>
        </div>
    </div>
</section>

<script type="text/javascript" src="{{ URL::asset('js/nhomquyen.js') }}"></script>
<script>

</script>
@endsection