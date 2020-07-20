@extends('master')

@section('title','Thông tin chung')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title">
                <div class="row">
                <div class="col-lg-10"> <h5>Danh sách thông số</h5></div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-w-m btn-success" data-toggle="modal" id="addnew" style="float: right;">Thêm mới</button>
                </div>
               </div>
                
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <table class="table table-striped table-bordered table-hover dataTables-example dataTable" id="table-lophoc" aria-describedby="DataTables_Table_0_info" role="grid">
                            <thead>
                               <tr>
                            <th class="sorting">Mức lương cơ bản</th>
                            <th class="sorting">Tiền hỗ trợ đồ dùng học tập</th>              
                            <th class="sorting">Mức hưởng tiền dân tộc thiểu số</th>              
                            <th class="sorting">Tiền hỗ trợ học phí</th> 
                            <th class="sorting">Tiền học phí</th>
                            <th class="sorting">Từ ngày</th>      
                            <th class="sorting">Đến ngày</th>         
                            <th class="sorting" width="15%">Chức năng</th>
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<div class="modal fade" id="modal-xoalop">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="modal-title-lophoc">Xoá thông tin</h4>

              </div>
              <div class="modal-body">
                <p>Bạn có muốn xoá thông tin này không?</p>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-danger" id="delLop">Xoá</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

@include('cauhinh.dialog')
<script type="text/javascript" src="{{asset('js/cauhinh.js')}}"></script>
@endsection