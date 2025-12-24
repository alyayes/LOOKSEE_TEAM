@extends('layouts.main')

@section('title', 'Checkout - LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

@endsection

@section('content')
<div class="contain">
    
    {{-- ALERT PESAN --}}
    @if(session('success'))
        <div style="position: fixed; top: 20px; right: 20px; background: #d4edda; color: #155724; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 9999;">
            <i class='bx bxs-check-circle'></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="position: fixed; top: 20px; right: 20px; background: #f8d7da; color: #721c24; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 9999;">
            <i class='bx bxs-error-circle'></i> {{ session('error') }}
        </div>
    @endif

    <div class="checkout-container">
        <header class="checkout-page-header">
            <a href="{{ route('cart') }}" class="back-arrow"><i class='bx bx-arrow-back'></i></a>
            <h2>Checkout</h2>
        </header>

        <form method="POST" action="{{ route('checkout.process') }}" id="checkoutForm"> 
            @csrf
            <input type="hidden" name="selected_products_ids" value="{{ $selectedIdsString }}">
            
            {{-- Default Address ID --}}
            @php 
                $defaultAddr = $all_addresses->where('is_default', 1)->first() ?? $all_addresses->first();
                $defaultId = $defaultAddr ? $defaultAddr->id : 0;
            @endphp
            <input type="hidden" name="address_id" id="selectedAddressId" value="{{ $defaultId }}">

            <div class="checkout-main-content">
                <div class="left-column">
                    <div class="section-card delivery-address-section">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); padding-bottom: 10px; margin-bottom: 15px;">
                            <h3 style="border: none; margin: 0; padding: 0;">Delivery Address</h3>
                            <button type="button" class="edit-address-btn" style="position: static; width: auto;" onclick="openAddressModal()">Change</button>
                        </div>
                        
                        <div class="address-details">
                            <p><strong>Name:</strong> <span id="deliveryNameDisplay">{{ $delivery_data['name'] }}</span></p>
                            <p><strong>Phone:</strong> <span id="deliveryPhoneDisplay">{{ $delivery_data['phone'] }}</span></p>
                            <p><strong>Address:</strong> <br>
                                <span id="deliveryAddressDisplay" style="color: var(--text-medium); display: block; margin-top: 5px;">
                                {{ $delivery_data['address'] }}, 
                                {{ $delivery_data['district'] }},
                                {{ $delivery_data['city'] }}, 
                                {{ $delivery_data['province'] }}, 
                                {{ $delivery_data['postal_code'] }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="section-card product-summary-section">
                        <h3>Order Summary</h3>
                        <div class="product-list-checkout" style="border: none; margin-top: 0;">
                            @forelse ($cart_items as $item)
                                <div class="product-item-checkout" style="grid-template-columns: 60px 1fr auto; gap: 15px; border-bottom: 1px solid var(--border-light); padding: 15px 0;">
                                    <img src="{{ asset('assets/images/produk-looksee/' . ($item['gambar_produk'] ?? 'placeholder.jpg')) }}" 
                                         class="product-thumb-checkout" 
                                         onerror="this.onerror=null;this.src='https://via.placeholder.com/60';">
                                    
                                    <div style="display: flex; flex-direction: column; justify-content: center;">
                                        <span style="font-weight: 600; color: var(--text-dark);">{{ $item['nama_produk'] }}</span>
                                        <span style="font-size: 0.9em; color: var(--text-light);">{{ $item['quantity'] }} x Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <div style="font-weight: 600; color: var(--primary-pink); align-self: center;">
                                        Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <p style="text-align: center; color: var(--text-light);">No products.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <div class="section-card shipping-method-section">
                        <h3>Shipping</h3>
                        <div class="shipping-option">
                            <input type="radio" checked disabled> 
                            <label style="cursor: default;">
                                <i class='bx bxs-truck'></i> Regular Shipping
                                <span class="shipping-cost">Rp {{ number_format($shipping_cost, 0, ',', '.') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="section-card payment-method-section">
                        <h3>Payment Method</h3>
                        
                        <div class="payment-type">
                            <input class="payment-trigger" type="radio" name="payment_method" value="COD" id="cod" required>
                            <label for="cod">Cash On Delivery (COD)</label>
                        </div>

                        <div class="payment-type">
                            <input class="payment-trigger" type="radio" name="payment_method" value="E-Wallet" id="ewallet">
                            <label for="ewallet">E-Wallet</label>
                        </div>
                        <div id="ewallet_options" class="payment-sub-options" style="display: none;">
                            @foreach($e_wallet_options as $ew)
                                <div style="display:flex; align-items:center; margin-bottom:8px;">
                                    <input type="radio" name="ewallet_choice" value="{{ $ew->e_wallet_payment_id }}" id="ew_{{ $ew->e_wallet_payment_id }}" style="margin-right:8px;">
                                    <label for="ew_{{ $ew->e_wallet_payment_id }}">{{ $ew->ewallet_provider_name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="payment-type">
                            <input class="payment-trigger" type="radio" name="payment_method" value="Bank Transfer" id="bank">
                            <label for="bank">Bank Transfer</label>
                        </div>
                        <div id="bank_options" class="payment-sub-options" style="display: none;">
                            @foreach($bank_options as $bank)
                                <div style="display:flex; align-items:center; margin-bottom:8px;">
                                    <input type="radio" name="bank_choice" value="{{ $bank->bank_payment_id }}" id="bank_{{ $bank->bank_payment_id }}" style="margin-right:8px;">
                                    <label for="bank_{{ $bank->bank_payment_id }}">{{ $bank->bank_name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="checkout-summary-footer">
                <div class="summary-details">
                    <div class="total-item-count-display">Total Items: <span id="totalItemCountCheckout" style="color: var(--text-dark);">{{ count($cart_items) }}</span></div>
                    <div class="total-price-display">Total Payment: <span id="grandTotalPriceCheckout">Rp {{ number_format($grand_total, 0, ',', '.') }}</span></div>
                </div>
                <button type="submit" class="place-order-button">Place Order</button>
            </div>
        </form>
    </div>

    {{-- MODAL GANTI ALAMAT --}}
    <div id="addressManagerModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeAddressModal()">&times;</span>

            {{-- VIEW: LIST ALAMAT --}}
            <div id="addressListView">
                <h3>Select Address</h3>
                
                {{-- Tombol Tambah Alamat (Warna Pink dari CSS) --}}
                <button class="add-new-address-btn" onclick="showAddForm()" style="background-color: rgb(250, 156, 211); color: white; padding: 10px; border: none; border-radius: 6px; width: 100%; margin-bottom: 15px; font-weight: 600; cursor: pointer;">
                    <i class='bx bx-plus-circle'></i> Add New Address
                </button>
                
                <div class="address-list-container">
                    @forelse($all_addresses as $addr)
                        <div class="address-card-item {{ $defaultId == $addr->id ? 'selected' : '' }}" 
                             onclick="selectAddress(this)"
                             data-id="{{ $addr->id }}"
                             data-name="{{ $addr->receiver_name }}"
                             data-phone="{{ $addr->phone_number }}"
                             data-address="{{ $addr->full_address }}"
                             data-city="{{ $addr->city }}"
                             data-district="{{ $addr->district }}"
                             data-province="{{ $addr->province }}"
                             data-postal="{{ $addr->postal_code }}">
                            
                            <div style="font-weight: 600; margin-bottom: 5px; color: var(--text-dark);">
                                {{ $addr->receiver_name }} 
                                <span style="font-weight: 400; color: var(--text-light); font-size: 0.9em;">| {{ $addr->phone_number }}</span>
                                
                                {{-- BADGE DEFAULT DISINI --}}
                                @if($addr->is_default) 
                                    <span class="badge-default">Default</span> 
                                @endif
                            </div>
                            
                            <div style="font-size: 0.9em; color: var(--text-medium); line-height: 1.4;">
                                {{ $addr->full_address }}<br>
                                {{ $addr->district }}, {{ $addr->city }}, {{ $addr->province }}
                            </div>

                            {{-- TOMBOL AKSI DENGAN STOP PROPAGATION --}}
                            <div class="address-actions">
                                <button type="button" class="btn-action-sm btn-edit" 
                                        onclick="event.stopPropagation(); showEditForm(this)">Edit</button>
                                
                                <form action="{{ route('checkout.address.delete', $addr->id) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-sm btn-delete" onclick="event.stopPropagation()">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 30px; color: var(--text-light);">
                            <p>You don't have any saved addresses yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- VIEW: FORM INPUT --}}
            <div id="addressFormView" style="display:none;">
                <div class="modal-header-nav">
                    <button type="button" class="back-to-list-btn" onclick="showListView()">
                        <i class='bx bx-left-arrow-alt'></i> Back
                    </button>
                    <h3 style="margin: 0; border: none; padding: 0;">Address Form</h3>
                </div>

                <form id="addressFormInput" method="POST" action="{{ route('checkout.address.add') }}">
                    @csrf
                    <div id="methodField"></div> 
                    
                    <div class="form-group">
                        <label>Receiver Name</label>
                        <input type="text" name="receiver_name" id="inputName" required style="width: 100%; padding: 8px;">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone_number" id="inputPhone" required style="width: 100%; padding: 8px;">
                    </div>
                    <div class="form-group">
                        <label>Full Address</label>
                        <textarea name="full_address" id="inputAddress" rows="3" required style="width: 100%; padding: 8px;"></textarea>
                    </div>
                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label>City</label>
                            <input type="text" name="city" id="inputCity" required style="width: 100%; padding: 8px;">
                        </div>
                        <div>
                            <label>District</label>
                            <input type="text" name="district" id="inputDistrict" style="width: 100%; padding: 8px;">
                        </div>
                    </div>
                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label>Province</label>
                            <input type="text" name="province" id="inputProvince" required style="width: 100%; padding: 8px;">
                        </div>
                        <div>
                            <label>Postal Code</label>
                            <input type="text" name="postal_code" id="inputPostal" required style="width: 100%; padding: 8px;">
                        </div>
                    </div>

                    <button type="submit" class="save-address-btn" style="background-color: rgb(250, 156, 211); color: white; padding: 10px; width: 100%; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Save Address</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
<script>
    // --- LOGIC PEMBAYARAN (Show/Hide Sub-menu) ---
    document.addEventListener('DOMContentLoaded', function() {
        const triggers = document.querySelectorAll('.payment-trigger');
        const bankOpts = document.getElementById('bank_options');
        const walletOpts = document.getElementById('ewallet_options');

        function updatePaymentUI() {
            bankOpts.style.display = 'none';
            walletOpts.style.display = 'none';
            document.querySelectorAll('[name="bank_choice"]').forEach(e => e.required = false);
            document.querySelectorAll('[name="ewallet_choice"]').forEach(e => e.required = false);

            const checked = document.querySelector('[name="payment_method"]:checked');
            if(checked) {
                if(checked.value === 'Bank Transfer') {
                    bankOpts.style.display = 'block';
                    document.querySelectorAll('[name="bank_choice"]').forEach(e => e.required = true);
                } else if(checked.value === 'E-Wallet') {
                    walletOpts.style.display = 'block';
                    document.querySelectorAll('[name="ewallet_choice"]').forEach(e => e.required = true);
                }
            }
        }

        triggers.forEach(t => t.addEventListener('change', updatePaymentUI));
        updatePaymentUI();
    });

    // --- LOGIC MODAL ALAMAT ---
    const modal = document.getElementById('addressManagerModal');
    const viewList = document.getElementById('addressListView');
    const viewForm = document.getElementById('addressFormView');
    const formInput = document.getElementById('addressFormInput');

    window.openAddressModal = function() {
        modal.style.display = 'flex';
        showListView();
    }

    window.closeAddressModal = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeAddressModal();
        }
    }

    window.showListView = function() {
        viewList.style.display = 'block';
        viewForm.style.display = 'none';
    }

    window.showAddForm = function() {
        viewList.style.display = 'none';
        viewForm.style.display = 'block';
        document.getElementById('methodField').innerHTML = ''; 
        formInput.action = "{{ route('checkout.address.add') }}"; 
        formInput.reset();
    }

    // --- LOGIC EDIT (DIPERBAIKI: Mengambil data dari card parent, bukan JSON) ---
    window.showEditForm = function(btn) {
        // Stop bubbling lagi untuk keamanan
        event.stopPropagation();

        viewList.style.display = 'none';
        viewForm.style.display = 'block';
        
        // Cari elemen parent .address-card-item
        var card = btn.closest('.address-card-item');
        var data = card.dataset;

        // Set Action URL
        formInput.action = "/checkout/address/update/" + data.id; 
        
        // Tambahkan Method PUT
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        // Isi form
        document.getElementById('inputName').value = data.name;
        document.getElementById('inputPhone').value = data.phone;
        document.getElementById('inputAddress').value = data.address;
        document.getElementById('inputCity').value = data.city;
        document.getElementById('inputDistrict').value = data.district;
        document.getElementById('inputProvince').value = data.province;
        document.getElementById('inputPostal').value = data.postal;
    }

    window.selectAddress = function(el) {
        document.getElementById('deliveryNameDisplay').innerText = el.dataset.name;
        document.getElementById('deliveryPhoneDisplay').innerText = el.dataset.phone;
        document.getElementById('deliveryAddressDisplay').innerHTML = 
            `${el.dataset.address}, ${el.dataset.district}, ${el.dataset.city}, ${el.dataset.province}, ${el.dataset.postal}`;
        
        document.getElementById('selectedAddressId').value = el.dataset.id;

        document.querySelectorAll('.address-card-item').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');

        closeAddressModal();
    }
</script>
@endsection