@extends('layouts.main')

@section('title', 'Shopping Cart - LOOKSEE')

@section('head_scripts')
<link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('content')

<div class="contain">
    <div class="cart-container"
        data-update-url="{{ route('cart.update') }}"
        data-delete-url="{{ route('cart.delete') }}"
        data-checkout-url="{{ route('checkout.index') }}" 
        data-csrf-token="{{ csrf_token() }}">

        <a href="{{ route('homepage') }}" class="back-arrow"><i class='bx bx-arrow-back'></i></a>
        <h2>Shopping Cart</h2>

        @if (empty($cart_items))
            <div class="empty-cart-message">
                <p>Your cart is empty. Let's <a href="{{ route('homepage') }}">start shopping!</a></p>
            </div>
        @else
            <div class="cart-header">
                <div class="header-checkbox">
                    <input type="checkbox" id="selectAllItems" class="checkbox-pink">
                    <label for="selectAllItems">Product</label>
                </div>
                <div class="header-price">Unit Price</div>
                <div class="header-quantity">Quantity</div>
                <div class="header-total">Total Price</div>
            </div>

            <div class="cart-items-list">
                @foreach ($cart_items as $id_produk => $item)
                    <div class="cart-item-card" 
                        data-id="{{ $id_produk }}" 
                        data-price="{{ $item['harga'] ?? 0 }}" 
                        data-stock="{{ $item['stock'] ?? 0 }}">
                        <div class="item-selection">
                            <input type="checkbox" class="item-checkbox checkbox-pink" checked>
                        </div>
                        <div class="item-details">
                            <img src="{{ asset('assets/images/produk-looksee/' . ($item['gambar_produk'] ?? 'placeholder.jpg')) }}"
                                class="product-thumb">
                            <span class="product-name">{{ $item['nama_produk'] ?? 'Unknown' }}</span>
                        </div>
                        <div class="item-price">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</div>
                        <div class="item-quantity-control">
                            <button class="quantity-btn decrease-btn" data-action="decrease">-</button>
                            <span class="quantity-value">{{ $item['quantity'] ?? 1 }}</span>
                            <button class="quantity-btn increase-btn" data-action="increase">+</button>
                            <div class="stock-info">Stock: {{ $item['stock'] ?? 0 }}</div>
                        </div>
                        <div class="item-total-price">
                            Rp {{ number_format(($item['harga'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}
                        </div>
                        <button class="remove-item-btn"><i class='bx bx-trash'></i></button>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary-footer">
                <div class="summary-details">
                    <div class="total-price-display">
                        Total Price : <span id="grandTotalPrice">Rp {{ number_format($total_selected_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-product-count-display">
                        Total Products : <span id="totalProductCount">0</span>
                    </div>
                </div>

                <button type="button" class="checkout-button" id="checkoutBtn">Checkout</button>
            </div>
        @endif
    </div>
</div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/cart.js') }}"></script>
@endsection
