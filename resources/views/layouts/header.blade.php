<header>
    <div class="container">
        <div class="navbar">
            
            {{-- LOGO --}}
            <div class="logo">
                <a href="{{ route('homepage') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" width="125px" alt="LOOKSEE Logo">
                </a>
            </div>

            {{-- NAV --}}
            <nav>
                <ul id="MenuItems">
                    <li><a href="{{ route('homepage') }}">Home</a></li>
                    <li><a href="{{ route('community.trends') }}">Trends Now</a></li>
                    <li><a href="{{ route('community.todays-outfit') }}">Today's Outfit</a></li>
                    <li><a href="{{ route('journal.index') }}">Style Journal</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>

            <div class="nav-icon">
                <a href="#"><i class='bx bx-search'></i></a>
                <a href="{{ route('favorites.index') }}"><i class='bx bx-heart'></i></a>
                <a href="{{ route('cart') }}"><i class='bx bx-cart'></i></a>
                <a href="#"><i class='bx bx-bell'></i></a>

                <i class="dropdown">
                    <i class='bx bx-user'>
                        <div class="dropdown-profile">
                            
                            <div class="dropdown-profile-submenu">
                                <a href="{{ route('profile.index') }}">My Profile</a>
                            </div>

                            <div class="dropdown-profile-submenu">
                                <a href="{{ route('orders.list') }}">My Orders</a>
                            </div>

                            <div class="dropdown-profile-submenu">
                                <a href="{{ route('profile.settings') }}">Settings</a>
                            </div>

                            <div class="dropdown-profile-submenu">
                                <a href="{{ route('logout') }}" class="submenu-title">Log Out</a>
                            </div>

                        </div>
                    </i>
                </i>

            </div>
        </div>
    </div>
</header>
