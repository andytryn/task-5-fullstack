<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>@yield('title', 'Auth') | {{ config('app.name', 'Laravel') }}</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css" />
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
        <!-- Theme style -->
        <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css" />

        <!-- Scripts -->
        {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="{{ url('/') }}" class="h1"><b>Admin</b>LTE</a>
                </div>
                @yield('content')
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="https://adminlte.io/themes/v3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js"></script>
    </body>
</html>
