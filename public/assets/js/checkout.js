 // public/assets/js/checkout.js

document.addEventListener('DOMContentLoaded', function() {
    // Hitung total item saat halaman dimuat
    const totalItems = document.querySelectorAll('.product-item-checkout').length;
    if (document.getElementById('totalItemCountCheckout')) {
        document.getElementById('totalItemCountCheckout').textContent = totalItems;
    }

    const addressEditModal = document.getElementById('addressEditModal');

    // Fungsi untuk membuka modal edit alamat
    window.openAddressModal = function() { 
        document.getElementById('modalName').value = document.getElementById('hiddenDeliveryName').value;
        document.getElementById('modalPhone').value = document.getElementById('hiddenDeliveryPhone').value;
        document.getElementById('modalAddress').value = document.getElementById('hiddenDeliveryAddress').value;
        document.getElementById('modalCity').value = document.getElementById('hiddenDeliveryCity').value;
        document.getElementById('modalProvince').value = document.getElementById('hiddenDeliveryProvince').value;
        document.getElementById('modalPostalCode').value = document.getElementById('hiddenDeliveryPostalCode').value;

        if (addressEditModal) {
            addressEditModal.style.display = 'flex';
        }
    };

    // Fungsi untuk menutup modal
    window.closeAddressModal = function() {
        if (addressEditModal) {
            addressEditModal.style.display = 'none';
        }
    };

    // Tutup modal jika klik di luar area modal
    window.onclick = function(event) {
        if (event.target === addressEditModal) {
            closeAddressModal();
        }
    };

    // Fungsi untuk menyimpan perubahan alamat
    window.saveAddressChanges = function() {
        const newName = document.getElementById('modalName').value;
        const newPhone = document.getElementById('modalPhone').value;
        const newAddress = document.getElementById('modalAddress').value;
        const newCity = document.getElementById('modalCity').value;
        const newProvince = document.getElementById('modalProvince').value;
        const newPostalCode = document.getElementById('modalPostalCode').value;

        // Update tampilan di halaman utama
        document.getElementById('deliveryNameDisplay').textContent = newName;
        document.getElementById('deliveryPhoneDisplay').textContent = newPhone;
        document.getElementById('deliveryAddressDisplay').textContent = `${newAddress}, ${newCity}, ${newProvince}, ${newPostalCode}`;

        // Update nilai di input hidden form
        document.getElementById('hiddenDeliveryName').value = newName;
        document.getElementById('hiddenDeliveryPhone').value = newPhone;
        document.getElementById('hiddenDeliveryAddress').value = newAddress;
        document.getElementById('hiddenDeliveryCity').value = newCity;
        document.getElementById('hiddenDeliveryProvince').value = newProvince;
        document.getElementById('hiddenDeliveryPostalCode').value = newPostalCode;

        closeAddressModal();
    };

    // --- Logika toggle payment method ---
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const bankSelectionDiv = document.getElementById('bankSelectionDiv');
    const ewalletSelectionDiv = document.getElementById('ewalletSelectionDiv');

    function togglePaymentDetails() {
        const selectedPaymentMethodElement = document.querySelector('input[name="payment_method"]:checked');
        const selectedPaymentMethod = selectedPaymentMethodElement ? selectedPaymentMethodElement.value : '';

        if (selectedPaymentMethod === 'Bank Transfer') {
            if (bankSelectionDiv) bankSelectionDiv.style.display = 'block';
            if (ewalletSelectionDiv) ewalletSelectionDiv.style.display = 'none';
            
            const firstBankRadio = document.querySelector('input[name="bank_choice"]');
            if (firstBankRadio && !document.querySelector('input[name="bank_choice"]:checked')) {
                firstBankRadio.checked = true;
            }
            const currentEwalletChoice = document.querySelector('input[name="ewallet_choice"]:checked');
            if (currentEwalletChoice) {
                currentEwalletChoice.checked = false;
            }

        } else if (selectedPaymentMethod === 'E-Wallet') {
            if (bankSelectionDiv) bankSelectionDiv.style.display = 'none';
            if (ewalletSelectionDiv) ewalletSelectionDiv.style.display = 'block';

            const firstEwalletRadio = document.querySelector('input[name="ewallet_choice"]');
            if (firstEwalletRadio && !document.querySelector('input[name="ewallet_choice"]:checked')) {
                firstEwalletRadio.checked = true;
            }
            const currentBankChoice = document.querySelector('input[name="bank_choice"]:checked');
            if (currentBankChoice) {
                currentBankChoice.checked = false;
            }

        } else { // Untuk COD atau metode lain
            if (bankSelectionDiv) bankSelectionDiv.style.display = 'none';
            if (ewalletSelectionDiv) ewalletSelectionDiv.style.display = 'none';
            
            const currentBankChoice = document.querySelector('input[name="bank_choice"]:checked');
            if (currentBankChoice) currentBankChoice.checked = false;
            
            const currentEwalletChoice = document.querySelector('input[name="ewallet_choice"]:checked');
            if (currentEwalletChoice) currentEwalletChoice.checked = false;
        }
    }

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', togglePaymentDetails);
    });

    togglePaymentDetails(); // Panggil saat load pertama kali

    // --- Validasi form submit ---
    const checkoutForm = document.querySelector('form[action="{{ route(\'checkout.process\') }}"]'); // Lebih spesifik
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(event) {
            const selectedPaymentMethodElement = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPaymentMethodElement) {
                event.preventDefault(); // Hentikan submit
                alert('Mohon pilih metode pembayaran.');
                return;
            }
            const selectedPaymentMethod = selectedPaymentMethodElement.value;

            if (selectedPaymentMethod === 'Bank Transfer') {
                const selectedBankChoice = document.querySelector('input[name="bank_choice"]:checked');
                if (!selectedBankChoice) {
                    event.preventDefault();
                    alert('Mohon pilih bank untuk metode pembayaran Bank Transfer.');
                }
            } else if (selectedPaymentMethod === 'E-Wallet') {
                const selectedEwalletChoice = document.querySelector('input[name="ewallet_choice"]:checked');
                if (!selectedEwalletChoice) {
                    event.preventDefault();
                    alert('Mohon pilih penyedia e-wallet untuk metode pembayaran E-Wallet.');
                }
            }
        });
   }
});
