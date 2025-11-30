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
            <a href="#mood" class="main-btn">Suits the Mood<i class='bx bx-right-arrow-alt'></i></a>
            <a href="#latest-product" class="main-btn">Shop Now<i class='bx bx-right-arrow-alt'></i></a>
        </div>
    </div>
</section>

<section class="main-about">
    <div class="main-about-us">
        <h2>Why <Span>Choose Us?</Span></h2>     
        <p><d>LOOKSEE</d> is an innovative fashion platform that provides outfit recommendations for campus activities. 
            We offer a personalization feature that allows users to customize their outfit concepts according to their mood. ... 
            <a href="aboutLaPe.php"><i>Learn More</i></a>
        </p>
        <br>
    </div>
</section>


{{-- ========================  PRODUK WANITA  ======================== --}}
<section class="recommend-product" id="latest-product">
    <div class="text">
        <h2>Latest <span>Products</span></h2>
        <h3>Woman</h3>
    </div> 

    <div class="product-grid">
        @forelse($productsWoman as $row)
        <div class="product-card">
            <div class="product-image">
                <img src="{{ asset('storage/uploads/' . $row['gambar_produk']) }}" 
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
</section>


{{-- ========================  PRODUK PRIA  ======================== --}}
<section class="recommend-product" id="recommend">
    <div class="text">
        <h3>Man</h3>
    </div>

    <div class="product-grid">
        @forelse($productsMan as $row)
        <div class="product-card">
            <div class="product-image">
                <img src="{{ asset('storage/uploads/' . $row['gambar_produk']) }}" 
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
</section>


@include('home.mood')


{{-- ========================  PARTNERSHIP  ======================== --}}
<div class="partner-platform-wrapper">
    <div class="partner-group">
        <h3 class="partner-title">Our Partner</h3>
        <div class="partner-logos">
            <a href="https://www.instagram.com/satriabandungjaya/" class="brand-toko">
                <img src="{{ asset('assets/images/sbj.jpg') }}" alt="Satria Bandung Jaya">
            </a>
        </div>
    </div>

    <div class="platform-group">
        <h3 class="platform-title">Our Platform</h3>
        <div class="platform-logos">
            <a href="https://shopee.co.id/" class="brand-toko">
                <img src="{{ asset('assets/images/shopee.jpg') }}" alt="Shopee">
            </a>
            <a href="https://www.tokopedia.com/" class="brand-toko">
                <img src="{{ asset('assets/images/tokped.jpg') }}" alt="Tokopedia">
            </a>
        </div>
    </div>
</div>


@endsection


{{-- ========================  JAVASCRIPT FAVORITE & CART  ======================== --}}
@section('footer_scripts')
<script>

function addToFavorites(idProduk) {
    fetch("{{ route('products.addToFavorite') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            id_produk: idProduk
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan saat menambah favorite.");
    });
}


function addToCart(idProduk) {
    fetch("{{ route('products.addToCart') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            id_produk: idProduk
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan saat menambah ke keranjang.");
    });
}

</script>
@endsection
