@extends('layouts.main')

@section('title', 'Shopping Cart - LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<div class="contain">
    <div class="cart-container">
        <a href="{{ route('homepage') }}" class="back-arrow"><i class='bx bx-arrow-back'></i></a>
        <h2>Shopping Cart</h2>

        @if (empty($cart_items) || count($cart_items) == 0)
            <div class="empty-cart-message" style="text-align: center; padding: 50px;">
                <i class='bx bx-cart-alt' style="font-size: 50px; color: #ccc;"></i>
                <p>Keranjangmu kosong. <a href="{{ route('homepage') }}" style="color: #ff6b81; font-weight: bold;">Belanja sekarang!</a></p>
            </div>
        @else
            <div class="cart-header">
                <div class="header-checkbox">
                    <input type="checkbox" id="selectAllItems" class="checkbox-pink" checked>
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
                            <img src="{{ asset(($item['gambar_produk'] ?? 'default.jpg')) }}" 
                                 alt="{{ $item['nama_produk'] }}"
                                 class="product-thumb"
                                 onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'"> 
                            
                            <div class="item-info">
                                <span class="product-name">{{ $item['nama_produk'] ?? 'Produk Tidak Dikenal' }}</span>
                            </div>
                        </div>

                        <div class="item-price">
                            Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}
                        </div>
                        
                        <div class="item-quantity-control">
                            <button class="quantity-btn decrease-btn" onclick="updateQty({{ $id_produk }}, 'decrease')">-</button>
                            <span class="quantity-value" id="qty-{{ $id_produk }}">{{ $item['quantity'] ?? 1 }}</span>
                            <button class="quantity-btn increase-btn" onclick="updateQty({{ $id_produk }}, 'increase')">+</button>
                            <div class="stock-info">Stock: {{ $item['stock'] ?? 0 }}</div>
                        </div>

                        <div class="item-total-price" id="total-{{ $id_produk }}">
                            Rp {{ number_format(($item['harga'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}
                        </div>

                        <form action="{{ route('cart.delete') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id_produk" value="{{ $id_produk }}">
                            <button type="submit" class="remove-item-btn" onclick="return confirm('Hapus barang ini?')">
                                <i class='bx bx-trash'></i>
                            </button>
                        </form>

                    </div>
                @endforeach
            </div>

            <div class="cart-summary-footer">
                <div class="summary-details">
                    <div class="total-price-display">
                        Total Price : <span id="grandTotalPrice">Rp 0</span>
                    </div>
                    
                    <div class="total-product-count-display">
                        Total Products : <span id="totalProductCount">0</span>
                    </div>
                </div>

                <form id="checkoutForm" action="{{ route('checkout.index') }}" method="GET">
                    <input type="hidden" name="selected_products" id="selectedProductsInput">
                    <button type="button" class="checkout-button" onclick="submitCheckout()">Checkout</button>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
    function formatRupiah(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }

    function updateQty(id, action) {
        let qtySpan = document.getElementById('qty-' + id);
        let card = qtySpan.closest('.cart-item-card');
        let itemTotalSpan = document.getElementById('total-' + id);
        
        let currentQty = parseInt(qtySpan.innerText);
        let price = parseInt(card.dataset.price);
        let stock = parseInt(card.dataset.stock);

        let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;

        if (newQty < 1) return;
        if (newQty > stock) {
            alert("Stok tidak mencukupi!");
            return;
        }

        qtySpan.innerText = newQty;
        let newItemTotal = price * newQty;
        itemTotalSpan.innerText = formatRupiah(newItemTotal);

        updateSummary();

        fetch("{{ route('cart.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                id_produk: id,
                quantity: newQty
            })
        }).then(response => {
            console.log("Qty updated successfully");
        }).catch(err => {
            console.error(err);
            qtySpan.innerText = currentQty;
            alert("Gagal koneksi ke server");
        });
    }

    function updateSummary() {
        let total = 0;
        let count = 0;
        
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            let card = checkbox.closest('.cart-item-card');
            let price = parseInt(card.dataset.price);
            let qty = parseInt(card.querySelector('.quantity-value').innerText);
            
            total += price * qty;
            count += qty; 
        });

        document.getElementById('grandTotalPrice').innerText = formatRupiah(total);
        document.getElementById('totalProductCount').innerText = count;
    }

    document.querySelectorAll('.item-checkbox').forEach(box => {
        box.addEventListener('change', updateSummary);
    });

    const selectAll = document.getElementById('selectAllItems');
    if(selectAll) {
        selectAll.addEventListener('change', function(e) {
            document.querySelectorAll('.item-checkbox').forEach(box => {
                box.checked = e.target.checked;
            });
            updateSummary();
        });
    }

    function submitCheckout() {
        let selectedIds = [];
        
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            let card = checkbox.closest('.cart-item-card');
            selectedIds.push(card.dataset.id); 
        });

        if (selectedIds.length === 0) {
            alert("Pilih minimal satu produk!");
            return;
        }

        console.log("Checkout IDs:", selectedIds.join(',')); 

        document.getElementById('selectedProductsInput').value = selectedIds.join(',');
        document.getElementById('checkoutForm').submit();
    }

    updateSummary();
</script>

@endsection