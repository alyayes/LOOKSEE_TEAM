document.addEventListener('DOMContentLoaded', function() {
const cartContainer = document.querySelector('.cart-container');
if (!cartContainer) return;

const selectAllCheckbox = document.getElementById('selectAllItems');
const checkoutBtn = document.getElementById('checkoutBtn');
const cartItemsList = document.querySelector('.cart-items-list');

const UPDATE_URL = cartContainer.dataset.updateUrl;
const DELETE_URL = cartContainer.dataset.deleteUrl;
const CHECKOUT_URL = cartContainer.dataset.checkoutUrl;
const CSRF_TOKEN = cartContainer.dataset.csrfToken;

function formatRupiah(number) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

function updateItemTotalPrice(itemCard) {
    const pricePerItem = parseFloat(itemCard.dataset.price);
    const quantity = parseInt(itemCard.querySelector('.quantity-value').textContent);
    const itemTotalPriceElement = itemCard.querySelector('.item-total-price');
    itemTotalPriceElement.textContent = formatRupiah(pricePerItem * quantity);
}

function updateGrandTotal() {
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    let grandTotal = 0;
    let totalProducts = 0;
    let allChecked = true;

    itemCheckboxes.forEach(checkbox => {
        const itemCard = checkbox.closest('.cart-item-card');
        const price = parseFloat(itemCard.dataset.price);
        const quantity = parseInt(itemCard.querySelector('.quantity-value').textContent);

        if (checkbox.checked) {
            grandTotal += price * quantity;
            totalProducts += quantity;
        } else {
            allChecked = false;
        }
    });

    document.getElementById('grandTotalPrice').textContent = formatRupiah(grandTotal);
    document.getElementById('totalProductCount').textContent = totalProducts;

    // Update status checkbox select all
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = itemCheckboxes.length > 0 && allChecked;
    }
}

// --- Event: checkbox individual ---
document.querySelectorAll('.item-checkbox').forEach(cb => {
    cb.addEventListener('change', updateGrandTotal);
});

// --- Event: checkbox "select all" ---
selectAllCheckbox?.addEventListener('change', function() {
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.checked = selectAllCheckbox.checked;
    });
    updateGrandTotal();
});

// --- Event: tombol + / - ---
cartItemsList?.addEventListener('click', function(e) {
    const target = e.target;
    const card = target.closest('.cart-item-card');
    if (!card) return;

    if (target.classList.contains('quantity-btn')) {
        const qtyEl = card.querySelector('.quantity-value');
        let qty = parseInt(qtyEl.textContent);
        const stock = parseInt(card.dataset.stock);

        if (target.dataset.action === 'increase' && qty < stock) qty++;
        else if (target.dataset.action === 'decrease' && qty > 1) qty--;

        qtyEl.textContent = qty;
        updateItemTotalPrice(card);
        updateGrandTotal();

        fetch(UPDATE_URL, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ id_produk: card.dataset.id, quantity: qty })
        });
    }

    if (target.closest('.remove-item-btn')) {
        if (confirm('Are you sure you want to remove this product?')) {
            fetch(DELETE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ id_produk: card.dataset.id })
            }).then(res => res.json()).then(data => {
                card.remove();
                updateGrandTotal();
            });
        }
    }
});

// --- Event: checkout ---
checkoutBtn?.addEventListener('click', function() {
    const selectedItems = document.querySelectorAll('.item-checkbox:checked').length;
    if (selectedItems === 0) {
        alert('Please select at least one item to checkout.');
        return;
    }
    window.location.href = CHECKOUT_URL;
});

// Hitung total awal
updateGrandTotal();


});
