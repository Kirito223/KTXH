@extends('master')
@section('title','Báo cáo chỉ tiêu kinh tế tổng hợp Daksong')
@section('content')

<div class="container-fluid" id="summaryindicatorreport">
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

                        <div class="col-md-2 col-sm-12">

                            <button class="btn btn-success btn-sm btn-search" id="btnSearch"><i
                                    class="fas fa-search fa-sm fa-fw"></i>
                                Xem dữ
                                liệu</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">Báo cáo</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <div id="report"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="module" src="js/baocaoktxhdaksong.js"></script>
@endsection