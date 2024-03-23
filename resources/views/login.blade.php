@php
$page_title =  Session::get('cms_name') . " | Login";
@endphp

@include('header')
  
<!-- Navbar -->
@include('navnd')
<!-- /.navbar -->

<style>
    .border-md {
        border-width: 2px !important;
    }

    .btn-success, .btn-primary {
        background-color: {{Session::get('cms_theme_color')}} !important;
        border: #06513D !important;
    }

    .btn-facebook {
        background: #405D9D;
        border: none;
    }

    .btn-facebook:hover, .btn-facebook:focus {
        background: #314879;
    }

    .btn-twitter {
        background: #42AEEC;
        border: none;
    }

    .btn-twitter:hover, .btn-twitter:focus {
        background: #1799e4;
    }

    body {
        min-height: 100vh;
    }

    .form-control:not(select) {
        padding: 1.5rem 0.5rem;
    }

    select.form-control {
        height: 52px;
        padding-left: 0.5rem;
    }

    .form-control::placeholder {
        color: #ccc;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .form-control:focus {
        box-shadow: none;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div></div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <!-- Main content -->
                <div class="login-card">
                    <div class="row no-gutters">
                        <div class="col-md-5 mt-5">
                            <img src="images/{{Session::get('cms_undraw_img')}}" class="img-fluid" alt="login" style="width: 150%; height: auto;">
                        </div>
                    </div>
                </div>
                <!-- /.login-card -->
            </div>
            <!-- /.col-md-7 -->
        </div>
        <!-- /.row justify-content-center -->

        <div class="row justify-content-center">
            <div class="col-md-7">
                <!-- Login Form -->
                <div class="card-body">
                    <!-- Your login form here -->
                    @include('includes.alerts')
                    <p class="login-card-description">Sign in to your School Merchandise account</p>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <!-- First Name -->
                            <div class="input-group col-lg-12 mb-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white px-4 border-md border-right-0">
                                        <i class="fa fa-user text-muted"></i>
                                    </span>
                                </div>
                                <input id="username" type="text" name="username" placeholder="Username" class="form-control bg-white border-left-0 border-md" required>
                            </div>
                            <!-- Password -->
                            <div class="input-group col-lg-12 mb-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white px-4 border-md border-right-0">
                                        <i class="fa fa-lock text-muted"></i>
                                    </span>
                                </div>
                                <input id="password" type="password" name="password" placeholder="Password" class="form-control bg-white border-left-0 border-md" required autocomplete="off">
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group col-lg-12 mx-auto mb-0">
                                <button type="submit" class="btn btn-primary btn-block py-2">
                                    <span class="font-weight-bold">Login</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.col-md-7 -->
        </div>
        <!-- /.row justify-content-center -->
    </div>
    <!-- /.container -->

</div>
<!-- /.content-wrapper -->


