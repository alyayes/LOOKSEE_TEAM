{{-- resources/views/checkout/checkout.blade.php --}}

@extends('layouts.main')

@section('title', 'Checkout - LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('content')
<div class="contain">
    {{-- Tampilkan pesan sukses jika ada --}}
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="checkout-container">
        <header class="checkout-page-header">
            <a href="{{ route('cart') }}" class="back-arrow"><i class='bx bx-arrow-back'></i></a>
            <h2>Checkout</h2>
        </header>

        <form method="POST" action="{{ route('checkout.process') }}" id="checkoutForm"> 
            @csrf
            <div class="checkout-main-content">
                <div class="left-column">
                    <div class="section-card delivery-address-section">
                        <h3>Delivery Address</h3>
                        <div class="address-details">
                            <p><strong>Name:</strong> <span id="deliveryNameDisplay">{{ $delivery_data['name'] }}</span></p>
                            <p><strong>Phone:</strong> <span id="deliveryPhoneDisplay">{{ $delivery_data['phone'] }}</span></p>
                            <p><strong>Address:</strong> <span id="deliveryAddressDisplay">
                                {{ $delivery_data['address'] }}, 
                                {{ $delivery_data['district'] }},
                                {{ $delivery_data['city'] }}, 
                                {{ $delivery_data['province'] }}, 
                                {{ $delivery_data['postal_code'] }}
                            </span></p>
                        </div>
                        
                        {{-- Tombol Buka Modal --}}
                        <button type="button" class="edit-address-btn" onclick="openAddressModal()">Change / Add Address</button>

                        {{-- Hidden inputs untuk data checkout final --}}
                        <input type="hidden" id="hiddenDeliveryName" name="delivery_name" value="{{ $delivery_data['name'] }}">
                        <input type="hidden" id="hiddenDeliveryPhone" name="delivery_phone" value="{{ $delivery_data['phone'] }}">
                        <input type="hidden" id="hiddenDeliveryAddress" name="delivery_address" value="{{ $delivery_data['address'] }}">
                        <input type="hidden" id="hiddenDeliveryCity" name="delivery_city" value="{{ $delivery_data['city'] }}">
                        <input type="hidden" id="hiddenDeliveryProvince" name="delivery_province" value="{{ $delivery_data['province'] }}">
                        <input type="hidden" id="hiddenDeliveryPostalCode" name="delivery_postal_code" value="{{ $delivery_data['postal_code'] }}">
                    </div>

                    <div class="section-card product-summary-section">
                        <h3>Products</h3>
                        <div class="product-list-checkout">
                            <div class="product-header-checkout">
                                <div class="col-product">Product</div>
                                <div class="col-unit-price">Unit Price</div>
                                <div class="col-quantity">Quantity</div>
                                <div class="col-total-price">Total Price</div>
                            </div>

                            @forelse ($cart_items as $id_produk => $item)
                                <div class="product-item-checkout">
                                    <div class="col-product">
                                        <img src="{{ asset('assets/images/produk-looksee/' . ($item['gambar_produk'] ?? 'placeholder.jpg')) }}" 
                                              onerror="this.onerror=null;this.src='https://placehold.co/60x60/E0E0E0/ADADAD?text=No+Image';"
                                              alt="{{ $item['nama_produk'] ?? 'Product' }}" class="product-thumb-checkout">
                                        <span>{{ $item['nama_produk'] ?? 'Unknown Product' }}</span>
                                    </div>
                                    <div class="col-unit-price">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</div>
                                    <div class="col-quantity">{{ $item['quantity'] ?? 1 }}</div>
                                    <div class="col-total-price">Rp {{ number_format(($item['harga'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</div>
                                </div>
                            @empty
                                <p style="padding: 15px; text-align: center; color: var(--text-light);">No products in cart.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <div class="section-card shipping-method-section">
                        <h3>Shipping Method</h3>
                        <div class="shipping-option">
                            <input type="radio" id="regularShipping" name="shipping_method" value="Regular Shipping" checked>
                            <label for="regularShipping">
                                <i class='bx bxs-truck'></i> Regular Shipping
                                <span class="shipping-cost">Rp {{ number_format($shipping_cost, 0, ',', '.') }}</span>
                            </label>
                            <p class="shipping-estimate">Estimated Delivery: 3-5 business days after payment confirmation.</p>
                        </div>
                    </div>

                    <div class="section-card payment-method-section">
                        <h3>Payment Method</h3>
                        <div class="payment-options">
                            @foreach ($main_payment_methods as $method)
                                <div class="payment-type">
                                    <input type="radio" id="paymentMethod{{ $method['method_id'] }}" name="payment_method" value="{{ $method['method_name'] }}"
                                        @if($loop->first || $method['method_name'] == 'Bank Transfer') checked @endif> 
                                    <label for="paymentMethod{{ $method['method_id'] }}">
                                        {{ $method['method_name'] }}
                                    </label>
                                </div>
                            @endforeach

                            <div class="bank-selection" id="bankSelectionDiv">
                                <h4>Select Bank for Transfer</h4>
                                <div class="bank-list">
                                    @forelse ($bank_options as $index => $bank)
                                        <div class="bank-option">
                                            <input type="radio" id="bankChoice{{ $bank['bank_payment_id'] }}" name="bank_choice" value="{{ $bank['bank_payment_id'] }}"
                                                @if($loop->first) checked @endif> 
                                            <label for="bankChoice{{ $bank['bank_payment_id'] }}">
                                                {{ $bank['bank_name'] }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="no-options-msg">No bank options available.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="ewallet-selection" id="ewalletSelectionDiv" style="display: none;">
                                <h4>Select E-Wallet Provider</h4>
                                <div class="ewallet-list">
                                    @forelse ($e_wallet_options as $index => $ewallet)
                                        <div class="ewallet-option">
                                            <input type="radio" id="ewalletChoice{{ $ewallet['e_wallet_payment_id'] }}" name="ewallet_choice" value="{{ $ewallet['e_wallet_payment_id'] }}"
                                                @if($loop->first) checked @endif> 
                                            <label for="ewalletChoice{{ $ewallet['e_wallet_payment_id'] }}">
                                                {{ $ewallet['ewallet_provider_name'] }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="no-options-msg">No e-wallet options available.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="checkout-summary-footer">
                <div class="summary-details">
                    <div class="total-price-display">
                        Total Price : <span id="grandTotalPriceCheckout">Rp {{ number_format($grand_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-item-count-display">
                        Total Products : <span id="totalItemCountCheckout">{{ count($cart_items) }}</span>
                    </div>
                </div>
                <button type="submit" class="place-order-button">Place Order</button>
            </div>
        </form>
    </div>

    <div id="addressManagerModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close-btn" onclick="closeAddressModal()">&times;</span>
            
            <div id="addressListView">
                <h3>Select Address</h3>
                {{-- TOMBOL PINK ADA DISINI --}}
                <button type="button" class="add-new-address-btn" onclick="showAddForm()">+ Add New Address</button>
                
                <div class="address-list-container">
                    @forelse($all_addresses as $addr)
                        <div class="address-card-item" onclick="selectAddress(this)"
                             data-id="{{ $addr->id }}"
                             data-name="{{ $addr->receiver_name }}"
                             data-phone="{{ $addr->phone_number }}"
                             data-address="{{ $addr->full_address }}"
                             data-city="{{ $addr->city }}"
                             data-district="{{ $addr->district }}"
                             data-province="{{ $addr->province }}"
                             data-postal="{{ $addr->postal_code }}">
                             
                            <div style="font-weight: bold; margin-bottom: 5px;">
                                {{ $addr->receiver_name }} <span style="font-weight: normal; color: #666">| {{ $addr->phone_number }}</span>
                                @if($addr->is_default) <span class="badge-default">Default</span> @endif
                            </div>
                            <div style="color: #444; font-size: 14px; line-height: 1.4;">
                                {{ $addr->full_address }}<br>
                                {{ $addr->district }}, {{ $addr->city }}, {{ $addr->province }}, {{ $addr->postal_code }}
                            </div>
                            
                            <div class="address-actions">
                                <button type="button" class="btn-action-sm btn-edit" 
                                    onclick="event.stopPropagation(); showEditForm({{ json_encode($addr) }})">Edit</button>
                                
                                <form action="{{ route('checkout.address.delete', $addr->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-sm btn-delete" onclick="event.stopPropagation()">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p style="text-align: center; color: gray; padding: 20px;">You don't have any saved addresses. Click "Add New Address" above.</p>
                    @endforelse
                </div>
            </div>

            <div id="addressFormView" style="display: none;">
                <div class="modal-header-nav">
                    <button type="button" class="back-to-list-btn" onclick="showListView()"><i class='bx bx-left-arrow-alt'></i> Back to List</button>
                    <h3 id="formTitle" style="margin: 0;">Add New Address</h3>
                </div>

                <form id="addressFormInput" method="POST" action="{{ route('checkout.address.add') }}">
                    @csrf
                    <div id="methodField"></div> {{-- Tempat naruh @method('PUT') --}}
                    
                    <div class="form-group">
                        <label>Receiver Name:</label>
                        <input type="text" name="receiver_name" id="inputName" required placeholder="e.g. John Doe">
                    </div>
                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="text" name="phone_number" id="inputPhone" required placeholder="e.g. 08123456789">
                    </div>
                    <div class="form-group">
                        <label>Full Address:</label>
                        <textarea name="full_address" id="inputAddress" rows="2" required placeholder="Street name, Building, House No."></textarea>
                    </div>
                    <div class="form-group" style="display: flex; gap: 10px;">
                        <div style="flex:1">
                            <label>District (Kecamatan):</label>
                            <input type="text" name="district" id="inputDistrict" placeholder="e.g. Tebet">
                        </div>
                        <div style="flex:1">
                            <label>City:</label>
                            <input type="text" name="city" id="inputCity" required placeholder="e.g. Jakarta Selatan">
                        </div>
                    </div>
                    <div class="form-group" style="display: flex; gap: 10px;">
                        <div style="flex:1">
                            <label>Province:</label>
                            <input type="text" name="province" id="inputProvince" required placeholder="e.g. DKI Jakarta">
                        </div>
                        <div style="flex:1">
                            <label>Postal Code:</label>
                            <input type="text" name="postal_code" id="inputPostal" required placeholder="e.g. 12810">
                        </div>
                    </div>
                    
                    <button type="submit" class="save-address-btn" style="width: 100%; margin-top: 10px;">Save Address</button>
                </form>
            </div>

        </div>
    </div>
@endsection


@section('footer_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal Logic
            const addressEditModal = document.getElementById('addressEditModal');
            window.openAddressModal = function() {
                document.getElementById('modalName').value = document.getElementById('hiddenDeliveryName').value;
                document.getElementById('modalPhone').value = document.getElementById('hiddenDeliveryPhone').value;
                document.getElementById('modalAddress').value = document.getElementById('hiddenDeliveryAddress').value;
                document.getElementById('modalCity').value = document.getElementById('hiddenDeliveryCity').value;
                document.getElementById('modalProvince').value = document.getElementById('hiddenDeliveryProvince').value;
                document.getElementById('modalPostalCode').value = document.getElementById('hiddenDeliveryPostalCode').value;
                if (addressEditModal) addressEditModal.style.display = 'flex';
            };
            window.closeAddressModal = function() { if (addressEditModal) addressEditModal.style.display = 'none'; };
            window.onclick = function(event) { if (event.target === addressEditModal) closeAddressModal(); };
            window.saveAddressChanges = function() {
                const newAddressData = { name: document.getElementById('modalName').value, phone: document.getElementById('modalPhone').value, address: document.getElementById('modalAddress').value, city: document.getElementById('modalCity').value, province: document.getElementById('modalProvince').value, postal_code: document.getElementById('modalPostalCode').value };
                document.getElementById('deliveryNameDisplay').textContent = newAddressData.name;
                document.getElementById('deliveryPhoneDisplay').textContent = newAddressData.phone;
                document.getElementById('deliveryAddressDisplay').textContent = `${newAddressData.address}, ${newAddressData.city}, ${newAddressData.province}, ${newAddressData.postal_code}`;
                document.getElementById('hiddenDeliveryName').value = newAddressData.name; document.getElementById('hiddenDeliveryPhone').value = newAddressData.phone; document.getElementById('hiddenDeliveryAddress').value = newAddressData.address; document.getElementById('hiddenDeliveryCity').value = newAddressData.city; document.getElementById('hiddenDeliveryProvince').value = newAddressData.province; document.getElementById('hiddenDeliveryPostalCode').value = newAddressData.postal_code;

                 fetch('{{ route("checkout.saveAddress") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }, body: JSON.stringify(newAddressData) }) .then(response => response.json()) .then(data => console.log('Temp address saved:', data)) .catch(error => console.error('Error saving temp address:', error));
                closeAddressModal();
            };

            const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
            const bankSelectionDiv = document.getElementById('bankSelectionDiv');
            const ewalletSelectionDiv = document.getElementById('ewalletSelectionDiv');
            function togglePaymentDetails() {
                const selectedElem = document.querySelector('input[name="payment_method"]:checked');
                const selectedMethod = selectedElem ? selectedElem.value : '';
                if(bankSelectionDiv) bankSelectionDiv.style.display = 'none';
                if(ewalletSelectionDiv) ewalletSelectionDiv.style.display = 'none';
                document.querySelectorAll('input[name="bank_choice"], input[name="ewallet_choice"]').forEach(input => input.disabled = true);
                if (selectedMethod === 'Bank Transfer') {
                    if(bankSelectionDiv) bankSelectionDiv.style.display = 'block';
                    document.querySelectorAll('input[name="bank_choice"]').forEach(input => input.disabled = false);
                    const firstBank = bankSelectionDiv?.querySelector('input[name="bank_choice"]');
                    if (firstBank && !bankSelectionDiv.querySelector('input[name="bank_choice"]:checked')) firstBank.checked = true;
                } else if (selectedMethod === 'E-Wallet') {
                    if(ewalletSelectionDiv) ewalletSelectionDiv.style.display = 'block';
                    document.querySelectorAll('input[name="ewallet_choice"]').forEach(input => input.disabled = false);
                    const firstEwallet = ewalletSelectionDiv?.querySelector('input[name="ewallet_choice"]');
                    if (firstEwallet && !ewalletSelectionDiv.querySelector('input[name="ewallet_choice"]:checked')) firstEwallet.checked = true;
                }
            }
            paymentMethodRadios.forEach(radio => radio.addEventListener('change', togglePaymentDetails));
            togglePaymentDetails(); // Initial call

            // Form Validation on Submit
            const checkoutForm = document.getElementById('checkoutForm');
            if(checkoutForm) {
                checkoutForm.addEventListener('submit', function(event) {
                    const selectedPayMethodElem = document.querySelector('input[name="payment_method"]:checked');
                    if (!selectedPayMethodElem) { event.preventDefault(); alert('Please select a payment method.'); return; }
                    const selectedPayMethod = selectedPayMethodElem.value;
                    if (selectedPayMethod === 'Bank Transfer') {
                        if (!document.querySelector('input[name="bank_choice"]:checked:not(:disabled)')) { event.preventDefault(); alert('Please select a bank.'); }
                    } else if (selectedPayMethod === 'E-Wallet') {
                        if (!document.querySelector('input[name="ewallet_choice"]:checked:not(:disabled)')) { event.preventDefault(); alert('Please select an e-wallet.'); }
                    }
                });
            }
        });
    </script>
@endsection
