<div class="wrapper">
    <!--sidebar wrapper -->
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img src="{{ asset('assets/images/logo.png') }}" class="logo-icon" alt="logo icon">
            </div>
            <div>
                <h4 class="logo-text">LOOKSEE</h4>
            </div>
            <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
            </div>
        </div>
        
        <!--navigation-->
        <ul class="metismenu" id="menu">
    <li>
        <a href="{{ route('dashboard.dashboardAdmin') }}"> 
            <i class='bx bx-home-alt'></i>
            <div class="menu-title">Dashboard</div>
        </a>
        <a href="{{ route('dashboard.dashboardAdmin') }}">
            <i class='bx bxs-bar-chart-alt-2'></i>
            <div class="menu-title">Analytics</div>
        </a>
        <a href="{{ route('products.index') }}"> 
            <i class='bx bxs-shopping-bags'></i>
            <div class="menu-title">Products</div>
        </a>
        <a href="{{ route('users-admin.usersAdmin') }}"> 
            <i class='bx bxs-group' ></i>
            <div class="menu-title">Users</div>
        </a>
        <a href="{{ route('admin.orders.index') }}"> 
    <i class='bxr bx-shopping-bag-alt'></i> 
    <div class="menu-title">Order</div>
</a>
>
        <a href="{{ route('stylejournalAdmin.index') }}"> 
            <i class='bx bx-note'></i>
            <div class="menu-title">Style Journal</div>
        </a>
        <a href="{{ route('toAdmin.toAdmin') }}">
            <i class='bx bxs-t-shirt'></i>
            <div class="menu-title">Today's Outfit</div>
        </a>
    </li>
</ul>

    </div>
    
    <header>
        <div class="topbar d-flex align-items-center">
            <nav class="navbar navbar-expand gap-3">
                <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                </div>

                <div class="top-menu ms-auto">
                    <ul class="navbar-nav align-items-center gap-1">
                        <li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="avascript:;" data-bs-toggle="dropdown"><img src="{{ asset('assets/flags/4x3/idn.svg') }}" width="22" alt=""></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item d-flex align-items-center py-2" href="assets/flags/4x3/idn.svg"><img src="{{ asset('assets/flags/4x3/idn.svg') }}" width="20" alt=""><span class="ms-2">Indonesia</span></a></li>
                                {{-- ... item bahasa lainnya ... --}}
                            </ul>
                        </li>

                        {{-- Dark Mode & Notifikasi --}}
                        <li class="nav-item dark-mode d-none d-sm-flex">
                            <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i></a>
                        </li>
                        
                        {{-- Dropdown Notifikasi --}}
                        <li class="nav-item dropdown dropdown-large">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">8</span>
                                <i class='bx bx-bell'></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:;">
                                    <div class="msg-header">
                                        <p class="msg-header-title">Notifications</p>
                                        <p class="msg-header-badge">8 New</p>
                                    </div>
                                </a>
                                <div class="header-notifications-list">
                                    {{-- Mengubah URL aset pada notifikasi --}}
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div class="user-online">
                                                <img src="{{ asset('assets/images/sanha.jpg') }}" class="msg-avatar" alt="user avatar">
                                            </div>
                                            <div class="grow ">
                                                <h6 class="msg-name">Sanha <span class="msg-time float-end">5 sec ago</span></h6>
                                                <p class="msg-info">Is this product still available?</p>
                                            </div>
                                        </div>
                                    </a>
                                    {{-- ... item notifikasi lainnya ... --}}
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div class="notify bg-light-primary">
                                                <img src="{{ asset('assets/images/app/github.png') }}" width="25" alt="user avatar">
                                            </div>
                                            <div class="grow">
                                                <h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day ago</span></h6>
                                                <p class="msg-info">24 new authors joined last week</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <a href="javascript:;">
                                    <div class="text-center msg-footer">
                                        <button class="btn btn-primary w-100">View All Notifications</button>
                                    </div>
                                </a>
                            </div>
                        </li>
                        
                    </ul>
                </div>
                <div class="user-box dropdown px-3">
                    <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/images/aya.jpg') }}" class="user-img" alt="user avatar">
                        <div class="user-info"></div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        {{-- Placeholder route for profile --}}
                        <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
                        </li>
                        {{-- Placeholder route for messages --}}
                        <li><a class="dropdown-item d-flex align-items-center" href="#"><i class='bx bx-message-dots'></i></i><span>Message</span></a>
                        </li>
                        <div class="dropdown-divider mb-0"></div>
                        {{-- Logout menggunakan form POST --}}
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out-circle"></i><span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>    
</div>
