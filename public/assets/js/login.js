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
        var belumPunyaAkun = document.getElementById("belumPunyaAkun");
        

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

// <!--------------js for POP UP----------------->
        // Function to show the pop-up
        function showPopup(popupId) {
            document.getElementById(popupId).style.display = 'flex';
        }

        // Function to close the pop-up
        function closePopup(popupId) {
            document.getElementById(popupId).style.display = 'none';
        }

        // Event listeners for form submissions
        document.getElementById('LoginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form from submitting
            showPopup('loginPopup'); // Show the login success pop-up
        });

        document.getElementById('RegisterForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form from submitting
            showPopup('registerPopup'); // Show the register success pop-up
        });


