@extends('master')
@section('title','Thông tin báo cáo')
@section('content')
<div class="col-md-12" id="thongtinbaocao">
    <div class="widget">
        <div class="widget-header">
            <h4>Thông tin báo cáo</h4>
        </div>
        <hr class="widget-separator" />
        <div class="widget-body">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="panel-title">Thông tin chung</h4>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Kỳ báo cáo</label>
                            <div class="col-sm-10">
                                <div id="kybaocao"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Số/ký hiệu báo cáo</label>
                            <div class="col-sm-10">
                                <input id="sokyhieubaocao" class="form-control" type="text" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Tiêu đề báo cáo</label>
                            <div class="col-sm-10">
                                <input id="tieudebaocao" class="form-control" type="text" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Năm báo cáo</label>
                            <div class="col-sm-10">
                                <div id="nambaocao"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Đã hoàn thành báo cáo</label>
                            <div class="col-sm-10">
                                <input class="checkbox-inline" type="checkbox" id="hoanthanh" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Đơn vị nhận báo cáo</label>
                            <div class="col-sm-10">
                                <button id="btnDonvi" class="btn btn-primary btn-sm"><i class="fa fa-plus"
                                        aria-hidden="true"></i> Thêm đơn
                                    vị</button>
                                <div id="gridDonvi"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Các biểu số liệu</label>
                            <div class="col-sm-10">
                                <button id="btnBieumau" class="btn btn-primary btn-sm"><i class="fa fa-plus"
                                        aria-hidden="true"></i>Thêm biểu
                                    mẫu
                                    và địa bàn báo cáo</button>
                                <div id="gridBieumau"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Tập tin đính kèm</label>
                            <div class="col-sm-10">
                                <input type="file" id="file" placeholder="Chọn tập tin" />
                                <p style="margin-top: 10px" id="filedinhkem"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Nội dung báo cáo</label>
                            <div class="col-sm-10">
                                <textarea id="noidung" name="noidung"></textarea>

                            </div>
                        </div>
                        <div class="col-md-12" style="text-align: center">
                            <button id="btnLuu" class="btn btn-primary">Lưu</button>
                            <button id="btnThoat" class="btn btn-danger">Thoát</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modelDonvi" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn đơn vị</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="col-sm-11" id="chondiaban"></div>
                                    <div class="col-sm-1">
                                        <button id="getdiaban" class="btn btn-sm btn-success"><i
                                                class="fa fa-street-view" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="gridDanhsachdonvi" style="height: 200px; overflow: auto;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th></th>
                                                <th>Tên</th>
                                                <th>Địa chỉ</th>
                                                <th>Số điện thoại</th>
                                                <th>Email</th>
                                            </thead>
                                            <tbody id="listDonvi">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnChondonvi" class="btn btn-primary">Chọn</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modelBieumau" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn biểu mẫu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="gridDanhsachbieumau" style="height: 200px; overflow: auto;">
                                    <table class="table-bordered table">
                                        <thead>
                                            <th></th>
                                            <th>Số hiệu</th>
                                            <th>Tên biểu mẫu</th>
                                        </thead>
                                        <tbody id="listBieumau">

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnChonBieumau" class="btn btn-primary" data-dismiss="modal">Chọn</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="module" src="js/KTXH/thembaocaodinhky.js"></script>
@endsection