@extends('master')
@section('title','Nhập liệu số liệu báo cáo')
@section('content')
<div class="col-md-12" id="nhaplieubaocao">
    <div class="widget p-lg">

        <div class="widget">
            <header class="widget-header">
                <h4 class="widget-title">Nhập liệu số liệu báo cáo</h4>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title">Chọn chỉ tiêu cho biểu mẫu</h4>
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
                             
                                <label class="col-md-2 col-sm-2">Chọn biểu mẫu</label>
                                <div class="col-sm-10 col-md-10">
                                    <div id="cbBieumau"></div>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-12" style="text-align: center;">
                            <button class="btn btn-info" id="btnTaibieumau"><i class="fa fa-refresh"
                                    aria-hidden="true"></i> Tải Biểu
                                mẫu</button>
                            <button class="btn btn-info" id="btnImportFromExcel"><i class="fa fa-upload"
                                    aria-hidden="true"></i> Nhập từ Excel</button>
                        </div>

                        <div class="col-md-12" style="margin: 5px 0px 5px 0px; text-align: right;">
                            <button class="btn btn-sm btn-primary" id="sum-with-report">Cộng dồn theo báo cáo</button>
                            <button class="btn btn-sm btn-primary" id="sum-with-location">Cộng dồn theo địa
                                bàn</button>
                            <button class="btn btn-sm btn-primary" id="sum-with-time">Cộng dồn theo kỳ</button>
                            <button class="btn btn-sm btn-primary" id="sum-with-bm">Cộng dồn theo biểu mẫu</button>
                        </div>
                        <div class="col-md-12">
                            <span style="color: blue">Click chuột phải vào từng chỉ tiêu để cập nhật số liệu theo hệ
                                thống</span>
                            <div id="GridSolieu"></div>
                        </div>
                        <div class="col-md-12" style="text-align: center; margin-top: 10px;">
                            <button class="btn btn-info" id="btnImport">Nhập dữ liệu</button>
                            <button class="btn btn-default" id="btnExit">Bỏ qua</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="modelCongtheobieu" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cộng theo biểu mẫu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div id="cbLoaibieumau"></div>
                <div id="gridBieumauNhaplieu"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnCongdontheobieu"> <i class="fas fa-plus"></i> Cộng dồn</button>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="modalImportFromExcel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nhập file</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-horizontal">
                            <div class="col-sm-8">
                                <input type="file" id="file" />
                            </div>
                            <div class="col-sm-4">
                                <button id="btnNhap" class="btn btn-danger btn-sm"> <i class="fa fa-upload"
                                        aria-hidden="true">Tải lên</i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modelReportSelect" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn báo cáo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="grid-template"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="fas fa-window-close fa-sm fa-fw "></i> Đóng</button>
                    <button type="button" id="btnplus" class="btn btn-primary"><i class="fas fa-plus fa-sm fa-fw"></i>
                        Cộng
                        dồn</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modelLocaltion" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn địa bàn </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">

                                <div id="grid-location"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" id="btnSumlocation" class="btn btn-primary"><i
                            class="fas fa-plus fa-sm fa-fw"></i> Cộng
                        dồn</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="js/chonbieumau.js"></script>
@endsection