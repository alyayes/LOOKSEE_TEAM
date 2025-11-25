
        var MenuItems = document.getElementById("MenuItems");
        MenuItems.style.maxHeight = "0px";

        function menutoggle(){
            if(MenuItems.style.maxHeight == "0px") {
                MenuItems.style.maxHeight = "200px";
            } else {
                MenuItems.style.maxHeight = "0px";
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const paginationContainer = document.getElementById('pagination'); 
            const articles = document.querySelectorAll('.blog-post');
            
            function showPage(page) {
                articles.forEach((article) => {
                    if (article.dataset.page === page.toString()) {
                        article.style.display = 'block'; // Atau 'flex' jika .blog-post Anda adalah flex container
                    } else {
                        article.style.display = 'none';
                    }
                });

                const pageNumbersInContainer = paginationContainer.querySelectorAll('.page-number');
                pageNumbersInContainer.forEach((num) => {
                    num.classList.remove('active');
                });
                const currentPageButton = paginationContainer.querySelector(`.page-number[data-page="${page}"]`);
                if (currentPageButton) currentPageButton.classList.add('active');
            }

            // Memastikan paginationContainer ada sebelum menambahkan event listener
            if (paginationContainer) {
                paginationContainer.addEventListener('click', function(event) {
                    const target = event.target;

                    if (target.classList.contains('page-number')) {
                        const page = parseInt(target.dataset.page);
                        showPage(page);
                    } else if (target.classList.contains('page-next')) {
                        const currentPageActive = paginationContainer.querySelector('.page-number.active');
                        if (currentPageActive) {
                            const currentPage = parseInt(currentPageActive.dataset.page);
                            const totalPageSpans = paginationContainer.querySelectorAll('.page-number').length;
                            if (currentPage < totalPageSpans) {
                                 showPage(currentPage + 1);
                            }
                        }
                    }
                });
            }
            
            if (articles.length > 0) { 
                 showPage(1);
            }
        });