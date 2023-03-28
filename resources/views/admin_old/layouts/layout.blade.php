<?php use App\Http\Controllers\CommonController; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="icon" type="image/png" href="{{(new CommonController)->GetSetting('favicon')}}" />
        <title>{{(new CommonController)->GetSetting('company_name')}} - @yield('title')</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="{{URL::asset('admin-panel/css/styles.css')}}" rel="stylesheet" />
        <link href="{{URL::asset('admin-panel/css/preloader.css')}}" rel="stylesheet">
        @yield("style")
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <style>
    .required::before {
        content:" *";
        color: red;
    }
    </style>
    <body class="sb-nav-fixed">
        @include('admin.layouts.header')
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                @include('admin.layouts.navbar')
            </div>
            <div id="layoutSidenav_content">
                <main>
                    @include('admin.layouts.preloader')
                    @include('admin.layouts.notification')
                    @yield('content')
                </main>
                @include("admin.layouts.footer")
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{URL::asset('admin-panel/js/scripts.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="{{URL::asset('admin-panel/assets/demo/chart-area-demo.js')}}"></script>
        <script src="{{URL::asset('admin-panel/assets/demo/chart-bar-demo.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="{{URL::asset('admin-panel/js/datatables-simple-demo.js')}}"></script>
        <script>
        window.onload = (event) => {
            $("#pre-loader").hide();
        };
        $(document).ready(function() {

        });
    </script>

    @yield('script')
    </body>
</html>
