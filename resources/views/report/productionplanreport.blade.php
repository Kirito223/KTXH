@extends('master')
@section('title','Báo cáo kế hoạch sản xuất')
@section('content')

<div class="container-fluid" id="productionplanreport">
    <div class="row">
        <div class="col-md-12">
            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">Lọc thông tin</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <label>Huyện</label>
                            <div id="cbHuyen"></div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label>Năm</label>
                            <div id="cbNam" style="width: 100%"></div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label>Biểu mẫu</label>
                            <div id="cbBieumau" style="width: 100%"></div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <button class="btn btn-success btn-sm btn-search" id="btnView"><i
                                    class="fas fa-eye fa-sm fa-fw"></i>
                                Xem báo cáo</button>
                            <button class="btn btn-success btn-sm btn-search" id="btnSearch"><i
                                    class="fas fa-download fa-sm fa-fw"></i>
                                Xuất báo cáo</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget">

                <hr class="widget-separator">
                <div class="widget-body">
                    <div id="report"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="module" src="js/productionplanreport.js"></script>
@endsection