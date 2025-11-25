<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- title --}}
    <title>@yield('title', 'LOOKSEE Platform')</title>

    {{-- CSS UMUM --}}
    <link rel="stylesheet" href="{{ asset ('assets/css/header2.css') }}">
    <link rel="stylesheet" href="{{ asset ('assets/css/footer2.css') }}">

    {{-- Link Fonts dan Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=favorite" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" 
    integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF@nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    {{-- CSS khusus --}}
    @yield('head_scripts') 
</head>
<body>

    @if (!isset($hideHeader) || !$hideHeader)
        @include('layouts.header')
    @endif

    {{-- Konten --}}
    <div id="app">
        @yield('content')
    </div>

    @if (!isset($hideFooter) || !$hideFooter)
        @include('layouts.footer')
    @endif

    {{-- Script JS --}}
    @yield('footer_scripts') 
    
</body>
</html>