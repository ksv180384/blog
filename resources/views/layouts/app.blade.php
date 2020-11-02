<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free/css/all.min.css') }}">

    @stack('styles')

    <title>{{ !empty($title) ? $title : config('app.name', 'Laravel') }}</title>
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>

        </ul>

        <!-- Right navbar links -->
        @guest
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/login">
                        <i class="fas fa-sign-in-alt"></i>
                    </a>
                </li>
            </ul>
        @else
            <? /*
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
            */?>
        @endguest
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link elevation-4">
            <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png"
                 alt="Logo"
                 class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light ml-4">Блог</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            @guest
            @else
            <div class="user-panel mt-3 pb-3 mb-3 d-flex dropdown-toggle text-link dropdown-after-top-user-name" data-toggle="dropdown" aria-expanded="false">
                <div class="image">
                    <div style="background-image: url({{ \Auth::user()->avatar }})" class="img-circle avatar-circle-mini elevation-2 js-user-avatar"></div>
                </div>
                <div class="info">
                    <span class="d-block">{{ \Auth::user()->name }}</span>
                </div>
            </div>
            <ul class="dropdown-menu text-dropdown-default" x-placement="bottom-start">
                <li class="dropdown-item">
                    <a href="{{ route('profile.index') }}" class="text-dark">
                        <i class="far fa-address-card"></i> Профиль
                    </a>
                </li>
                <li class="dropdown-item">
                    <a href="{{ route('post.my') }}" class="text-dark">
                        <i class="far fa-address-card"></i> Мои посты
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li class="dropdown-item">
                    <a class="text-dark"  href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Выход
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
            @endguest

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('post.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Добавить пост</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('follow') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Подписан</p>
                            </a>
                        </li>
                    @endauth
                    @if(Gate::check('user-list') || Gate::check('role-list') || Gate::check('blog-posts-control'))
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Управление
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('user-list')
                                    <li class="nav-item">
                                        <a href="{{ route('user.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Пользователи</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('role-list')
                                    <li class="nav-item">
                                        <a href="{{ route('roles.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Права</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('blog-posts-control')
                                    <li class="nav-item">
                                        <a href="{{ route('adm_post.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Посты</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        @yield('content')

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; 2019{{ (date("Y") != '2019') ? ' - ' . date("Y") : '' }} Тестовый блог на шаблоне <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<!-- jQuery -->
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('js/bootstrap/bootstrap.bundle.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js') }}"></script>

<!-- index script -->
<script src="{{ asset('js/index.js') }}"></script>

<link href="{{ asset('js/toastr/toastr.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/toastr/toastr.min.js') }}"></script>

@stack('scripts')

</body>
</html>
