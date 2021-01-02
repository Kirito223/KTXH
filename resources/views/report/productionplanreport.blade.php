<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta name="description" content="Admin, Dashboard, Bootstrap">
    <base href="/public">
    <style>
        .hidden-item {
            display: none !important;
        }
    </style>
    <link rel="shortcut icon" sizes="196x196" href="images/huyhieu.png">
    <title>Báo cáo kế hoạch sản xuất</title>
    <link rel="stylesheet" href="theme/libs/bower/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet"
        href="theme/libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.css">
    <link rel="stylesheet" href="theme/assets/css/app.min.css">
    <script src="theme/libs/bower/breakpoints.js/dist/breakpoints.min.js"></script>
    <script>
        Breakpoints();
    </script>
    <script src="theme/assets/js/core.min.js"></script>



    <meta name="csrf-token" content="g4uwZXR6bgCXn4Nsp2yAkQ2dQ3D1aoG5QsJjH5bE">
    <!-- Report Viewer Office2013 style -->
    <link href="stimulsoft/Libs/Reports.JS/Css/stimulsoft.viewer.office2013.whiteblue.css" rel="stylesheet">
    <link href="stimulsoft/Libs/Reports.JS/Css/stimulsoft.designer.office2013.whiteblue.css" rel="stylesheet">

    <!-- Stimusloft Reports.JS -->
    <script src="stimulsoft/Libs/Reports.JS/Scripts/stimulsoft.reports.js" type="text/javascript"></script>
    <script src="stimulsoft/Libs/Reports.JS/Scripts/stimulsoft.reports.maps.js" type="text/javascript"></script>
    <script src="stimulsoft/Libs/Reports.JS/Scripts/stimulsoft.viewer.js" type="text/javascript"></script>
    <script src="stimulsoft/Libs/Reports.JS/Scripts/stimulsoft.designer.js" type="text/javascript"></script>

    <link rel="stylesheet" href="css/global.css" />
    <!-- DevExtreme themes -->
    <link rel="stylesheet" href="dx/css/dx.common.css">
    <link rel="stylesheet" href="dx/css/dx.material.blue.light.compact.css">




</head>

