@extends('master')
@section('title','Quản lý chỉ tiêu')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<header class="widget-header">
				<h4 class="widget-title">Chỉ tiêu</h4>
			</header>
			<hr class="widget-separator">
			<div class="widget-body">
				<div class="row">
					<div class="col-md-8 col-xl-8">
						<div id="treelist"></div>
					</div>
					<div class="col-md-4 col-xl-4">
						<form id="form">
							<div class="form-group">
								<label for="">Mã chỉ tiêu</label>
								<input type="text" name="" id="code" class="form-control">
							</div>
							<div class="form-group">
								<label for="">Tên chỉ tiêu</label>
								<input type="text" name="" id="name" class="form-control">
							</div>
							<div class="form-group">
								<label for="">Đơn vị tính</label>
								<select id="unit" class="form-control"></select>
							</div>
							<div class="form-group">
								<label for="">Chỉ tiêu cha:</label>
								<select id="parent" class="form-control"></select>
							</div>

							<div class="toolbar">
								<button id="btnSave" class="btn btn-sm btn-primary"><i class="fas fa-save"></i>
									Lưu</button>
								<button id="btnUpdate" disabled class="btn btn-sm btn-primary"><i
										class="fas fa-edit"></i> Cập
									nhật</button>
								<button disabled id="btnCancel" class="btn btn-sm btn-danger"><i
										class="far fa-window-close"></i>
									Hủy</button>
								<button id="btnDelete" class="btn btn-sm btn-danger"> <i class="fa fa-trash"></i>
									Xóa</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript" src="{{asset('ktxh/Quanlydanhmuc/Quanlychitieu/chitieu.js')}}"></script>


@endsection