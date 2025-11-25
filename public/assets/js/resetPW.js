    // <!---js for toggle menu--->
            var MenuItems = document.getElementById("MenuItems");

            MenuItems.style.maxHeight = "0px";

            function menutoggle(){
                if(MenuItems.style.maxHeight == "0px")
                    {
                        MenuItems.style.maxHeight = "200px";
                    }
                else
                    {
                        MenuItems.style.maxHeight = "0px"
                    }
            }

    // <!---js for toggle Form--->
            var LoginForm = document.getElementById("LoginForm");
            var RegisterForm = document.getElementById("RegisterForm");
            var Indicator = document.getElementById("Indicator");

                function register(){
                    RegisterForm.style.transform = "translateX(0px)";
                    LoginForm.style.transform = "translateX(0px)";
                    Indicator.style.transform = "translateX(100px)";
                }

                function login(){
                    RegisterForm.style.transform = "translateX(300px)";
                    LoginForm.style.transform = "translateX(300px)";
                    Indicator.style.transform = "translateX(0px)";
                }

    // Di dalam ../assets/js/forgotPW.js (atau file JS Anda)
    // Pastikan fungsi ini ada dan dipanggil dengan benar

    // Fungsi untuk menampilkan pop-up (dipanggil setelah aksi sukses)
    window.showPopup = function() {
        const popup = document.getElementById('popup');
        if (popup) {
            popup.classList.remove('hidden'); // Atau cara lain Anda menampilkan popup
        }
    }

    // Fungsi untuk menutup pop-up (dipanggil dari tombol close '×' di HTML)
    window.closePopup = function() {
        const popup = document.getElementById('popup');
        if (popup) {
            popup.classList.add('hidden'); // Atau cara lain Anda menyembunyikan popup
        }
    }

    // Inisialisasi lain jika ada (misalnya event listener untuk form submit)
    document.addEventListener('DOMContentLoaded', function() {
        const resetForm = document.getElementById('resetPasswordActualForm'); // atau form lain
        if (resetForm) {
            resetForm.addEventListener('submit', function(event) {
                event.preventDefault();
    //             // Logika submit Anda
                showPopup(); // Panggil setelah sukses
            });
        }
    });