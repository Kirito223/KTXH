@extends('master')
@section('title','danh mục đơn vị')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<div class="row">
  <div class="col-lg-12">
    <div class="ibox ">
      <div class="ibox-title">
        <div class="row">
          <div class="col-lg-10">
            <h5>Danh sách quản trị viên</h5>
          </div>
          <div class="col-lg-2">
            <button type="button" class="btn btn-w-m btn-success" data-toggle="modal" id="adddonvi"
              style="float: right;">Thêm mới</button>
          </div>
        </div>

      </div>
      <div class="ibox-content">
        <div class="table-responsive">
          <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
            <table class="table table-striped table-bordered table-hover dataTables-example dataTable" id="table-donvi"
              aria-describedby="DataTables_Table_0_info" role="grid">
              <thead>
                <tr>
                  <th class="sorting">Tên đơn vị</th>
                  <th class="sorting">Tên đăng nhập</th>
                  <th class="sorting">Kế toán</th>
                  <th class="sorting">Người ký</th>
                  <th class="sorting">Số điện thoại</th>
                  <th class="sorting" width="30%">Chức năng</th>

                </tr>
              </thead>

            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- modal-xoa roleUser -->
<div class="modal fade" id="modal-xoadonvi">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal-title-donvi">Xoá user</h4>
      </div>
      <div class="modal-body">
        <p>Bạn có muốn xoá đơn vị này không?</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-danger" id="deldonvi">Xoá</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>




<div class="modal inmodal fade " id="modal-themdonvi" tabindex="-1" role="dialog" style="display: none;"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 97% ; max-width: -webkit-fill-available">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
            class="sr-only">Close</span></button>
        <h4 class="modal-title">Thêm mới đơn vị</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <label for="exampleInputPassword1">Loại tài khoản:</label>
            <input type="checkbox" id="chkxa" name="chkxa" value="1"> Tài khoản xã/phường<br>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4">

            <div class="form-group" hidden="">
              <input id="iddonvi" class="form-control" type="text" value="">
            </div>


            <div class="form-group">
              <label for="exampleInputPassword1">Tên đơn vị:</label>
              <!-- <input id="donvi" class="form-control" type="text"> -->
              <select class="form-control" id="donvi" readonly>
              </select>
            </div>

            <div class="form-group">
              <label for="exampleInputPassword1">Chức danh 1:</label>
              <input id="chucdanh1" class="form-control" type="text" value="chức danh 1">
            </div>

            <div class="form-group">
              <label for="exampleInputPassword1">Mã quan hệ ns:</label>
              <input id="ma_qhns" class="form-control" type="text">
            </div>



            <div class="form-group">
              <label for="exampleInputEmail1">điện thoại:</label>
              <input id="dienthoai" class="form-control" type="text">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">Chức danh 2:</label>
              <input id="chucdanh2" class="form-control" type="text">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">kế toán:</label>
              <input id="ketoan" class="form-control" type="text">
            </div>












          </div>


          <div class="col-lg-4">
            <div class="form-group">
              <label for="exampleInputEmail1">Trưởng phòng:</label>
              <input id="truongphong" class="form-control" type="text">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">Chức danh QĐ 1:</label>
              <input id="chucdanh_qd2" class="form-control" type="text">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">Tên người dùng:</label>
              <input id="tennguoidung" class="form-control" type="text">
            </div>



            <div class="form-group">
              <label for="exampleInputEmail1">Người ký:</label>
              <input id="nguoiky" class="form-control" type="text">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">Trưởng phòng:</label>
              <input id="truongphongtc" class="form-control" type="text">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">Chức danh quyết định:</label>
              <input id="chucdanh_qd" class="form-control" type="text">
            </div>

          </div>

          <div class="col-lg-4">


            <div class="form-group">
              <label for="exampleInputEmail1">Người ký quyết định:</label>
              <input id="nguoiky_qd" class="form-control" type="text">
            </div>

            <div class="form-group" hidden="">
              <label for="exampleInputEmail1">Loại tài khoản:</label>
              <input id="loaitaikhoan" class="form-control" type="text">
            </div>




            <div class="form-group">
              <label for="exampleInputEmail1">Tên đăng nhập::</label>
              <input id="email" class="form-control" type="email">
            </div>

            <div class="form-group">
              <label for="exampleInputPassword1">Password:</label>
              <input id="password" class="form-control" type="password" value="abc@123">
            </div>


            <div class="form-group">
              <label for="quyen" class="control-label required">Quyền:</label>
              <select class="form-control" id="quyen"></select>
            </div>
          </div>










        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-w-m btn-default" data-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-w-m btn-primary" id="savedonvi">Lưu</button>
      </div>
    </div>
  </div>
</div>







<script src="{{asset('js/thongtindonvi.js')}}"></script>
@endsection