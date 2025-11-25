    function showTab(tabName, element) {
        const contents = document.querySelectorAll('.fav-container .content');
        contents.forEach(content => content.style.display = 'none');
        document.getElementById(tabName).style.display = 'block';

        const tabs = document.querySelectorAll('.fav-container .tab');
        tabs.forEach(tab => tab.classList.remove('active'));
        element.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi tab pertama
        const firstTab = document.querySelector('.fav-container .tab');
        if (firstTab) {
            showTab('style', firstTab);
        }
        
        const favContainer = document.querySelector('.fav-container');
        if (!favContainer) return;

        const DELETE_URL = favContainer.dataset.deleteUrl;
        const ADD_TO_CART_URL = favContainer.dataset.addToCartUrl;
        const CSRF_TOKEN = favContainer.dataset.csrfToken;

        // Fungsi Custom Alert (bisa dijadikan global)
        function showCustomAlert(message) {
            // (implementasi custom alert Anda di sini)
            alert(message); // Menggunakan alert() bawaan sebagai fallback
        }

        // Fungsi Custom Confirm
        function showCustomConfirm(message) {
            return Promise.resolve(confirm(message)); // Menggunakan confirm() bawaan
        }

        // Event listener untuk tombol 'Delete' dan 'Add to Cart'
        document.getElementById('products').addEventListener('click', async function (e) {
            const target = e.target;

            // Logika Hapus Favorit
            if (target.classList.contains('favorite-btn')) {
                const favId = target.dataset.favId;
                const confirmation = await showCustomConfirm('Are you sure you want to remove this product from favorites?');
                if (!confirmation) return;

                fetch(DELETE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ id_favorite: favId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`product-${favId}`)?.remove();
                    }
                    showCustomAlert(data.message);
                })
                .catch(err => {
                    console.error('Error:', err);
                    showCustomAlert('An error occurred.');
                });
            }

            // Logika Tambah ke Keranjang
            if (target.classList.contains('buy-now-btn')) {
                const productId = target.dataset.productId;
                fetch(ADD_TO_CART_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ id_produk: productId })
                })
                .then(res => res.json())
                .then(data => {
                    showCustomAlert(data.message);
                })
                .catch(err => {
                    console.error('Error:', err);
                    showCustomAlert('An error occurred.');
                });
            }
        });
    });