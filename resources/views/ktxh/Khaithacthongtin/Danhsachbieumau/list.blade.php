@extends('master')
@section('title','Danh sách biểu mẫu')
@section('content')



<div class="row">
	<div class="widget">
		<header class="widget-header">
			<h4 class="widget-title">Danh sách biểu mẫu</h4>
		</header>
		<hr class="widget-separator">
		<div class="widget-body">
			<div class="row">
				<div class="col-lg-6">
					<form action="#" class="form-horizontal">
						<div class="form-group">
							<label for="select2-demo-1" class="col-sm-4 control-label">Ngày quyết định :</label>
							<div class="col-sm-8">
								<div class="input-daterange input-group" id="datepicker">
									<span class="input-group-addon">Từ</span>
									<input type="text" class="form-control-sm form-control" name="start" value=""
										id="datepicker1">
									<span class="input-group-addon">Đến</span>
									<input type="text" class="form-control-sm form-control" name="end" value=""
										id="datepicker2">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="select2-demo-1" class="col-sm-4 control-label">Tên biểu mẫu :</label>
							<div class="col-sm-8">
								<select id="tenbieumau" class="form-control" tabindex="-1" aria-hidden="true">
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="select2-demo-2" class="col-sm-4 control-label">Cơ quan đào tạo :</label>
							<div class="col-sm-8">
								<select id="coquandaotao" class="form-control" tabindex="-1" aria-hidden="true">
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="select2-demo-2" class="col-sm-4 control-label">Loại biểu mẫu :</label>
							<div class="col-sm-8">
								<select id="loaibieumau" class="form-control" tabindex="-1" aria-hidden="true">
									<option></option>
									<option value="1">biểu mẫu nhập liệu</option>
									<option value="2">biểu mẫu số liệu báo cáo</option>
								</select>
							</div>
						</div>
					</form>
				</div>



				<div class="col-lg-6">
					<form action="#" class="form-horizontal">
						<div class="form-group">
							<label for="select2-demo-1" class="col-sm-4 control-label">Ngày tạo :</label>
							<div class="col-sm-8">
								<div class="input-daterange input-group" id="datepicker">
									<span class="input-group-addon">Từ</span>
									<input type="text" class="form-control-sm form-control" name="start" value=""
										id="datepicker3">
									<span class="input-group-addon">Đến</span>
									<input type="text" class="form-control-sm form-control" name="end" value=""
										id="datepicker4">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="select2-demo-1" class="col-sm-4 control-label">Biểu mẫu số :</label>
							<div class="col-sm-8">
								<input type="number" name="" class="form-control" id="bieumauso">
							</div>
						</div>
						<div class="form-group">
							<label for="select2-demo-2" class="col-sm-4 control-label">Quyết định số :</label>
							<div class="col-sm-8">
								<input type="number" class="form-control" id="quyetdinhso">
							</div>
						</div>
					</form>
				</div>
			</div>


		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" id="timkiem"><i class='fa fa-search'></i> Tìm kiếm</button>
		</div>
	</div>
</div>



<div class="row" style="display: none" id="tablegrid">
	<div class="widget">
		<hr class="widget-separator">
		<div class="widget-body">
			<div id="gridContainer"></div>
		</div>
	</div>
</div>


<script type="text/javascript" src="ktxh/Khaithacthongtin/Danhsachbieumau/danhsachbieumau.js"></script>
@endsection