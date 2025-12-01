@extends('layouts.main') 
@section('title', 'LOOKSEE')

@section('head_scripts')
<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
@endsection

@section('content')

<section class="main-home">
    <div class="main-text">
        <div class="slider">
            <h5>New Collection</h5>
            <h1>New Woman Tops <br> Collection</h1>
            <p>Outfit the Day, Own the Mood!</p>
            <a href="#mood" class="main-btn">Suits the Mood <i class='bx bx-right-arrow-alt'></i></a>
            <a href="#latest-product" class="main-btn">Shop Now <i class='bx bx-right-arrow-alt'></i></a>
        </div>
    </div>
</section>

<section class="main-about">
    <div class="main-about-us">
        <h2>Why <span>Choose Us?</span></h2>
        <p><d>LOOKSEE</d> is an innovative platform...</p>
        <br>

        <div class="about-icon">
            <h>
                <i class='bx bx-wink-smile'> Full Personalization</i>
                <p>The recommended outfit truly matches each user's mood.</p>
                <br>

                <i class='bx bx-trending-up'> Current Trends</i>
                <p>Stay updated with the latest fashion trends.</p>
                <br>

                <i class='bx bx-wallet'> Quality Assurance and Budget-Friendly</i>
                <p>Trusted brands & budget-friendly prices.</p>
            </h>
        </div>
    </div>
</section>

{{-- ================= WOMAN ================= --}}
<section class="recommend-product" id="latest-product">
    <div class="text">
        <h2>Latest <span>Products</span></h2>
        <h3>Woman</h3>
    </div>

    <div class="product-grid">
        @forelse($productsWoman as $row)
        <div class="product-card">
            <div class="product-image">
                <img 
                    src="{{ asset('assets/images/produk-looksee/' . $row['gambar_produk']) }}" 
                    alt="{{ $row['nama_produk'] }}">
            </div>

            <div class="product-details">
                <h4>{{ $row['nama_produk'] }}</h4>
                <p>Rp. {{ number_format($row['harga'], 0, ',', '.') }}</p>

                <div class="actions">
                    <button class="btn favorite-btn" 
                            onclick="addToFavorites({{ $row['id_produk'] }})">
                        Add to Favorite
                    </button>

                    <button class="btn buy-now-btn" 
                            onclick="addToCart({{ $row['id_produk'] }})">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        @empty
            <p>Tidak ada produk wanita saat ini.</p>
        @endforelse
    </div>

    <div class="page-btn">
        @for ($i = 1; $i <= $totalPagesWoman; $i++)
            <span class="{{ $i == $pageWoman ? 'active' : '' }}" onclick="goToPage('page_woman', {{ $i }})">
                {{ $i }}
            </span>
        @endfor

        @if ($pageWoman < $totalPagesWoman)
            <span onclick="goToPage('page_woman', {{ $pageWoman + 1 }})">&#8594;</span>
        @endif
    </div>
</section>

{{-- ================= MAN ================= --}}
<section class="recommend-product" id="recommend">
    <div class="text">
        <h3>Man</h3>
    </div>

    <div class="product-grid">
        @forelse($productsMan as $row)
        <div class="product-card">
            <div class="product-image">
                <img 
                    src="{{ asset('assets/images/produk-looksee/' . $row['gambar_produk']) }}" 
                    alt="{{ $row['nama_produk'] }}">
            </div>

            <div class="product-details">
                <h4>{{ $row['nama_produk'] }}</h4>
                <p>Rp. {{ number_format($row['harga'], 0, ',', '.') }}</p>

                <div class="actions">
                    <button class="btn favorite-btn" 
                            onclick="addToFavorites({{ $row['id_produk'] }})">
                        Add to Favorite
                    </button>

                    <button class="btn buy-now-btn" 
                            onclick="addToCart({{ $row['id_produk'] }})">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        @empty
            <p>Tidak ada produk pria saat ini.</p>
        @endforelse
    </div>

    <div class="page-btn">
        @for ($i = 1; $i <= $totalPagesMan; $i++)
            <span class="{{ $i == $pageMan ? 'active' : '' }}" onclick="goToPage('page_man', {{ $i }})">
                {{ $i }}
            </span>
        @endfor

        @if ($pageMan < $totalPagesMan)
            <span onclick="goToPage('page_man', {{ $pageMan + 1 }})">&#8594;</span>
        @endif
    </div>
</section>

@include('home.mood')

@include('home.mood')


{{-- ========================  PARTNERSHIP  ======================== --}}
<div class="partner-platform-wrapper">
    <div class="partner-group">
        <h3 class="partner-title">Our Partner</h3>
        <div class="partner-logos">
            <a href="https://www.instagram.com/satriabandungjaya/">
                <img src="{{ asset('assets/images/sbj.jpg') }}" alt="Satria Bandung Jaya">
            </a>
        </div>
    </div>

    <div class="platform-group">
        <h3 class="platform-title">Our Platform</h3>
        <div class="platform-logos">
            <a href="https://shopee.co.id/"><img src="{{ asset('assets/images/shopee.jpg') }}" alt="Shopee"></a>
            <a href="https://www.tokopedia.com/"><img src="{{ asset('assets/images/tokped.jpg') }}" alt="Tokopedia"></a>
        </div>
    </div>
</div>

@endsection

@section('footer_scripts')
<script>
    function addToFavorites(id) {
    const formData = new FormData();
    formData.append('id_produk', id);
    formData.append('_token', "{{ csrf_token() }}");

    fetch("{{ route('favorite.add') }}", {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => alert(data.message));
}

function addToCart(id) {
    const formData = new FormData();
    formData.append('id_produk', id);
    formData.append('_token', "{{ csrf_token() }}");

    fetch("{{ route('cart.add') }}", {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => alert(data.message));
}

</script>
@endsection
