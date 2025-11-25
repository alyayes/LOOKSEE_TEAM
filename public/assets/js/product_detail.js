    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.product-detail-container');
        if (!container) return;

        const addToCartBtn = container.querySelector('.add-to-cart-btn');
        const addToFavoriteBtn = container.querySelector('.add-to-favorite-btn');

        // Ambil URL dan Token dari data-attributes
        const CART_URL = container.dataset.addToCartUrl;
        const FAVORITE_URL = container.dataset.addToFavoriteUrl;
        const CSRF_TOKEN = container.dataset.csrfToken;

        function showCustomAlert(message) {
            alert(message); // Menggunakan alert bawaan sebagai fallback
        }

        // --- Event Listener untuk "Add to Cart" ---
        addToCartBtn?.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            fetch(CART_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ id_produk: productId })
            })
            .then(response => response.json())
            .then(data => {
                showCustomAlert(data.message);
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showCustomAlert('An error occurred while adding to cart.');
            });
        });

        // --- Event Listener untuk "Favorite" ---
        addToFavoriteBtn?.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const icon = this.querySelector('i');

            fetch(FAVORITE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ id_produk: productId })
            })
            .then(response => response.json())
            .then(data => {
                showCustomAlert(data.message);
                if (data.success) {
                    if (data.status === 'added') {
                        this.classList.add('favorited'); // Tambah class 'favorited'
                        icon.classList.remove('bx-heart');
                        icon.classList.add('bxs-heart'); // Ganti ikon jadi filled
                    } else if (data.status === 'removed') {
                        this.classList.remove('favorited');
                        icon.classList.remove('bxs-heart');
                        icon.classList.add('bx-heart');
                    }
                }
            })
            .catch(error => {
                console.error('Error adding to favorite:', error);
                showCustomAlert('An error occurred while managing favorites.');
            });
        });
    });