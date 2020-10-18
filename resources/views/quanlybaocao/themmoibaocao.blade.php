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
                                <div style="display: flex;">
                                    <input type="file" id="file" placeholder="Chọn tập tin" />
                                    <button class="btn btn-primary btn-sm" id="btnkyso">Ký văn bản</button>
                                </div>
                                <div>
                                    <p style="margin-top: 10px; cursor: pointer;" data-file="" id="filedinhkem"></p>
                                </div>

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

            <div id="makeReport"></div>
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
                                            <th>Loại số liệu</th>
                                            <th>Năm</th>
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

    <!-- Modal -->
    <div class="modal fade" id="modelReviewReport" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xem trước báo cáo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div id="Report" style="height: 570px; overflow: auto"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal loading fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="width: 100%">
                <p>Đang tải báo cáo vui lòng đợi</p>
                <span class="fa fa-spinner fa-spin fa-3x"></span>
            </div>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="modelKyso" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 70%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ký số văn bản</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="preview">
                                <iframe id="viewfile"></iframe>
                            </div>
                            <div class="toolbar">
                                <button id="btnkypheduyet" class="btn btn-default btn-sm">Ký phê duyệt</button>
                                <button id="btnĐongauphathanh" class="btn btn-default btn-sm">Đóng dấu phát
                                    hành</button>
                                <button id="btnKycongvan" class="btn btn-default btn-sm">Ký công văn đến</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>


<script type="module" src="js/thembaocaodinhky.js"></script>
<script src="js/vgcaplugin.js"></script>
<style>
    .preview {

        height: 500px;
    }

    .preview>iframe {
        width: 100%;
        height: 100%;
    }

    .toolbar {
        display: flex;
        flex-direction: row;
        align-items: center;
        align-content: center;
        justify-content: center;
        margin-top: 5px;
    }

    .toolbar button {
        margin: 0px 5px 0px 5px;
    }
</style>
@endsection