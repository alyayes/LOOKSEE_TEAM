<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOOKSEE ADMIN | @yield('title', 'Dashboard')</title>

    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png"/>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet"/>
    
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet"/>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}"/>

    @yield('styles')
</head>

<body>

    <div class="wrapper">

        @include('layouts.headerAdmin')

        <main class="page-wrapper">
            <div class="page-content">
                @yield('content')
            </div>
        </main>

        @include('layouts.footerAdmin')

    </div> 
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/perfect-scrollbar.js') }}"></script>
    
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Logika untuk tombol Dark Mode
            $(".dark-mode-icon").on("click", function(e) {
                e.preventDefault(); // Mencegah link pindah halaman

                // Cek apakah sedang mode gelap atau terang berdasarkan ikon
                if ($(this).find("i").hasClass("bx-moon")) {
                    // Masuk ke Dark Mode
                    $(this).find("i").removeClass("bx-moon").addClass("bx-sun"); // Ubah ikon jadi Matahari
                    $("html").addClass("dark-theme"); // Tambah class ke HTML (micu css/dark-theme.css)
                } else {
                    // Kembali ke Light Mode
                    $(this).find("i").removeClass("bx-sun").addClass("bx-moon"); // Ubah ikon jadi Bulan
                    $("html").removeClass("dark-theme"); // Hapus class dari HTML
                }
            });
        });
    </script>

    @yield('scripts')

</body>
</html>