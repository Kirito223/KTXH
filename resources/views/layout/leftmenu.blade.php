    <aside id="menubar" class="menubar light">
        <div class="app-user">
            <div class="media">
                <div class="media-left">
                    <div class="avatar avatar-md avatar-circle"><a href="javascript:void(0)"><img class="img-responsive" src="images/huyhieu.png" alt="avatar"></a></div>
                </div>
                <div class="media-body">
                    <div class="foldable">
                        <h5><a href="javascript:void(0)" class="username">{{session('name')}}</a></h5>
                        <ul>
                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle usertitle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><small>{{session('tendonvi')}}</small> <span class="caret"></span></a>
                                <ul class="dropdown-menu animated flipInY">
                                    
                                    <li><a class="text-color" href="quanlytaikhoan"><span class="m-r-xs"><i class="fa fa-gear"></i></span> <span>Thông tin tài khoản</span></a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a class="text-color" href="logout"><span class="m-r-xs"><i class="fa fa-power-off"></i></span> <span>Logout</span></a></li>
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
		@can('super-admin')
        <li class="has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-hc-lg zmdi-settings"></i> <span class="menu-text">Hệ thống</span> 
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
            <ul class="submenu">
                <li><a href="/taikhoan"><span class="menu-text">1.1. Quản lý tài khoản</span></a></li>
                <li><a href="/nhomquyen"><span class="menu-text">1.2. Quản lý nhóm & phân quyền </span></a></li>
            </ul>
        </li>
		@endcan
        <li class="has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-view-list-alt"></i> <span class="menu-text">Quản lý danh mục</span> 
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
            <ul class="submenu">
				@can('super-admin')
                <li><a href="loaisolieu"><span class="menu-text">2.1. Quản lý loại số liệu</span></a></li>
                <li><a href="donvitinh"><span class="menu-text">2.2. Quản lý đơn vị tính</span></a></li>
                <li><a href="donvihanhchinh"><span class="menu-text">2.3. Quản lý đơn vị hành chính</span></a></li>
                <li><a href="kybaocao"><span class="menu-text">2.4. Quản lý kỳ báo cáo</span></a></li>
                <li><a href="diaban"><span class="menu-text">2.5. Quản lý địa bàn</span></a></li>
                <li><a href="listchitieu"><span class="menu-text">2.6. Quản lý chỉ tiêu</span></a></li>
				@endcan
                <li><a href="thongbao"><span class="menu-text">2.7. Quản lý thông báo</span></a></li>
            </ul>
        </li>

        <li class="has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-collection-text"></i> <span class="menu-text">Quản lý biểu mẫu</span> 
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
            <ul class="submenu">
				<li><a href="kehoachktxhxa"><span class="menu-text">3.1. Quản lý kế hoạch</span></a></li>
                <li><a href="viewDanhsachBieumauNhaplieu"><span class="menu-text">3.2. Quản lý biểu mẫu nhập liệu</span></a></li>
                <li><a href="viewQuanlyBieumaubaocao"><span class="menu-text">3.3. Quản lý biểu số liệu báo cáo</span></a></li>
            </ul>
        </li>

        <li class="has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-chart"></i> <span class="menu-text">Quản lý số liệu</span> 
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
            <ul class="submenu">
                <li><a href="viewListNhaplieu"><span class="menu-text">4.1. Quản lý biểu mẫu nhập liệu</span></a></li>
                <li><a href="viewDanhsachNhaplieu"><span class="menu-text">4.2. Quản lý biểu số liệu báo cáo</span></a></li>
            </ul>
        </li>

        <li class="has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-assignment-o"></i> <span class="menu-text">Báo cáo</span> 
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
            <ul class="submenu">
                <li><a href="viewDanhsachBaocao"><span class="menu-text">5.1. Quản lý báo cáo</span></a></li>
				<li><a href="viewReportChitieuKTXH"><span class="menu-text">5.2. Báo cáo chỉ tiêu</span></a></li>
				<li><a href="viewProductionPlanreport"><span class="menu-text">5.3. Báo cáo so sánh tổng hợp</span></a></li>
            </ul>
        </li>

        <li class="has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle"><i class="zmdi zmdi-dns"></i> <span class="menu-text">Khai thác thông tin</span> 
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
            <ul class="submenu">
                <li><a href="viewTimkiembaocao"><span class="menu-text">6.1. Danh sách báo cáo</span></a></li>
                <li><a href="listdanhsachbieumau"><span class="menu-text">6.2. Danh sách biểu mẫu</span></a></li>
               <!-- <li><a href="listsosanhsolieu"><span class="menu-text">6.3. So sánh số liệu theo kỳ, năm</span></a></li>
                <li><a href="listsosanhsolieutheodiaban"><span class="menu-text">6.4. So sánh số liệu theo địa bàn, đơn vị</span></a></li>
                <li><a href="listtracuusolieuchitieu"><span class="menu-text">6.5. Tra cứu số liệu theo chỉ tiêu</span></a></li>
                <li><a href="listtracuusolieubieumau"><span class="menu-text">6.6. Tra cứu số liệu theo biểu mẫu</span></a></li>
                <li><a href="listdubaosolieu"><span class="menu-text">6.7. Dự báo số liệu</span></a></li> -->
            </ul>
        </li>
					@if(Auth::user() !== null)
        <li>
			<form action="/dangxuat" method="post" id="dangxuat-form">
				{{ csrf_field() }}
			</form>
			<a href="javascript:void(0)" onclick="document.getElementById('dangxuat-form').submit()"><i class="glyphicon glyphicon-off"></i> <span class="menu-text">Đăng xuất</span></a>
			
		</li>
		@endif
    </ul>
    </div>
    </div>
</aside>