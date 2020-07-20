<!DOCTYPE html>
<html lang="en">
@include('layout.header')

<body class="menubar-left menubar-unfold menubar-light theme-primary">

    @include('layout.top')

    @include('layout.leftmenu')

    <div id="navbar-search" class="navbar-search collapse">
        <div class="navbar-search-inner">
            <form action="#"><span class="search-icon"><i class="fa fa-search"></i></span> <input class="search-field" type="search" placeholder="search..."></form>
            <button type="button" class="search-close" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false"><i class="fa fa-close"></i></button></div>
            <div class="navbar-search-backdrop" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false"></div>
        </div>
		<div class="modal fade in" id="model-for-thongbao-details" tabindex="-1" role="dialog"
        aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header row" style="padding: 5px">
                    <h3 class="modal-title" id="model-for-thongbao-detailsLabel">Chi tiết thông báo
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @csrf
                <div class="modal-body">
                    <div class="row no-gutter p-sm">
                        <div>
                            <h3 class="widget-title fz-lg text-primary m-b-sm" id="tophead-details-title">
                            </h3>
                            <small>Từ ngày: </small><small id="tophead-details-start-day"></small> -
                                    <small>Đến ngày: </small><small id="tophead-details-end-day"></small>
                                    <p class="m-b-lg" id="tophead-details-content" style="margin-top: 15px; padding:5px;border-style:dotted;border-width:1px">
                                    </p>
                        </div>
                        <span><b>Tải tài liệu :</b></span><i class="fa fa-file-pdf-o" style="margin-left: 10px"></i> -
                        <span><a href="javascript:void(0);" id="tophead-details-download-doc" class="hidden-item">Tên tài
                                liệu</a></span><span id="tophead-details-non-donwnload-doc" class="text-danger">Không có tài
                            liệu đính kèm</span>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">Đóng
                        Lại</button>
                </div>

            </div>
        </div>
    </div>




        <main id="app-main" class="app-main">
            <div class="wrap">
                <!-- Main view  -->
				
				  <!-- Main view  -->
            <input type="text" style="display: none" id="userid" value="{{Session::get('userid')}}" />
            <input type="text" style="display: none" id="nameaccount" value="{{session('name')}}" />
            <input type="text" style="display: none" id="phongbanid" value="{{session('phongbanid')}}" />
            <input type="text" style="display: none" id="madonvi" value="{{session('madonvi')}}" />
            <input type="text" style="display: none" id="tendonvi" value="{{session('tendonvi')}}" />
            <input type="text" style="display: none" id="tenphongban" value="{{session('tenphongban')}}" />

                @yield('content')
            </div>


            @include('layout.foodter')
        </main>




        @include('layout.js')
    </body>

    </html>

