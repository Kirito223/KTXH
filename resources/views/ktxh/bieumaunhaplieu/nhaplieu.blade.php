@extends('master')
@section('title','Nhập liệu số liệu biểu mẫu')
@section('content')
<div class="col-md-12" id="nhapdulieutheobieu">
    <div class="widget p-lg">

        <div class="widget">
            <header class="widget-header">
                <h4 class="widget-title">Nhập liệu số liệu biểu mẫu</h4>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title">Thông tin</h4>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2">Đơn vị nhập số liệu</label>
                                <div class="col-sm-10">
                                    <p id="donvi"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Người nhập số liệu</label>
                                <div class="col-sm-10">
                                    <p id="nguoitao"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Số liệu của địa bàn</label>
                                <div class="col-sm-5">
                                    <div id="cbTinh"></div>
                                </div>
                                <div class="col-sm-5">
                                    <div id="cbphamvi"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Loại số liệu cần nhập</label>
                                <div class="col-sm-10">
                                    <div id="cbLoaisolieu"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Kỳ nhập số liệu</label>
                                <div class="col-sm-10">
                                    <div id="cbKynhaplieu"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Năm nhập biểu mẫu</label>
                                <div class="col-sm-10">
                                    <div id="cbNamnhaplieu"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Biểu mẫu nhập liệu</label>
                                <div class="col-sm-10">
                                    <div id="cbBieumau"></div>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-12" style="text-align: center;">
                            <button class="btn btn-info" id="btnTaibieumau"><i class="fa fa-refresh"
                                    aria-hidden="true"></i> Tải Biểu
                                mẫu</button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info" id="modalNhapExcel" style="display: block">
                    <div class="panel-heading">
                        <h4 class="panel-title">Nhập biểu mẫu</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input type="file" id="file" />
                                </div>
                                <div class="col-sm-2">
                                    <button id="btnNhap" class="btn btn-danger"> <i class="fa fa-upload"
                                            aria-hidden="true">Nhập
                                            biểu mẫu</i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="js/KTXH/nhapsolieutheobieu.js"></script>
@endsection