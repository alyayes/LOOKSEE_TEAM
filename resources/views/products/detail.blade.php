@extends('layouts.main')

@section('title', 'Detail: ' . $product->nama_produk)

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/product_detail.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="product-detail-container" 
     data-product-id="{{ $product->id_produk }}"
     data-add-to-cart-url="{{ route('cart.add') }}">

    <div class="product-image-section">
        <img src="{{ asset('assets/images/produk-looksee/' . ($product->gambar_produk ?? 'placeholder.jpg')) }}" 
             alt="{{ $product->nama_produk }}"
             class="main-product-image">
    </div>

    <div class="product-info-section">
        <h1 class="product-name">{{ $product->nama_produk }}</h1>
        <p class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>

        <div class="product-description">
            <h3>Deskripsi Produk</h3>
            <p>{!! nl2br(e($product->deskripsi)) !!}</p>
        </div>

        <div class="product-actions">
            <button class="action-btn add-to-cart-btn" onclick="addToCart({{ $product->id_produk }})">
                <i class='bx bx-cart-add'></i> Add to Cart
            </button>
            <button class="action-btn add-to-favorite-btn" onclick="addToFavorite({{ $product->id_produk }})">
                <i class='bx bx-heart'></i> Favorite
            </button>
        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
<script>
    function addToCart(productId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                id_produk: productId,
                quantity: 1
            })
        })
        .then(response => {
            if (response.status === 401) {
                alert("Silakan login terlebih dahulu!");
                window.location.href = "{{ route('login') }}";
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.status === 'success') {
                alert(data.message);
            } else if (data) {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function addToFavorite(id) {
        fetch("{{ route('products.addToFavorite') }}", {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            },
            body: JSON.stringify({ id_produk: id })
        })
        .then(res => res.json())
        .then(data => alert(data.message))
        .catch(err => console.error('Error:', err));
    }
</script>
@endsection