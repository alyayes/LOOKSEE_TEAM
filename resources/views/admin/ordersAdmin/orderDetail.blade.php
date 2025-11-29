@extends('layouts.mainAdmin')

@php
    use Carbon\Carbon;
    use Illuminate\Support\Str; // Tambahkan Str untuk Str::limit
@endphp

@section('title', 'Detail Order #' . ($order_details['order_id'] ?? 'N/A'))

@section('styles')
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            color: #343a40;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .order-detail-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 40px;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        h4, h5 {
            color: #212529;
            font-weight: 600;
            margin-bottom: 25px;
        }
        hr {
            border-top: 1px solid rgba(0,0,0,.1);
            margin-top: 30px;
            margin-bottom: 30px;
        }
        /* Product Details Section */
        .product-header {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
            font-weight: bold;
            color: #6c757d;
            font-size: 0.95em;
            text-transform: uppercase;
        }
        .product-header div:nth-child(1) { flex-grow: 1; text-align: left; }
        .product-header div:nth-child(2),
        .product-header div:nth-child(3),
        .product-header div:nth-child(4) {
            width: 100px;
            text-align: right;
            flex-shrink: 0;
        }
        .product-header div:nth-child(3) {
            width: 80px;
        }
        .product-item {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #f2f2f2;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-image {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
            flex-shrink: 0;
            border: 1px solid #e9ecef;
        }
        .product-info {
            flex-grow: 1;
        }
        .product-info h6 {
            font-size: 1.1em;
            margin-bottom: 5px;
            color: #343a40;
            font-weight: 600;
        }
        .product-info p {
            margin-bottom: 2px;
            font-size: 0.85em;
            color: #6c757d;
        }
        .product-info p.description-preview {
            font-style: italic;
            font-size: 0.8em;
            color: #999;
            margin-top: 5px;
        }
        .item-col {
            width: 100px;
            text-align: right;
            font-weight: 500;
            color: #212529;
            flex-shrink: 0;
        }
        .item-price { width: 100px; }
        .item-quantity { width: 80px; }
        .item-total { width: 100px; font-weight: bold; }
        /* Summary Section */
        .summary-section {
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
            margin-top: 40px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 1.05em;
        }
        .summary-row.total {
            font-weight: bold;
            font-size: 1.3em;
            padding-top: 15px;
            border-top: 2px solid #212529;
            margin-top: 15px;
        }
        .summary-label {
            color: #6c757d;
            flex-grow: 1;
        }
        .summary-value {
            color: #212529;
            text-align: right;
        }
        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: .35em .65em;
            font-size: .9em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            border-radius: .375rem;
            color: #fff;
        }
        .status-badge.pending { background-color: rgba(255, 193, 7, .8); }
        .status-badge.prepared { background-color: rgb(253, 152, 0); }
        .status-badge.shipped { background-color: rgb(148, 190, 253); }
        .status-badge.completed { background-color: rgb(60, 199, 134); }
        .status-badge.cancelled { background-color: rgb(220, 53, 69); }
        /* Receiver Info Section */
        .receiver-info p { margin-bottom: 5px; font-size: 0.95em; }
        .receiver-info p strong { display: inline-block; width: 120px; color: #6c757d; }
        /* Responsive */
        @media (max-width: 767.98px) {
            .order-detail-card { padding: 20px; }
            .product-header, .product-item { flex-wrap: wrap; }
            .product-image { width: 60px; height: 60px; margin-right: 10px; flex-shrink: 0; }
            .product-info { flex-basis: calc(100% - 70px); }
            .item-col { width: 100%; text-align: left; margin-top: 5px; }
            .item-price, .item-quantity, .item-total { width: 100%; text-align: left; margin-top: 5px; }
            .item-quantity { order: 2; margin-left: auto; }
            .item-price { order: 1; }
            .product-header div:nth-child(2), .product-header div:nth-child(3), .product-header div:nth-child(4) { display: none; }
            .summary-label { flex-basis: 60%; }
            .summary-value { flex-basis: 40%; }
            .receiver-info p strong { width: auto; display: block; }
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="order-detail-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h4>Detail Order #{{ $order_details['order_id'] ?? 'N/A' }}</h4>
            <a href="javascript:history.back()" class="btn btn-secondary"><i class='bx bx-arrow-back'></i> Kembali</a>
        </div>

        <div class="row" style="margin-bottom: 1.5rem;">
            <div class="col-md-6">
                <p style="margin-bottom: 0.5rem;"><strong>Tanggal Order:</strong> {{ Carbon::parse($order_details['order_date'] ?? '')->format('d M Y, H:i') }}</p>
                <p style="margin-bottom: 0.5rem;">
                    <strong>Status:</strong>
                    @php
                        $status = $order_details['status'] ?? 'unknown';
                        $status_class = strtolower($status);
                    @endphp
                    <span class="status-badge {{ $status_class }}">{{ ucfirst($status_class) }}</span>
                </p>
                <p style="margin-bottom: 0.5rem;"><strong>Username:</strong> {{ $order_details['username'] ?? 'N/A' }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Email User:</strong> {{ $order_details['email'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <p style="margin-bottom: 0.5rem;"><strong>Metode Pembayaran:</strong> {{ $order_details['metode_pembayaran'] ?? 'N/A' }}</p>
                @if (($order_details['metode_pembayaran'] ?? '') == 'Bank Transfer')
                    <p style="margin-bottom: 0.5rem;"><strong>Bank Pilihan:</strong> {{ $order_details['bank_name'] ?? '-' }}</p>
                    <p style="margin-bottom: 0.5rem;"><strong>Nomor Rekening:</strong> {{ $order_details['bank_account_number'] ?? '-' }}</p>
                    <p style="margin-bottom: 0.5rem;"><strong>Nama Pemilik Rekening:</strong> {{ $order_details['bank_account_holder_name'] ?? '-' }}</p>
                @elseif (($order_details['metode_pembayaran'] ?? '') == 'E-Wallet')
                    <p style="margin-bottom: 0.5rem;"><strong>Provider E-Wallet:</strong> {{ $order_details['ewallet_provider_name'] ?? '-' }}</p>
                    <p style="margin-bottom: 0.5rem;"><strong>Nomor Telepon E-Wallet:</strong> {{ $order_details['ewallet_phone_number'] ?? '-' }}</p>
                    <p style="margin-bottom: 0.5rem;"><strong>ID Akun E-Wallet:</strong> {{ $order_details['e_wallet_account_id'] ?? '-' }}</p>
                @else
                    <p style="margin-bottom: 0.5rem;"><strong>Detail Pembayaran:</strong> -</p>
                @endif
                <p style="margin-bottom: 0.5rem;"><strong>Kurir:</strong> {{ $order_details['kurir'] ?? 'N/A' }}</p>
            </div>
        </div>

        <hr>

        <h5 style="margin-bottom: 1rem;">Detail Produk</h5>
        <div class="product-header">
            <div>Product Details</div>
            <div>Item Price</div>
            <div>Quantity</div>
            <div>Total Amount</div>
        </div>

        @if (!empty($order_itemss_data))
            @foreach ($order_itemss_data as $item)
                <div class="product-item">
                    @php
                        // Memastikan gambar_produk selalu ada, set placeholder jika tidak ada
                        $image_path = $item['gambar_produk'] ?? 'default.jpg';
                        $image_url = asset('storage/uploads/admin/produk_looksee/' . $image_path);
                        $placeholder_url = 'https://via.placeholder.com/90x90.png?text=No+Image';
                    @endphp
                    <img src="{{ $image_url }}"
                        alt="Product Image"
                        class="product-image"
                        onerror="this.onerror=null; this.src='{{ $placeholder_url }}'; this.alt='Gambar tidak tersedia';"
                    >
                    <div class="product-info">
                        <h6>{{ $item['nama'] ?? 'Nama Produk N/A' }}</h6>
                        @if (!empty($item['deskripsi']))
                            <p class="description-preview">
                                {{ Str::limit($item['deskripsi'], 80) }}
                            </p>
                        @endif
                    </div>
                    <div class="item-col item-price">
                        Rp{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="item-col item-quantity">
                        {{ $item['qty'] ?? 1 }}
                    </div>
                    <div class="item-col item-total">
                        Rp{{ number_format(($item['harga'] ?? 0) * ($item['qty'] ?? 1), 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center text-muted mt-3">Tidak ada produk dalam order ini.</p>
        @endif

        <hr>

        <h5 style="margin-bottom: 1rem;">Ringkasan Pembayaran</h5>
        <div class="summary-section">
            <div class="summary-row">
                <span class="summary-label">Sub Total Produk:</span>
                <span class="summary-value">Rp{{ number_format($sub_total_calculated ?? 0, 0, ',', '.') }}</span>
            </div>
            @if (($discount_amount ?? 0) > 0)
                <div class="summary-row">
                    <span class="summary-label">Diskon:</span>
                    <span class="summary-value">-Rp{{ number_format($discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            @if (($shipping_charge ?? 0) > 0)
                <div class="summary-row">
                    <span class="summary-label">Biaya Pengiriman:</span>
                    <span class="summary-value">Rp{{ number_format($shipping_charge, 0, ',', '.') }}</span>
                </div>
            @endif
            @if (($estimated_tax ?? 0) > 0)
                <div class="summary-row">
                    <span class="summary-label">Pajak (Estimasi):</span>
                    <span class="summary-value">Rp{{ number_format($estimated_tax, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="summary-row total">
                <span class="summary-label">Total Pembayaran:</span>
                <span class="summary-value">Rp{{ number_format($order_details['total_price'] ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <hr>

        <h5 style="margin-bottom: 1rem;">Informasi Penerima</h5>
        <div class="receiver-info">
            <p><strong>Nama Penerima:</strong> {{ $order_details['nama_penerima'] ?? 'N/A' }}</p>
            <p><strong>No. Telepon:</strong> {{ $order_details['no_telepon'] ?? 'N/A' }}</p>
            <p><strong>Alamat Lengkap:</strong> {{ $order_details['alamat_lengkap'] ?? 'N/A' }}</p>
            <p><strong>Kota:</strong> {{ $order_details['kota'] ?? 'N/A' }}</p>
            <p><strong>Provinsi:</strong> {{ $order_details['provinsi'] ?? 'N/A' }}</p>
            <p><strong>Kode Pos:</strong> {{ $order_details['kode_pos'] ?? 'N/A' }}</p>
        </div>

        <div style="margin-top: 1.5rem; text-align: center;">
        </div>
    </div>
</div>
@endsection
