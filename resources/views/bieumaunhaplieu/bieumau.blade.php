@extends('master')
@section('title','Quản lý biểu mẫu nhập liệu')
@section('content')
<!--- Author Duc ---->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">Thông tin biểu mẫu</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">Thông tin chung</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <p><strong>Đơn vị tạo biểu mẫu:</strong> <span id="donvi"></p>
                                <p><strong>Người tạo biểu mẫu:</strong> <span id="nguoitao"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">Thông tin biểu mẫu</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">Thông tin chung</h4>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Số hiệu</label>
                                    <div class="col-sm-10">
                                        <input id="sohieu" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Tên biểu mẫu</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="tenbieumau" type="text" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2">Số quyết định</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" id="soquyetdinh" type="text" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Ngày quyết định</label>
                                    <div class="col-sm-10">
                                        <div id="ngayquyetdinh" style="width: 100%"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Mô tả</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="mota" type="text"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Trạng thái áp dụng</label>
                                    <div class="col-sm-10">
                                        <div class="checkbox checkbox-default"><input type="checkbox"
                                                id="trangthaiapdung"><label for="trangthaiapdung"></label></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Đính kèm:</label>
                                    <div class="col-sm-10 col-md-10">
                                        <input type="file" id="file" />
                                        <div class="">
                                            <ul id="listFile">
                                                
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!--Chọn chỉ tiêu---->
            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">Thông tin biểu mẫu</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">Chọn chỉ tiêu cho biểu mẫu</h4>
                        </div>
                        <div class="panel-body">
                            <table id="tableChitieu" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="allSelect" /></th>
                                        <th>Chỉ tiêu</th>
                                        <th>Đơn vị tính</th>
                                    </tr>
                                </thead>
                                <tbody id="TreeGridContainer">

                                </tbody>
                            </table>
                            <div class="col-md-12" style="text-align: center; margin-top: 10px;">
                                <button class="btn btn-primary" id="btnLuu"> <i class="fa fa-file-archive-o"
                                        aria-hidden="true"></i>
                                    Lưu</button>
                                <button class="btn btn-warning" id="btnThoat"><i class="fa fa-window-close"
                                        aria-hidden="true">Thoát</i></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script type="module" src="js/bieumaunhaplieu.js"></script>
@endsection