<div class="garis">
</div>

<footer> 
    <div class="footer">
        <div class="container">
            <div class="row">
                
                <div class="footer-col-1">
                    <h3>Contact Us</h3>
                    <ul>
                        <li><a href="#">Address : Faculty of Applied Science, Telkom University.</a></li>
                        <li><a href="#">Phone : (+62) 821 2345 6789</a></li>
                        <li><a href="#">Email : looksee@gmail.com</a></li>
                    </ul>
                </div>
                
                <div class="footer-col-2">
                    {{-- asset() tetap aktif karena merujuk ke file statis (gambar) --}}
                    <img src="{{ asset('assets/images/logoFooter.png') }}" alt="LOOKSEE Footer Logo">
                    <p>Our Purpose Is To Help Users Discover and Explore the Best Outfit Recommendations.</p>
                </div>
                
                <div class="footer-col-3">
                    <h3>Quick Shop</h3>
                    <ul>
                        {{-- <li><a href="{{ route('shop.man') }}">Man</a></li> --}}
                        <li><a href="#">Man</a></li>
                        
                        {{-- <li><a href="{{ route('shop.woman') }}">Woman</a></li> --}}
                        <li><a href="#">Woman</a></li>

                        {{-- <li><a href="{{ route('trends.index') }}">Trends Now</a></li> --}}
                        <li><a href="#">Trends Now</a></li>
                    </ul>
                </div>
                
                <div class="footer-col-4">
                    <h3>My Account</h3>
                    <ul>
                        {{-- <li><a href="{{ route('profile.show') }}">My Account</a></li> --}}
                        <li><a href="#">My Account</a></li>

                        {{-- <li><a href="{{ route('favorites.index') }}">Product's Favorite</a></li> --}}
                        <li><a href="#">Product's Favorite</a></li>

                        {{-- <li><a href="{{ route('notifications.index') }}">Notification</a></li> --}}
                        <li><a href="#">Notification</a></li>
                    </ul>
                </div>
                
            </div>
            <hr>
            <p class="Copyright">Copyright &copy; {{ date('Y') }} LOOKSEE. All rights reserved.</p>
        </div>
    </div>
</footer>