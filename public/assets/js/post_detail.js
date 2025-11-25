        document.addEventListener('DOMContentLoaded', function() {
            // --- Like Button Logic ---
            const likeButton = document.querySelector('.post-like-button');
            likeButton?.addEventListener('click', function(event) {
                event.preventDefault();
                const postId = this.dataset.postId;
                const likeUrl = this.dataset.likeUrl;
                const csrfToken = this.dataset.csrf;
                const icon = this.querySelector('.bx');
                const likeCountSpan = this.querySelector('.count');
                
                fetch(likeUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ id_post: postId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        icon.classList.toggle('bx-heart');
                        icon.classList.toggle('bxs-heart');
                        likeCountSpan.textContent = data.like_count;
                    }
                })
                .catch(error => console.error('Error liking post:', error));
            });
        
            // --- Share Button Logic ---
            const shareButton = document.querySelector('.post-share-button');
            shareButton?.addEventListener('click', function(event) {
                event.preventDefault();
                const shareUrl = this.dataset.shareUrl;
                const postUrl = this.dataset.postUrl;
                const postTitle = this.dataset.postTitle;
                const csrfToken = this.dataset.csrf;
                const shareCountSpan = this.querySelector('.count');

                // Logika share... (sama seperti sebelumnya, lalu call fetch)
                console.log('Sharing post:', postTitle, postUrl);

                fetch(shareUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ id_post: this.dataset.postId })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        shareCountSpan.textContent = data.share_count;
                        alert('Post shared! (Dummy)');
                    }
                })
                .catch(error => console.error('Error sharing post:', error));
            });

            // --- Add to Cart/Favorite Logic (jika ada) ---
            document.querySelectorAll('.add-to-cart-product, .add-to-favorite').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.classList.contains('add-to-cart-product') ? 'cart' : 'favorite';
                    console.log(`Dummy action: Add product ${this.dataset.productId} to ${action}`);
                    alert(`Product added to ${action}! (Dummy)`);
                });
            });
        });
        
