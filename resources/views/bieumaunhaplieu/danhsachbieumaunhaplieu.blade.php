@extends('master')
@section('title','Danh sách biểu mẫu nhập liệu')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">Danh sách biểu mẫu nhập liệu</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <div id="gridBieumau"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="module" src="js/danhsachbieumaunhaplieu.js"></script>
@endsection