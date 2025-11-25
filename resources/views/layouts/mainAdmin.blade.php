<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOOKSEE ADMIN | @yield('title', 'Dashboard')</title>
        
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png"/>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link href="{{ asset('assets/css/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet"/>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}"/>

    {{-- Placeholder untuk CSS kustom halaman tertentu (misalnya, CSS tabel di index.blade.php) --}}
    @yield('styles') 
</head>
<body>
   
    @include('layouts.headerAdmin')

    {{-- 2. KONTEN UTAMA (Page Wrapper) --}}
    <main class="page-wrapper">
        <div class="page-content">
            {{-- Ini adalah placeholder (@yield) tempat konten spesifik halaman (index/create) akan dimasukkan --}}
            @yield('content')
        </div>
    </main>
    
    {{-- 3. FOOTER --}}
    
    @include('layouts.footerAdmin')
    
    {{-- Placeholder untuk JavaScript kustom --}}
    @yield('scripts')

</body>
</html>