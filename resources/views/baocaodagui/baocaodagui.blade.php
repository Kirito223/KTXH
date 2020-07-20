@extends('master')
@section('title','Danh sách báo cáo đã gửi')
@section('content')
<div class="col-md-12" id="baocaodagui">
    <div class="widget p-lg">
        <h4 class="m-b-lg">Danh sách báo cáo</h4>
        {{-- <div id="GridBaocao"></div> --}}
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Số hiệu</th>
                            <th>Tiêu đề</th>
                            <th>Kỳ báo cáo</th>
                            <th>Ngày báo cáo</th>
                            <th>Người gửi</th>
                            <th>Xem báo cáo</th>
                            {{-- <th>Duyệt báo cáo</th> --}}
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($baocao as $item)
                        <tr>
                            <td scope="row">{{$item->sohieu}}</td>
                            <td>{{$item->tieude}}</td>
                            <td>{{$item->tenky}}</td>
                            <td scope="row">{{$item->ngaygui}}</td>
                            <td>{{$item->tentaikhoan}}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-xs btn-view"
                                    data-id="{{$item->baocao}}">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </button>
                            </td>
                            {{-- <td>
                                <button type="button" class="btn btn-success btn-xs btn-tick" data-id="{{$item->id}}">
                            <i class="fas fa-check-circle"></i>
                            </button>
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12" style="text-align: center">
                    {{ $baocao->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="js/confirmreport.js"></script>
@endsection