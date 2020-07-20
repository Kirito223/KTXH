    <nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top primary">
        <div class="navbar-header"><button type="button" id="menubar-toggle-btn" class="navbar-toggle visible-xs-inline-block navbar-toggle-left hamburger hamburger--collapse js-hamburger"><span class="sr-only">Toggle navigation</span> <span class="hamburger-box"><span class="hamburger-inner"></span></span></button>            <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false"><span class="sr-only">Toggle navigation</span> <span class="zmdi zmdi-hc-lg zmdi-more"></span></button>            <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false"><span class="sr-only">Toggle navigation</span> <span class="zmdi zmdi-hc-lg zmdi-search"></span></button>            <a href="../index.html" class="navbar-brand"><span class="brand-icon"><i class="fa fa-gg"></i></span> <span class="brand-name">Infinity</span></a></div>
        <div class="navbar-container container-fluid">
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-toolbar navbar-toolbar-left navbar-left">
                    <li class="hidden-float hidden-menubar-top"><a href="javascript:void(0)" role="button" id="menubar-fold-btn" class="hamburger hamburger--arrowalt is-active js-hamburger"><span class="hamburger-box"><span class="hamburger-inner"></span></span></a></li>
                    <li>
                        <h5 class="page-title hidden-menubar-top hidden-float">Dashboard</h5>
                    </li>
                </ul>
                <ul class="nav navbar-toolbar navbar-toolbar-right navbar-right">
                    <li class="nav-item dropdown hidden-float"><a href="javascript:void(0)" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false"><i class="zmdi zmdi-hc-lg zmdi-search"></i></a></li>
             		<li class="dropdown">
                        @if(Auth::user() !== null && Auth::user()->thongbaos()->wherePivot('isSeen',0)->count() > 0)
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-haspopup="true" aria-expanded="false" id="notify-button"><i
                                class="zmdi zmdi-hc-lg zmdi-notifications"></i>
                            
                            <span class="label label-danger menu-label" style="margin-left:5px;margin-top:5px"
                                id="tophead-unseen-notify-couter">
                                {{ Auth::user()->thongbaos()->wherePivot('isSeen', 0)->count() }}
                            </span>
                        </a>
                        @else
                        <a href="/danhsachthongbao">
                        <i class="zmdi zmdi-hc-lg zmdi-notifications"></i>
                        </a>
                        @endif
                        <div class="dropdown-menu animated" style="top:88px;width:500px; max-height:300px; overflow-y:auto">
                            <div class="media-group">
                                @if(Auth::user() !== null && Auth::user()->thongbaos()->wherePivot('isSeen',0)->count()
                                > 0)
                                @foreach(Auth::user()->thongbaos()->wherePivot('isSeen',0)->get() as $thongbao)
                                <a href="javascript:void(0)" class="media-group-item notify-item"
                                    id="notify-item-{{ $thongbao->id }}">
                                    <div class="media">
                                        <div class="media-body">
                                            <h5 class="media-heading">{{$thongbao->tieude}}</h5>
                                            <small class="media-meta"><i>Đơn vị gửi: </small></i><small
                                                class="media-meta">{{ $thongbao->pivot->donvigui }}</small>
                                            <small class="media-meta"><i> - Lúc: </i></small>
                                            <small
                                                class="media-meta">{{ date("H:i:s d/m/Y", strtotime($thongbao->pivot->thoigiangui)) }}</small>
                                        </div>
                                        <div class="media-right">
                                            <!-- <div class="avatar avatar-xs avatar-circle"><img src="../assets/images/221.jpg" alt=""> <i class="status status-online"></i></div> -->
                                            <span class="label label-danger">Chưa xem</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                                @endif
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
                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-hc-lg zmdi-settings"></i></a>
                        <ul class="dropdown-menu animated flipInY">
                            <li><a href="javascript:void(0)"><i class="zmdi m-r-md zmdi-hc-lg zmdi-account-box"></i>My Profile</a></li>
                            <li><a href="javascript:void(0)"><i class="zmdi m-r-md zmdi-hc-lg zmdi-balance-wallet"></i>Balance</a></li>
                            <li><a href="javascript:void(0)"><i class="zmdi m-r-md zmdi-hc-lg zmdi-phone-msg"></i>Connection<span class="label label-primary">3</span></a></li>
                            <li><a href="javascript:void(0)"><i class="zmdi m-r-md zmdi-hc-lg zmdi-info"></i>privacy</a></li>
                        </ul>
                    </li>
                    <!--           <li class="dropdown"><a href="javascript:void(0)" class="side-panel-toggle" data-toggle="class" data-target="#side-panel" data-class="open" role="button"><i class="zmdi zmdi-hc-lg zmdi-apps"></i></a></li> -->
                </ul>
            </div>
        </div>
    </nav>