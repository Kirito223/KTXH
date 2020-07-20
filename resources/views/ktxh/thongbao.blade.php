@extends('master')
@section('title','Thông Báo')
@section('content')
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/thongbao" id="thongbaoForm" style="margin-bottom : 10px;">
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
                        <h4 class="widget-title">Danh Sách Thông Báo</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade in" id="modelForCreateThongbao" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForCreateThongbao">Tạo Mới Thông Báo
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createThongbaoForm" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="create-input-tieude">Tiêu đề</label>
                                                <input type="text" name="tieude" class="form-control"
                                                    id="create-input-tieude" placeholder="Nhập tên tiêu đề">
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="create-input-ngaybatdau">Ngày bắt đầu</label>
                                                    <input type="text" name="ngaybatdau" class="form-control datepicker"
                                                        id="create-input-ngaybatdau">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="create-input-ngayketthuc">Ngày kết thúc</label>
                                                    <input type="text" name="ngayketthuc"
                                                        class="form-control datepicker" id="create-input-ngayketthuc">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-noidung">Nội dung</label>
                                                <textarea class="form-control" id="create-input-noidung" name="noidung"
                                                    rows="3"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-taptin">Tải tập tin</label>
                                                <input type="file" class="form-control-file" name="taptin"
                                                    id="create-input-taptin">
                                            </div>
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

                        <div class="modal fade in" id="modelForDetailsThongbao" tabindex="-1" role="dialog"
                            aria-labelledby="detailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForDetailsThongbaoLabel">Chi Tiết Thông Báo
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row no-gutter p-sm">
                                            <div>
                                                <h3 class="widget-title fz-lg text-primary m-b-sm" id="details-title">
                                                </h3>
                                                <small>Từ ngày: <small><small id="details-start-day"></small> -
                                                        <small>Đến ngày: </small><small id="details-end-day"></small>
                                                        <p class="m-b-lg" id="details-content" style="margin-top: 15px; padding:5px; border-style:dotted;border-width:1px">
                                                        </p>
                                            </div>
                                            <span><b>Tải tài liệu :</b></span><i class="fa fa-file-pdf-o"
                                                style="margin-left: 10px"></i> - <span><a href="javascript:void(0);"
                                                    id="details-download-doc" class="hidden-item">Tên tài
                                                    liệu</a></span><span id="details-non-donwnload-doc"
                                                class="text-danger">Không có tài liệu đính kèm</span>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">Đóng
                                            Lại</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal fade in" id="modelForEditThongbao" tabindex="-1" role="dialog"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForEditLoaisolieuLabel">Chỉnh Sửa Thông Báo
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="editLoaisolieuForm">
                                            <input type="hidden" name="id" id="edit-input-id">
                                            <div class="form-group">
                                                <label for="edit-input-tieude">Tiêu đề</label>
                                                <input type="text" name="tieude" class="form-control"
                                                    id="edit-input-tieude" placeholder="Nhập tên tiêu đề">
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="edit-input-ngaybatdau">Ngày bắt đầu</label>
                                                    <input type="text" name="ngaybatdau" class="form-control datepicker"
                                                        id="edit-input-ngaybatdau">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="edit-input-ngayketthuc">Ngày kết thúc</label>
                                                    <input type="text" name="ngayketthuc"
                                                        class="form-control datepicker" id="edit-input-ngayketthuc">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-input-noidung">Nội dung</label>
                                                <textarea class="form-control" id="edit-input-noidung" name="noidung"
                                                    rows="3"></textarea>
                                            </div>
                                            <div class="form-group row">
                                               <!-- <label for="create-input-taptin">Tải tập tin</label>
                                                <input type="file" class="form-control-file" name="taptin"
                                                    id="edit-input-taptin"> -->
												<div class="col-sm-2" style="padding-left: 0px; padding-right: 0px">
                                                <input type="file" name="uploadfile" id="edit-input-taptin" style="width: 85px;"/>
                                                </div>
                                                <label for="edit-input-taptin" class="col-sm-10" style="padding-left: 0px;" id="edit-label-taptin">No file chosen</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="kichhoat" class="form-check-input"
                                                    id="edit-input-kichhoat" value="1">
                                                <label class="form-check-label" for="edit-input-kichhoat">Kích
                                                    hoạt</label>
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
                        <div class="modal fade in" id="deleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Xóa Thông Báo</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa thông báo này?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            id="button-confirm-delete">Xóa</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade in" id="sendModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="deleteModalLabel">Gửi Thông Báo</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <fieldset class="form-group">
                                            <div class="row">
                                                <legend class="col-form-label col-sm-3 pt-0">Đơn vị nhận</legend>
                                                <div class="col-sm-9">
                                                    @if(count($donvihanhchinhcons) > 0)
                                                    @foreach($donvihanhchinhcons as $donvihanhchinhcon)
                                                    <div class="form-check">
                                                        <input class="form-check-input donvinhan-input"
                                                            type="checkbox" name="donvinhan"
                                                            value="{{ $donvihanhchinhcon-> id }}">
                                                        <label class="form-check-label">
                                                            {{ $donvihanhchinhcon->tendonvi }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                    @else 
                                                    <p>Không có đơn vị hành chính trực thuộc</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="alert alert-danger print-error-msg-on-send" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success"
                                            id="confirm-send-btn">Gửi thông báo</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">STT</th>
                                        <th style="width: 25%">Tiêu Đề Thông Báo</th>
                                        <th style="width: 15%">Ngày Bắt Đầu</th>
                                        <th style="width: 15%">Ngày Kết Thúc</th>
                                        <th class="hidden-item">Nội Dung</th>
                                        <th style="width: 10%">Kích Hoạt</th>
                                        <th class="hidden-item">Tập Tin</th>
                                        <th style="width: 30%">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-thongbao">
                                    <div class="row" style="display:flex; justify-content: flex-start">
                                        <button type="submit" class="btn mw-md btn-primary m-xs">Lưu thay
                                            đổi</button>
                                        <div class="hidden-item">
                                            <input type="hidden" name="apdung" id="apdungArray" />
                                        </div>
                                        <div style="margin-left: auto">
                                            <button type="button" class="btn mw-md btn-success m-xs"
                                                id="show-create-modal">
                                                Tạo Mới</button>
                                        </div>
                                    </div>
                                    @if(count($thongbaos) > 0)
                                    @foreach($thongbaos as $thongbao)
                                    <tr id="thongbao{{ $thongbao->id }}" class="row-thongbao">
                                        <td style="vertical-align: middle; text-align: center">{{ $thongbao->id }}</td>
                                        <td style="vertical-align: middle"><a class="details-btn"
                                                id="details-btn-{{ $thongbao->id }}"
                                                href="javascript:void(0);">{{ $thongbao->tieude }}</a></td>
                                        <td style="vertical-align: middle">{{ $thongbao->ngaybatdau }}</td>
                                        <td style="vertical-align: middle">{{ $thongbao->ngayketthuc }}</td>
                                        <td class="hidden-item" style="vertical-align: middle">{{ $thongbao->noidung }}
                                        </td>
                                        <td style="vertical-align: middle; text-align:center">
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" value="{{ $thongbao->id }}"
                                                    id="kichhoatoCheckbox{{ $thongbao->id }}" class="kichhoat-checkbox"
                                                    {{$thongbao->kichhoat == 1 ? 'checked' : ''}} />
                                                <label for="kichhoatCheckbox{{ $thongbao->id }}"></label>
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle" class="hidden-item">{{ $thongbao->taptin }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm send-btn" id="send-btn-{{ $thongbao->id }}" style="margin: 2px">
                                                @if(count($thongbao->taikhoans) > 0)
                                                <span>Đã gửi</span>
                                                @else
                                                <i class="fa fa-send"></i> Gửi
                                                @endif
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                style="margin: 2px" id="edit-btn-{{ $thongbao->id }}">
                                                <i class="fa fa-edit"></i> Sửa
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm button-delete"
                                                style="margin: 2px" id="delete-btn-{{ $thongbao->id }}">
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

<script type="text/javascript" src="{{ URL::asset('js/thongbao.js') }}"></script>

@endsection