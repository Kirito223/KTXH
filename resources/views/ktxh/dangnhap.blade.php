<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/loginpage.css">
</head>

<body>
    <div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
        <div class="card card0 border-0">
            <div class="row d-flex">
                <div class="col-lg-6">
                    <div class="card1 pb-5">
                        <div class="row justify-content-center"> <img src="{{ url('images/logo.png') }}"
                                class="logo"><img src="{{ url('images/name1.png') }}" class="title"> </div>
                        <div class="row px-3 justify-content-center mt-4 mb-5 border-line"> <img
                                src="{{ url('images/loginimage.png') }}" class="image"> </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card2 card border-0 px-4 py-5">
                        <div class="row px-3 mb-4 justify-content-center">
                            <h3><b>ĐĂNG NHẬP<b></h3>
                        </div>
                        <form action="/dangnhap" method="post" class="col-form" novalidate>
                            {{ csrf_field() }}
                            <div class="row px-3"> <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Tên đăng nhập</h6>
                                </label for="tendangnhap"> <input id="tendangnhap" class="mb-4" type="text"
                                    name="tendangnhap" value="{{ old('tendangnhap') }}" required autofocus></div>
                            <div class="row px-3"> <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Mật khẩu</h6>
                                </label for="password"><input type="password" name="password" required> </div>
                            <div class="row mb-1 mt-1 px-3">
                                @if(count($errors) > 0)
                                <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <small class="text-danger"><b>{{ $error }}</b></small>
                                    <br>
                                @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="row mb-3 mt-3 px-3"> <button type="submit" class="btn btn-blue text-center">Đăng
                                    nhập</button> </div>
                            <div class="row px-3">
                                <div class="col-12 row m-0 p-0">
                                    <small class="mb-1"><b>Hỗ trợ trực tuyến</b>
                                    </small>
                                </div>
                                <div class="col-12  row m-0 p-0">
                                    <small class="ml-3 mb-1"><b>Phòng CSKH</b></small>
                                </div>
                                <div class="col-12 row m-0 p-0">
                                    <small class="ml-3 mb-1">Điện thoại: (0256) 6.555.678
                                    </small>
                                </div>
                                <div class="col-12 row m-0 p-0">
                                    <small class="ml-3 mb-1">Email: trunghauqn2008@gmail.com
                                    </small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-blue py-4 fluid-container">
                <div class="row px-3">
                    <div class="col-12 justify-content-center row m-0 p-0">
                        <small class="m-0">Bản quyền thuộc về: Công ty TNHH Ứng Dụng và Triển Khai Phần
                            Mềm Lihanet
                        </small>
                    </div>
                    <div class="col-12 justify-content-center row m-0 p-0">
                        <small class="m-0">Địa chỉ: Lô 29 Hoa Lư Nối Dài - P.Đống Đa - Tp Quy Nhơn - Tình Bình Định
                        </small>
                    </div>
                    <div class="col-12 justify-content-center row m-0 p-0">
                        <small class="m-0">Điện thoại: (0256) 6.555.678 - Email: lihanet@gmail.com
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>