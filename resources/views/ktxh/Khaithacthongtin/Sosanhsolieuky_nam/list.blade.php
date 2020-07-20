@extends('master')
@section('title','So sánh số liệu kỳ,năm')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<h5 class="widget-title">So sánh số liệu theo kỳ & năm</h5>
			</header>
			<hr class="widget-separator">
			<div class="widget-body">
				<div class="row">
					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Số liệu của địa bàn : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-4">
									<select type="text" id="tinh" placeholder="Tỉnh/Thành phố"
										class="form-control"></select>
								</div>
								<div class="col-md-4">
									<select type="text" id="huyen" placeholder="Quận huyện"
										class="form-control"></select>
								</div>
								<div class="col-md-4">
									<select type="text" id="xa" placeholder="Xã/Phường" class="form-control"></select>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">So sánh số liệu của đơn vị : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-8">
									<select type="text" placeholder="Số liệu đơn vị" id="solieudonvi"
										class="form-control"></select>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">So sánh số liệu dựa theo biểu mẫu : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-12">
									<select type="text" id="sosanhsl" placeholder="So sánh SL theo biểu mẫu"
										class="form-control"></select>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Dựa trên loại số liệu : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-sm-8">
									<select id="loaisolieu" class="form-control" tabindex="-1" aria-hidden="true">
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Kiểu so sánh : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-4">
									<div class="checkbox checkbox-primary">
										<input type="checkbox" id="checkbox1">
										<label for="checkbox-demo-3">Cùng kỳ giữa các năm</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="checkbox checkbox-primary">
										<input type="checkbox" id="checkbox2">
										<label for="checkbox-demo-3">Các kỳ trong cùng 1 năm</label>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" id="kysl" style="margin-bottom: 10px;display: none;">
						<h5 class="col-sm-3 col-form-label">So sánh theo kỳ số liệu : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-8">
									<select type="text" id="kysolieu" placeholder="So sánh kỳ số liệu"
										class="form-control" style="width: 420px"></select>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" id="thangss" style="margin-bottom: 10px;display: none;">
						<h5 class="col-sm-3 col-form-label">Tháng so sánh : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<div class='input-group date' id='datepicker1'>
											<input type='text' class="form-control" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" id="namss" style="margin-bottom: 10px;display: none;">
						<h5 class="col-sm-3 col-form-label">Các năm cần so sánh : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-8">
									<div class="input-daterange input-group">
										<span class="input-group-addon">Từ</span>
										<input type="text" class="form-control-sm form-control" name="start" value=""
											id="datepicker3">
										<span class="input-group-addon">Đến</span>
										<input type="text" class="form-control-sm form-control" name="end" value=""
											id="datepicker4">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="timkiem"><i class='fa fa-search'></i> Tìm
							kiếm</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="row" style="display: none" id="tablegrid">
	<div class="widget">
		<hr class="widget-separator">
		<div class="widget-body">
			<div id="treelist"></div>
		</div>
	</div>
</div>




<div id="bieudo" class="modal fade in" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="demo-container">
						<div id="chart"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm" id="dong" data-dismiss="modal">Đóng</button>
			</div>
		</div>
	</div>
</div>




<script type="text/javascript" src="ktxh/Khaithacthongtin/Sosanhsolieuky_nam/sosanhsolieuky.js"></script>

@endsection