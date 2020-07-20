@extends('master')

@section('title','danh mục quản trị viên')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<div class="row">
  <div class="col-lg-12">
    <div class="ibox ">
      <div class="ibox-title">
        <div class="row">
          <div class="col-lg-10"> <h5>Danh sách quản trị viên</h5></div>
          <div class="col-lg-2">
            <button type="button" class="btn btn-w-m btn-success" data-toggle="modal" id="addroleUser" style="float: right;">Thêm mới</button>
          </div>
        </div>

      </div>
      <div class="ibox-content">
        <div class="table-responsive">
          <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
            <table class="table table-striped table-bordered table-hover dataTables-example dataTable" id="table-roleUser" aria-describedby="DataTables_Table_0_info" role="grid">
              <thead>
               <tr>
                <th class="sorting">ID</th>                 
                <th class="sorting" >Tên user</th>  
                <th class="sorting" >Email</th>   
                <th class="sorting" >Tên quyền</th>    
                <th class="sorting" >Thuộc huyện</th>                          
                <th class="sorting"  >Ngày tạo</th>
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




<!-- modal-xoa roleUser -->
<div class="modal fade" id="modal-xoaadminUser">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal-title-roleUser">Xoá user</h4>
      </div>
      <div class="modal-body">
        <p>Bạn có muốn xoá user này không?</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-danger" id="deladminUser">Xoá</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>




<div class="modal inmodal fade " id="modal-themadminUser" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Thêm mới user</h4>       
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="form-group">
              <label for="exampleInputPassword1">Tên user:</label>
              <input id="tenuser" class="form-control" type="text">
            </div>



            <div class="form-group">
              <label for="exampleInputPassword1">Password:</label>
              <input id="password" class="form-control" type="password">        
            </div>
            


            <div class="form-group mg-b-10-force">
              <label class="form-control-label">Tỉnh/thành :</label>
              <select class="form-control select2 select2-hidden-accessible addtinh" data-placeholder="Choose country" tabindex="-1" aria-hidden="true" id="tinhthanh">

              </select>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Email:</label>
              <input id="email" class="form-control" type="email" >           
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">Nhập lại password:</label>
              <input id="repassword" class="form-control" type="password" >           
            </div>           

            <div class="form-group mg-b-10-force">
              <label class="form-control-label">Quận/huyện :</label>
              <select class="form-control select2 select2-hidden-accessible addhuyen" data-placeholder="Choose country" tabindex="-1" aria-hidden="true" id="quanhuyen">

              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
           <div class="form-group col-md-12">
            <label for="quyen" class="control-label required">Quyền:</label>
            <select class="form-control" id="quyen" multiple="multiple"></select>
          </div> 
        </div>
      </div>

    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-w-m btn-default" data-dismiss="modal">Đóng</button>
      <button type="button" class="btn btn-w-m btn-primary" id="saveadminUser">Lưu</button>
    </div>
  </div>
</div>
</div>







<div class="modal inmodal fade " id="modal-editadminUser" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Edit User</h4>       
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="tabs-container">
              <ul class="nav nav-tabs" role="tablist">
                <li><a class="nav-link active show" data-toggle="tab" href="#tab-1"> Thông tin user</a></li>
                <li><a class="nav-link" data-toggle="tab" href="#tab-2">Đổi mật khẩu</a></li>
              </ul>
              <div class="tab-content">
                <div role="tabpanel" id="tab-1" class="tab-pane active show">
                  <div class="panel-body">
                    <form role="form"><input name="_token" type="hidden">
                      <div class="form-group col-md-12">
                        <label for="tenuser" class="control-label required">Tên user:</label>
                        <input class="form-control" id="tenuser1">
                      </div>
                      <div class="form-group col-md-12">
                        <label for="email" class="control-label required">Email:</label>
                        <input class="form-control" id="email1">
                      </div>
                      <div class="form-group col-md-12">
                        <label for="quyen" class="control-label required">Quyền:</label>
                        <select class="form-control" id="quyen1" multiple="multiple"></select>
                      </div>
                      <div class="clearfix"></div>
                      <div class="form-group col-12">
                        <div class="form-actions">
                          <div class="btn-set text-center">
                           <div class="modal-footer">
                             <button type="button" class="btn btn-w-m btn-default" data-dismiss="modal">Đóng</button>
                             <button type="button" class="btn btn-w-m btn-primary" id="saveadminUser1">Lưu</button>                       
                           </div>
                         </div>
                       </div>
                     </div>
                   </form>

                 </div>
               </div>
               <div role="tabpanel" id="tab-2" class="tab-pane">
                <div class="panel-body">
                  <form ><input name="_token" type="hidden">
                    <div class="form-group col-md-12">
                      <label for="password" class="control-label required">Mật khẩu mới:</label>
                      <input class="form-control" type="password" name="password" id="password1">
                      <div class="pwstrength_viewport_progress">
                        <span class="hidden">Password Strength</span></div>
                      </div>
                      <div class="form-group col-md-12">
                        <label for="password_confirmation" class="control-label required">Nhập lại mật khẩu</label>
                        <input class="form-control" type="password" name="repassword" id="repassword1">
                      </div>
                      <div class="clearfix"></div>
                      <div class="form-group col-12">
                        <div class="form-actions">
                          <div class="btn-set text-center">
                           <div class="modal-footer ">
                            <button type="button" class="btn btn-w-m btn-default" data-dismiss="modal">Đóng</button>
                             <button type="button" class="btn btn-w-m btn-primary" id="saveadminpassword">Lưu</button>
                          
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>


          </div>
        </div>
      </div>

    </div>


  </div>
</div>
</div>


 




<script src="{{asset('js/roleUser.js')}}"></script>
@endsection