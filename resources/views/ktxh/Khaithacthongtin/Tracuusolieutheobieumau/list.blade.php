@extends('master')
@section('title','Tra cứu số liệu theo biểu mẫu')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<h5 class="widget-title">Tra cứu số liệu theo biểu mẫu</h5>
			</header>
			<hr class="widget-separator">
			<div class="widget-body">
				<div class="row">


					<div class="form-group row" id="slcdb" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Số liệu của địa bàn : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-4">
									<select type="text" id="tinh" placeholder="tinh" class="form-control"></select>
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
						<h5 class="col-sm-3 col-form-label">Đơn vị thu thập : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-8">
									<select type="text" id="donvithuthap" class="form-control"></select>
								</div>
							</div>
						</div>
					</div>


					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Số liệu của kỳ : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-8">
									<select type="text" id="solieucuaky" class="form-control"></select>
								</div>
							</div>
						</div>
					</div>



					<div class="form-group row" id="tss" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Chọn tháng : </h4>
							<div class="col-sm-9" style="height: 35px">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<div class='input-group date' id='chonthang'>
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



					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Số liệu của năm : </h4>
							<div class="col-sm-9" style="height: 35px">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<div class='input-group date' id='solieucuanam'>
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

					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Chọn biểu mẫu số liệu: </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-4">
									<select type="text" id="solieubieumau" class="form-control"
										multiple="multiple"></select>
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


<div id="sosanhtheobieumau" class="modal fade in" tabindex="-1" role="dialog">
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




<script type="text/javascript" src="ktxh/Khaithacthongtin/Tracuusolieutheobieumau/tracuusolieubieumau.js">
	@endsection