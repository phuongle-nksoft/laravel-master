<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control panel</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{url('nksoft/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{url('nksoft/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{url('nksoft/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{url('nksoft/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{mix('/nksoft/css/app.css')}}">
    <!-- Styles -->
    @yield('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('master::parts.header')
        @include('master::parts.sidebar')
        <div class="content-wrapper">
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

    <!-- Bootstrap 4 -->
    <script src="{{url('nksoft/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{url('nksoft/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{url('nksoft/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{url('nksoft/plugins/editor/ckeditor/ckeditor.js')}}"></script>
    <script src="{{url('nksoft/plugins/editor/ckeditor/config.js')}}"></script>
    <script src="{{url('nksoft/plugins/editor/ckfinder/ckfinder.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{url('nksoft/plugins/daterangepicker/daterangepicker.js')}}"></script>
    @yield('script')
    <script src="{{mix('/nksoft/js/app.js')}}"></script>
    <script>
        (function() {
            console.log(abc);
        }).jQuery();
        $(document).ready(function() {
            console.log('abc');
            console.log($('.daterangepicker'));
            if($('.daterangepicker').length) {
                console.log($('.daterangepicker'));
            }
        });
    </script>
</body>

</html>
