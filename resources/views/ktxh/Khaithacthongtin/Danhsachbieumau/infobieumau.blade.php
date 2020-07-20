@extends('master')
@section('title','Chi tiết biểu mẫu')
@section('content')
<div class="row">
	<div class="widget">
		<header class="widget-header">
			<h4 class="widget-title">Chi tiết biểu mẫu</h4>
		</header>
		<hr class="widget-separator">
		<div class="widget-body">
			<div class="row">
				<div class="col-lg-4">
					<form action="#" class="form-horizontal">
						<div class="form-group">
							<label for="select2-demo-1" class="col-sm-4 control-label">Biểu mẫu số :</label>
							<div class="col-sm-8">
								@foreach($data as $bieumauso)
								<input id="bieumauso" class="form-control" value="{{ $bieumauso->id }}"
									disabled="disabled">
								@endforeach
							</div>
						</div>
						<div class="form-group">
							<label for="select2-demo-2" class="col-sm-4 control-label">Quyết định số :</label>
							<div class="col-sm-8">
								@foreach($data as $quyetdinhso)
								<input id="quyetdinhso" class="form-control" value="{{ $quyetdinhso->soquyetdinh }}"
									disabled="disabled">
								@endforeach
							</div>
						</div>
						<div class="form-group">
							<label for="select2-demo-2" class="col-sm-4 control-label">Ngày :</label>
							<div class="col-sm-8">
								@foreach($data as $ngayquyetdinh)
								<?php
								$date=date_create($ngayquyetdinh->ngayquyetdinh);
								$dd = date_format($date,"d-m-Y");
								?>
								<input id="ngayquyetdinh" class="form-control" value="{{ $dd }}" disabled="disabled">
								@endforeach
							</div>
						</div>
					</form>
				</div>


				<div class="col-lg-4">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h5 class="panel-title" style="text-align: center;font-size: 13px">Báo cáo chỉ số chỉ tiêu
								về công tác tư pháp</h5>
						</div>
						<div class="panel-body">
							<h5 style="text-align: center;">Báo cáo Tháng,...tháng,Quý,...tháng,Năm,không định kỳ</h5>
						</div>
					</div>
				</div>


				<div class="col-lg-4">
					<form action="#" class="form-horizontal">
						<div class="form-group">
							<label for="select2-demo-1" class="col-sm-4 control-label">Ngày tạo :</label>
							<div class="col-sm-8">
								@foreach($data as $ngaytao)
								<?php
								$date=date_create($ngaytao->create_at);
								$dd = date_format($date,"d-m-Y");
								?>
								<input id="ngaytao" class="form-control" value="{{ $dd }}" disabled="disabled">
								@endforeach
							</div>
						</div>
						<div class="form-group">
							<label for="select2-demo-2" class="col-sm-4 control-label">Đơn vị đào tạo :</label>
							<div class="col-sm-8">
								@foreach($data as $donvidaotao)
								<input id="coquandaotao" class="form-control" value="{{ $donvidaotao->tenphongban }}"
									disabled="disabled">
								@endforeach
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row" id="tablegrid">
	<div class="widget">
		<hr class="widget-separator">
		<div class="widget-body">
			<div id="treelist"></div>
		</div>
		<div class="row" style="display: flex;
		justify-content: center;
		align-items: center;">
			<button type="button" id="trolai" class="btn btn-warning btn-sm">Trở lại</button>
		</div>
	</div>
</div>





<script type="text/javascript" src="ktxh/Khaithacthongtin/Danhsachbieumau/infobieumau.js"></script>








@endsection