<!DOCTYPE html>
<html lang="en">
@section('title','Đăng nhập')
@include('layout.topheader')

<body class="body-bg-color">
  <div class="wrapper">
    <div class="form-body">




      @if(Session::has('fail'))
      <div class="alert alert-danger">
        <p class="text-danger">{{ Session::get('fail') }}</p>
      </div>
      @endif
      <script>
        $(document).ready(()=>{
        setTimeout(() => {
          $('.alert-danger').hide();
        }, 1000);
      });
      </script>

      <form action="dangnhap" method="post" class="col-form" role="form" novalidate>
        {{ csrf_field() }}
        {{-- <div class="col-logo"><a></a></div> --}}
        <header>Đăng nhập</header>

        @if ($errors->has('errorlogin'))
        <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          {{ $errors->first('errorlogin') }}
        </div>
        @endif


        <fieldset>
          <section>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="tendangnhap" class="col-md-4 control-label">Tên đăng nhập</label>
              <input id="tendangnhap" type="text" class="form-control form-control-user" name="tendangnhap"
                value="{{ old('tendangnhap') }}" required autofocus>
            </div>
          </section>
          <section>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password" class="col-md-4 control-label">Mật khẩu</label>
              <input id="password" type="password" class="form-control form-control-user" name="password" required>
            </div>
          </section>
        </fieldset>
        <footer class="text-right">
          <button type="submit" class="btn btn-info pull-right">Đồng ý</button>
      </form>





    </div>
  </div>

  @include('layout.js')

</body>

</html>