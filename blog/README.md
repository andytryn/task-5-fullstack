
# Laravel 9 - Laravel UI
## Step 1: Install Laravel

``composer create-project --prefer-dist laravel/laravel blog``
## Step 2: Database Configuration

Create a database and configure the env file.
## Step 3: Laravel UI Installation

- [Documentation](https://github.com/laravel/ui)

``composer require laravel/ui``

``php artisan ui bootstrap --auth``

``npm install && npm run dev``

``php artisan migrate``
## Step 4: Link Storage

``php artisan storage:link``

## Step 5: Create Models, Controllers & Migration

``php artisan make:model Category -mcr``

``php artisan make:model Post -mcr``

``php artisan make:controller WelcomeController -r``

**database/migrations/*_create_categories_table.php**
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
```

**app/Models/Category.php**
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'user_id'];

    /**
     * The Category that has Many Post.
     */
    public function PostHasMany(){
        return $this->hasMany(Post::class, 'category_id');
    }
}
```

**database/migrations/*_create_posts_table**
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->text('image');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            // Foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
```

**app/Models/Post.php**
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'image', 'category_id', 'user_id',
    ];

    /**
     * The Post that belong to Category.
     */
    public function CategoriesBelongsTo(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * The Post that belong to Category.
     */
    public function UserBelongsTo(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
```

After create migration, need to run migration ``php artisan migrate``
## Step 6: Datatables Installation

 - [Documentation](https://yajrabox.com/docs/laravel-datatables)
 - [Tutorial](https://www.tutsmake.com/laravel-9-yajra-datatables-crud-example-tutorial/)

``composer require yajra/laravel-datatables-oracle:"^10.0"``
## Step 7: Writing AdminLTE Template

Layout

**resources/views/layouts/app.blade.php**
```
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <!-- Site wrapper -->
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>

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
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="brand-link">
                    <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8;" />
                    <span class="brand-text font-weight-light">AdminLTE 3</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>

                            <li class="nav-header">Blog</li>
                            <li class="nav-item">
                                <a href="{{ route('post.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-note-sticky"></i>
                                    <p>
                                        Posts
                                        {{-- <span class="badge badge-info right">2</span> --}}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('category.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-box-archive"></i>
                                    <p>
                                        Category
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        @yield('page_header')
                    </div>
                    <!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <footer class="main-footer">
                <strong>Copyright &copy; 2014-2021 <a href="https://github.com/andytryn">AdminLTE.io</a>, {{ config('app.name', 'Laravel') }} v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}).</strong> All rights reserved.

                <div class="float-right d-none d-sm-inline">
                    <small>Built with <i class="fas fa-heart text-pink"></i> <a href="https://github.com/andytryn">Andi Triyanto</a></small>
                </div>
            </footer>
        </div>
        <!-- ./wrapper -->

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
```

**resources/views/layouts/auth.blade.php**
```
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
```

**resources/views/layouts/front.blade.php**
```
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
```

create/modify folder auth in views

**resources/views/auth/login.blade.php**
```
@extends('layouts.auth')

@section('content')
<div class="card-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="email" type="email" placeholder="{{ __('Email Address') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>

            <!-- /.col -->
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Login') }}
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    <p class="mb-1">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </p>
    <p class="mb-0">
        @if (Route::has('register'))
            <a href="{{ route('register') }}">
                {{ __('Register') }}
            </a>
        @endif
    </p>
</div>
@endsection
```

**resources/views/auth/register.blade.php**
```
@extends('layouts.auth')

@section('content')
<div class="card-body">
    <p class="login-box-msg">Register a new membership</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="name" type="text" placeholder="{{ __('Name') }}" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="email" type="email" placeholder="{{ __('Email') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required autocomplete="new-password" />
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-8">
            </div>

            <!-- /.col -->
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Register') }}
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>


    @if (Route::has('login'))
        <a href="{{ route('login') }}" class="text-center">
            {{ __('Login') }}
        </a>
    @endif
</div>
@endsection
```

**resources/views/auth/verify.blade.php**
```
@extends('layouts.auth')

@section('content')
<div class="card-body">
    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    <p class="login-box-msg">
        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }},
    </p>

    <form class="text-center" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
    </form>
</div>
@endsection
```

**resources/views/auth/passwords/confirm.blade.php**
```
@extends('layouts.auth')

@section('content')
<div class="card-body">
    <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Confirm Password') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
            <!-- /.col -->
        </div>
    </form>

</div>
@endsection
```

**resources/views/auth/passwords/email.blade.php**
```
@extends('layouts.auth')

@section('content')
<div class="card-body">
    <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="email" type="email" placeholder="{{ __('Email Address') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    <p class="mt-3 mb-1">
        @if (Route::has('login'))
            <a href="{{ route('login') }}">
                {{ __('Login') }}
            </a>
        @endif
    </p>
</div>
@endsection
```

**resources/views/auth/passwords/reset.blade.php**
```
@extends('layouts.auth')

@section('content')
<div class="card-body">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-group mb-3">
            <input id="email" type="email" placeholder="{{ __('Email Address') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required autocomplete="new-password" />
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6"></div>

            <!-- /.col -->
            <div class="col-6">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Reset Password') }}
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>

</div>
@endsection
```

Front-end

create folder welcome in views

**resources/views/welcome/index.blade.php**
```
@extends('layouts.front')

@section('title', 'Blog')

@section('content-header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-solid fa-blog"></i> Posts</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="col-md-9">
    <div class="row">

        @forelse ($posts as $item)
        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card bg-light d-flex flex-fill shadow-none">
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="card mb-2 bg-gradient-dark">
                            <img class="card-img-top shadow" alt="Cover" src="{{ url('storage/images/' . $item->image) }}" />
                            <div class="card-img-overlay d-flex flex-column justify-content-end">
                                <h5 class="card-title">
                                    <span class="bg-primary" style="padding-left: 3%; padding-right: 3%;">{{ $item->title }}</span>
                                </h5>
                                <p class="card-text text-white pb-2 pt-1"></p>
                                <a href="{{ route('welcome.category', $item->category_id) }}" class="btn btn-info btn-block btn-flat btn-xs" style="width: 49%;"><i class="far fa-circle text-danger"></i> {{ $item->CategoriesBelongsTo->name }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <span class="bg-primary" style="padding-left: 1%; padding-right: 1%;">{{ $item->created_at }}</span>
                        <span class="text-muted" style="margin-left: 10px;"><b>{{ $item->UserBelongsTo->name }}</b></span>
                    </div>
                    <div class="row">
                        <p class="text-muted text-sm">
                            {!!html_entity_decode(Str::limit($item->content, 50))!!}
                        </p>
                    </div>
                    <a href="{{ route('welcome.read', $item->id) }}" class="btn btn-xs btn-primary"> Read More</a>
                </div>
            </div>
        </div>
        @empty
        <div class="error-page">
            <h2 class="headline text-danger">👀</h2>
            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops!</h3>
                <p>Post is empty, pelase write a post</p>
            </div>
        </div>
        @endforelse

    </div>
    <div class="row">
        <div class="col-12">
            {!! $posts->withQueryString()->links('pagination::bootstrap-5') !!}
        </div>
    </div>

</div>

<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Categories</h3>
        </div>

        <div class="card-body p-0">
            <ul class="nav nav-pills flex-column">
                @forelse ($categories as $item)
                <li class="nav-item">
                    <a href="{{ route('welcome.category', $item->id) }}" class="nav-link">
                        <i class="far fa-circle text-danger"></i> {{ $item->name }}
                        <span class="badge bg-primary float-right">{{ $item->PostHasMany->count() }}</span>
                    </a>
                </li>
                @empty
                <li class="nav-item">
                    <span class="nav-link text-danger text-center">
                        Category is empty
                    </span>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
```

**resources/views/welcome/category.blade.php**
```
@extends('layouts.front')

@section('title', 'Category: ' . $categories->name)

@section('content-header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="far fa-circle text-danger"></i> Category: {{ $categories->name }}</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="col-md-9">
    <div class="row">

        @forelse ($posts->where('category_id', '=', $categories->id) as $item)
        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card bg-light d-flex flex-fill shadow-none">
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="card mb-2 bg-gradient-dark">
                            <img class="card-img-top shadow" alt="Cover" src="{{ url('storage/images/' . $item->image) }}" />
                            <div class="card-img-overlay d-flex flex-column justify-content-end">
                                <h5 class="card-title">
                                    <span class="bg-primary" style="padding-left: 3%; padding-right: 3%;">{{ $item->title }}</span>
                                </h5>
                                <p class="card-text text-white pb-2 pt-1"></p>
                                <a href="{{ route('welcome.category', $item->category_id) }}" class="btn btn-info btn-block btn-flat btn-xs" style="width: 49%;"><i class="far fa-circle text-danger"></i> {{ $item->CategoriesBelongsTo->name }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <span class="bg-primary" style="padding-left: 1%; padding-right: 1%;">{{ $item->created_at }}</span>
                        <span class="text-muted" style="margin-left: 10px;"><b>{{ $item->UserBelongsTo->name }}</b></span>
                    </div>
                    <div class="row">
                        <p class="text-muted text-sm">
                            {!!html_entity_decode(Str::limit($item->content, 50))!!}
                        </p>
                    </div>
                    <a href="{{ route('welcome.read', $item->id) }}" class="btn btn-xs btn-primary"> Read More</a>
                </div>
            </div>
        </div>
        @empty
        <div class="error-page">
            <h2 class="headline text-danger">👀</h2>
            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops!</h3>
                <p>Post is empty, pelase write a post</p>
            </div>
        </div>
        @endforelse

    </div>
    <div class="row">
        <div class="col-12">
            {!! $posts->withQueryString()->links('pagination::bootstrap-5') !!}
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Categories</h3>
        </div>

        <div class="card-body p-0">
            <ul class="nav nav-pills flex-column">
                @forelse ($category as $item)
                <li class="nav-item">
                    <a href="{{ route('welcome.category', $item->id) }}" class="nav-link">
                        <i class="far fa-circle text-danger"></i> {{ $item->name }}
                        <span class="badge bg-primary float-right">{{ $item->PostHasMany->count() }}</span>
                    </a>
                </li>
                @empty
                <li class="nav-item">
                    <span class="nav-link text-danger text-center">
                        Category is empty
                    </span>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
```

**resources/views/welcome/posts.blade.php**
```
@extends('layouts.front')

@section('title', $posts->title)

@section('content-header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $posts->title }}</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="col-md-9">
    <div class="row">
        <div class="col-12">
            <div class="invoice p-3 mb-3">
                <div class="row">
                    <img class="card-img-top shadow" alt="Cover" src="{{ url('storage/images/' . $posts->image) }}" />
                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-4">
                        Author
                        <address>
                            <strong>{{ $posts->UserBelongsTo->name }}</strong><br />
                        </address>
                    </div>
                    <div class="col-sm-4">
                        Category
                        <address>
                            <strong><a href="{{ route('welcome.category', $posts->CategoriesBelongsTo->id) }}">{{ $posts->CategoriesBelongsTo->name }}</a></strong><br />
                        </address>
                    </div>
                    <div class="col-sm-4">
                        Publish
                        <address>
                            <strong>{{ $posts->created_at }}</strong><br />
                        </address>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 order-1 order-md-2">
                        <p class="text-muted">
                            {!!html_entity_decode($posts->content)!!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Categories</h3>
        </div>

        <div class="card-body p-0">
            <ul class="nav nav-pills flex-column">
                @forelse ($category as $item)
                <li class="nav-item">
                    <a href="{{ route('welcome.category', $item->id) }}" class="nav-link">
                        <i class="far fa-circle text-danger"></i> {{ $item->name }}
                        <span class="badge bg-primary float-right">{{ $item->PostHasMany->count() }}</span>
                    </a>
                </li>
                @empty
                <li class="nav-item">
                    <span class="nav-link text-danger text-center">
                        Category is empty
                    </span>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
```

Admin Panel

create folder admin/pages/category in views

**resources/views/admin/pages/category/index.blade.php**
```
@extends('layouts.app')

@section('title', 'Category')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Category</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Category</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible text-sm">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Error!</h5>
        {{ $message }}
    </div>
@elseif ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible text-sm">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        {{ $message }}
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('category.create') }}" class="btn btn-dark"><i class="fas fa-plus pr-1"></i> Add New</a>
            </div>
            <div class="card-body">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th width="30px">#</th>
                            <th>Name</th>
                            <th width="130px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('stylesheets')
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@push('scripts')
<!-- DataTables  & Plugins -->
<script src="https://adminlte.io/themes/v3/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/jszip/jszip.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/pdfmake/pdfmake.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/pdfmake/vfs_fonts.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script type="text/javascript">
    $(function(){
        // Token
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Data Tables
        var table = $(".data-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('category.index') }}",
            columns: [
                { data: "id", name: "id" },
                { data: "name", name: "name" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false
                },
            ],
        });

        // Delete
        $('body').on('click', '.delete', function (){
            var get_id = $(this).data("id");
            var result = confirm("Are You sure want to delete !");

            if(result){
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('category.store') }}"+'/'+get_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }else{
                return false;
            }
        });
    });
</script>
@endpush
```

**resources/views/admin/pages/category/create.blade.php**
```
@extends('layouts.app')

@section('title', 'Add New - Category')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Category</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category</a></li>
            <li class="breadcrumb-item active">Add New</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('category.index') }}" class="btn btn-secondary"><i class="fas fa-angle-left pr-1"></i> Back</a>
            </div>

            <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-row">

                        <!-- Photo -->
                        {{-- <div class="form-group col-12">
                            <label for="crud_example_try_photo">Photo</label>
                            <input type="file" wire:model="crud_example_try_photo" id="crud_example_try_photo" class="form-control @error('crud_example_try_photo') is-invalid @enderror" />
                            @error('crud_example_try_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="name">Name</label>
                            <input type="text" name="name" placeholder="Category Name ..." class="form-control @error('name') is-invalid @enderror" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Textarea -->
                        {{-- <div class="form-group col-12">
                            <label for="crud_example_try_textarea">Textarea</label>
                            <textarea wire:model="crud_example_try_textarea" id="crud_example_try_textarea" class="form-control @error('crud_example_try_textarea') is-invalid @enderror"></textarea>
                            @error('crud_example_try_textarea')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
```

**resources/views/admin/pages/category/edit.blade.php**
```
@extends('layouts.app')

@section('title', 'Edit ' . Str::limit($category->name, 10). ' - Category')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Category</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category</a></li>
            <li class="breadcrumb-item active">{{ $category->id }} &bull; {{ Str::limit($category->name, 10) }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('category.index') }}" class="btn btn-secondary"><i class="fas fa-angle-left pr-1"></i> Back</a>
            </div>

            <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="name">Name</label>
                            <input type="text" name="name" placeholder="Category Name ..." class="form-control @error('name') is-invalid @enderror" value="{{ $category->name }}" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
```

**resources/views/admin/pages/category/show.blade.php**
```
@extends('layouts.app')

@section('title', Str::limit($category->name, 10). " - Category")

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Category</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Category</a></li>
            <li class="breadcrumb-item active">{{ $category->id }} &bull; {{ Str::limit($category->name, 10) }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('category.index') }}" class="btn btn-secondary"><i class="fas fa-angle-left pr-1"></i> Back</a>
                <a href="{{ route('category.edit', $category->id) }}" class="btn btn-secondary float-right"><i class="fas fa-pencil-alt"></i> Edit</a>
            </div>

            <form>
                <div class="card-body">
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="name">Name</label>
                            <input type="text" name="name" placeholder="Category Name ..." class="form-control" value="{{ $category->name }}" disabled/>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

```

create folder admin/pages/dashboard in views

**resources/views/admin/pages/dashboard/index.blade.php**
```
@extends('layouts.app')

@section('title', 'Dashboard')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Dashboard</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $posts }}</h3>
                <p>Posts</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ route('post.index') }}" class="small-box-footer">View all <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $categories }}</h3>
                <p>Category</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('category.index') }}" class="small-box-footer">View all <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $total_user }}</h3>
                <p>User Registrations</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <span class="small-box-footer">&nbsp;</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Recently Category (5)</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th width="100x">#</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rec_categories as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">No Category</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Recently Posts</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Author</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rec_posts as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{!!html_entity_decode(Str::limit($item->content, 50))!!}</td>
                            <td>{{ $item->UserBelongsTo->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No Posts</td>
                        </tr>
                        @endforelse


                        {{-- <tr>
                            <td>Title</td>
                            <td>Content</td>
                            <td>User</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
```

create folder admin/pages/post in views

**resources/views/admin/pages/post/index.blade.php**
```
@extends('layouts.app')

@section('title', 'Post')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Post</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Post</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible text-sm">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Error!</h5>
        {{ $message }}
    </div>
@elseif ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible text-sm">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        {{ $message }}
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('post.create') }}" class="btn btn-dark"><i class="fas fa-plus pr-1"></i> Add New</a>
            </div>
            <div class="card-body">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th width="30px">#</th>
                            <th>Title</th>
                            <th width="130px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('stylesheets')
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@push('scripts')
<!-- DataTables  & Plugins -->
<script src="https://adminlte.io/themes/v3/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/jszip/jszip.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/pdfmake/pdfmake.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/pdfmake/vfs_fonts.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script type="text/javascript">
    $(function(){
        // Token
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Data Tables
        var table = $(".data-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('post.index') }}",
            columns: [
                { data: "id", name: "id" },
                { data: "title", name: "title" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false
                },
            ],
        });

        // Delete
        $('body').on('click', '.delete', function (){
            var get_id = $(this).data("id");
            var result = confirm("Are You sure want to delete !");

            if(result){
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('post.store') }}"+'/'+get_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }else{
                return false;
            }
        });
    });
