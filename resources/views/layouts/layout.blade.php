<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理中心 - @yield('title', config('app.name', 'Laravel'))</title>
    <meta name="keywords" content="{{ config('app.name', 'Laravel') }}">
    <meta name="description" content="{{ config('app.name', 'Laravel') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="/favicon.ico">
    <link href="{{loadEdition('/admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{loadEdition('/admin/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{loadEdition('/admin/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{loadEdition('/admin/css/style.min.css')}}" rel="stylesheet">
    <link href="{{loadEdition('/admin/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">
    @yield('css')
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    @include('flash::message')
    @yield('content')
</div>
<script src="{{loadEdition('/js/jquery.min.js')}}"></script>
<script src="{{loadEdition('/admin/js/bootstrap.min.js')}}"></script>
<script src="{{loadEdition('/admin/js/plugins/toastr/toastr.min.js')}}"></script>
@yield('js')
<script>
    $('div.alert').delay(3000).fadeOut(350);
    // var address = 'ws://127.0.0.1:9502';
    // var webSocket = new WebSocket(address);
    // $(function(){
    //     $("#btn").click(function(){
    //         webSocket.send(JSON.stringify({
    //             'message': "test",
    //             'type': 'chat'
    //         }));
    //     });
    //
    //     webSocket.onerror = function (event) {
    //         //alert('服务器连接错误，请稍后重试');
    //     };
    //     webSocket.onopen = function (event) {
    //
    //         username = "test";
    //         webSocket.send(JSON.stringify({
    //             'message': username,
    //             'type': 'init'
    //         }));
    //     };
    //     webSocket.onmessage = function (event) {
    //         console.log(event);
    //
    //         let data = JSON.parse(event.data)
    //         toastr.options = {
    //             "closeButton": true,
    //             "debug": false,
    //             "progressBar": true,
    //             "positionClass": "toast-top-right",
    //             "onclick": null,
    //             "showDuration": "400",
    //             "hideDuration": "1000",
    //             "timeOut": "7000",
    //             "extendedTimeOut": "1000",
    //             "showEasing": "swing",
    //             "hideEasing": "linear",
    //             "showMethod": "fadeIn",
    //             "hideMethod": "fadeOut"
    //         }
    //         toastr.success(data.message)
    //
    //     };
    // });
</script>
@yield('footer-js')
</body>
</html>