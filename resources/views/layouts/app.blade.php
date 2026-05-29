<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '메일 발송 테스트')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="min-h-screen bg-slate-100 flex @yield('body-class', 'items-start') justify-center p-4 sm:p-8 font-sans text-slate-900">
@yield('content')
</body>
</html>
