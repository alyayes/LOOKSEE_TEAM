        function showTab(tabName, event) {
            const contents = document.querySelectorAll('.content');
            contents.forEach(c => c.style.display = 'none');
            
            const selectedContent = document.getElementById(tabName);
            if(selectedContent) {
                selectedContent.style.display = 'block';
            }

            const tabButtons = document.querySelectorAll('.tab');
            tabButtons.forEach(btn => btn.classList.remove('active'));

            if(event && event.currentTarget) {
                event.currentTarget.classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Tampilkan tab pertama secara default
            const firstTabButton = document.querySelector('.tabs .tab');
            if (firstTabButton) {
                showTab('myStyle', { currentTarget: firstTabButton });
            }

            // Logika tombol upload
            const uploadBtn = document.getElementById('uploadBtn');
            const imageUploadInput = document.getElementById('imageUpload');
            const uploadForm = document.getElementById('uploadForm');

            uploadBtn?.addEventListener('click', function () {
                imageUploadInput?.click();
            });

            imageUploadInput?.addEventListener('change', function () {
                if (this.files.length > 0) {
                    uploadForm?.submit();
                }
            });
        });
        
