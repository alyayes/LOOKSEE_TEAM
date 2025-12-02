@extends('layouts.main')

@section('title', 'Detail: ' . ($product['nama_produk'] ?? 'Product') . ' | LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/product_detail.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
@endsection

@section('content')

{{-- Container utama untuk menampung semua data JS --}}
<div class="product-detail-container"
     data-add-to-cart-url="{{ route('products.addToCart') }}"
     data-add-to-favorite-url="{{ route('products.addToFavorite') }}"
     data-csrf-token="{{ csrf_token() }}"
     data-product-id="{{ $product['id_produk'] }}">

    {{-- GAMBAR PRODUK --}}
    <div class="product-image-section">
        <img src="{{ asset('assets/images/produk-looksee/' . ($product['gambar_produk'] ?? 'placeholder.jpg')) }}"
             alt="{{ $product['nama_produk'] }}"
             onerror="this.onerror=null;this.src='https://placehold.co/400x400/EFEFEF/AAAAAA?text=No+Image';"
             class="main-product-image">
    </div>

    {{-- INFORMASI PRODUK --}}
    <div class="product-info-section">
        <h1 class="product-name">{{ $product['nama_produk'] }}</h1>
        <p class="product-price">Rp {{ number_format($product['harga'], 0, ',', '.') }}</p>

        <div class="product-description">
            <h3>Deskripsi Produk</h3>
            <p>{!! nl2br(e($product['deskripsi'])) !!}</p>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="product-actions">
            <button class="action-btn add-to-cart-btn" data-product-id="{{ $product['id_produk'] }}">
                <i class='bx bx-cart-add'></i> Add to Cart
            </button>

            {{-- tombol favorite pakai satu fungsi saja: addToFavorites --}}
            <button class="action-btn add-to-favorite-btn" data-product-id="{{ $product['id_produk'] }}">
                <i class='bx bx-heart'></i> Favorite
            </button>
        </div>
    </div>

</div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/product_detail.js') }}"></script>
@endsection
