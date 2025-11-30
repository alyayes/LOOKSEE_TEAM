@extends('layouts.main')

@section('title', 'Home | LOOKSEE')

@section('head_scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@endsection

@section('content')

    <section class="main-home">
        <div class="main-text">
            <div class="slider">
                <h5>New Collection</h5>
                <h1>New Woman Tops <br> Collection</h1>
                <p>Outfit the Day, Own the Mood!</p>
                <a href="#mood" class="main-btn">Suits the Mood<i class='bx bx-right-arrow-alt'></i></a>
                <a href="#latest-product" class="main-btn">Shop Now<i class='bx bx-right-arrow-alt'></i></a>
            </div>
        </div>
    </section>

    <section class="main-about">
        <div class="main-about-us">
            <h2>Why <span>Choose Us?</span></h2>
            <p>
                <d>LOOKSEE</d> is an innovative fashion platform that provides outfit recommendations for campus activities.
                We offer a personalization feature that allows users to customize their outfit concepts according to their
                mood. ...
                <a href="{{ url('aboutLaPe') }}"><i>Learn More</i></a>
            </p>
            <br>
            <div class="about-icon">
                <i class='bx bx-wink-smile'> Full Personalization</i>
                <p>The recommended outfit truly matches each user's mood, providing freedom of expression and a unique
                    appearance.</p>
                <br>
                <i class='bx bx-trending-up'> Current Trends</i>
                <p>Always stay updated with the latest fashion trends and provide outfit recommendations that make you feel
                    confident on campus.</p>
                <br>
                <i class='bx bx-wallet'> Quality Assurance and Budget-Friendly</i>
                <p>All product recommendations are from trusted brands with high quality, and they fit various budgets
                    suitable for students.</p>
            </div>
        </div>
    </section>

    {{-- WOMAN SECTION --}}
    <section class="recommend-product" id="latest-product">
        <div class="text">
            <h2>Latest <span>Products</span></h2>
            <h3>Woman</h3>
        </div>
        <div class="product-grid">
            @foreach ($produk_woman as $row)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('storage/uploads/' . $row->gambar_produk) }}" alt="{{ $row->nama_produk }}">
                    </div>
                    <div class="product-details">
                        <h4>{{ $row->nama_produk }}</h4>
                        <p>Rp. {{ number_format($row->harga, 0, ',', '.') }}</p>
                        <div class="actions">
                            <button class="btn favorite-btn" onclick="addToFavorites({{ $row->id_produk }})">Add to
                                Favorite</button>
                            <button class="btn buy-now-btn" onclick="addToCart({{ $row->id_produk }})">Add to Cart</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="page-btn">
            @for ($i = 1; $i <= $total_pages_woman; $i++)
                <span onclick="goToPage('page_woman', {{ $i }})">{{ $i }}</span>
            @endfor
            @if ($page_woman < $total_pages_woman)
                <span onclick="goToPage('page_woman', {{ $page_woman + 1 }})">&#8594;</span>
            @endif
        </div>
    </section>

    {{-- MAN SECTION --}}
    <section class="recommend-product" id="recommend">
        <div class="text">
            <h3>Man</h3>
        </div>
        <div class="product-grid">
            @foreach ($produk_man as $row)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('uploads/' . $row->gambar_produk) }}" alt="{{ $row->nama_produk }}">
                    </div>
                    <div class="product-details">
                        <h4>{{ $row->nama_produk }}</h4>
                        <p>Rp. {{ number_format($row->harga, 0, ',', '.') }}</p>
                        <div class="actions">
                            <button class="btn favorite-btn" onclick="addToFavorites({{ $row->id_produk }})">Add to
                                Favorite</button>
                            <button class="btn buy-now-btn" onclick="addToCart({{ $row->id_produk }})">Add to Cart</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="page-btn">
            @for ($i = 1; $i <= $total_pages_man; $i++)
                <span onclick="goToPage('page_man', {{ $i }})">{{ $i }}</span>
            @endfor
            @if ($page_man < $total_pages_man)
                <span onclick="goToPage('page_man', {{ $page_man + 1 }})">&#8594;</span>
            @endif
        </div>
    </section>

    <div class="center-text">
        <h3>Our Partner</h3>
        <h3>Our Platform</h3>
    </div>
    <div class="baru">
        <sbj>
            <a href="https://www.instagram.com/satriabandungjaya/" class="brand-toko">
                <img src="{{ asset('assets/images/sbj.jpg') }}">
            </a>
        </sbj>
        <section class="brand" id="brand">
            <a href="https://shopee.co.id/" class="brand-toko"><img src="{{ asset('assets/images/shopee.jpg') }}"></a>
            <a href="https://www.tokopedia.com/" class="brand-toko"><img src="{{ asset('assets/images/tokped.jpg') }}"></a>
        </section>
    </div>

@endsection

@section('footer_scripts')
    <script>
        function goToPage(param, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(param, value);
            window.location.href = url.toString();
        }

        function addToFavorites(id) {
            fetch('/add-to-favorite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_produk: id
                    })
                })
                .then(res => res.json())
                .then(data => alert(data.message));
        }

        function addToCart(id) {
            fetch('/add-to-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_produk: id
                    })
                })
                .then(res => res.json())
                .then(data => alert(data.message));
        }
    </script>
@endsection
