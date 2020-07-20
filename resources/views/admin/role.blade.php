@extends('master')

@section('title','danh mục nhóm và phân quyền')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <div class="row">
                <div class="col-lg-10"> <h5>Danh sách nhóm và phân quyền</h5></div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-w-m btn-success" data-toggle="modal" id="addrole" style="float: right;">Thêm mới</button>
                </div>
               </div>
                
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <table class="table table-striped table-bordered table-hover dataTables-example dataTable" id="table-role" aria-describedby="DataTables_Table_0_info" role="grid">
                            <thead>
                               <tr>
                            <th class="sorting">ID</th>
                            <th class="sorting">Tên quyền</th>
                            <th class="sorting">Mô tả</th>
                            <th class="sorting">Ngày tạo</th>
                            <th class="sorting" width="15%">Chức năng</th>
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
            <div id="Grid"></div>
        </div>
    </div>
</div>
</div>



        <!-- modal-xoa role -->
        <div class="modal fade" id="modal-xoarole">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="modal-title-role">Xoá quyền</h4>
              </div>
              <div class="modal-body">
                <p>Bạn có muốn xoá quyền này không?</p>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-danger" id="delrole">Xoá</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>


        <!-- modal-them role -->
        <div class="modal fade" id="modal-themrole">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="modal-title-role">Thêm mới quyền</h4>             
              </div>
              <div class="modal-body">
                <form role="form">
                  <div class="card-body">                  
                      <div class="form-group">
                        <label for="exampleInputPassword1">Tên quyền:</label>
                        <input class="form-control" id="tenrole" type="text">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Mô tả :</label>
                        <textarea class="form-control" id="mota" type="number"></textarea>
                      </div>
                      <!-- <div id="created_at"></div>  -->                                    
                </div>
                <!-- /.card-body -->
              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
              <button type="button" class="btn btn-primary" id="saverole">Lưu</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->








<script src="{{asset('js/role.js')}}"></script>
@endsection