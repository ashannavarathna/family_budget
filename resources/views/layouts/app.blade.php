<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>家計簿 - @yield('title')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    
    {{-- Favicon: 以前の会話で指定されたパスを使用 --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('adminlte/dist/img/accounting1.png') }}">
</head>
{{-- body_classセクションは、ログインページでは 'hold-transition login-page' に、管理画面では 'hold-transition sidebar-mini' などに置き換えられます --}}
<body class="@yield('body_class', 'hold-transition sidebar-mini ')">

    {{-- ログインページでは @yield('content') のみを使用 --}}
    @yield('content')
    
    {{-- トースト/セッションメッセージの表示ロジックは保持しますが、スタイルはAdminLTE/Bootstrapを想定 --}}
    @if(session('success'))
    <div class="fixed bottom-4 right-4 bg-success text-white px-4 py-3 rounded-lg shadow-lg text-sm max-w-xs">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="fixed bottom-4 right-4 bg-danger text-white px-4 py-3 rounded-lg shadow-lg text-sm max-w-xs">
        {{ session('error') }}
    </div>
    @endif
    
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    
    @stack('scripts')
</body>
</html>