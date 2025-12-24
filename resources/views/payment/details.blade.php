{{-- resources/views/payment/details.blade.php --}}
@extends('layouts.main')

@section('title', 'Payment Details - Order #' . $order_id)

@section('head_scripts')
    {{-- Pastikan file payment.css ada di public/assets/css/ --}}
    <link rel="stylesheet" href="{{ asset('assets/css/payment.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('content')
<div class="contain">
    <div class="payment-container">
        
        {{-- HEADER --}}
        <header class="payment-header">
            <a href="{{ route('orders.list') }}" class="back-arrow">
                <i class='bx bx-arrow-back'></i>
            </a>
            <h2>Payment Details</h2>
        </header>

        {{-- SUMMARY SECTION (Ringkasan Order) --}}
        <div class="payment-summary">
            <div class="summary-item">
                <span>Order ID</span>
                <span class="value">#{{ $order_id }}</span>
            </div>
            
            <div class="summary-item">
                <span>Payment Method</span>
                <span class="value" style="text-transform: uppercase;">
                    {{ $payment_method_display }} 
                    @if($bank_choice_display) - {{ $bank_choice_display }} @endif
                    @if($ewallet_provider_display) - {{ $ewallet_provider_display }} @endif
                </span>
            </div>

            <div class="summary-item expiration-info">
                <span>Pay Before</span>
                <span class="value">{{ \Carbon\Carbon::parse($payment_expiration_time)->format('d M Y, H:i') }} WIB</span>
            </div>

            <div class="summary-item total-amount">
                <span>Total Amount Due</span>
                <span class="value">{{ $total_amount_display }}</span>
            </div>
        </div>

        {{-- INSTRUCTION SECTION (Instruksi Bayar) --}}
        <div class="payment-instructions">
            <h3>{{ $payment_instructions['title'] }}</h3>

            {{-- Tampilkan Kode Bayar / VA jika bukan COD --}}
            @if($payment_method_display !== 'COD')
                <div style="text-align: center; margin-bottom: 20px;">
                    <p style="margin-bottom: 5px; color: #888; font-size: 0.9em;">Nomor / Kode Bayar:</p>
                    <div class="payment-code" id="codeToCopy">{{ $payment_code }}</div>
                </div>
            @endif

            {{-- List Langkah-langkah --}}
            <ol>
                @foreach($payment_instructions['steps'] as $step)
                    {{-- Menggunakan {!! !!} agar tag HTML seperti <strong> terbaca --}}
                    <li>{!! $step !!}</li>
                @endforeach
            </ol>

            <div class="instruction-note">
                <p>Once payment is successful, your order will be processed immediately. You can check the status on <a href="{{ route('orders.list') }}">My Orders</a> page.</p>
            </div>
        </div>

        {{-- ACTION BUTTONS (BAGIAN YANG DIUBAH) --}}
        <div class="payment-actions">
            {{-- Tombol Back Selalu Ada --}}
            <a href="{{ route('orders.list') }}" class="btn-secondary">
                Back to My Orders
            </a>

            {{-- Tombol Copy Hanya Muncul Jika BUKAN COD --}}
            @if($payment_method_display !== 'COD')
                <button type="button" onclick="copyToClipboard('{{ $payment_code }}')" class="btn-primary">
                    <i class='bx bx-copy'></i> Copy Payment Code
                </button>
            @endif
        </div>

    </div>
</div>

{{-- SCRIPT COPY TO CLIPBOARD --}}
<script>
    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            // Cara modern
            navigator.clipboard.writeText(text).then(function() {
                alert('Kode berhasil disalin: ' + text);
            }, function(err) {
                console.error('Gagal menyalin: ', err);
                alert('Gagal menyalin kode. Silakan salin manual.');
            });
        } else {
            // Fallback cara lama (untuk browser lama/http biasa)
            let textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                alert('Kode berhasil disalin: ' + text);
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
                alert('Gagal menyalin kode. Silakan salin manual.');
            }
            document.body.removeChild(textArea);
        }
    }
</script>
@endsection