<body class="menubar-left menubar-unfold menubar-light theme-primary">

    <nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top primary">
        <div class="navbar-header"><button type="button" id="menubar-toggle-btn"
                class="navbar-toggle visible-xs-inline-block navbar-toggle-left hamburger hamburger--collapse js-hamburger"><span
                    class="sr-only">Toggle navigation</span> <span class="hamburger-box"><span
                        class="hamburger-inner"></span></span></button> <button type="button"
                class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse"
                data-target="#app-navbar-collapse" aria-expanded="false"><span class="sr-only">Toggle navigation</span>
                <span class="zmdi zmdi-hc-lg zmdi-more"></span></button> <button type="button"
                class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#navbar-search"
                aria-expanded="false"><span class="sr-only">Toggle navigation</span> <span
                    class="zmdi zmdi-hc-lg zmdi-search"></span></button> <a href="../index.html"
                class="navbar-brand"><span class="brand-icon"><i class="fa fa-gg"></i></span> <span
                    class="brand-name">Infinity</span></a></div>
        <div class="navbar-container container-fluid">
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-toolbar navbar-toolbar-left navbar-left">
                    <li class="hidden-float hidden-menubar-top"><a href="javascript:void(0)" role="button"
                            id="menubar-fold-btn" class="hamburger hamburger--arrowalt is-active js-hamburger"><span
                                class="hamburger-box"><span class="hamburger-inner"></span></span></a></li>
                    <li>
                        <h5 class="page-title hidden-menubar-top hidden-float">Dashboard</h5>
                    </li>
                </ul>
                <ul class="nav navbar-toolbar navbar-toolbar-right navbar-right">
                    <li class="nav-item dropdown hidden-float"><a href="javascript:void(0)" data-toggle="collapse"
                            data-target="#navbar-search" aria-expanded="false"><i
                                class="zmdi zmdi-hc-lg zmdi-search"></i></a></li>
                    <li class="dropdown">
                        <a href="/danhsachthongbao">
                            <i class="zmdi zmdi-hc-lg zmdi-notifications"></i>
                        </a>
                        <div class="dropdown-menu animated"
                            style="top:88px;width:500px; max-height:300px; overflow-y:auto">
                            <div class="media-group">
                            </div>

                            <!-- <a href="javascript:void(0)" class="media-group-item">
                                <div class="media">
                                    <div class="media-left">
                                        <div class="avatar avatar-xs avatar-circle"><img src="../assets/images/205.jpg"
                                                alt=""> <i class="status status-offline"></i></div>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="media-heading">John Doe</h5><small class="media-meta">2 hours
                                            ago</small>
                                    </div>
                                </div>
                            </a> -->
                        </div>
                        <div class="dropdown-menu animated"
                            style="top:58px;width:500px;height:30px;text-align:center; padding:5px; box-shadow:none; border-width:2px; border-bottom-style: solid">
                            <a href="/danhsachthongbao">Xem tất cả</a>
                        </div>

                    </li>
                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"
                            role="button" aria-haspopup="true" aria-expanded="false"><i
                                class="zmdi zmdi-hc-lg zmdi-settings"></i></a>
                        <ul class="dropdown-menu animated flipInY">
                            <li><a href="javascript:void(0)"><i class="zmdi m-r-md zmdi-hc-lg zmdi-account-box"></i>My
                                    Profile</a></li>
                            <li><a href="javascript:void(0)"><i
                                        class="zmdi m-r-md zmdi-hc-lg zmdi-balance-wallet"></i>Balance</a></li>
                            <li><a href="javascript:void(0)"><i
                                        class="zmdi m-r-md zmdi-hc-lg zmdi-phone-msg"></i>Connection<span
                                        class="label label-primary">3</span></a></li>
                            <li><a href="javascript:void(0)"><i class="zmdi m-r-md zmdi-hc-lg zmdi-info"></i>privacy</a>
                            </li>
                        </ul>
                    </li>
                    <!--           <li class="dropdown"><a href="javascript:void(0)" class="side-panel-toggle" data-toggle="class" data-target="#side-panel" data-class="open" role="button"><i class="zmdi zmdi-hc-lg zmdi-apps"></i></a></li> -->
                </ul>
            </div>
        </div>
    </nav>
    <aside id="menubar" class="menubar light">
        <div class="app-user">
            <div class="media">
                <div class="media-left">
                    <div class="avatar avatar-md avatar-circle"><a href="javascript:void(0)"><img class="img-responsive"
                                src="images/huyhieu.png" alt="avatar"></a></div>
                </div>
                <div class="media-body">
                    <div class="foldable">
                        <h5><a href="javascript:void(0)" class="username">Phòng Tài chính - Kế hoạch huyện Chư Pưh</a>
                        </h5>
                        <ul>
                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle usertitle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><small>Huyện Chư
                                        Pưh</small> <span class="caret"></span></a>
                                <ul class="dropdown-menu animated flipInY">

                                    <li><a class="text-color" href="quanlytaikhoan"><span class="m-r-xs"><i
                                                    class="fa fa-gear"></i></span> <span>Thông tin tài khoản</span></a>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a class="text-color" href="logout"><span class="m-r-xs"><i
                                                    class="fa fa-power-off"></i></span> <span>Logout</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="menubar-scroll">
            <div class="menubar-scroll-inner">
                <ul class="app-menu">

                    <!-- <li><a href="listchitieu"><i class="menu-icon zmdi zmdi-file-text zmdi-hc-lg"></i> <span class="menu-text">Chỉ tiêu</span></a></li> -->
                    <li class="has-submenu">
                        <a href="javascript:void(0)" class="submenu-toggle"><i
                                class="zmdi zmdi-hc-lg zmdi-settings"></i> <span class="menu-text">Hệ thống</span>
                            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu">
                            <li><a href="/taikhoan"><span class="menu-text">1.1. Quản lý tài khoản</span></a></li>
                            <li><a href="/nhomquyen"><span class="menu-text">1.2. Quản lý nhóm & phân quyền </span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-view-list-alt"></i>
                            <span class="menu-text">Quản lý danh mục</span>
                            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu">
                            <li><a href="loaisolieu"><span class="menu-text">2.1. Quản lý loại số liệu</span></a></li>
                            <li><a href="donvitinh"><span class="menu-text">2.2. Quản lý đơn vị tính</span></a></li>
                            <li><a href="donvihanhchinh"><span class="menu-text">2.3. Quản lý đơn vị hành
                                        chính</span></a></li>
                            <li><a href="kybaocao"><span class="menu-text">2.4. Quản lý kỳ báo cáo</span></a></li>
                            <li><a href="diaban"><span class="menu-text">2.5. Quản lý địa bàn</span></a></li>
                            <li><a href="listchitieu"><span class="menu-text">2.6. Quản lý chỉ tiêu</span></a></li>
                            <!---->
                            <li><a href="thongbao"><span class="menu-text">2.7. Quản lý thông báo</span></a></li>
                            <!---->
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-collection-text"></i>
                            <span class="menu-text">Quản lý biểu mẫu</span>
                            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu">
                            <li><a href="kehoachktxhxa"><span class="menu-text">3.1. Quản lý kế hoạch</span></a></li>
                            <li><a href="viewDanhsachBieumauNhaplieu"><span class="menu-text">3.2. Quản lý biểu mẫu nhập
                                        liệu</span></a></li>
                            <li><a href="viewQuanlyBieumaubaocao"><span class="menu-text">3.3. Quản lý biểu số liệu báo
                                        cáo</span></a></li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-chart"></i> <span
                                class="menu-text">Quản lý số liệu</span>
                            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu">
                            <li><a href="viewListNhaplieu"><span class="menu-text">4.1. Quản lý biểu mẫu nhập
                                        liệu</span></a></li>
                            <!-- <li><a href="viewDanhsachNhaplieu"><span class="menu-text">4.2. Quản lý biểu số liệu báo cáo</span></a></li>-->
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-assignment-o"></i> <span
                                class="menu-text">Báo cáo</span>
                            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                        <ul class="submenu">


                            <!--<li><a href="viewReportChitieuKTXH"><span class="menu-text">5.2. Báo cáo chỉ tiêu</span></a></li>-->
                            <li><a href="viewProductionPlanreport"><span class="menu-text">5.1. Báo cáo</span></a></li>
                            <!--<li><a href="viewdubaoreport"><span class="menu-text">5.2. Báo cáo giai đoạn</span></a></li>
				<li><a href="viewsanxuatreport"><span class="menu-text">5.3. Báo cáo giá trị</span></a></li>-->
                            <li><a href="viewDanhsachBaocao"><span class="menu-text">5.2. Quản lý báo cáo</span></a>
                            </li>
                        </ul>
                    </li>
                    <!--
        <li class="has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-dns"></i> <span class="menu-text">Khai thác thông tin</span> 
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
            <ul class="submenu">
                <!--<li><a href="viewTimkiembaocao"><span class="menu-text">6.1. Danh sách báo cáo</span></a></li>
                <li><a href="listdanhsachbieumau"><span class="menu-text">6.2. Danh sách biểu mẫu</span></a></li>-->
                    <!--
                <li><a href="listsosanhsolieu"><span class="menu-text">6.1. So sánh số liệu theo kỳ, năm</span></a></li>
               <!-- <li><a href="listsosanhsolieutheodiaban"><span class="menu-text">6.4. So sánh số liệu theo địa bàn, đơn vị</span></a></li>
                <li><a href="listtracuusolieuchitieu"><span class="menu-text">6.5. Tra cứu số liệu theo chỉ tiêu</span></a></li>
                <li><a href="listtracuusolieubieumau"><span class="menu-text">6.6. Tra cứu số liệu theo biểu mẫu</span></a></li>-->
                    <!--
                <li><a href="listdubaosolieu"><span class="menu-text">6.2. Dự báo số liệu</span></a></li> 
            </ul>
        </li>
			-->
                    <li>
                        <form action="/dangxuat" method="post" id="dangxuat-form">
                            <input type="hidden" name="_token" value="g4uwZXR6bgCXn4Nsp2yAkQ2dQ3D1aoG5QsJjH5bE">
                        </form>
                        <a href="javascript:void(0)" onclick="document.getElementById('dangxuat-form').submit()"><i
                                class="glyphicon glyphicon-off"></i> <span class="menu-text">Đăng xuất</span></a>

                    </li>
                </ul>
            </div>
        </div>
    </aside>
    <div id="navbar-search" class="navbar-search collapse">
        <div class="navbar-search-inner">
            <form action="#"><span class="search-icon"><i class="fa fa-search"></i></span> <input class="search-field"
                    type="search" placeholder="search..."></form>
            <button type="button" class="search-close" data-toggle="collapse" data-target="#navbar-search"
                aria-expanded="false"><i class="fa fa-close"></i></button>
        </div>
        <div class="navbar-search-backdrop" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false">
        </div>
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
                <input type="hidden" name="_token" value="g4uwZXR6bgCXn4Nsp2yAkQ2dQ3D1aoG5QsJjH5bE">
                <div class="modal-body">
                    <div class="row no-gutter p-sm">
                        <div>
                            <h3 class="widget-title fz-lg text-primary m-b-sm" id="tophead-details-title">
                            </h3>
                            <small>Từ ngày: </small><small id="tophead-details-start-day"></small> -
                            <small>Đến ngày: </small><small id="tophead-details-end-day"></small>
                            <p class="m-b-lg" id="tophead-details-content"
                                style="margin-top: 15px; padding:5px;border-style:dotted;border-width:1px">
                            </p>
                        </div>
                        <span><b>Tải tài liệu :</b></span><i class="fa fa-file-pdf-o" style="margin-left: 10px"></i> -
                        <span><a href="javascript:void(0);" id="tophead-details-download-doc" class="hidden-item">Tên
                                tài
                                liệu</a></span><span id="tophead-details-non-donwnload-doc" class="text-danger">Không có
                            tài
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
            <input type="text" style="display: none" id="userid" value="68" />
            <input type="text" style="display: none" id="nameaccount"
                value="Phòng Tài chính - Kế hoạch huyện Chư Pưh" />
            <input type="text" style="display: none" id="phongbanid" value="" />
            <input type="text" style="display: none" id="madonvi" value="20" />
            <input type="text" style="display: none" id="tendonvi" value="Huyện Chư Pưh" />
            <input type="text" style="display: none" id="tenphongban" value="" />


            <div class="container-fluid" id="productionplanreport">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget">
                            <header class="widget-header">
                                <h4 class="widget-title">Lọc thông tin</h4>
                            </header>
                            <hr class="widget-separator">
                            <div class="widget-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Địa bàn</label>
                                        <div id="cbDiaban"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <label id="titleDiaban">Đơn vị</label>
                                        <div id="cbHuyen"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Loại số liệu</label>
                                        <div id="cbSoLieu"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Năm</label>
                                        <div id="cbNam" style="width: 100%"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Biểu mẫu</label>
                                        <div id="cbBieumau" style="width: 100%"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <button class="btn btn-warning btn-sm btn-search" id="btnView"><i
                                                class="fas fa-eye fa-sm fa-fw"></i>
                                            Xem báo cáo</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success btn-sm btn-search" id="btnGiaiDoan"><i
                                                class="fas fa-eye fa-sm fa-fw"></i>
                                            Báo cáo giai đoạn</button>
                                    </div>
                                    <!--<div class="col-md-3">
							<button class="btn btn-success btn-sm btn-search" id="btnGiaTri" ><i
                                    class="fas fa-download fa-sm fa-fw"></i>
                                Xuất báo cáo tổng hợp KTXH</button>
						</div>-->

                                    <div class="col-md-3" style="padding-top: 20px;">
                                        <!--id="btnSearch"-->

                                        <div id="selectbox" style="
    text-transform: none;
