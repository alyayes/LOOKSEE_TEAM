{{-- resources/views/orders/list.blade.php --}}
@extends('layouts.main')

@section('title', 'My Orders - LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/my_orders.css') }}"> 
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('content')
<div class="contain">
    <div class="my-orders-container">
        <header class="orders-header">
            <a href="{{ route('homepage') }}" class="back-arrow"><i class='bx bx-arrow-back'></i></a>
            <h2>My Orders</h2>
        </header>

        <nav class="order-status-nav">
            @php $statuses = ['all', 'pending', 'prepared', 'shipped', 'completed', 'cancelled']; @endphp
            @foreach($statuses as $status)
                <a href="{{ route('orders.list', ['status' => $status]) }}"
                   class="{{ $status_filter === $status ? 'active' : '' }}">
                   {{ ucfirst($status) }}
                   <span class="count-badge">{{ $order_counts[$status] ?? 0 }}</span>
                </a>
            @endforeach
        </nav>

        <div class="orders-list">
            @forelse ($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">Order ID: #{{ $order['order_id'] }}</span>
                        <span class="order-date">{{ \Carbon\Carbon::parse($order['order_date'])->format('d M Y') }}</span>
                        <span class="order-status-badge status-{{ strtolower($order['status'] ?? 'unknown') }}">
                            {{ ucfirst($order['status'] ?? 'N/A') }}
                        </span>
                    </div>
                    <div class="order-items-summary">
                        @if (!empty($order['items']))
                            @php $first_item = $order['items'][0]; @endphp
                            <div class="order-item">
                                <img src="{{ asset('assets/images/produk-looksee/' . ($first_item['gambar_produk'] ?? 'placeholder.jpg')) }}"
                                     onerror="this.onerror=null;this.src='https://placehold.co/60x60/E0E0E0/ADADAD?text=No+Image';"
                                     alt="{{ $first_item['nama_produk'] ?? '' }}" class="item-thumb">
                                <div class="item-details">
                                    <span class="item-name">{{ $first_item['nama_produk'] ?? 'Product Name' }}</span>
                                    <span class="item-quantity">x{{ $first_item['quantity'] ?? 1 }}</span>
                                    @if (count($order['items']) > 1)
                                        <span class="more-items-text">+ {{ count($order['items']) - 1 }} more item(s)</span>
                                    @endif
                                </div>
                                <span class="item-price">Rp {{ number_format(($first_item['price_at_purchase'] ?? 0) * ($first_item['quantity'] ?? 1), 0, ',', '.') }}</span>
                            </div>
                        @else
                            <p style="padding: 10px 0; color: var(--text-light); font-size: 0.9em;">No items found.</p>
                        @endif
                    </div>
                    <div class="order-footer">
                        <span class="order-total">Total: Rp {{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}</span>
                        <button class="view-details-btn" onclick="showOrderDetails({{ $order['order_id'] }})">View Details</button>
                    </div>
                </div>
            @empty
                <div class="empty-orders-message">
                    <p>No orders found{{ $status_filter !== 'all' ? ' for status: ' . ucfirst($status_filter) : '' }}.</p>
                    <a href="{{ route('homepage') }}">Start Shopping</a> {{-- Link ke Home --}}
                </div>
            @endforelse
        </div>
    </div>

    <div id="orderDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeOrderDetailsModal()">&times;</span>
            <h3>Order Details <span id="modalOrderId"></span></h3>
            <div id="modalOrderDetailsContent">
                <p>Loading order details...</p>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        function showOrderDetails(orderId) {
            const modal = document.getElementById('orderDetailsModal');
            const modalContent = document.getElementById('modalOrderDetailsContent');
            const modalOrderIdSpan = document.getElementById('modalOrderId');

            if (!modal || !modalContent || !modalOrderIdSpan) return; // Exit if elements not found

            modalOrderIdSpan.textContent = #${orderId};
            modalContent.innerHTML = '<p style="text-align: center; color: var(--text-light);">Loading...</p>';
            modal.style.display = 'flex'; // Tampilkan modal dengan loading

            // Buat URL Ajax ke route Laravel
            const url = {{ route('orders.details.ajax', ['order_id' => ':id']) }}.replace(':id', orderId);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text || Network response was not ok (${response.status})); });
                    }
                    return response.text(); // Ambil HTML dari response
                })
                .then(html => {
                    modalContent.innerHTML = html; // Masukkan HTML ke dalam modal
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                    modalContent.innerHTML = <p style="color: red; text-align: center;">Failed to load order details. ${error.message}</p>;
                });
        }

        function closeOrderDetailsModal() {
            const modal = document.getElementById('orderDetailsModal');
            if (modal) modal.style.display = 'none';
        }

        // Tutup modal jika klik di luar
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('orderDetailsModal');
            if (event.target === modal) {
                closeOrderDetailsModal();
            }
        });
    </script>
@endsection