@extends('master')
@section('title','Cập nhật chỉ tiêu')
@section('content')
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
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
                        <h4 class="widget-title">Cập Nhật Chỉ Tiêu Đơn Vị Hành Chính</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Tên đơn vị:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->tendonvi }}
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Thuộc:</b> 
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->thuoc == 1 ? 'Sở ban ngành' : 'Thị xã/TP/Huyện' }}
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Số điện thoại:</b>
                            </div>
                            <div class="col-sm-10">
                                {{ $donvihanhchinh->sodienthoai }}
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Địa chỉ email:</b>
                            </div>
                            <div class="col-sm-10">
                            {{ $donvihanhchinh->email }}
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Địa chỉ liên hệ:</b>
                            </div>
                            <div class="col-sm-10">
                            {{ $donvihanhchinh->diachi }}
                            </div>
                        </div>
                        <div class="row m-h-sm">
                            <div class="col-sm-2">
                                <b>Mô tả:</b> 
                            </div>
                            <div class="col-sm-10">
                            {{ $donvihanhchinh->mota }}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">Mã Chỉ Tiêu</th>
                                        <th style="width: 55%">Tên Chỉ Tiêu</th>
                                        <th style="width: 20%">Đơn vị tính</th>
                                        <th style="width: 8%">Áp dụng</th>
										<th style="width: 12%">
											<div class="checkbox checkbox-primary m-0">
												<input type="checkbox" id="check-all">
												<label for="check-all"></label>
											</div>
										</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <form method="POST" action="/donvihanhchinh/{{ $donvihanhchinh->id }}/updatechitieu" id="loaisolieuForm" style="margin-bottom : 10px;">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="row" style="display:flex; justify-content: flex-start">
                                        <a href="/donvihanhchinh" class="btn mw-md btn-primary m-xs" style="display:flex; padding: 3px 16px"><i class="zmdi zmdi-arrow-left zmdi-hc-2x"></i><div style="padding: 5px; margin-left: 5px">Quay Lại</div></a>
                                            <button type="submit" class="btn mw-md btn-success m-xs">Lưu thay
                                                đổi</button>
                                        </div>
                                        <?php function TraverseNodesTree($chitieuNode, $nodeLevel, $chitieuArr) {
                                            if($chitieuNode == null || $chitieuNode->IsDelete == true) {
                                                return;
                                            }
                                            $checked = in_array($chitieuNode->id, $chitieuArr) ? 'checked' : '';
                                            $isEmpty = true;
                                            foreach($chitieuNode->chitieucon as $chitieucon) {
                                                    if($chitieucon->IsDelete == false){
                                                        $isEmpty = false;    
                                                    }
                                            }
                                            echo 
                                            '<tr id="chitieu-self-row-'.$chitieuNode->id.'" class="'.($chitieuNode->chitieucha != null ? ' chitieu-children-row-'.$chitieuNode->chitieucha->id : '').' ">
                                            <td style="vertical-align: middle">'.$chitieuNode->id.'</td>
                                            <td style="vertical-align: middle; display: flex">'.addSpanElement($nodeLevel).''.((count($chitieuNode->chitieucon) > 0 && !$isEmpty) ? '<div><i id="chitieu-right-caret-'.$chitieuNode->id.'" class="zmdi zmdi-hc-2x zmdi-caret-right chitieu-right-caret hidden-item"></i><i id="chitieu-down-caret-'.$chitieuNode->id.'" class="zmdi zmdi-hc-2x zmdi-caret-down chitieu-down-caret"></i></div>' : '').'<div style="padding: 4px; margin-left : 2px">'.$chitieuNode->tenchitieu.'</div></td>
                                            <td style="vertical-align: middle"></td>
                                            <td>
                                            <div class="checkbox checkbox-primary">
                                                <input '.$checked.' type="checkbox" value="'.$chitieuNode->id.'"
                                                    id="chitieu-checkbox-'.$chitieuNode->id.'" class="apdung-checkbox" name="chitieu[]" />
                                                <label for="chitieu-checkbox-'.$chitieuNode->id.'"></label>
                                            </div>
                                            </td>
											<td></td>
                                            </tr>';
                                            $nodeLevel++;
                                            for($i=0; $i<count($chitieuNode->chitieucon); $i++){
                                                TraverseNodesTree($chitieuNode->chitieucon[$i], $nodeLevel, $chitieuArr );
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
                                        $chitieuArr = array();
                                        foreach($donvihanhchinh->chitieus as $chitieu) {
                                            if(!$chitieu->IsDelete){
                                                array_push($chitieuArr, $chitieu->id);
                                            }
                                        }
                                        for($j = 0; $j < count($chitieusAll); $j++) {
                                            TraverseNodesTree($chitieusAll[$j], 0, $chitieuArr);
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
<script>
TreeViewServices('chitieu');
let checkAllInput = document.getElementById('check-all');
let apdungCheckboxes = [...document.getElementsByClassName('apdung-checkbox')];

checkAllInput.addEventListener('change', function() {
    if(this.checked) {
        for(let i = 0; i < apdungCheckboxes.length; i++) {
            apdungCheckboxes[i].checked = true;
        }
    } else {
        for(let i = 0; i < apdungCheckboxes.length; i++) {
            apdungCheckboxes[i].checked = false;
        }
    }
})
</script>
@endsection