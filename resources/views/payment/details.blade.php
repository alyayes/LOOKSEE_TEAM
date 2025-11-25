{{-- resources/views/payment/details.blade.php --}}
@extends('layouts.main')

@section('title', 'Payment Details - Order #' . $order_id)

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/payment.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    {{-- Style untuk Custom Alert bisa ditaruh di CSS utama atau di sini --}}
    <style>
        .custom-alert { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); display: flex; justify-content: center; align-items: center; z-index: 10000; animation: fadeIn 0.3s ease-out; }
        .custom-alert-content { background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2); text-align: center; max-width: 450px; width: 90%; font-family: 'Inter', sans-serif; color: #333; position: relative; }
        .custom-alert-content p { margin-bottom: 25px; font-size: 1.15em; line-height: 1.5; color: #444; }
        .custom-alert-content button { padding: 12px 25px; margin: 0 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 1em; font-weight: 600; background-color: var(--primary-pink, #fa9cd3); color: white; transition: background-color 0.2s ease, transform 0.1s ease; }
        .custom-alert-content button:hover { background-color: var(--primary-pink-hover, #f863ba); transform: translateY(-1px); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
@endsection

@section('content')
<div class="contain">
    <div class="payment-container">
        <header class="payment-header">
            <a href="{{ route('orders.list') }}" class="back-arrow"><i class='bx bx-arrow-back'></i></a> {{-- Link ke My Orders --}}
            <h2>Payment Details</h2>
        </header>

        <div class="payment-summary">
            <div class="summary-item">
                <span>Order ID</span>
                <span class="value">#{{ $order_id }}</span>
            </div>
            <div class="summary-item">
                <span>Payment Code</span>
                <span class="value payment-code">{{ $payment_code }}</span>
            </div>
            <div class="summary-item">
                <span>Payment Method</span>
                <span class="value method-selected">{{ $payment_method_display }}</span>
            </div>
            @if ($payment_method_display === 'Bank Transfer' && !empty($bank_choice_display))
            <div class="summary-item">
                <span>Bank Choice</span>
                <span class="value bank-choice">{{ $bank_choice_display }}</span>
            </div>
            @elseif ($payment_method_display === 'E-Wallet' && !empty($ewallet_provider_display))
            <div class="summary-item">
                <span>E-Wallet Provider</span>
                <span class="value ewallet-provider">{{ $ewallet_provider_display }}</span>
            </div>
            @endif

            <div class="summary-item total-amount">
                <span>Total Amount Due</span>
                <span class="value amount-due">{!! $total_amount_display !!}</span> {{-- Biarkan {!! !!} jika formatRupiah menghasilkan HTML --}}
            </div>
            <div class="summary-item expiration-info">
                <span>Pay Before</span>
                <span class="value expiration-time">{{ $payment_expiration_time->format('d M Y, H:i') }} WIB</span>
            </div>
        </div>

        <div class="payment-instructions">
            <h3>How to Pay via {{ $payment_instructions['title'] }}</h3>
            <ol>
                @foreach ($payment_instructions['steps'] as $step)
                    <li>{!! $step !!}</li> {{-- {!! !!} untuk mengizinkan HTML seperti &emsp; --}}
                @endforeach
            </ol>
            <p class="instruction-note">
                Once payment is successful, your order will be processed immediately.
                You can check the order status on the <a href="{{ route('orders.list') }}">My Orders</a> page.
            </p>
        </div>

        <div class="payment-actions">
            <button class="btn-primary copy-code-btn" data-clipboard-text="{{ $payment_code }}">
                <i class='bx bx-copy'></i> Copy Payment Code
            </button>
            <a href="{{ route('orders.list') }}" class="btn-secondary">
                Back to My Orders
            </a>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        // Fungsi Custom Alert (bisa dipindah ke JS global)
        function showCustomAlert(message, callback = null) {
            const alertBox = document.createElement('div');
            alertBox.className = 'custom-alert';
            alertBox.innerHTML = `
            <div class="custom-alert-content">
                <p>${message}</p>
                <button class="alert-ok-btn">OK</button>
            </div>`;
            document.body.appendChild(alertBox);
            alertBox.querySelector('.alert-ok-btn').addEventListener('click', function() {
                alertBox.remove();
                if (callback && typeof callback === 'function') callback();
            });
        }

        // Script Copy Payment Code
        document.querySelector('.copy-code-btn')?.addEventListener('click', function() {
            const codeToCopy = this.getAttribute('data-clipboard-text');
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(codeToCopy).then(() => {
                    showCustomAlert('Payment Code copied: ' + codeToCopy);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    showCustomAlert('Failed to copy. Please copy manually: ' + codeToCopy);
                });
            } else { // Fallback for older browsers
                try {
                    const tempInput = document.createElement('textarea');
                    tempInput.value = codeToCopy; document.body.appendChild(tempInput);
                    tempInput.select(); document.execCommand('copy'); document.body.removeChild(tempInput);
                    showCustomAlert('Payment Code copied: ' + codeToCopy);
                } catch (e) {
                        showCustomAlert('Failed to copy. Please copy manually: ' + codeToCopy);
                    }
            }
        });
    </script>
@endsection
