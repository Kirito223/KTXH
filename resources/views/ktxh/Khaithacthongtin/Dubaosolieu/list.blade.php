@extends('master')
@section('title','Dự báo số liệu')
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



					<div class="form-group row" id="slcdb" style="margin-bottom: 10px" hidden>
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
						<h5 class="col-sm-3 col-form-label">Số liệu của địa bàn: </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-8">
									<select type="text" id="donvibaocao" class="form-control"></select>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Kỳ số liệu : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-4">
									<select type="text" id="kysolieu" class="form-control"></select>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Năm : </h5>
						<div class="col-sm-9" style="height: 35px">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<div class='input-group date'>
											<input type='text' class="form-control" id='nam' />
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
						<h5 class="col-sm-3 col-form-label">Loại số liệu : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-8">
									<select type="text" id="loaisolieu" class="form-control"></select>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Độ dài dãy số liệu : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-8">
									<input type="number" id="dodaidaysolieu" class="form-control">
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Tính toán dựa trên : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-4">
									<div class="checkbox checkbox-primary">
										<input type="checkbox" id="checkbox1">
										<label for="checkbox-demo-3">Số liệu cùng kỳ của các năm trước</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="checkbox checkbox-primary">
										<input type="checkbox" id="checkbox2">
										<label for="checkbox-demo-3">Số liệu của các kỳ trước</label>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="form-group row" style="margin-bottom: 10px">
						<h5 class="col-sm-3 col-form-label">Chọn chỉ tiêu : </h5>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-md-8">
									<select type="text" id="chitieu" class="form-control"></select>
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



<div id="bieudo" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="title"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
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




<script type="text/javascript" src="/ktxh/Khaithacthongtin/Dubaosolieu/dubaosolieu.js"></script>
@endsection