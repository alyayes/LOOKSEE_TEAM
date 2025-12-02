@extends('layouts.main')

@section('title', 'My Favorites - LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/favorite.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
@endsection

@section('content')
<div class="fav-container"
     data-delete-url="{{ route('favorites.delete') }}"
     data-add-to-cart-url="{{ route('favorites.addToCart') }}"
     data-csrf-token="{{ csrf_token() }}">

    {{-- Navigasi Tab --}}
    <div class="tabs">
        <div class="tab active" onclick="showTab('style', this)">Style</div>
        <div class="tab" onclick="showTab('products', this)">Products</div>
    </div>

    {{-- =======================
         TAB: STYLE
       ======================= --}}
    <div id="style" class="content">
        <div class="gallery">
            @forelse ($liked_posts as $post)
                <div class="card">
                    <a href="{{ route('community.post.detail', ['id' => $post['id_post']]) }}" class="card-link-wrapper">
                        <div class="image-placeholder">
                            <img src="{{ asset('assets/images/todays outfit/' . ($post['image_post'] ?? 'placeholder.jpg')) }}"
                                alt="Post image: {{ $post['caption'] }}">
                            <p class="like-count-overlay"><i class='bx bxs-heart'></i> {{ $post['total_likes'] }}</p>
                        </div>
                        <div class="card-body">
                            <p class="post-caption">{{ Str::limit($post['caption'], 50) }}</p>
                            <div class="user-info">
                                <img src="{{ asset('assets/images/profile/' . ($user['profile_picture'] ?? 'profile2.jpeg')) }}"
                                    alt="Profile picture of {{ $user['username'] ?? '' }}" />
                                <p class="username-text">{{ $post['username'] }}</p>

                                @if(!empty($post['mood']))
                                    @php $mood_class = 'mood-' . strtolower(str_replace(' ', '-', $post['mood'])); @endphp
                                    <span class="tag {{ $mood_class }}">{{ $post['mood'] }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p class="empty-message">You haven't liked any styles yet.</p>
            @endforelse
        </div>
    </div>

    {{-- =======================
         TAB: PRODUCTS
       ======================= --}}
    <div id="products" class="content" style="display: none;">
        <div class="product-grid">

            @forelse ($favorite_products as $fav)
                <div class="product-card" id="product-{{ $fav->id_fav }}">
                    
                    {{-- IMAGE --}}
                    <div class="product-image">
                        <img src="{{ asset('assets/images/produk-looksee/' . ($fav->product->gambar_produk ?? 'placeholder.jpg')) }}"
                             alt="{{ $fav->product->nama_produk }}"
                             onerror="this.onerror=null;this.src='https://placehold.co/200x200/EFEFEF/AAAAAA?text=No+Image';">
                    </div>

                    {{-- DETAILS --}}
                    <div class="product-details">
                        <h4>{{ $fav->product->nama_produk }}</h4>
                        <p>Rp {{ number_format($fav->product->harga, 0, ',', '.') }}</p>
                        
                        <div class="actions">
                            <button class="btn favorite-btn" data-fav-id="{{ $fav->id_fav }}">Delete</button>
                            <button class="btn buy-now-btn" data-product-id="{{ $fav->id_produk }}">Add to Cart</button>
                        </div>
                    </div>

                </div>
            @empty
                <p class="empty-message">You haven't added any products to your favorites yet.</p>
            @endforelse

        </div>
    </div>

</div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/favorite.js') }}"></script>
@endsection