">
                                            xuất báo cáo tổng hợp KTXH
                                        </div>

                                        <!--<div class="dropdown">
						  <button class="fas fa-download btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Xuất báo cáo
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<ul>
							<li><button type="button" class="btn btn-default btn-xs" id="btnGiaTri" style="width: -webkit-fill-available;">Xuất báo cáo tổng hợp KTXH</button></li>
							<li><button type="button" class="btn btn-default btn-xs" id="baocaogiaidoan" style="width: -webkit-fill-available;">Báo cáo giai đoạn</button></li>
							</ul>
						  </div>
						</div>-->
                                    </div>

                                    <style>
                                        .custom-item {
                                            position: relative;
                                            white-space: nowrap;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        }
                                    </style>
                                    <script>
                                        $(function() {
								var list = [{
								"id":"1",
								"ids":"btnGiaTri",
								"Name": "Xuất báo cáo tổng hợp KTXH",
							}, {
								"id":"1",
								"ids":"baocaogiaidoan",
								"Name": "Báo cáo giai đoạn",
							}];
						$("#selectbox").dxDropDownButton({
							text: "Xuất báo cáo tổng hợp KTXH",
							icon: "export",
							stylingMode: "contained",
							type: "success",
							items:list,
							elementAttr: {  
							   class : 'btn btn-primary'  
							},
							itemTemplate: function (data) {  
								if(data.id == 1){
								return "<div class='custom-item'id='" + data.ids + "' title='" + data.Name + "'>" + data.Name + "</div>";  
								}else if(data.id == 2){
								return "<div class='custom-item' id='" + data.ids + "' title='" + data.Name + "'>" + data.Name + "</div>";  
								}									
							},
							onItemClick: function(e) {
								DevExpress.ui.notify("Download " + e.Name, "success", 600);
							},
						});
								});
                                    </script>



                                </div>


                            </div>
                        </div>

                        <div class="widget">

                            <hr class="widget-separator">
                            <div class="widget-body">
                                <div id="report"></div>
                            </div>
                        </div>



                        <!-- Modal -->
                        <div class="modal fade" id="modelDanhsachBieumau" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Chọn biểu mẫu</h5>
                                        <button type="button" id="btnThembieumau" class="btn btn-sm btn-info">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Quản lý biểu mẫu
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div style="overflow-y: scroll; height: 500px;">
                                                        <div id="containerBieumau">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Bieu mau -->


                        <!-- Thêm mới biểu mẫu -->
                        <div class="modal fade" id="modalThembieumau" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" style="min-width: 70%;" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Thêm biểu mẫu</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div style="display:flex;justify-content: flex-end;">
                                                        <button id="btnAddbieumau" class="btn btn-sm btn-success">Thêm
                                                            biểu
                                                            mẫu</button>
                                                    </div>
                                                    <table id="tableBieumau" class="table border-dark">
                                                        <thead>
                                                            <tr>
                                                                <th>Tên biểu mẫu</th>
                                                                <th>Tập tin</th>
                                                                <th>Áp dụng</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="gridBieumau">


                                                        </tbody>
                                                    </table>
                                                    <div id="nav"></div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalBieumau" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Quản lý biểu mẫu</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12 col-xl-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="txtTenbieumau">Tên biểu mẫu</label>
                                                        <input type="text" name="txtTenbieumau" id="txtTenbieumau"
                                                            class="form-control" placeholder="Tên biểu mẫu">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="custom-file">
                                                            <input type="file" name="fileBieumau" id="fileBieumau">

                                                        </label>
                                                        <p class="fileBM"></p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="txtTenbieumau">Loại: </label>
                                                        <div id="loaibieumau"></div>
                                                    </div>
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="chkApdung" id="chkApdung" checked>
                                                            Áp dụng
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Đóng</button>
                                        <button type="button" id="btnLuuBieumau" class="btn btn-primary">Lưu</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="module" src="js/productionplanreport.js"></script>

            <style>
                .custom-item {
                    position: relative;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }



                .homeproduct {
                    clear: both;
                    display: block;
                    background: #fff;
                    display: -webkit-box;
                    display: -moz-box;
                    display: -ms-flexbox;
                    display: -webkit-flex;
                    display: flex;
                    -webkit-flex-flow: row wrap;
                    flex-flow: row wrap;
                    flex: 1 100%;
                    border-top: 1px solid #eee;
                    border-left: 1px solid #eee;
                    margin-bottom: 15px;
                    list-style: none;
                }

                .item-bieumau {
                    float: left;
                    position: relative;
                    width: 19.91%;
                    overflow: hidden;
                    text-align: center;
                    cursor: pointer;
                }

                .iconbieumau i {
                    font-size: 20pt;
                    /* color: orangered; */
                }

                .item-bieumau:hover {
                    background-color: bisque;
                }

                .type1 {
                    color: rgb(224, 42, 10);
                }

                .type2 {
                    color: rgb(237, 61, 243);
                }

                .type3 {
                    color: rgb(6, 187, 21)
                }

                .modal-title {
                    text-align: center;
                    font-size: 16pt;
                }

                #nav {
                    text-align: center;
                }
            </style>
        </div>


        <!-- 
