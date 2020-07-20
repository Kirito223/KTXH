@extends('master')
@section('title','Thông Báo')
@section('content')
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/thongbao" id="thongbaoForm" style="margin-bottom : 10px;">
                @csrf
                {{ method_field('PUT') }}
                <div class="widget">
                    <header class="widget-header">
                        @if(!empty(Session::get('success')))
                        <div class="alert alert-success">
                            <p class="text-success">{{ Session::get('success') }}</p>
                        </div>
                        @endif
                        @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <div class="alert alert-danger">
                            <p class="text-danger">{{ $error }}</p>
                        </div>
                        @endforeach
                        @endif
                        @if(!empty(Session::get('error')))
                        <div class="alert alert-danger">
                            <p class='text-danger'>{{ Session::get('error') }}</p>
                        </div>
                        @endif
                        <h4 class="widget-title">Danh Sách Thông Báo</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade in" id="modelForDetailsThongbao" tabindex="-1" role="dialog"
                            aria-labelledby="detailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForDetailsThongbaoLabel">Chi Tiết Thông Báo
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row no-gutter p-sm">
                                            <div>
                                                <h3 class="widget-title fz-lg text-primary m-b-sm" id="details-title">
                                                </h3>
                                                <small>Từ ngày: <small><small id="details-start-day"></small> -
                                                        <small>Đến ngày: </small><small id="details-end-day"></small>
                                                        <p class="m-b-lg" id="details-content" style="margin-top: 15px;padding: 5px; border-style:dotted; border-width: 1px">
                                                        </p>
                                            </div>
                                            <span><b>Tải tài liệu :</b></span><i class="fa fa-file-pdf-o"
                                                style="margin-left: 10px"></i> - <span><a href="javascript:void(0);"
                                                    id="details-download-doc" class="hidden-item">Tên tài
                                                    liệu</a></span><span id="details-non-donwnload-doc"
                                                class="text-danger">Không có tài liệu đính kèm</span>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">Đóng
                                            Lại</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table no-footer" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Tiêu Đề Thông Báo</th>
                                        <th class="hidden-item">Ngày bắt đầu</th>
                                        <th class="hidden-item">Ngày kết thúc</th>
										<th style="width: 20%">Đơn vị gửi</th>
                                        <th style="width: 20%">Thời gian gửi</th>
                                        <th class="hidden-item">Nội Dung</th>
                                        <th class="hidden-item">Tập Tin</th>
                                        <th style="width: 20%">Tình Trạng</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-thongbao">
                                    @if(count($thongbaos) > 0)
                                    @foreach($thongbaos as $thongbao)
                                    <tr id="thongbao{{ $thongbao->id }}" class="row-thongbao">
                                        <td style="vertical-align: middle"><a class="details-btn"
                                                id="details-btn-{{ $thongbao->id }}"
                                                href="javascript:void(0);">{{ $thongbao->tieude }}</a></td>
                                        <td class="hidden-item">{{ $thongbao->ngaybatdau }}</td>
                                        <td class="hidden-item">{{ $thongbao->ngayketthuc }}</td>
                                        <td style="vertical-align: middle">{{ $thongbao->pivot->donvigui }}</td>
                                        <td style="vertical-align: middle">{{ date("H:i:s d/m/Y", strtotime($thongbao->pivot->thoigiangui)) }}</td>
                                        <td class="hidden-item" style="vertical-align: middle">{{ $thongbao->noidung }}
                                        </td>
                                        <td style="vertical-align: middle" class="hidden-item">{{ $thongbao->taptin }}</td>
                                        <td>
                                            <span class="label {{ $thongbao->pivot->isSeen == 1 ? 'label-success' : 'label-danger' }}">{{$thongbao->pivot->isSeen == 1 ? 'Đã xem' : 'Chưa xem'}}</span>
                                        </td> 
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            {{ $thongbaos->links() }}
                        </div>
                    </div>
            </form>
        </div>
    </div>
</section>

<!-- <script type="text/javascript" src="{{ URL::asset('js/thongbao.js') }}"></script> -->
<!-- startDayId, endDayId, contentId, downloadBtnId, nonDownloadId -->
<script>

function generateDetailsButtonsEvent(detailsBtnClassName) {
        let titleElement = document.getElementById('details-title');
        let startDayElement = document.getElementById('details-start-day');
        let endDayElement = document.getElementById('details-end-day');
        let contentElement = document.getElementById('details-content');
        let downloadBtnElement = document.getElementById('details-download-doc');
        let nonDownloadElement = document.getElementById('details-non-donwnload-doc');
        let detailsButtons = [...document.getElementsByClassName(detailsBtnClassName)];
        let id;
        console.log(detailsButtons);
        for (let i = 0; i < detailsButtons.length; i++) {
            detailsButtons[i].addEventListener('click', function(e) {
                e.preventDefault();
                $('#modelForDetailsThongbao').modal('show');
                let idArr = this.id.split('-');
                id = idArr[idArr.length - 1];
				let detailsButton = this;
                let parentRow = this.closest('tr');
                titleElement.innerHTML = parentRow.children[0].innerHTML;
                startDayElement.innerHTML = parentRow.children[1].innerHTML;
                endDayElement.innerHTML = parentRow.children[2].innerHTML;
                contentElement.innerHTML = parentRow.children[5].innerHTML;
                if(parentRow.children[6].innerHTML.length > 0) {
                    let filename = parentRow.children[6].innerHTML.split('-')[1];
                    downloadBtnElement.innerHTML = filename;
                    downloadBtnElement.classList.remove('hidden-item');
                    nonDownloadElement.classList.add('hidden-item');
                    downloadBtnElement.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location = '/downloadtaptinthongbao/' + id;
                    });
                } else {
                    downloadBtnElement.classList.add('hidden-item');
                    nonDownloadElement.classList.remove('hidden-item');
                }
                $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: "thongbao/" + id + "/changethongbaostatus",
						data: {
							"_method": 'PUT'
						},
                        success: function(data) {
                            if (!$.isEmptyObject(data.error)) {
                                alert(data.error);
                            } else {
                                console.log(data.success);
								let seenLabelElement = getSeenLabelElement();
                				let statusTdElement = detailsButton.closest('.row-thongbao').lastElementChild;
                				statusTdElement.innerHTML = '';
                				statusTdElement.appendChild(seenLabelElement);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr
                                .responseText);
                        }
                    }); 
        });
    }
}
	
function getSeenLabelElement() {
    let seenLabelElement = document.createElement('span');
    seenLabelElement.innerText = 'Đã xem';
    seenLabelElement.classList.add('label', 'label-success');
    return seenLabelElement
}

generateDetailsButtonsEvent('details-btn');
</script>
@endsection