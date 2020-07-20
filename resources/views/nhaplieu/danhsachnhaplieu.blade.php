@extends('master')
@section('title','Quản lý biểu mẫu nhập liệu')
@section('content')

<div class="col-md-12">
    <div class="widget p-lg">
        <div class="widget">
            <header class="widget-header">
                <h4 class="widget-title">Quản lý biểu mẫu</h4>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                <div id="GridBieumau"></div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="js/danhsachbieumau.js"></script>
@endsection