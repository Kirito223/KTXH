@extends('master')
@section('title','Danh sách báo cáo')
@section('content')
<div class="col-md-12" id="timkiembaocao">
    <div class="widget p-lg">
        <h4 class="m-b-lg">Danh sách báo cáo</h4>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Tìm kiếm</h4>
            </div>
            <div class="panel-body">
                <div class="col-md-6">
                    <div class="row search-group">
                        <label class="col-sm-2">Đơn vị gửi</label>
                        <div class="col-sm-10" id="Donvigui"></div>
                    </div>
                    <div class="row search-group">
                        <label class="col-sm-2">Phòng ban</label>
                        <div class="col-sm-10" id="Phongban"></div>
                    </div>
                    <div class="row search-group">
                        <label class="col-sm-2">Từ khoá</label>
                        <input class="col-sm-10" id="Tukhoa" type="text" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6 search-group">
                    <div class="row">
                        <label class="col-sm-2">Năm báo cáo</label>
                        <div class="col-sm-10" id="Nambaocao"></div>
                    </div>
                    <div class="row search-group">
                        <label class="col-sm-2">Kỳ báo cáo</label>
                        <div class="col-sm-10" id="Kybaocao"></div>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="row" style="text-align: right">
                        <button class="btn btn-primary btn-sm" id="btnTimkiem"><i class="fa fa-search"
                                aria-hidden="true"></i>Tìm
                            kiếm</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="GridBaocao"></div>
    </div>
</div>
<style>
    .search-group {
        margin-top: 10px;
    }
</style>
<script type="module" src="js/timkiembaocao.js"></script>
@endsection