<div class="col-lg-12">
    <div class="wrap p-t-0">
        <footer class="app-footer">
            <div class="clearfix">
                <ul class="footer-menu pull-right">
                    <li><a href="javascript:void(0)">Careers</a></li>
                    <li><a href="javascript:void(0)">Privacy Policy</a></li>
                    <li><a href="javascript:void(0)">Feedback <i class="fa fa-angle-up m-l-md"></i></a></li>
                </ul>
                <div class="copyright pull-left">Copyright RaThemes 2016 ©</div>
            </div>
        </footer>
    </div>


</div>

 -->
    </main>




    <script src="theme/libs/bower/moment/moment.js"></script>
    <script src="theme/assets/js/app.min.js"></script>


    <!-- DevExtreme themes -->
    <link rel="stylesheet" href="dx/css/dx.common.css">
    <link rel="stylesheet" href="dx/css/dx.material.blue.light.compact.css">

    <script type="text/javascript" src="assets/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="assets/sweetalert2/dist/sweetalert2.all.min.js"></script>




    <script src="assets/external/jquery-3.4.1.min.js"></script>
    <script src="assets/external/jsrender.min.js"></script>
    <script src="theme/assets/js/bootstrap.min.js"></script>
    <script src="theme/libs/misc/datatables/datatables.min.js" defer></script>
    <script src="assets/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/js/bootstrap-datepicker.vi.min.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="theme/libs/bower/select2/dist/css/select2.min.css">
    <script src="theme/libs/bower/select2/dist/js/select2.full.min.js"></script>
    <!-- DevExtreme library -->
    <script type="text/javascript" src="dx/js/dx.all.js"></script>





    <script type="text/javascript" src="dx/js/knockout-latest.js"></script>
    <script type="text/javascript" src="dx/js/angular.min.js"></script>


    <!-- DevExtreme library -->
    <!-- <script type="text/javascript" src="dx/js/dx.all.js"></script>-->
    <!-- <script type="text/javascript" src="js/dx.web.js"></script> -->
    <!-- <script type="text/javascript" src="js/dx.viz.js"></script> -->
    <!-- <script type="text/javascript" src="js/dx.viz-web.js"></script> ---->

    <script type="module" src="js/getinfoLogin.js"></script>

    <script src="ckeditor/ckeditor.js"></script>\

    <script src="https://kit.fontawesome.com/2768643463.js" crossorigin="anonymous"></script>

    <!-- Tophead Notify Service -->
    <script type="text/javascript" src="http://ctktxh.lihanet.com/js/tophead-notify.js"></script>
    <link rel="stylesheet" type="text/css" href="jtreetable/css/jquery.treetable.css" />
    <link rel="stylesheet" type="text/css" href="jtreetable/css/jquery.treetable.theme.default.css" />
    <link rel="stylesheet" type="text/css" href="jtreetable/css/screen.css" />
    <script src="jtreetable/jquery.treetable.js"></script>

    <link rel="stylesheet" href="css/jquery.contextMenu.min.css">
    <script src="js/jquery.contextMenu.min.js"></script>
    <script src="js/jquery.ui.position.js"></script>
</body>

</html>