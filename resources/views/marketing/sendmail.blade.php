@extends('master')
@section('title','Gửi email')
@section('content')

<div class="container-fluid" id="marketing">
    <div class="row">
        <div class="col-md-12">
            <div class="widget">
                <header class="widget-header">
                    <h4 class="widget-title">Gửi email</h4>
                </header>
                <hr class="widget-separator">
                <div class="widget-body">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">Thông tin</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-horizontal">

                                <div class="form-group">
                                    <label class="col-md-2">Tên bạn</label>
                                    <div class="col-md-10">
                                        <input class="form-control" id="name" placeholder="Họ và tên" />
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="col-md-2">Email</label>
                                    <div class="col-md-10">
                                        <input class="form-control" id="email" placeholder="Địa chỉ email" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2">Nội dung</label>
                                    <div class="col-md-10">
                                        <input class="form-control" id="content" placeholder="Nội dung" />
                                    </div>
                                </div>
                                <div class="col-md-12" style="text-align: center">
                                    <button id="btnSend" class="btn btn-sm btn-primary"><i
                                            class="fas fa-mail-bulk fa-sm fa-fw"></i>
                                        Gửi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">Gửi SMS</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-horizontal">

                                <div class="form-group">
                                    <label class="col-md-2">Nội dung</label>
                                    <div class="col-md-10">
                                        <input class="form-control" id="msg" placeholder="Tin nhắn" />
                                    </div>

                                </div>

                                <div class="col-md-12" style="text-align: center">
                                    <button id="btnSendSMS" class="btn btn-sm btn-primary"><i
                                            class="fas fa-mail-bulk fa-sm fa-fw"></i>
                                        Gửi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="module" src="js/marketing.js"></script>
@endsection