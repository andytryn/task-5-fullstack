<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>@yield('title', 'Laravel') | {{ config('app.name', 'Gampangan') }}</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
        <!-- Theme style -->
        <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css" />
        <!-- Custom Stylesheet -->
        @stack('stylesheets')
        {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    </head>

    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>

                    @if (Route::has('login'))
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}" role="button">Panel</a>
                    </li>
                    @else
                    @endauth
                    @endif

                </ul>

                @if (Route::has('login'))
                @auth
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
                @else
                @endauth
                @endif
            </nav>

            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <a href="{{ url('/') }}" class="brand-link">
                    <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8;" />
                    <span class="brand-text font-weight-light">Blog Name</span>
                </a>

                <div class="sidebar">
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="{{ url('/') }}" class="nav-link">
                                    <i class="nav-icon far fa-circle text-info"></i>
                                    <p>Homepage</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <div class="content-wrapper">
                <section class="content-header">
                    @yield('content-header')
                </section>

                <section class="content">
                    <div class="row">
                        @yield('content')
                    </div>
                </section>
            </div>

            <footer class="main-footer">
                <strong>Copyright &copy; 2014-2021 <a href="https://github.com/andytryn">AdminLTE.io</a>, {{ config('app.name', 'Laravel') }}.</strong> All rights reserved.

                <div class="float-right d-none d-sm-inline">
                    <small>Built with <i class="fas fa-heart text-pink"></i> <a href="https://github.com/andytryn">Andi Triyanto</a></small>
                </div>
            </footer>
        </div>

        <!-- jQuery -->
        <script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="https://adminlte.io/themes/v3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="https://adminlte.io/themes/v3/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js"></script>
        <!-- Custom Script -->
        @stack('scripts')
    </body>
</html>
