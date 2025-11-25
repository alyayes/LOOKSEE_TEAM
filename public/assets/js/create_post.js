document.addEventListener('DOMContentLoaded', function() {

    // --- Inisialisasi Elemen ---
    const openModalBtn = document.getElementById('openProductModalBtn');
    const closeModalBtn = document.getElementById('closeProductModalBtn');
    const productModal = document.getElementById('productModal');
    const searchInput = document.getElementById('productSearchInput');
    const productGrid = document.querySelector('#productModal .product-grid-modal');
    const selectedProductsContainer = document.getElementById('selectedProductsDisplay');

    // Set untuk melacak ID produk yang sudah ditambahkan
    const addedProductIds = new Set();

    // --- Fungsi-fungsi Modal ---
    function openModal() {
        if (productModal) {
            productModal.style.display = 'flex';
            searchInput.value = ''; // Kosongkan pencarian saat modal dibuka
            filterProducts(); // Tampilkan semua produk
        }
    }

    function closeModal() {
        if (productModal) {
            productModal.style.display = 'none';
        }
    }

    // --- Fungsi Logika Produk ---
    function filterProducts() {
        const filter = searchInput.value.toLowerCase();
        const productItems = productGrid.getElementsByClassName('product-item-modal');

        for (let item of productItems) {
            const name = item.dataset.name.toLowerCase();
            if (name.includes(filter)) {
                item.style.display = "flex";
            } else {
                item.style.display = "none";
            }
        }
    }

    function addProductToPost(id, name) {
        if (addedProductIds.has(id)) {
            alert('This product has already been added!');
            return;
        }

        const entry = document.createElement('div');
        entry.className = 'selected-product-entry';
        entry.dataset.id = id;

        entry.innerHTML = `
            <span>${name}</span>
            <button type="button" class="remove-selected-product-btn">&times;</button>
            <input type="hidden" name="selected_product_ids[]" value="${id}">
        `;
        
        selectedProductsContainer.appendChild(entry);
        addedProductIds.add(id);

        // Tambahkan event listener untuk tombol hapus yang baru dibuat
        entry.querySelector('.remove-selected-product-btn').addEventListener('click', function() {
            entry.remove();
            addedProductIds.delete(id);
        });
    }

    // --- Event Listeners ---
    openModalBtn?.addEventListener('click', openModal);
    closeModalBtn?.addEventListener('click', closeModal);
    searchInput?.addEventListener('keyup', filterProducts);

    // Tutup modal jika klik di luar area kontennya
    window.addEventListener('click', function(event) {
        if (event.target === productModal) {
            closeModal();
        }
    });

    // Event listener untuk tombol 'Add' di dalam modal
    productGrid?.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('add-to-post-btn')) {
            const productItem = e.target.closest('.product-item-modal');
            const productId = productItem.dataset.id;
            const productName = productItem.dataset.name;
            addProductToPost(productId, productName);
            closeModal();
        }
    });
});
