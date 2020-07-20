@extends('master')
@section('title','Tài khoản')
@section('content')
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/taikhoan" id="taikhoanForm" style="margin-bottom : 10px;">
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
                        <h4 class="widget-title">Danh Sách Tài Khoản</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade in" id="modelForCreateTaikhoan" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForCreateTaikhoanLabel">Tạo Mới Tài Khoản
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createTaikhoanForm">
                                            <div class="form-group row">
                                                <label for="create-input-tendangnhap"
                                                    class="col-sm-3 col-form-label">Tên
                                                    đăng nhập</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="tendangnhap" class="form-control"
                                                        id="create-input-tendangnhap" placeholder="Nhập tên đăng nhập">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="create-input-email"
                                                    class="col-sm-3 col-form-label">Email</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="email" class="form-control"
                                                        id="create-input-email" placeholder="Nhập Email">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="create-input-matkhau" class="col-sm-3 col-form-label">Mật
                                                    khẩu</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="matkhau" class="form-control"
                                                        id="create-input-matkhau" placeholder="Nhập mật khẩu">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="create-input-tentaikhoan"
                                                    class="col-sm-3 col-form-label">Tên
                                                    tài khoản</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="tentaikhoan" class="form-control"
                                                        id="create-input-tentaikhoan" placeholder="Nhập tên tài khoản">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="create-input-ho" class="col-sm-3 col-form-label">Họ</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="ho" class="form-control"
                                                        id="create-input-ho" placeholder="Nhập họ">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="create-input-ten"
                                                    class="col-sm-3 col-form-label">Tên</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="ten" class="form-control"
                                                        id="create-input-ten" placeholder="Nhập tên">
                                                </div>
                                            </div>
                                            <fieldset class="form-group">
                                                <div class="row">
                                                    <legend class="col-form-label col-sm-3 pt-0">Nhóm quyền</legend>
                                                    <div class="col-sm-9">
                                                        @if(count($nhomquyens) > 0)
                                                        @foreach($nhomquyens as $nhomquyen)
                                                        <div class="form-check">
                                                            <input class="form-check-input create-input-nhomquyen"
                                                                type="checkbox" name="nhomquyen"
                                                                id="create-input-nhomquyen-{{ $nhomquyen->id }}"
                                                                value="{{ $nhomquyen-> id }}">
                                                            <label class="form-check-label"
                                                                for="create-input-nhomquyen-{{ $nhomquyen->id }}">
                                                                {{ $nhomquyen->tennhomquyen }}
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

                        <div class="modal fade in" id="modelForEditTaikhoan" tabindex="-1" role="dialog"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForEditTaikhoanLabel">Chỉnh Sửa Tài Khoản
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="edit-input-tendangnhap" class="col-sm-3 col-form-label">Tên đăng
                                                nhập</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="tendangnhap" class="form-control"
                                                    id="edit-input-tendangnhap" placeholder="Nhập tên đăng nhập">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="edit-input-email" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="email" class="form-control"
                                                    id="edit-input-email" placeholder="Nhập email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="edit-input-matkhau" class="col-sm-3 col-form-label">Mật
                                                khẩu</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="matkhau" class="form-control"
                                                    id="edit-input-matkhau" placeholder="Nhập mật khẩu">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="edit-input-tentaikhoan" class="col-sm-3 col-form-label">Tên tài
                                                khoản</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="tentaikhoan" class="form-control"
                                                    id="edit-input-tentaikhoan" placeholder="Nhập tên tài khoản">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="edit-input-ho" class="col-sm-3 col-form-label">Họ</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="ho" class="form-control" id="edit-input-ho"
                                                    placeholder="Nhập họ">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="edit-input-ten" class="col-sm-3 col-form-label">Tên</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="ten" class="form-control" id="edit-input-ten"
                                                    placeholder="Nhập tên">
                                            </div>
                                        </div>
                                        <fieldset class="form-group">
                                            <div class="row">
                                                <legend class="col-form-label col-sm-3 pt-0">Nhóm quyền</legend>
                                                <div class="col-sm-9">
                                                    @if(count($nhomquyens) > 0)
                                                    @foreach($nhomquyens as $nhomquyen)
                                                    <div class="form-check">
                                                        <input class="form-check-input edit-input-nhomquyen"
                                                            type="checkbox" name="nhomquyen"
                                                            id="edit-input-nhomquyen-{{ $nhomquyen->id }}"
                                                            value="{{ $nhomquyen-> id }}">
                                                        <label class="form-check-label"
                                                            for="edit-input-nhomquyen-{{ $nhomquyen->id }}">
                                                            {{ $nhomquyen->tennhomquyen }}
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
                                            <label class="form-check-label" for="edit-input-kichhoat">Kích hoạt</label>
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
                                        <h2 class="modal-title" id="deleteModalLabel">Xóa Tài Khoản</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa tài khoản này?
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
                            <table class="table" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Tên đăng nhập</th>
                                        <th style="width: 15%">Email</th>
                                        <th style="width: 15%">Tên tài khoản</th>
                                        <th style="width: 7%">Họ</th>
                                        <th style="width: 8%">Tên</th>
                                        <th class="hidden-item">Nhóm quyền Id</th>
                                        <th style="width: 10%">Nhóm quyền</th>
                                        <th style="width: 5%">Kích hoạt</th>
                                        <th style="width: 20%">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-taikhoan">
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
                                    @if(count($taikhoans) > 0)
                                    @foreach($taikhoans as $taikhoan)
                                    <tr id="taikhoan-{{ $taikhoan->id }}" class="row-taikhoan">
                                        <td style="vertical-align: middle">{{ $taikhoan->tendangnhap }}</td>
                                        <td style="vertical-align: middle">{{ $taikhoan->email }}</td>
                                        <td style="vertical-align: middle">{{ $taikhoan->tentaikhoan }}</td>
                                        <td style="vertical-align: middle">{{ $taikhoan->ho }}</td>
                                        <td style="vertical-align: middle">{{ $taikhoan->ten }}</td>
                                        <td style="vertical-align: middle" class="hidden-item">
                                            <ul>
                                                @if(count($taikhoan->nhomquyens) > 0)
                                                @foreach($taikhoan->nhomquyens as $nhomquyen)
                                                <li>{{ $nhomquyen->id }}</li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <ul>
                                                @if(count($taikhoan->nhomquyens) > 0)
                                                @foreach($taikhoan->nhomquyens as $nhomquyen)
                                                <li>- {{ $nhomquyen->tennhomquyen }} </li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </td>
                                        <td style="vertical-align: middle; text-align: center">
                                            <div class="checkbox checkbox-primary" style="vertical-align: middle">
                                                <input type="checkbox" value="{{ $taikhoan->id }}"
                                                    id="taikhoanCheckbox{{ $taikhoan->id }}" class="taikhoan-checkbox"
                                                    {{$taikhoan->kichhoat == 1 ? 'checked' : ''}} />
                                                <label for="taikhoanCheckbox{{ $taikhoan->id }}"></label>
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                style="margin: 2px" id="edit-btn-{{ $taikhoan -> id }}">
                                                <i class="fa fa-edit"></i> Sửa
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                style="margin: 2px" id="delete-btn-{{ $taikhoan -> id }}">
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
<script type="text/javascript" src="{{ URL::asset('js/taikhoan.js') }}"></script>
@endsection