document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. LIKE BUTTON ---
    document.querySelectorAll('.post-like-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            
            const postId = this.dataset.postId;
            const likeUrl = this.dataset.likeUrl;
            const csrfToken = this.dataset.csrf;
            const icon = this.querySelector('.bx');
            const likeCountSpan = this.querySelector('.count');

            // Debugging
            console.log("Liking post:", postId, "URL:", likeUrl);

            fetch(likeUrl, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json' // Penting agar Laravel tau ini request AJAX
                },
                body: JSON.stringify({ id_post: postId })
            })
            .then(response => {
                if (response.status === 401) {
                    alert("Silakan login terlebih dahulu untuk menyukai postingan.");
                    return null;
                }
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    // Ubah Icon
                    if (data.is_liked) {
                        icon.classList.remove('bx-heart');
                        icon.classList.add('bxs-heart');
                        this.classList.add('liked'); // Tambah class CSS merah
                    } else {
                        icon.classList.remove('bxs-heart');
                        icon.classList.add('bx-heart');
                        this.classList.remove('liked');
                    }
                    // Update Angka
                    likeCountSpan.textContent = data.new_like_count;
                }
            })
            .catch(error => {
                console.error('Error liking post:', error);
                alert('Gagal menyukai postingan. Cek console log.');
            });
        });
    });

    // --- 2. COMMENT TRIGGER (SCROLL TO FORM) ---
    const commentTrigger = document.querySelector('.post-comment-trigger'); // Pastikan class ini ada di Blade (lihat langkah 3)
    const commentInput = document.querySelector('.comment-input-field');

    if (commentTrigger && commentInput) {
        commentTrigger.addEventListener('click', function() {
            commentInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            commentInput.focus();
        });
    }

    // --- 3. SHARE BUTTON ---
    document.querySelectorAll('.post-share-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const shareUrl = this.dataset.shareUrl;
            const csrfToken = this.dataset.csrf;
            const shareCountSpan = this.querySelector('.count');

            fetch(shareUrl, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': csrfToken 
                },
                body: JSON.stringify({ id_post: this.dataset.postId })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    shareCountSpan.textContent = data.share_count;
                    // Copy Link ke Clipboard
                    navigator.clipboard.writeText(this.dataset.postUrl).then(() => {
                        alert('Link copied to clipboard & Post shared count updated!');
                    });
                }
            })
            .catch(error => console.error('Error sharing post:', error));
        });
    });

    // --- 4. CART & FAVORITE ---
    document.querySelectorAll('.add-to-cart-product, .add-to-favorite').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.classList.contains('add-to-cart-product') ? 'cart' : 'favorite';
            // Placeholder Logic
            alert(Product ID ${this.dataset.productId} added to ${action}! (Backend logic required));
        });
    });

});