<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="{{url('css/app.css')}}">
    <!-- Styles -->
    @yield('style')
</head>

<body class="skin-blue">
    @include('master::parts.header')
    @include('master::parts.sidebar')
    <div class="content-wrapper">
        @yield('content')
    </div>
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            v3.0.2
        </div>
        <strong>Copyright &copy; 2014-2020 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
    <script src="{{url('js/app.js')}}"></script>
    @yield('script')
</body>

</html>