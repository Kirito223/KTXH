@extends('master')
@section('title','Đơn Vị Hành Chính')
@section('content')


<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/donvihanhchinh" id="donvihanhchinhForm" style="margin-bottom : 10px;">
                <div class="widget">
                    <header class="widget-header">
                        @if(!empty(Session::get('success')))
                        <div class="alert alert-success">
                            <p class="text-success">{{ Session::get('success') }}</p>
                        </div>
                        @endif
                        @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <div class="alert alert-danger">
                            <p class="text-danger">{{ $error }}</p>
                        </div>
                        @endforeach
                        @endif
                        @if(!empty(Session::get('error')))
                        <div class="alert alert-danger">
                            <p class='text-danger'>{{ Session::get('error') }}</p>
                        </div>
                        @endif
                        <h4 class="widget-title">Danh Sách Đơn Vị Hành Chính</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade in" id="modelForCreateDonvihanhchinh" tabindex="-1" role="dialog"
                            aria-labelledby="modelForCreateDonvihanhchinhLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h3 class="modal-title" id="modelForCreateDonvihanhchinhLabel">Tạo mới ĐV hành
                                            chính
                                        </h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createDonvihanhchinhForm">
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="create-input-tendonvi">Tên Đơn Vị</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="create-input-tendonvi" placeholder="Nhập tên đơn vị">
                                                    <div class="row" style="display:flex; margin-top : 10px">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="thuoc"
                                                                id="create-input-thuoc-sobannganh" value="1">
                                                            <label class="form-check-label" for="inlineRadio1">Sở ban
                                                                ngành</label>
                                                        </div>
                                                        <div class="form-check form-check-inline"
                                                            style="margin-left: 10px">
                                                            <input class="form-check-input" type="radio" name="thuoc"
                                                                id="create-input-thuoc-huyen" value="2">
                                                            <label class="form-check-label" for="inlineRadio2">Thị
                                                                xã/TP/Huyện</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="create-input-donvihanhchinhcha">Đơn vị trực
                                                        thuộc</label>
                                                    <select class="form-control" id="create-input-donvihanhchinhcha"
                                                        name="donvihanhchinhcha">
                                                        <option value="none" selected>Không có</option>
                                                        @if(count($donvihanhchinhsAll))
                                                        @foreach($donvihanhchinhsAll as $donvihanhchinh)
                                                        <option value="{{ $donvihanhchinh->id }}">
                                                            {{ $donvihanhchinh->tendonvi }}</option>
                                                        @endforeach
                                                        @endif()
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="create-input-sodienthoai">Số điện thoại</label>
                                                    <input type="text" name="sodienthoai"
                                                        class="form-control form-control-sm"
                                                        id="create-input-sodienthoai" placeholder="Nhập số điện thoại">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="create-input-email">Email</label>
                                                    <input type="text" name="email" class="form-control"
                                                        id="create-input-email" placeholder="Nhập email">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-diachi">Địa chỉ</label>
                                                <input type="text" name="diachi" class="form-control form-control-sm"
                                                    id="create-input-diachi" placeholder="Nhập địa chỉ">
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-tinh">Tỉnh</label>
                                                <select class="form-control" id="create-input-tinh" name="tinh">
                                                    <option value="none" selected>Không có</option>
                                                    @if(count($tinh))
                                                    @foreach($tinh as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->_name }}</option>
                                                    @endforeach
                                                    @endif()
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-huyen">Quận/Huyện</label>
                                                <select class="form-control" id="create-input-huyen" name="huyen">
                                                    <option value="none" selected>Không có</option>

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-phuong">Xã/Phường</label>
                                                <select class="form-control" id="create-input-phuong" name="phuong">
                                                    <option value="none" selected>Không có</option>

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="create-input-mota">Mô tả</label>
                                                <input type="text" name="mota" class="form-control form-control-sm"
                                                    id="create-input-mota" placeholder="Nhập mô tả">
                                            </div>
                                        </form>
                                        <div class="alert alert-danger print-error-msg-on-create" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success mw-md" id="submit-button-for-create">Tạo
                                            mới</button>
                                        <button type="button" class="btn btn-danger mw-md" data-dismiss="modal"
                                            id="cancel-button-for-create">Hủy
                                            bỏ</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade in" id="deleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Xóa Đơn Vị Hành Chính</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa đơn vị hành chính này?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            id="button-confirm-delete">Xóa</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Model khoi tao  --}}
                        <div class="modal fade in" id="khoitaoModal" tabindex="-1" role="dialog"
                            aria-labelledby="khoitaoModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="khoitaoModalLabel">Khởi Tạo Dữ liệu Đơn Vị Hành
                                            Chính</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label for="khoitao-input-donvihanhchinhcha">Đơn vị nguồn</label>
                                        <select class="form-control" id="khoitao-input-donvihanhchinhcha"
                                            name="donvihanhchinhcha">
                                            @if(count($donvihanhchinhsAll))
                                            @foreach($donvihanhchinhsAll as $donvihanhchinh)
                                            <option value="{{ $donvihanhchinh->id }}">
                                                {{ $donvihanhchinh->tendonvi }}</option>
                                            @endforeach
                                            @endif()
                                        </select>
                                        <div class="alert alert-danger print-error-msg-on-khoitao" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success" id="button-confirm-khoitao">Khởi
                                            Tạo</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    ...
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="row" style="display:flex; justify-content: flex-start">
                                <div style="margin-left: auto">
                                    <button type="button" class="btn mw-md btn-success m-xs" id="open-create-modal">
                                        Tạo Mới</button>
                                </div>
                            </div>
                            <table class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">Tên đơn vị</th>
                                        <th class="hidden-item">Thuộc</th>
                                        <th class="hidden-item">Tên đơn vị cha</th>
                                        <th style="width: 10%">SĐT</th>
                                        <th style="width: 7%">Email</th>
                                        <th style="width: 19%">Địa chỉ</th>
                                        <th class="hidden-item">Địa bàn</th>
                                        <th class="hidden-item">Quận/Huyện</th>
                                        <th class="hidden-item">Xã/Phường</th>
                                        <th class="hidden-item">Mô tả</th>
                                        <th style="width: 5%; font-size: small">Người dùng</th>
                                        <th style="width: 5%; font-size: small">Chỉ tiêu</th>
                                        <th style="width: 5%; font-size: small">Phòng ban</th>
                                        <th style="width: 5%; font-size: small">Khởi tạo</th>
                                        <th style="width: 14%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <form method="POST" action="/loaisolieu" id="loaisolieuForm"
                                        style="margin-bottom : 10px;">
                                        @csrf
                                        {{ method_field('PUT') }}


                                        <?php function TraverseNodesTree($donvihanhchinhNode, $nodeLevel) {
                                            if($donvihanhchinhNode == null || $donvihanhchinhNode->isDelete == true) {
                                                return;
                                            }
                                            $isEmpty = true;
                                            foreach($donvihanhchinhNode->donvihanhchinhcon as $donvihanhchinhcon) {
                                                    if($donvihanhchinhcon->isDelete == false){
                                                        $isEmpty = false;    
                                                    }
                                            } 
                                           // dd($donvihanhchinhNode);
                                            echo 
                                            '<tr id="donvihanhchinh-self-row-'.$donvihanhchinhNode->id.'" class="'.($donvihanhchinhNode->donvihanhchinhcha != null ? 'hidden-item donvihanhchinh-children-row-'.$donvihanhchinhNode->donvihanhchinhcha->id : '').' ">
                                            <td style="vertical-align: middle;"><div style="display:flex">'.addSpanElement($nodeLevel).''.((count($donvihanhchinhNode->donvihanhchinhcon) > 0 && !$isEmpty) ? '<div><i id="donvihanhchinh-right-caret-'.$donvihanhchinhNode->id.'" class="zmdi zmdi-hc-2x zmdi-caret-right donvihanhchinh-right-caret"></i><i id="donvihanhchinh-down-caret-'.$donvihanhchinhNode->id.'" class="zmdi zmdi-hc-2x zmdi-caret-down hidden-item donvihanhchinh-down-caret"></i></div>' : '').'<div style="padding: 4px; margin-left : 2px" class="content-tendonvi">'.$donvihanhchinhNode->tendonvi.'</div></div></td>
                                            <td class="hidden-item">'.$donvihanhchinhNode->thuoc.'</td>
                                            <td class="hidden-item">'.($donvihanhchinhNode->donvihanhchinhcha != null ? $donvihanhchinhNode->donvihanhchinhcha->id : "none").'</td>
                                            <td style="vertical-align: middle">'.$donvihanhchinhNode->sodienthoai.'</td>
                                            <td style="vertical-align: middle">'.$donvihanhchinhNode->email.'</td>
                                            <td style="vertical-align: middle">'.$donvihanhchinhNode->diachi.'</td>
                                            <td class="hidden-item">'.$donvihanhchinhNode->diaban.'</td>
                                            <td class="hidden-item"></td>
                                            <td class="hidden-item">'.$donvihanhchinhNode->phuong.'</td>
                                            <td class="hidden-item">'.$donvihanhchinhNode->mota.'</td>
                                            <td style="vertical-align: middle"> 
                                            <a href="/donvihanhchinh/'.$donvihanhchinhNode->id.'/editusers" class="btn btn-primary btn-xs users-btn"
                                                    style="margin: 2px">
                                                    <i class="fa fa-users"></i>
                                            </a>
                                            </td>
                                            <td style="vertical-align: middle">
                                                <a href="/donvihanhchinh/'.$donvihanhchinhNode->id.'/editchitieu" class="btn btn-primary btn-xs loaisolieu-btn"
                                                    style="margin: 2px">
                                                    <i class="fa fa-book"></i>
                                                </a>
                                            </td>
                                            <td style="vertical-align: middle">
                                            <a href="donvihanhchinh/'.$donvihanhchinhNode->id.'/editphongban" class="btn btn-primary btn-xs"
                                                    style="margin: 2px">
                                                    <i class="fa fa-building"></i>
                                            </a>
                                            </td>
                                            <td style="vertical-align: middle">
                                            <button type="button" class="btn btn-primary btn-xs khoitao-btn"
                                                    style="margin: 2px" id="khoitao-button-'.$donvihanhchinhNode->id.'">
                                                    <i class="fa fa-database"></i>
                                                </button>
                                            </td>
                                            <td style="vertical-align: middle">
                                                <button onclick="themDonvi(this);" data-id="'.$donvihanhchinhNode->id.'" type="button" class="btn btn-success btn-xs detail-create-btn"
                                                    style="margin: 2px" id="detail-create-btn-'.$donvihanhchinhNode->id.'">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                                <button onclick="suaDonvi(this)" type="button" class="btn btn-primary btn-xs edit-btn"
                                                    style="margin: 2px" data-id="'.$donvihanhchinhNode->id.'" id="detail-edit-btn-'.$donvihanhchinhNode->id.'">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button onclick="xoaDonvi(this)" type="button" class="btn btn-danger btn-xs button-delete"
                                                    style="margin: 2px" data-id="'.$donvihanhchinhNode->id.'" id="delete-button-'.$donvihanhchinhNode->id.'">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </td>
                                            </tr>';
                                            $nodeLevel++;
                                            for($i=0; $i<count($donvihanhchinhNode->donvihanhchinhcon); $i++){
                                                TraverseNodesTree($donvihanhchinhNode->donvihanhchinhcon[$i], $nodeLevel );
                                            }
                                            return;           
                                        }
                                        function addSpanElement($number) {
                                            $spans = '';
                                            if($number > 0) 
                                            {
                                                for($i = 0; $i< $number; $i++) {
                                                    $spans .= '<span style="color:red;margin-right:1.25em;">&nbsp;</span>';
                                                }
                                            }
                                            return $spans;
                                        }
                                         
                                        for($j = 0; $j < count($donvihanhchinhs); $j++) {
                                            TraverseNodesTree($donvihanhchinhs[$j], 0);
                                        }
                                        ?>
                                    </form>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
</section>

<script type="text/javascript" src="{{ URL::asset('js/TreeViewService.js') }}"></script>
<script src="{{ URL::asset('js/donvihanhchinh.js') }}">

</script>

@endsection