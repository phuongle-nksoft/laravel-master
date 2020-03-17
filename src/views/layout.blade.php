<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control panel</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{url('nksoft/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{url('nksoft/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{url('nksoft/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{url('nksoft/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{url('nksoft/css/app.css')}}">
    <!-- Styles -->
    @yield('style')
</head>
@php
$breadcrumb = [
'title' => 'Dashboard',
'breadcrumb' => [
['title' => 'Home', 'link' => url('admin')],
['title' => 'Dashboard', 'link' => url('admin/dashboard')]
]];
@endphp

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('master::parts.header')
        @include('master::parts.sidebar')
        <div class="content-wrapper">
            @include('master::parts.breadcrumb', $breadcrumb)
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @if($element == 'list')
                    <div id="nk-list"></div>
                    @else
                    <div id="nk-form"></div>
                    @endif
                </div>
            </section>
            @yield('content')
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2014-{{date('Y')}}.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.0.2
        </div>
    </footer>
    <!-- jQuery -->
    <script src="{{url('nksoft/plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{url('nksoft/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- overlayScrollbars -->
    <script src="{{url('nksoft/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{url('nksoft/js/adminlte.min.js')}}"></script>
    @yield('script')
    <script src="{{url('nksoft/js/app.js')}}"></script>
</body>

</html>
