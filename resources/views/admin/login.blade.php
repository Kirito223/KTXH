<!DOCTYPE html>
<html lang="en">
@section('title','Đăng nhập')
@include('layout.topheader')





<body class="body-bg-color">
  <div class="wrapper">
    <div class="form-body">


      

      <form action="{{ route('postLogin') }}" method="post" class="col-form" novalidate>
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
              <label for="email" class="col-md-4 control-label">email</label>              
              <input id="email" type="text" class="form-control form-control-user" 
              name="email" value="{{ old('email') }}" required autofocus>
              @if ($errors->has('email'))
              <div class="alert alert-danger"> 
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {{ $errors->first('email') }}
              </div>
              @endif                            
            </div>
          </section>
          <section>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password" class="col-md-4 control-label">Mật khẩu</label>                 
              <input id="password" type="password" class="form-control form-control-user" name="password" required>
              @if ($errors->has('password'))
              <div class="alert alert-danger"> 
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {{ $errors->first('password') }}
              </div>
              @endif                         
            </div>
          </section>
          <section>
            <div class="row">           
              <div class="form-group">
                <div class="col-md-6 checkbox">
                  <div class="checkbox">
                    <label>
                     <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> Ghi nhớ
                   </label>
                 </div>
               </div>
             </div>  
           </div>
         </section>
       </fieldset>
       <footer class="text-right">
        <button type="submit" class="btn btn-info pull-right">Đồng ý</button>
        <a class="btn btn-link" href="{{ url('/password/reset') }}">Quên mật khẩu?</a>
      </form>





    </div>
  </div>
  <!-- wrapper --> 

  <!-- jQuery --> 
  @include('layout.js')

</body>
</html>