</script>
@endpush
```

**resources/views/admin/pages/post/create.blade.php**
```
@extends('layouts.app')

@section('title', 'Add New - Post')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Post</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('post.index') }}">Post</a></li>
            <li class="breadcrumb-item active">Add New</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('post.index') }}" class="btn btn-secondary"><i class="fas fa-angle-left pr-1"></i> Back</a>
            </div>

            <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-row">

                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="title">Title</label>
                            <input type="text" name="title" placeholder="Title ..." class="form-control @error('title') is-invalid @enderror" />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="form-group col-12">
                            <label for="category">Category</label>
                            <select class="form-control select2" name="category" style="width: 100%;">
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Image --}}
                        <div class="form-group col-12">
                            <label for="image">Image Cover</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image"/>
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                                @error('image')
                                    <div style="width: 100%;margin-top: .25rem;font-size: 80%;color: #dc3545;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="form-group col-12">
                            <label for="content">Content</label>
                            <textarea name="content" id="content_textarea" class="form-control @error('content') is-invalid @enderror"></textarea>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('stylesheets')
<!-- Select2 -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2/css/select2.min.css" />
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" />

<!-- summernote -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.css" />
@endpush

@push('scripts')
<!-- Select2 -->
<script src="https://adminlte.io/themes/v3/plugins/select2/js/select2.full.min.js"></script>
<!-- bs-custom-file-input -->
<script src="https://adminlte.io/themes/v3/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- Summernote -->
<script src="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.js"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2({
            theme: "bootstrap4",
        });

        //Initialize Select2 Elements
        $(".select2bs4").select2({
            theme: "bootstrap4",
        });

        bsCustomFileInput.init();

        // Summernote
        $('#content_textarea').summernote({
            placeholder: 'Text here ...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });


    });
