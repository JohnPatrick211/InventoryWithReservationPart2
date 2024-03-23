<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ isset($page_title) ? $page_title : Session::get('cms_name') }}</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/customer.css') }}" rel="stylesheet">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">

        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">

        <style>
          .bg-login-page {
          height: 100%;
          width: 100%;
          -o-background-size: cover;
          -moz-background-size: cover;
          -webkit-background-size: cover;
          background-size: cover;
          background-position: 50% 50%;
          background-repeat: no-repeat;
          object-fit: cover;
          background-image: url("/images/{{Session::get('cms_bg_admin_login')}}");
          -webkit-filter: blur(4px);
          -moz-filter: blur(4px);
          -o-filter: blur(4px);
          -ms-filter: blur(4px);
          filter: blur(4px); 
          z-index: -1;
          position: fixed;
          top: 0;
          left: 0;
        }

          .btn-success, .btn-primary {
                background-color: {{Session::get('cms_theme_color')}} !important;
                border: #06513D !important;
              }

            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="hold-transition login-page">
    <div class="bg-login-page"></div>

      <div class="login-box">
        <!-- /.login-logo -->
        @if(\Session::has('danger'))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ \Session::get('danger') }}
            </div>
        </div>
        </div>
        @endif
        <div class="card card-outline card-primary">
          <div class="card-header text-center">
            <h4><b>{{Session::get('cms_name')}}</b></h4>
          </div>
          <div class="card-body">
      
            <form method="POST" action="{{ route('login') }}">
              @csrf
              <div class="input-group mb-3">
                <input name="username" type="text" class="form-control" placeholder="Username">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-user"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input name="password" type="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- /.col -->
                <div class="col-12 mb-2">
                  <button type="submit" class="btn btn-primary btn-block">Log In</button>
                </div>
                <!-- /.col -->
              </div>
            </form>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.login-box -->
     
        <script src="{{ asset('js/app.js') }}" defer></script>
    </body>
</html>
