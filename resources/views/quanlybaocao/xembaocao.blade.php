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
                                <input id="kybaocao" class="form-control" type="text" disabled />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Số/ký hiệu báo cáo</label>
                            <div class="col-sm-10">
                                <input id="sokyhieubaocao" class="form-control" type="text" disabled />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Tiêu đề báo cáo</label>
                            <div class="col-sm-10">
                                <input id="tieudebaocao" class="form-control" type="text" disabled />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Năm báo cáo</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" disabled id="nambaocao" />
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
                                <table class="table table-bordered">
                                    <thead>
                                        <th>STT</th>
                                        <th>Tên đơn vị</th>
                                        <th>Số điện thoại</th>
                                        <th>Email</th>
                                    </thead>
                                    <tbody id="gridDonvi">

                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Các biểu số liệu</label>
                            <div class="col-sm-10">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Số TT</th>
                                            <th>Số hiệu</th>
                                            <th>Tên biểu mẫu</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="gridBieumau">


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Tập tin đính kèm</label>
                            <div class="col-sm-10" style="display:flex">
                                <p style="margin-top: 10px; cursor: pointer;" data-file="" id="filedinhkem"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Nội dung báo cáo</label>
                            <div class="col-sm-10">
                                <div id="noidung"></div>

                            </div>
                        </div>

                        <div class="form-group" style="text-align: center">
                            <button class="btn btn-info btn-sm" id="btnDuyetbaocao">Duyệt báo cáo</button>
                            <button class="btn btn-danger btn-sm" id="btnThoat">Thoát</button>
                        </div>

                    </div>
                </div>
            </div>

            <div id="makeReport"></div>
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

</div>
<script type="module" src="js/chitietbaocao.js"></script>
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