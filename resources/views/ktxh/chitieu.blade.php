@extends('master')
@section('title','chỉ tiêu')
@section('content')



<div class="col-md-12">
	<div class="widget p-lg">
		<h4 class="m-b-lg">Chỉ tiêu</h4>
		<div id="TreeGridContainer"></div>

	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelThemchitieu" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Thêm chỉ tiêu</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					Add rows here
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
	$('#exampleModal').on('show.bs.modal', event => {
		var button = $(event.relatedTarget);
		var modal = $(this);
		// Use above variables to manipulate the DOM
		
	});
</script>

<script type="text/javascript" src="{{asset('ktxh/chitieu.js')}}"></script>


@endsection