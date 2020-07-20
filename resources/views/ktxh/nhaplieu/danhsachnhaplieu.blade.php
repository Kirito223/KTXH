@extends('master')
@section('title','Quản lý biểu nhập liệu báo cáo')
@section('content')

<div class="col-md-12">
    <div class="widget p-lg">
        <div class="widget">
            <header class="widget-header">
                <h4 class="widget-title">Quản lý nhập liệu báo cáo</h4>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                <div id="GridBieumau"></div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="js/KTXH/danhsachbieumau.js"></script>
@endsection