</script>
@endpush
```

**resources/views/admin/pages/post/edit.blade.php**
```
@extends('layouts.app')

@section('title', 'Edit ' . Str::limit($post->title, 10). ' - Category')

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Category</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('post.index') }}">Category</a></li>
            <li class="breadcrumb-item active">{{ $post->id }} &bull; {{ Str::limit($post->title, 10) }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('post.index') }}" class="btn btn-secondary"><i class="fas fa-angle-left pr-1"></i> Back</a>
            </div>

            <form action="{{ route('post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="title">Title</label>
                            <input type="text" name="title" placeholder="Title ..." class="form-control @error('title') is-invalid @enderror" value="{{ $post->title }}" />
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="form-group col-12">
                            <label for="category">Category</label>
                            <select class="form-control select2" name="category" style="width: 100%;">
                                @foreach ($categories as $item)
                                    {{-- <option {{in_array($item->id, $selectedCategories) ? 'selected':''}} value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option> --}}


                                    <option value="{{ $item->id }}" {{ ( $item->id == $post->category_id) ? 'selected' : '' }}> {{ $item->name }} </option>

                                    {{-- <option value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option> --}}
                                @endforeach
                            </select>
                        </div>

                        {{-- Image --}}
                        <div class="form-group col-12">
                            <label for="image">Image</label><br/>
                            <img class="img-fluid pad" src="{{ url('storage/images/' . $post->image) }}" alt="Photo">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image"/>
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                                @error('image')
                                    <div style="width: 100%;margin-top: .25rem;font-size: 80%;color: #dc3545;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="form-group col-12">
                            <label for="content">Content</label>
                            <textarea name="content" id="content_textarea" class="form-control @error('content') is-invalid @enderror">{{ $post->content }}</textarea>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('stylesheets')
