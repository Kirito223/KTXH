@extends('master')
@section('title','Quản lý tài khoản')
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
                        <h4 class="widget-title">Thông Tin Tài Khoản</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade in" id="resetPasswordModal" tabindex="-1" role="dialog"
                            aria-labelledby="resetPasswordLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="resetPasswordLabel">Thay đổi mật khẩu</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" class="hidden-item" name="id" value="{{ $taikhoan->id }}" id="reset-input-id" >
                                        <div class="form-group row">
                                            <label for="reset-input-password" class="col-sm-3 col-form-label">Mật khẩu mới</label>
                                            <div class="col-sm-9">
                                                <input type="password" name="matkhau" class="form-control"
                                                    id="reset-input-password" placeholder="Nhập mật khẩu mới">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="confirm-input-password" class="col-sm-3 col-form-label">Xác nhận mật khẩu</label>
                                            <div class="col-sm-9">
                                                <input type="password" name="matkhau" class="form-control"
                                                    id="confirm-input-password" placeholder="Xác nhận mật khẩu">
                                            </div>
                                        </div>
                                        <div class="alert alert-danger print-error-msg-on-reset-password" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" id="button-confirm-reset">Thay
                                            đổi</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade in" id="changeInfoModal" tabindex="-1" role="dialog"
                            aria-labelledby="changeInfoLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="changeInfoLabel">Thay đổi thông tin</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" class="hidden-item" name="id" value="{{ $taikhoan->id }}" id="change-input-id" >
                                        <div class="form-group row">
                                            <label for="change-input-tendangnhap" class="col-sm-3 col-form-label">Tên đăng nhập</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="tendangnhap" class="form-control"
                                                    id="change-input-tendangnhap" placeholder="{{ $taikhoan-> tendangnhap }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="change-input-tentaikhoan" class="col-sm-3 col-form-label">Tên tài khoản</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="tentaikhoan" class="form-control"
                                                    id="change-input-tentaikhoan" value="{{ $taikhoan-> tentaikhoan }}" placeholder="Nhập tên tài khoản">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="change-input-email" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="email" class="form-control"
                                                    id="change-input-email" value="{{ $taikhoan-> email }}" placeholder="Nhập email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="change-input-ho" class="col-sm-3 col-form-label">Họ</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="ho" class="form-control"
                                                    id="change-input-ho" value="{{ $taikhoan-> ho }}" placeholder="Nhập họ">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="change-input-ten" class="col-sm-3 col-form-label">Tên</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="ten" class="form-control"
                                                    id="change-input-ten" value="{{ $taikhoan-> ten }}" placeholder="Nhập tên">
                                            </div>
                                        </div>
                                        <div class="alert alert-danger print-error-msg-on-change-info" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success" id="button-confirm-changeinfo">Thay
                                            đổi</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display:flex; justify-content: flex-start">
                            <div style="margin-left: auto">
                                <button type="button" class="btn mw-md btn-primary m-xs" id="show-reset-modal">Đổi mật khẩu</button>
                                <button type="button" class="btn mw-md btn-success m-xs" id="show-change-modal">
                                    Đổi thông tin</button>
                            </div>
                        </div>
                        <div class="row m-h-lg">
                            <div class="col-sm-2">
                                <b>Tên đăng nhập:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $taikhoan->tendangnhap }}
                            </div>
                        </div>
                        <div class="row m-h-lg">
                            <div class="col-sm-2">
                                <b>Tên tài khoản:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $taikhoan->tentaikhoan }}
                            </div>
                        </div>
                        <div class="row m-h-lg">
                            <div class="col-sm-2">
                                <b>Email:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $taikhoan->email }}
                            </div>
                        </div>
                        <div class="row m-h-lg">
                            <div class="col-sm-2">
                                <b>Họ</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $taikhoan->ho }}
                            </div>
                        </div>
                        <div class="row m-h-lg">
                            <div class="col-sm-2">
                                <b>Tên:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $taikhoan->ten }}
                            </div>
                        </div>
                        <div class="row m-h-lg">
                            <div class="col-sm-2">
                                <b>Phòng ban:</b>
                            </div>
                            @if($taikhoan->tbl_phongban != null)
                            <div class="col-sm-10">
                                {{ $taikhoan->tbl_phongban->tenphongban }}
                            </div>
                            @else
                            <div class="col-sm-10">
                                Chưa có
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
        </div>
</section>
<script type="text/javascript" src="{{ URL::asset('js/quanlytaikhoan.js') }}"></script>



@endsection