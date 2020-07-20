@extends('master')
@section('title','So sánh số liệu theo địa bàn')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header">
                <h5 class="widget-title">So sánh số liệu theo địa bàn</h4>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                <div class="row">

                    <div class="form-group row" style="margin-bottom: 10px">
                        <h5 class="col-sm-3 col-form-label">Kiểu so sánh : </h4>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="checkbox1">
                                            <label for="checkbox-demo-3">So sánh giữa các địa bàn</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="checkbox2">
                                            <label for="checkbox-demo-3">So sánh giữa các đơn vị</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>


                    <div class="form-group row" id="slcdb" style="margin-bottom: 10px;display: none;">
                        <h5 class="col-sm-3 col-form-label">Số liệu của địa bàn : </h5>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <select type="text" id="tinh" placeholder="tinh" class="form-control"></select>
                                </div>
                                <div class="col-md-4">
                                    <select type="text" id="huyen" placeholder="Quận huyện"
                                        class="form-control"></select>
                                </div>
                                <div class="col-md-4">
                                    <select type="text" id="xa" placeholder="Xã/Phường" class="form-control"></select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row" id="ssdtct" style="margin-bottom: 10px;display: none;">
                        <h5 class="col-sm-3 col-form-label">So sánh dựa theo chỉ tiêu : </h4>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-8">
                                        <select type="text" id="sosanhduatheochitieu" class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                    </div>


                    <div class="form-group row" id="sssldv" style="margin-bottom: 10px;display: none;">
                        <h5 class="col-sm-3 col-form-label">So sánh số liệu của đơn vị : </h4>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-8">
                                        <select type="text" id="sosanhsolieudonvi" class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="form-group row" id="ssdtbm" style="margin-bottom: 10px;display: none;">
                        <h5 class="col-sm-3 col-form-label">So sánh dựa theo biểu mẫu : </h4>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-8">
                                        <select type="text" id="sosanhduatheobieumau" class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                    </div>



                    <div class="form-group row" style="margin-bottom: 10px">
                        <h5 class="col-sm-3 col-form-label">Dựa trên loại số liệu : </h4>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-8">
                                        <select type="text" id="duatrenloaisolieu" class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                    </div>


                    <div class="form-group row" style="margin-bottom: 10px">
                        <h5 class="col-sm-3 col-form-label">So sánh theo kỳ số liệu : </h4>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-8">
                                        <select type="text" id="sosanhtheokysolieu" class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="form-group row" id="tss" style="margin-bottom: 10px;display: none;">
                        <h5 class="col-sm-3 col-form-label">Tháng so sánh : </h4>
                            <div class="col-sm-9" style="height: 35px">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class='input-group date' id='thangsosanh'>
                                                <input type='text' class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>



                    <div class="form-group row" style="margin-bottom: 10px">
                        <h5 class="col-sm-3 col-form-label">Năm so sánh : </h4>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class='input-group date'>
                                                <input type='text' class="form-control" id='namsosanh' />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <!--                     <div class="form-group row" id="cdbcss" style="margin-bottom: 10px;display: none;">
                        <h5 class="col-sm-3 col-form-label">Các địa bàn cần so sánh : </h4>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select multiple="multiple" type="text" id="cacdiabansosanh"
                                            class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                    </div> -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="timkiem" style="float: right;"><i
                                class='fa fa-search'></i> Tìm
                            kiếm</button>
                        <button type="button" class="btn btn-primary" id="timkiemdiaban"
                            style="display: none;float: right;"><i class='fa fa-search'></i> Tìm
                            kiếm địa bàn</button>
                        <button type="button" class="btn btn-primary" id="timkiemdonvi"
                            style="display: none;float: right;"><i class='fa fa-search'></i> Tìm
                            kiếm đơn vị</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row" style="display: none" id="tablegrid">
    <div class="widget">
        <hr class="widget-separator">
        <div class="widget-body">
            <div id="treelistdiaban"></div>
        </div>
    </div>
</div>

<div class="row" style="display: none" id="tablegrid1">
    <div class="widget">
        <hr class="widget-separator">
        <div class="widget-body">
            <div id="treelistdonvi"></div>
        </div>
    </div>
</div>


<div id="bieudodiaban" class="modal fade in" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="demo-container">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" id="dong" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


<div id="bieudodonvi" class="modal fade in" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="demo-container">
                        <div id="chart1"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" id="dong" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="ktxh/Khaithacthongtin/Sosanhsolieutheodiaban/sosanhsolieudiaban.js">
</script>


@endsection