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
                            <label>Địa bàn</label>
                            <div id="cbDiaban"></div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label id="titleDiaban"></label>
                            <div id="cbHuyen"></div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label>Loại số liệu</label>
                            <div id="cbSoLieu"></div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label>Năm</label>
                            <div id="cbNam" style="width: 100%"></div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label>Chỉ tiêu</label>
                            <div id="cbBieumau" style="width: 100%"></div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <button class="btn btn-success btn-sm btn-search" id="btnView"><i
                                    class="fas fa-eye fa-sm fa-fw"></i>
                                Xem báo cáo</button>
                            <button class="btn btn-success btn-sm btn-search" id="btnSearch" style="display: none"><i
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



            <!-- Modal -->
            <div class="modal fade" id="modelDanhsachBieumau" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chọn biểu mẫu</h5>
                            <button type="button" id="btnThembieumau" class="btn btn-sm btn-info">
                                <i class="fa fa-plus" aria-hidden="true"></i> Quản lý biểu mẫu
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div style="overflow-y: scroll; height: 500px;">
                                            <div id="containerBieumau">

                                            </div>
                                        </div>
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

            <!-- Modal Bieu mau -->


            <!-- Thêm mới biểu mẫu -->
            <div class="modal fade" id="modalThembieumau" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                aria-hidden="true">
                <div class="modal-dialog" style="min-width: 70%;" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm biểu mẫu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div style="display:flex;justify-content: flex-end;">
                                            <button id="btnAddbieumau" class="btn btn-sm btn-success">Thêm biểu
                                                mẫu</button>
                                        </div>
                                        <table id="tableBieumau" class="table border-dark">
                                            <thead>
                                                <tr>
                                                    <th>Tên biểu mẫu</th>
                                                    <th>Tập tin</th>
                                                    <th>Áp dụng</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="gridBieumau">


                                            </tbody>
                                        </table>
                                        <div id="nav"></div>

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

            <div class="modal fade" id="modalBieumau" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Quản lý biểu mẫu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12 col-xl-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="txtTenbieumau">Tên biểu mẫu</label>
                                            <input type="text" name="txtTenbieumau" id="txtTenbieumau"
                                                class="form-control" placeholder="Tên biểu mẫu">
                                        </div>

                                        <div class="form-group">
                                            <label class="custom-file">
                                                <input type="file" name="fileBieumau" id="fileBieumau">

                                            </label>
                                            <p class="fileBM"></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtTenbieumau">Loại: </label>
                                            <div id="loaibieumau"></div>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="chkApdung"
                                                    id="chkApdung" checked>
                                                Áp dụng
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="button" id="btnLuuBieumau" class="btn btn-primary">Lưu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="module" src="js/dubaosolieu.js"></script>

<style>
    .homeproduct {
        clear: both;
        display: block;
        background: #fff;
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-flex-flow: row wrap;
        flex-flow: row wrap;
        flex: 1 100%;
        border-top: 1px solid #eee;
        border-left: 1px solid #eee;
        margin-bottom: 15px;
        list-style: none;
    }

    .item-bieumau {
        float: left;
        position: relative;
        width: 19.91%;
        overflow: hidden;
        text-align: center;
        cursor: pointer;
    }

    .iconbieumau i {
        font-size: 20pt;
        /* color: orangered; */
    }

    .item-bieumau:hover {
        background-color: bisque;
    }

    .type1 {
        color: rgb(224, 42, 10);
    }

    .type2 {
        color: rgb(237, 61, 243);
    }

    .type3 {
        color: rgb(6, 187, 21)
    }

    .modal-title {
        text-align: center;
        font-size: 16pt;
    }

    #nav {
        text-align: center;
    }
</style>
@endsection