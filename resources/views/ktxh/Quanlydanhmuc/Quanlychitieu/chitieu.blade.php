@extends('master')
@section('title','chỉ tiêu')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<h4 class="widget-title">Chỉ tiêu</h4>
			</header>
			<hr class="widget-separator">
			<div class="widget-body">
				<div id="treelist"></div>
			</div>
		</div>
	</div>
</div>




<!-- thêm con -->
<div id="chitieuthemcon" class="modal fade in" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span>
				</button>
				<!-- <h4 class="modal-title">Thêm chỉ tiêu con</h4> -->
			</div>
			<form action="#" id="themcon">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="category_name">Tên chỉ tiêu</label>
								<input type="text" id="tenchitieu" class="form-control" placeholder="Tên chỉ tiêu">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="category_name">Idcha</label>
								<select class="form-control" id="idcha"></select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="category_name">Đơn vị tính</label>

								<select class="form-control" id="donvi"></select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-sm" id="luucon">Lưu</button>
					<button type="button" class="btn btn-danger btn-sm" id="huycon" data-dismiss="modal">Hủy</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- sửa dòng -->
<div id="chitieusua" class="modal fade in" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span>
				</button>
				<!-- <h4 class="modal-title">Thêm chỉ tiêu con</h4> -->
			</div>
			<form action="#" id="sua">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="category_name">Tên chỉ tiêu</label>
								<input type="text" id="tenchitieusua" class="form-control" placeholder="Tên chỉ tiêu">
								<input type="text" id="idsua" class="form-control" style="display: none;">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="category_name">Idcha</label>
								<select class="form-control" id="idchasua"></select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="category_name">Đơn vị tính</label>

								<select class="form-control" id="donvisua"></select>

							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-sm" id="luusua">Lưu</button>
					<button type="button" class="btn btn-danger btn-sm" id="huyconsua" data-dismiss="modal">Hủy</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- thêm mới -->
<div id="chitieuthem" class="modal fade in" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span>
				</button>
				<!-- <h4 class="modal-title">Thêm chỉ tiêu con</h4> -->
			</div>
			<form action="#" id="themmoi">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="category_name">Tên chỉ tiêu</label>
								<input type="text" id="tenchitieuthem" class="form-control" placeholder="Tên chỉ tiêu">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="category_name">Đơn vị tính</label>

								<select class="form-control" id="donvithem"></select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-sm" id="luuthem">Lưu</button>
					<button type="button" class="btn btn-danger btn-sm" id="huythem" data-dismiss="modal">Hủy</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{asset('ktxh/Quanlydanhmuc/Quanlychitieu/chitieu.js')}}"></script>


@endsection