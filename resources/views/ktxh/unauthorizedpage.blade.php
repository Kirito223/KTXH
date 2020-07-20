<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .btn {
    font-size: 14px;
    padding: 6px 12px;
    margin-bottom: 0;

    display: inline-block;
    text-decoration: none;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
}

    .btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
    }
</style>
</head>
<body style="text-align: center; padding: 70px" >
    <img src="{{ url('images/warning.png') }}" style="width:200px; height:160px"/>
    <h3 style="color:red; margin-top: 0px">Bạn không có quyền truy cập trang này</h3>
    <a href="{{ Session::get('previousUrl') }}" type="button" class="btn btn-default">Quay Lại</a>
</body>
</html>