<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Oasis Admin') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skins/skin-blue.min.css') }}">

</head>

<body class="skin-blue layout-top-nav hold-transition login-page">

<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <!-- Logo -->
                <a class="logo" href="#">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><img style="padding:5px;" src="{{ asset('storage/images/yzipet_logo.png') }}" height="50px" width="50px"></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><img style="border-radius: 50px;padding:5px;" src="{{ asset('storage/images/yzipet_logo.png') }}" height="50px" width="50px"> Yzipet @lang('Admin')</span>
                </a>
            </div>
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>

    @yield('content')
</body>
</html>
