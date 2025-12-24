document.addEventListener('DOMContentLoaded', function() {

    // ===========================
    // 1. FITUR LIKE (AJAX)
    // ===========================
    const likeBtn = document.querySelector('.post-like-button');
    
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            // Mencegah double click spam
            if(this.classList.contains('loading')) return;
            
            const postId = this.dataset.postId;
            const likeUrl = this.dataset.likeUrl;
            const csrfToken = this.dataset.csrf;
            const icon = this.querySelector('i');
            const countSpan = this.querySelector('.count');

            // Tambahkan efek loading sementara (opsional)
            this.classList.add('loading');

            // Kirim request ke Controller
            fetch(likeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ id: postId })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update Angka
                    countSpan.textContent = data.new_like_count;

                    // Update Ikon & Warna
                    if (data.is_liked) {
                        icon.classList.remove('bx-heart');
                        icon.classList.add('bxs-heart'); // Icon Solid
                        likeBtn.classList.add('liked'); // Class CSS merah
                    } else {
                        icon.classList.remove('bxs-heart');
                        icon.classList.add('bx-heart'); // Icon Outline
                        likeBtn.classList.remove('liked');
                    }
                } else {
                    // Jika user belum login (error 401)
                    if(data.message === 'Login required') {
                        alert('Silakan login terlebih dahulu untuk menyukai postingan.');
                        window.location.href = '/login'; 
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyukai postingan.');
            })
            .finally(() => {
                this.classList.remove('loading');
            });
        });
    }

    // ===========================
    // 2. FITUR SHARE (MODAL + AJAX)
    // ===========================
    const shareBtn = document.querySelector('.post-share-button');
    const shareModal = document.getElementById('shareModal');
    const closeShareBtn = document.querySelector('.close-share-btn');
    const shareLinkInput = document.getElementById('shareLinkInput');
    const copyLinkBtn = document.getElementById('copyLinkBtn');
    const copyMsg = document.getElementById('copySuccessMsg');

    if (shareBtn && shareModal) {
        // Buka Modal
        shareBtn.addEventListener('click', function() {
            const postUrl = this.dataset.postUrl; 
            shareLinkInput.value = postUrl;
            shareModal.style.display = 'flex';
            
            // Reset pesan sukses saat modal dibuka baru
            if(copyMsg) copyMsg.style.display = 'none';
        });

        // Tutup Modal (Tombol X)
        if(closeShareBtn) {
            closeShareBtn.addEventListener('click', function() {
                shareModal.style.display = 'none';
            });
        }

        // Tutup Modal (Klik Luar)
        window.addEventListener('click', function(e) {
            if (e.target === shareModal) {
                shareModal.style.display = 'none';
            }
        });

        // Logika Salin Link & Update Database
        if(copyLinkBtn) {
            copyLinkBtn.addEventListener('click', function() {
                // 1. Salin ke Clipboard
                shareLinkInput.select();
                shareLinkInput.setSelectionRange(0, 99999); // Untuk mobile
                
                // Gunakan API clipboard modern jika ada, fallback ke execCommand
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(shareLinkInput.value);
                } else {
                    document.execCommand('copy');
                }

                // 2. Tampilkan Pesan Sukses
                if(copyMsg) copyMsg.style.display = 'block';

                // 3. Update Database (Increment Share Count)
                const shareUrl = shareBtn.dataset.shareUrl;
                const csrfToken = shareBtn.dataset.csrf;
                const countSpan = shareBtn.querySelector('.count');

                fetch(shareUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update tampilan angka di halaman
                        if(countSpan) countSpan.textContent = data.share_count;
                        
                        // Tutup modal otomatis setelah 1.5 detik
                        setTimeout(() => {
                            shareModal.style.display = 'none';
                        }, 1500);
                    }
                })
                .catch(error => console.error('Error share:', error));
            });
        }
    }
});