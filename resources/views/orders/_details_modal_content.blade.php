@if($order_detail)
    <div class="modal-order-details">
        <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order_detail['order_date'])->format('d M Y, H:i') }}</p>
        <p><strong>Status:</strong> <span class="order-status-badge status-{{ strtolower($order_detail['status'] ?? 'unknown') }}">{{ ucfirst($order_detail['status'] ?? 'N/A') }}</span></p>
        <p><strong>Payment Method:</strong> {{ $order_detail['payment_method'] ?? 'N/A' }} {{ $order_detail['payment_detail'] ? '(' . $order_detail['payment_detail'] . ')' : '' }}</p>
        <p><strong>Transaction Code:</strong> {{ $order_detail['transaction_code'] ?? 'N/A' }}</p>

        <hr>

        <h4>Shipping Address</h4>
        <p><strong>Recipient:</strong> {{ $order_detail['nama_penerima'] ?? 'N/A' }}</p>
        <p><strong>Phone:</strong> {{ $order_detail['no_telepon'] ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $order_detail['alamat_lengkap'] ?? '' }}, {{ $order_detail['kota'] ?? '' }}, {{ $order_detail['provinsi'] ?? '' }}, {{ $order_detail['kode_pos'] ?? '' }}</p>
        <p><strong>Courier:</strong> {{ $order_detail['kurir'] ?? 'N/A' }}</p>

        <hr>

        <h4>Items Ordered</h4>
        <div class="modal-order-items-list">
            @forelse($order_detail['items'] ?? [] as $item)
                <div class="modal-order-item">
                    <img src="{{ asset('assets/images/produk-looksee/' . ($item['gambar_produk'] ?? 'placeholder.jpg')) }}"
                         onerror="this.onerror=null;this.src='https://placehold.co/50x50/E0E0E0/ADADAD?text=N/A';"
                         alt="{{ $item['nama_produk'] ?? '' }}" class="item-thumb">
                    <div class="item-info">
                        <span class="name">{{ $item['nama_produk'] ?? 'Product Name' }}</span>
                        <span class="qty-price">Qty: {{ $item['quantity'] ?? 1 }} @ Rp {{ number_format($item['price_at_purchase'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <span class="item-total">Rp {{ number_format(($item['price_at_purchase'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</span>
                </div>
            @empty
                <p style="padding: 10px 15px; color: var(--text-light); font-size: 0.9em;">No items found for this order.</p>
            @endforelse
        </div>

        <div class="modal-summary-bottom">
            <span class="total-price-modal">Total Order: Rp {{ number_format($order_detail['total_price'] ?? 0, 0, ',', '.') }}</span>
        </div>

        @if(strtolower($order_detail['status'] ?? '') == 'pending')
            <div class="modal-actions">
                 <a href="{{ route('payment.details') }}" class="btn-primary">Proceed to Payment</a> {{-- Asumsi order_id disimpan di session --}}
                 {{-- Atau: <a href="{{ route('payment.details', ['order_id' => $order_detail['order_id']]) }}" class="btn-primary">Proceed to Payment</a> --}}
            </div>
        @endif

    </div>
@else
    <p style="color: red; text-align: center;">Could not retrieve order details.</p>
@endif