<!-- Select2 -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2/css/select2.min.css" />
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" />

<!-- summernote -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.css" />
@endpush

@push('scripts')
<!-- Select2 -->
<script src="https://adminlte.io/themes/v3/plugins/select2/js/select2.full.min.js"></script>
<!-- bs-custom-file-input -->
<script src="https://adminlte.io/themes/v3/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- Summernote -->
<script src="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.js"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2({
            theme: "bootstrap4",
        });

        //Initialize Select2 Elements
        $(".select2bs4").select2({
            theme: "bootstrap4",
        });

        bsCustomFileInput.init();

        // Summernote
        $('#content_textarea').summernote({
            placeholder: 'Text here ...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });


    });
</script>
@endpush
```

**resources/views/admin/pages/post/show.blade.php**
```
@extends('layouts.app')

@section('title', Str::limit($post->title, 10). " - Category")

@section('page_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Category</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('post.index') }}">Category</a></li>
            <li class="breadcrumb-item active">{{ $post->id }} &bull; {{ Str::limit($post->title, 10) }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('post.index') }}" class="btn btn-secondary"><i class="fas fa-angle-left pr-1"></i> Back</a>
                <a href="{{ route('post.edit', $post->id) }}" class="btn btn-secondary float-right"><i class="fas fa-pencil-alt"></i> Edit</a>
            </div>

            <form>
                <div class="card-body">
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-group col-12">
                            <label for="title">Title</label>
                            <input type="text" name="title" placeholder="Title ..." class="form-control" value="{{ $post->title }}" disabled/>
                        </div>

                        <!-- Category -->
                        <div class="form-group col-12">
                            <label for="category">Category</label>
                            <input type="text" name="category" placeholder="Category ..." class="form-control" value="{{ $post->CategoriesBelongsTo->name }}" disabled/>
                        </div>

                        <!-- Image -->
                        <div class="form-group col-12">
                            <label for="category">Image</label><br/>
                            <img class="img-fluid pad" src="{{ url('storage/images/' . $post->image) }}" alt="Photo">
                        </div>

                        <!-- Content -->
                        <div class="form-group col-12">
                            <label for="content">Content</label>
                            <textarea name="content" id="content_textarea" class="form-control @error('content') is-invalid @enderror">{{ $post->content }}</textarea>
                        </div>

                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('stylesheets')
