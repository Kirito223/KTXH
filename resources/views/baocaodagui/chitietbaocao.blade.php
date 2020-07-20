@extends('master')
@section('title','Xem chi tiết báo cáo')
@section('content')
<div class="col-md-12">
    <div class="widget p-lg">
        <h4 class="m-b-lg">Chi tiết báo cáo</h4>
        @php
        $baocao = $report->bieumau;
        $chitiet = $report->chitiet;
        $bieusolieu = json_decode($chitiet->cacbieusolieu);
        @endphp
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-md-2">Kỳ báo cáo</label>
                <p class="col-md-10">{{$baocao->tenky}}</p>
            </div>
            <div class="form-group">
                <label class="col-md-2">Số/ký hiệu báo cáo</label>
                <p class="col-md-10">{{$baocao->sohieu}}</p>
            </div>
            <div class="form-group">
                <label class="col-md-2">Tiêu đề báo cáo</label>
                <p class="col-md-10">{{$baocao->tieude}}</p>
            </div>
            <div class="form-group">
                <label class="col-md-2">Năm báo cáo</label>
                <p class="col-md-10">{{$baocao->nambaocao}}</p>
            </div>
            <div class="form-group">
                <label class="col-md-2">Các đơn vị nhận</label>
                <div class="col-md-10"><span>{{$unit}}</span></div>
            </div>
            <div class="form-group">
                <label class="col-md-2">Các biểu số liệu</label>
                <div class="col-md-10">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Số hiệu</th>
                                <th>Tên biểu</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bieusolieu as $item)
                            <tr>
                                <td scope="row">{{$item->sohieu}}</td>
                                <td>{{$item->tenbieumau}}</td>
                                <td><button class="btn btn-primary btn-xs btn-view" data-id="{{$item->id}}"><i
                                            class="fas fa-eye"></i></button>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2">Tập tin đính kèm</label>

                @if ($baocao->file == null)
                <span class="col-md-10" style="color: dodgerblue">Không có tệp tin đính kèm</span>
                @else
                <ul class=" col-md-10">
                    @foreach ($collection as $item)
                    <li><a href="{{$item}}">{{$item}}</a></li>
                    @endforeach
                </ul>
                @endif



            </div>
            <div class="form-group">
                <label class="col-md-2">Nội dung báo cáo</label>
                <div class="col-md-10" id="content">
                    @php
                    echo $chitiet->noidung
                    @endphp
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2">Ngày ký</label>
                @if ($baocao->ngayky == null)
                <p class="col-md-10">Chưa ký</p>
                @else
                {{$baocao->ngayky}}
                @endif
            </div>
            <div class="form-group">
                <label class="col-md-2">Người ký</label>
                @if ($baocao->nguoiky == null)
                <p class="col-md-10">Chưa ký</p>
                @else
                {{$baocao->nguoiky}}
                @endif

            </div>
            <div class="form-group" style="text-align: center;">
                <button id="btnTrove" class="btn btn-sm btn-info"><i class="fa fa-backward" aria-hidden="true"></i>
                    Trở
                    về</button>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="modelReviewReport" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xem trước báo cáo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div id="Report" style="height: 570px; overflow: auto"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="js/detailReport.js"></script>
@endsection