<header>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="{{ route('homepage') }}"></a>
                    <img src="{{ asset('assets/images/logo.png') }}" width="125px" alt="LOOKSEE Logo">
                </a>
            </div>
            
            <nav>
                <ul id="MenuItems">
                    {{-- Navigasi Utama --}}
                    
                    {{-- 1. Home --}}
                    <li>
                        <a href="#" class="">Home</a>
                    </li>
                    
                    {{-- 2. Trends Now --}}
                    <li>
                        <a href="#" class="">Trends Now</a>
                    </li>
                    
                    {{-- 3. Today's Outfit --}}
                    <li>
                        <a href="#" class="">Today's Outfit</a>
                    </li>
                    
                    {{-- 4. Style Journal --}}
                    <li>
                        <a href="#" class="">Style Journal</a>
                    </li>
                    
                    {{-- 5. About --}}
                    <li>
                        <a href="#" class="">About</a>
                    </li>
                    
                    {{-- 6. Contact --}}
                    <li>
                        <a href="#" class="">Contact</a>
                    </li>
                </ul>
            </nav>
            
            <div class="nav-icon">
                <a href="#"><i class='bx bx-search'></i></a>
                
                <a href="#"><i class='bx bx-heart'></i></a>
                
                <a href="#"><i class='bx bx-cart'></i></a> 
                <a href="#"><i class='bx bx-bell'></i></a>
                
                <i class="dropdown">
                    <i class='bx bx-user'>
                        <div class="dropdown-profile">
                            
                            <div class="dropdown-profile-submenu">
                                <a href="#">My Profile</a>
                            </div>
                            <div class="dropdown-profile-submenu">
                                <a href="#">My Orders</a>
                            </div>
                            <div class="dropdown-profile-submenu">
                                <a href="#" class="submenu-title">Settings</a>
                            </div>
                            <div class="dropdown-profile-submenu">
                                <a href="#" class="submenu-title">Log Out</a>
                            </div>
                        </div>
                    </i>
                </div>
            </div>
        </div>
    </div>
</header>