<!-- summernote -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.css" />
@endpush

@push('scripts')
<!-- Summernote -->
<script src="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.js"></script>

<script>
    $(function () {
        // Summernote
        $('#content_textarea').summernote('disable');

    });
</script>
@endpush
```
## Step 8: Writing Controller Logic

**routes/web.php**
```
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');
Route::get('/cat/{category}/', [WelcomeController::class, 'cat'])->name('welcome.category');
Route::get('/read/{post}/', [WelcomeController::class, 'read'])->name('welcome.read');


Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('post', PostController::class);
Route::resource('category', CategoryController::class);
```

**app/Http/Controllers/CategoryController.php**
```
<?php

namespace App\Http\Controllers;

use DataTables;
use Exception;

use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="category/'. $row->id.'" class="view btn btn-primary btn-sm"><i class="fas fa-folder"></i></a> <a href="category/'. $row->id.'/edit" class="edit btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a> <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3',
        ]);

        try {
            Category::create([
                'name'      => $request->name,
                'user_id'   => auth()->user()->id
            ]);

            $response = 'success';
            $message = 'Yay! A category has been successfully created.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to create a new category.';
        }

        return redirect()
        ->route('category.index')
        ->with($response, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('admin.pages.category.show',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.pages.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3',
        ]);

        try {
            $category = Category::find($category->id);
            $category->name = $request->name;
            $category->save();


            $response = 'success';
            $message = 'Yay! A category has been successfully created.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to create a new category.';
        }

        return redirect()
        ->route('category.index')
        ->with($response, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            Category::find($category->id)->delete();

            $response = 'success';
            $message = 'The Category has been successfully deleted.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to delete Category.';
        }

        return response()->json([
            $response => $message
        ]);
    }
}
```

**app/Http/Controllers/PostController.php**
```
<?php

