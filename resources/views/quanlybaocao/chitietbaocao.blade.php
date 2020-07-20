@extends('master')
@section('title','Chi tiết báo cáo')
@section('content')
<div class="col-md-12" id="chitietbaocao">
    <div class="widget p-lg">
        <h4 class="m-b-lg">Chi tiết báo cáo</h4>

        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-md-2">Kỳ báo cáo</label>
                <p class="col-md-10" id="kybaocao"></p>
            </div>
            <div class="form-group">
                <label class="col-md-2">Số/ký hiệu báo cáo</label>
                <p class="col-md-10" id="sohieu"></p>
            </div>
            <div class="form-group">
                <label class="col-md-2">Tiêu đề báo cáo</label>
                <p class="col-md-10" id="tieude"></p>
            </div>
            <div class="form-group">
                <label class="col-md-2">Năm báo cáo</label>
                <p class="col-md-10" id="nam"></p>
            </div>
            <div class="form-group">
                <label class="col-md-2">Các đơn vị nhận</label>
                <div class="col-md-10">
                    <div id="donvinhan"></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2">Các biểu số liệu</label>
                <div class="col-md-10">
                    <div id="bieusolieu">

                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2">Tập tin đính kèm</label>
                <ul class="col-md-10" id="danhsachtaptin"></ul>
            </div>
            <div class="form-group">
                <label class="col-md-2">Nội dung báo cáo</label>
                <div class="col-md-10">
                    <div id="noidung" name="noidung"></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2">Ngày ký</label>
                <p class="col-md-10" id="ngayky"></p>
            </div>
            <div class="form-group">
                <label class="col-md-2">Người ký</label>
                <p class="col-md-10" id="nguoiky"></p>
            </div>
            <div class="form-group" style="text-align: center;">
                <button id="btnTrove" class="btn btn-sm btn-info"><i class="fa fa-backward" aria-hidden="true"></i>
                    Trở
                    về</button>
            </div>
        </div>

    </div>
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

<script type="module" src="js/xemchitietbaocao.js"></script>
@endsection