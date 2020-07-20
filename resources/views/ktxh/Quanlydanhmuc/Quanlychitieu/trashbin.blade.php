@extends('master')
@section('title','chỉ tiêu')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<h4 class="widget-title">Trash Chỉ tiêu</h4>
			</header>
			<hr class="widget-separator">
			<div class="widget-body">
				<div id="treelist_trash"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{asset('ktxh/Quanlydanhmuc/Quanlychitieu/trashchitieu.js')}}"></script>
@endsection