namespace App\Http\Controllers;

use DataTables;
use Exception;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Post::select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="post/'. $row->id.'" class="view btn btn-primary btn-sm"><i class="fas fa-folder"></i></a> <a href="post/'. $row->id.'/edit" class="edit btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a> <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.post.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.pages.post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'     => 'required|min:3',
            'content'   => 'required|min:10',
            'image'     => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'category'  => 'required',
        ]);

        try {
            $filenameWithExt = $request->file('image')->getClientOriginalName ();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . md5(date('YmdHi')) . '.' . $extension;

            // Public Folder
            // $request->image->move(public_path('images'), $fileNameToStore);

            //Store in Storage Folder
            $request->image->storeAs('public/images', $fileNameToStore);

            $save = new Post;
            $save->title        = $request->title;
            $save->content      = $request->content;
            $save->image        = $fileNameToStore;
            $save->category_id  = $request->category;
            $save->user_id      = auth()->user()->id;
            $save->save();

            $response = 'success';
            $message = 'Yay! A Post has been successfully created.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to create a new Post.';
        }

        return redirect()
        ->route('post.index')
        ->with($response, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $categories = Category::all();
        return view('admin.pages.post.show', compact('post', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();

        return view('admin.pages.post.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'title'     => 'required|min:3',
            'content'   => 'required|min:10',
            'image'     => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'category'  => 'required',
        ]);

        try {
            if ($request->hasFile('image')) {
                unlink(public_path('storage/images/' . $post->image));

                $filenameWithExt = $request->file('image')->getClientOriginalName ();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . md5(date('YmdHi')) . '.' . $extension;

                // Public Folder
                // $request->image->move(public_path('images'), $fileNameToStore);

                //Store in Storage Folder
                $request->image->storeAs('public/images', $fileNameToStore);

            } else {
                $fileNameToStore = $post->image;
            }

            $save = Post::find($post->id);
            $save->title        = $request->title;
            $save->content      = $request->content;
            $save->image        = $fileNameToStore;
            $save->category_id  = $request->category;
            $save->user_id      = auth()->user()->id;
            $save->save();

            $response = 'success';
            $message = 'Yay! A category has been successfully created.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to create a new category.' . $e;
        }

        return redirect()
        ->route('post.index')
        ->with($response, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        try {
            Post::find($post->id)->delete();
            unlink(public_path('storage/images/' . $post->image));

            $response = 'success';
            $message = 'The Post has been successfully deleted.';
        } catch (Exception $e) {
            $response = 'error';
            $message = 'Oops! Unable to delete Post.';
        }

        return response()->json([
            $response => $message
        ]);
    }
}
```

**app/Http/Controllers/HomeController.php**
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Count
        $categories = Category::count();
        $posts = Post::count();
        $total_user = User::count();

        // Recently
        $rec_categories = Category::orderBy('id', 'desc')->limit(5)->get();
        $rec_posts = Post::orderBy('id', 'desc')->limit(5)->get();

        // $getPosts = Post::find(1);

        // dd(
        //     $getPosts->CategoriesBelongsTo->name
        // );

        return view('admin.pages.dashboard.index', compact('categories', 'posts', 'total_user', 'rec_categories', 'rec_posts'));
    }
}
```

**app/Http/Controllers/WelcomeController.php**
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Count
        // Category::orderBy('id', 'desc')->limit(5)->get();
        $categories = Category::all();
        $posts = Post::orderBy('id', 'desc')->paginate(5);
        $total_user = User::count();

        // Recently
        $rec_categories = Category::orderBy('id', 'desc')->limit(5)->get();
        $rec_posts = Post::orderBy('id', 'desc')->limit(5)->get();

        // dd(
        //     $categories
        // );

        return view('welcome.index', compact('categories', 'posts', 'total_user', 'rec_categories', 'rec_posts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cat($id)
    {
        $categories = Category::FindOrFail($id);
        $category = Category::all();
        $posts = Post::paginate(5);

        return view('welcome.category', compact('categories', 'category', 'posts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function read($id)
    {
        $posts = Post::FindOrFail($id);
        $category = Category::all();

        return view('welcome.posts', compact('posts', 'category'));
    }

}
```

## Step 9: Create Seeder

Run this command to make create seeder

``php artisan make:seeder UserSeeder``

open, **database/seeders/UserSeeder.php**

```
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
```

Run seeder ``php artisan db:seed --class=UserSeeder``
## Step 10: Create PHPUnit

Run this command to make unit test

``php artisan make:test AuthenticationTest``

``php artisan make:test CategoryControllerTest``

``php artisan make:test HomepageTest``

``php artisan make:test PostControllerTest``

``php artisan make:test RegistrationTest``

OPEN

**tests/Feature/AuthenticationTest.php**
```
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticationTest extends TestCase
{
    public function test_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_login_users_successful()
    {
        $email = Str::random(10).'@gmail.com';
        $password = Hash::make('password');

        $response = $this->post('/register', [
            'name' => Str::random(10),
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_login_users_unsuccessful()
    {
        $response = $this->post('/login', [
            'email' => Str::random(10).'@gmail.com',
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
```

**tests/Feature/CategoryControllerTest.php**
```
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    public function test_category_create_new()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
        ->post(route('category.store'), [
            'name' => Str::random(10),
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));
    }

    public function test_category_create_new_invalid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
        ->from(route('category.create'))
        ->post(route('category.store'), [
            'name' => Str::random(2),
        ]);
        $response->assertStatus(500);
    }
}
```

**tests/Feature/HomepageTest.php**
```
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function test_homepage_successful()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_homepage_unsuccessful()
    {
        $response = $this->get('/non-existing-url');
        $response->assertStatus(200);
    }

    public function test_read_successful()
    {
        $response = $this->get('/read/1');

        $response->assertStatus(200);
    }

    public function test_read_unsuccessful()
    {
        $response = $this->get('/read/non-existing-url');
        $response->assertStatus(200);
    }

    public function test_cat_successful()
    {
        $response = $this->get('/cat/1');

        $response->assertStatus(200);
    }

    public function test_cat_unsuccessful()
    {
        $response = $this->get('/cat/non-existing-url');
        $response->assertStatus(200);
    }
}
```

**tests/Feature/PostControllerTest.php**
```
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Tests\TestCase;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class PostControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_post_create_new()
    {
        Storage::fake('public/images');

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg')->size(100);


        $response = $this->actingAs($user)
        ->post(route('post.store'), [
            'title' => Str::random(10),
            'content' => Str::random(200),
            'image' => $file,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // $this->assertEquals('file/' . $file->hashName(), $file->hashName());
        // Storage::disk('public/images')->assertExists($file->hashName());

        // Assert the file was stored...
        // Storage::disk('public/images')->assertExists($file->hashName());

        // Assert a file does not exist...
        Storage::disk('public/images')->assertMissing('missing.jpg');

        // $response->assertStatus(302);
        // $response->assertRedirect(route('post.index'));
    }
}
```

**tests/Feature/RegistrationTest.php**
```
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationTest extends TestCase
{
    public function test_register()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_register_new_users()
    {
        $password = Hash::make('password');

        $response = $this->post('/register', [
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
```

Run Test ``php artisan test``
## Step 11: Run! Laravel

Open split terminal in VScode,

**New Terminal PowerShell**

``php artisan serve``

**Split Terminal Command Prompt**

``npm run dev``


## URL App

Admin Panel

```
  http://127.0.0.1:8000/login
```
## Related

- [Reference 1](https://laravel-news.com/how-to-start-testing)
- [Reference 2](https://perogeremmer.medium.com/bermain-dengan-php-unit-test-di-laravel-part-1-d67771795733)
