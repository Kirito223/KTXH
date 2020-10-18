@extends('master')
@section('title','Chi tiết kế hoạch')
@section('content')
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
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
                    <h4 class="widget-title">Chi Tiết Kế Hoạch Phát Triển KT-XH Xã</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <input class="hidden-item" id="id-kehoachxhktxa-input" value="{{ $kehoachktxhxa->id }}" readonly>
                    <div class="row m-h-xs">
                        <div class="col-sm-2">
                            <b>Tên kế hoạch</b>
                        </div>
                        <div class="col-sm-10">
                            {{ $kehoachktxhxa->tenkehoach }}
                        </div>
                    </div>
                    <div class="row m-h-xs">
                        <div class="col-sm-2">
                            <b>Năm thực hiện:</b>
                        </div>
                        <div class="col-sm-6">
                            {{ $kehoachktxhxa->namthuchien }}
                        </div>
                        <div class="col-sm-2">
                            <a href="/bieumaukhktxh/{{ $kehoachktxhxa->id }}" type="button" class="btn btn-primary"
                                target="_blank">Danh sách biểu mẫu</a>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-success" id="save-changes-btn">Lưu thay đổi</button>
                        </div>
                    </div>
                    <div class="row m-h-sm">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item active">
                                <a class="nav-link tab-button" href="#" id="ii1-tab" role="tab" data-toggle="tab"
                                    aria-controls="home" aria-selected="true">Biểu mẫu II.1</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link tab-button" href="#" id="ii2-tab" role="tab" aria-controls="profile"
                                    data-toggle="tab" aria-selected="false">Biểu mẫu II.2</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link tab-button" href="#" id="ii4b-tab" role="tab" aria-controls="contact"
                                    data-toggle="tab" aria-selected="false">Biểu mẫu II.4b</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade in show active" id="ii1" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="card card-body" style="margin-top:20px">
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-sm-6 row">
                                                <label for="ii1-sobieumau-input" class="col-sm-3 col-form-label">Số
                                                    văn bản</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="sobieumau"
                                                        class="form-control ii1-sobieumau" id="ii1-sobieumau-input"
                                                        value="{{ $maubieuii1->sobieumau}}"
                                                        placeholder="Nhập số văn bản">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6 row ">
                                                <label for="ii1-ngaybanhanh-input" class="col-sm-3 col-form-label">Ngày
                                                    ban
                                                    hành</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="ngaybanhanh"
                                                        class="form-control ii1-ngaybanhanh datepicker"
                                                        id="ii1-ngaybanhanh-input"
                                                        value="{{ $maubieuii1->ngaybanhanh }}"
                                                        placeholder="Nhập ngày ban hành">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6 row ">
                                                <label for="ii1-vanbanchidao-input" class="col-sm-3 col-form-label">Văn
                                                    bản
                                                    chỉ đạo</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="vanbanchidao"
                                                        class="form-control ii1-vanbanchidao"
                                                        id="ii1-vanbanchidao-input"
                                                        value="{{ $maubieuii1->vanbanchidao }}"
                                                        placeholder="Nhập văn bản chỉ đạo">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <h4 class="title-color">Danh sách thôn</h4>
                                            </div>
                                            <div class="col-sm-3">
                                                <div style="margin-left: auto">
                                                    <button type="button" class="btn mw-md btn-success m-xs"
                                                        id="add-thon-btn">
                                                        <i class="fa fa-plus" aria-hidden="true"
                                                            style="margin-right:5px"></i>Thêm Thôn
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <h5 class="title-color">Tên thôn</h5>
                                            </div>
                                        </div>
                                        <div class="row thon-item-row hidden-item" id="thon-item-row-pattern">
                                            <input type="text" name="id"
                                                class="form-control form-control-sm hidden-item">
                                            <div class="form-group col-sm-6">
                                                <input type="text" name="tenthon"
                                                    class="form-control form-control-sm thon-item-input">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    style="margin: 2px">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="thon-list-row">
                                            @if(count($maubieuii1->thons) > 0)
                                            @foreach($maubieuii1->thons as $thon)
                                            <div class="row thon-item-row" id="thon-item-row-{{ $thon->id }}">
                                                <input type="text" name="id"
                                                    class="form-control form-control-sm hidden-item ii1-thon-{{ $thon->id }}"
                                                    value="{{ $thon->id }}">
                                                <div class="form-group col-sm-6">
                                                    <input type="text" name="tenthon"
                                                        class="form-control form-control-sm thon-item-input ii1-thon-{{ $thon->id }}"
                                                        id="ii1-tenthon-input-{{ $thon->id }}"
                                                        value="{{ $thon->tenthon }}">
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                        style="margin: 2px">
                                                        <i class="fa fa-close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <h4 class="title-color">Danh sách thành viên</h4>
                                            </div>
                                            <div class="col-sm-2">
                                                <div style="margin-left: auto">
                                                    <button type="button" class="btn mw-md btn-success m-xs"
                                                        id="add-thanhvien-btn">
                                                        <i class="fa fa-plus" aria-hidden="true"
                                                            style="margin-right:5px"></i>Thêm thành viên</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="col-sm-3">
                                                    <h5 class="title-color">Họ tên</h5>
                                                </div>
                                                <div class="col-sm-3">
                                                    <h5 class="title-color">Chức vụ hiện tại</h5>
                                                </div>
                                                <div class="col-sm-3">
                                                    <h5 class="title-color">Nhiệm vụ</h5>
                                                </div>
                                                <div class="col-sm-3">
                                                    <h5 class="title-color">Thuộc</h5>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                            </div>
                                        </div>
                                        <div class="row hidden-item" id="thanhvien-item-row-pattern">
                                            <div class="col-sm-11">
                                                <input class="hidden-item" name="id">
                                                <div class="form-group col-sm-3">
                                                    <input type="text" name="hoten"
                                                        class="form-control form-control-sm">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <input type="text" name="chucvu"
                                                        class="form-control form-control-sm">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <input type="text" name="nhiemvu"
                                                        class="form-control form-control-sm">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <select class="form-control thuoc-thanhvien-select" name="thuoc">
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    style="margin: 2px">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="thanhvien-list-row">
                                            @if(count($maubieuii1->thanhviens) > 0)
                                            @foreach($maubieuii1->thanhviens as $thanhvien)
                                            <div class="row thanhvien-item-row">
                                                <div class="col-sm-11">
                                                    <input class="hidden-item ii1-thanhvien-{{ $thanhvien->id }}"
                                                        name="id" value="{{ $thanhvien->id }}">
                                                    <div class="form-group col-sm-3">
                                                        <input type="text" name="hoten"
                                                            class="form-control form-control-sm ii1-thanhvien-{{ $thanhvien->id }}"
                                                            id="ii1-hoten-input-{{ $thanhvien->id }}"
                                                            value="{{ $thanhvien->hoten }}">
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <input type="text" name="chucvu"
                                                            class="form-control form-control-sm ii1-thanhvien-{{ $thanhvien->id }}"
                                                            id="ii1-chucvu-input-{{ $thanhvien->id }}"
                                                            value="{{ $thanhvien->chucvu }}">
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <input type="text" name="nhiemvu"
                                                            class="form-control form-control-sm ii1-thanhvien-{{ $thanhvien->id }}"
                                                            id="ii1-nhiemvu-input-{{ $thanhvien->id }}"
                                                            value="{{ $thanhvien->nhiemvu }}">
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <select
                                                            class="form-control thuoc-thanhvien-select ii1-thanhvien-{{ $thanhvien->id }}"
                                                            name="thuoc">
                                                            <option value="none"
                                                                {{ !empty($thanhvien) ? '' : !empty($thonthuocmaubieu) && ($thon->id == $thonthuocmaubieu->id ? 'selected' : '') }}>
                                                                Đơn vị</option>
                                                            @if(count($maubieuii1->thons)>0)
                                                            @foreach($maubieuii1->thons as $thonthuocmaubieu)
                                                            <option value="{{ $thonthuocmaubieu->id }}"
                                                                {{ !empty($thanhvien) ? '' : !empty($thonthuocmaubieu) && ($thon->id == $thonthuocmaubieu->id ? 'selected' : '') }}>
                                                                {{ $thonthuocmaubieu->tenthon }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                        style="margin: 2px">
                                                        <i class="fa fa-close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                            <?php $thanhvien = null ?>
                                            @if(count($maubieuii1->thons) > 0)
                                            @foreach($maubieuii1->thons as $thon)
                                            @if(count($thon->thanhviens) > 0)
                                            @foreach($thon->thanhviens as $thanhvienthuocthon)
                                            <div class="row">
                                                <div class="col-sm-11">
                                                    <input
                                                        class="hidden-item ii1-thanhvien-{{ $thanhvienthuocthon->id }}"
                                                        type="text" name="id" value="{{ $thanhvienthuocthon->id }}">
                                                    <div class="form-group col-sm-3">
                                                        <input type="text" name="hoten"
                                                            class="form-control form-control-sm ii1-thanhvien-{{ $thanhvienthuocthon->id }}"
                                                            value="{{ $thanhvienthuocthon->hoten }}">
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <input type="text" name="chucvu"
                                                            class="form-control form-control-sm ii1-thanhvien-{{ $thanhvienthuocthon->id }}"
                                                            value="{{ $thanhvienthuocthon->chucvu }}">
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <input type="text" name="nhiemvu"
                                                            class="form-control form-control-sm ii1-thanhvien-{{ $thanhvienthuocthon->id }}"
                                                            value="{{ $thanhvienthuocthon->nhiemvu }}">
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <select
                                                            class="form-control thuoc-thanhvien-select ii1-thanhvien-{{ $thanhvienthuocthon->id }}"
                                                            id="exampleFormControlSelect2" name="thuoc">
                                                            <option value="none"
                                                                {{ !empty($thanhvien) ? '' : !empty($thonthuocmaubieu) && ($thon->id == $thonthuocmaubieu->id ? 'selected' : '') }}>
                                                                Đơn vị</option>
                                                            @if(count($maubieuii1->thons)>0)
                                                            @foreach($maubieuii1->thons as $thonthuocmaubieu)
                                                            <option value="{{ $thonthuocmaubieu->id }}"
                                                                {{ !empty($thanhvien) ? '' : ($thon->id==$thonthuocmaubieu->id ? 'selected' : '') }}>
                                                                {{ $thonthuocmaubieu->tenthon }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                        style="margin: 2px">
                                                        <i class="fa fa-close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                            @endforeach
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade in" id="ii2" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="card card-body" style="margin-top:20px">
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-sm-6 row">
                                                <label for="edit-input-sodienthoai" class="col-sm-3 col-form-label">Số
                                                    văn bản</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="sobieumau"
                                                        class="form-control form-control-sm ii2-sobieumau"
                                                        value="{{ $maubieuii2->sobieumau }}"
                                                        placeholder="Nhập số văn bản">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6 row ">
                                                <label for="edit-input-email" class="col-sm-3 col-form-label">Ngày ban
                                                    hành</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="ngaybanhanh"
                                                        class="form-control ii2-ngaybanhanh datepicker"
                                                        value="{{ $maubieuii2->ngaybanhanh }}"
                                                        placeholder="Nhập ngày ban hành">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6 row ">
                                                <label for="edit-input-email" class="col-sm-3 col-form-label">Trước
                                                    ngày</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="truocngay"
                                                        class="form-control ii2-truocngay datepicker"
                                                        value="{{ $maubieuii2->truocngay }}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <h4 class="title-color">Lịch công tác triển khai lập kế hoạch phát triển
                                                    KT-XH</h4>
                                            </div>
                                            <div class="col-sm-2">
                                                <div style="margin-left: auto">
                                                    <button type="button" class="btn mw-md btn-success m-xs"
                                                        id="add-hoatdong-btn">
                                                        <i class="fa fa-plus" aria-hidden="true"
                                                            style="margin-right:5px"></i>Thêm hoạt động</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="col-sm-3">
                                                    <h5 class="title-color">Nội dung hoạt động</h5>
                                                </div>
                                                <div class="col-sm-2">
                                                    <h5 class="title-color">Thời gian thực hiện</h5>
                                                </div>
                                                <div class="col-sm-2">
                                                    <h5 class="title-color">Người chịu trách nhiệm</h5>
                                                </div>
                                                <div class="col-sm-2">
                                                    <h5 class="title-color">Người phối hợp</h5>
                                                </div>
                                                <div class="col-sm-3">
                                                    <h5 class="title-color">Kết quả cần đạt</h5>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">

                                            </div>
                                        </div>
                                        <div class="row hidden-item" id="hoatdong-item-row-pattern">
                                            <div class="col-sm-11">
                                                <input type="text" name="id"
                                                    class="form-control form-control-sm hidden-item">
                                                <div class="form-group col-sm-3">
                                                    <input type="text" name="noidung"
                                                        class="form-control form-control-sm">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <input type="text" name="thoigian"
                                                        class="form-control form-control-sm datepicker">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <input type="text" name="nguoichiutrachnhiem"
                                                        class="form-control form-control-sm">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <input type="text" name="nguoiphoihop"
                                                        class="form-control form-control-sm">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <input type="text" name="ketqua"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    style="margin: 2px">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="hoatdong-list-row">
                                            @if(count($maubieuii2->lichcongtacs) > 0)
                                            @foreach($maubieuii2->lichcongtacs as $lichcongtac)
                                            <div class="row">
                                                <div class="col-sm-11">
                                                    <input type="text" name="id"
                                                        class="form-control form-control-sm hidden-item ii2-hoatdong-{{ $lichcongtac->id}}"
                                                        value="{{ $lichcongtac->id }}">
                                                    <div class="form-group col-sm-3">
                                                        <input type="text" name="noidung"
                                                            class="form-control form-control-sm ii2-hoatdong-{{ $lichcongtac->id}}"
                                                            value="{{ $lichcongtac->noidung }}">
                                                    </div>
                                                    <div class="form-group col-sm-2">
                                                        <input type="text" name="thoigian"
                                                            class="form-control form-control-sm ii2-hoatdong-{{ $lichcongtac->id}} datepicker"
                                                            value="{{ $lichcongtac->thoigian }}">
                                                    </div>
                                                    <div class="form-group col-sm-2">
                                                        <input type="text" name="nguoichiutrachnhiem"
                                                            class="form-control form-control-sm ii2-hoatdong-{{ $lichcongtac->id}}"
                                                            value="{{ $lichcongtac->nguoichiutrachnhiem }}">
                                                    </div>
                                                    <div class="form-group col-sm-2">
                                                        <input type="text" name="nguoiphoihop"
                                                            class="form-control form-control-sm ii2-hoatdong-{{ $lichcongtac->id}}"
                                                            value="{{ $lichcongtac->nguoiphoihop }}">
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <input type="text" name="ketqua"
                                                            class="form-control form-control-sm ii2-hoatdong-{{ $lichcongtac->id}}"
                                                            value="{{ $lichcongtac->ketqua }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                        style="margin: 2px">
                                                        <i class="fa fa-close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade in" id="ii4b" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="row" style="margin: 10px">
                                    <div class="col-sm-9">
                                        <h4 class="title-color">Biểu tổng hợp đề xuất kế hoạch phát triển KT-XH</h4>
                                    </div>
                                    <div class="col-sm-2">
                                        <div style="margin-left: auto">
                                            <button type="button" class="btn mw-md btn-success m-xs"
                                                id="add-dexuat-btn">
                                                <i class="fa fa-plus" aria-hidden="true"
                                                    style="margin-right:5px"></i>Thêm đề xuất
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <li class="list-group-item row hidden-item" id="dexuat-item-row-pattern">
                                    <div class="row">
                                        <div class="col-sm-11">
                                            <button class="btn btn-link collapse-btn" type="button">
                                                Hoạt động mới
                                            </button>
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                style="margin: 2px">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="collapse" aria-labelledby="headingOne">
                                        <div class="row" style="margin-top: 10px">
                                            <input type="text" name="id"
                                                class="form-control form-control-sm hidden-item">
                                            <div class="form-group col-sm-6">
                                                <label>
                                                    Hoạt động
                                                </label>
                                                <input type="text" name="hoatdong"
                                                    class="form-control form-control-sm tendexuat-item-input">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Đơn vị tính
                                                </label>
                                                <input type="text" name="dvt" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Số lượng
                                                </label>
                                                <input type="text" name="soluong" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label>Thời gian
                                                </label>
                                                <input type="text" name="thoigian" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label>Địa điểm
                                                </label>
                                                <input type="text" name="diadiem" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Người chịu trách nhiệm
                                                </label>
                                                <input type="text" name="nguoichiutrachnhiem"
                                                    class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label>Ngành/Lĩnh Vực
                                                </label>
                                                <input type="text" name="linhvuc" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Ghi chú
                                                </label>
                                                <input type="text" name="ghichu" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Tổng số
                                                </label>
                                                <input type="text" name="tongso" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Ngân sách
                                                </label>
                                                <input type="text" name="ngansach" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Dân góp
                                                </label>
                                                <input type="text" name="dangop" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Đề xuất
                                                </label>
                                                <input type="text" name="dexuat" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <ul class="list-group" id="dexuat-list-row">
                                    @if(count($maubieuii4b->dexuats) > 0)
                                    @foreach($maubieuii4b->dexuats as $dexuat)
                                    <li class="list-group-item dexuat-item-row">
                                        <div class="row">
                                            <div class="col-sm-11">
                                                <button class="btn btn-link collapse-btn" type="button"
                                                    id="collapse-btn-{{ $dexuat->id }}">
                                                    {{ !empty($dexuat->hoatdong) ? $dexuat->hoatdong : 'Hoạt động mới' }}
                                                </button>
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    style="margin: 2px">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="dexuat-{{ $dexuat->id }}" class="collapse"
                                            aria-labelledby="headingOne">
                                            <div class="row" style="margin-top: 10px">
                                                <input type="text" name="id"
                                                    class="form-control form-control-sm hidden-item ii4b-dexuat-{{ $dexuat->id }}"
                                                    value="{{ $dexuat->id }}">
                                                <div class="form-group col-sm-6">
                                                    <label>
                                                        Hoạt động
                                                    </label>
                                                    <input type="text" name="hoatdong"
                                                        class="form-control form-control-sm tendexuat-item-input ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->hoatdong }}">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>Đơn vị tính
                                                    </label>
                                                    <input type="text" name="dvt"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->dvt }}">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>Số lượng
                                                    </label>
                                                    <input type="text" name="soluong"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->soluong }}">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label>Thời gian
                                                    </label>
                                                    <input type="text" name="thoigian"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->thoigian }}">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label>Địa điểm
                                                    </label>
                                                    <input type="text" name="diadiem"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->diadiem }} ">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>Người chịu trách nhiệm
                                                    </label>
                                                    <input type="text" name="nguoichiutrachnhiem"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->nguoichiutrachnhiem }}">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label>Ngành/Lĩnh Vực
                                                    </label>
                                                    <input type="text" name="linhvuc"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->linhvuc }}">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>Ghi chú
                                                    </label>
                                                    <input type="text" name="ghichu"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->ghichu }}">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>Tổng số
                                                    </label>
                                                    <input type="text" name="tongso"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->tongso }}">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>Ngân sách
                                                    </label>
                                                    <input type="text" name="ngansach"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->ngansach }}">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>Dân góp
                                                    </label>
                                                    <input type="text" name="dangop"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->dangop }}">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>Đề xuất
                                                    </label>
                                                    <input type="text" name="dexuat"
                                                        class="form-control form-control-sm ii4b-dexuat-{{ $dexuat->id }}"
                                                        value="{{ $dexuat->dexuat }}">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="{{ URL::asset('js/kehoachktxhxaDetails.js') }}"></script>
<script>
    kehoachktxhxaDetailsServices();

</script>



@endsection