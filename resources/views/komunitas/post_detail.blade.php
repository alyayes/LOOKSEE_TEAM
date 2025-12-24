@extends('layouts.main')

@section('title', 'Details: ' . Str::limit($post['caption'] ?? 'Post', 30) . ' | LOOKSEE')

@section('head_scripts')
    {{-- Menggunakan CSS khusus post detail --}}
    <link rel="stylesheet" href="{{ asset('assets/css/post_detail.css') }}">
    {{-- Pastikan boxicons sudah terload di layout main, jika belum uncomment baris bawah --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css"> --}}
@endsection

@section('content')
<div class="small-container single-product">
    <div class="row">
        {{-- Tombol Kembali --}}
        <div class="back-button">
            <a href="{{ url()->previous() }}" aria-label="Back">
                <i class="arrow"></i>
            </a>
        </div>

        {{-- KOLOM KIRI: Gambar & Detail Postingan --}}
        <div class="col-2">
            {{-- Gambar Utama --}}
            <div class="abcd">
                <img src="{{ asset('assets/images/todays outfit/' . ($post['image_post'] ?? 'placeholder.jpg')) }}" 
                     width="90%" class="single" 
                     alt="Outfit by {{ $user['username'] ?? '' }}"
                     onerror="this.onerror=null;this.src='https://placehold.co/600x600/EFEFEF/AAAAAA?text=No+Image';">
            </div>

            {{-- Info Postingan (User, Caption, Stats, Comments) --}}
            <div class="social-feed-item-looksee">
                {{-- Header: Avatar & Nama --}}
                <div class="feed-item-header">
                    <div class="feed-item-author">
                        <img src="{{ asset('assets/images/profile/' . ($user['profile_picture'] ?? 'default_profile.png')) }}" 
                             alt="Profile of {{ $user['username'] ?? '' }}" 
                             class="author-avatar"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/default_profile.png') }}';">
                        <span class="author-name">{{ $user['username'] ?? 'Unknown' }}</span>
                    </div>
                    
                    {{-- Tanggal & Mood --}}
                    <div class="date-and-mood-wrapper">
                        <span class="feed-item-date">{{ \Carbon\Carbon::parse($post['created_at'])->format('d M Y') }}</span>
                        @if(!empty($post['mood']))
                            @php $mood_class = 'mood-' . strtolower(str_replace(' ', '-', $post['mood'])); @endphp
                            <div class="mood-tag {{ $mood_class }}">
                                <span class="mood-text">{{ $post['mood'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Caption & Hashtags --}}
                <div class="feed-item-content">
                    <p class="caption-text">{{ $post['caption'] ?? '' }}</p>
                    <p class="hashtags-text">
                        @foreach(explode(' ', $post['hashtags'] ?? '') as $tag)
                            @if(trim($tag)) <a href="#">{{ $tag }}</a> @endif
                        @endforeach
                    </p>
                </div>

                {{-- Stats: Like, Comment, Share --}}
                <div class="feed-item-stats">
                    {{-- Tombol LIKE --}}
                    <span class="stat-item post-like-button {{ $is_liked_by_user ? 'liked' : '' }}" 
                          data-post-id="{{ $post['id_post'] }}" 
                          data-like-url="{{ route('community.post.like', ['id' => $post['id_post']]) }}"
                          data-csrf="{{ csrf_token() }}">
                        <i class='bx {{ $is_liked_by_user ? 'bxs-heart' : 'bx-heart' }}'></i> 
                        <span class="count">{{ $post['like_count'] ?? 0 }}</span>
                    </span>

                    {{-- Tombol COMMENT (Hanya Icon & Jumlah) --}}
                    <span class="stat-item post-comment-trigger" style="cursor: pointer;">
                        <i class='bx bx-comment'></i> 
                        <span class="count">{{ $post['comment_count'] ?? 0 }}</span>
                    </span>

                    {{-- Tombol SHARE --}}
                    <span class="stat-item post-share-button" 
                          data-post-id="{{ $post['id_post'] }}" 
                          data-post-url="{{ url()->current() }}" 
                          data-share-url="{{ route('community.post.share', ['id' => $post['id_post']]) }}"
                          data-csrf="{{ csrf_token() }}">
                        <i class='bx bx-share-alt'></i> <span class="count">{{ $post['share_count'] ?? 0 }}</span>
                    </span>
                </div>
                
                {{-- Bagian Komentar --}}
                <div class="feed-item-comments-section">
                    <div class="comments-list">
                        @forelse($comments as $comment)
                        <div class="comment-item">
                            <img src="{{ asset('assets/images/profile/' . ($comment['user']['profile_picture'] ?? 'default_profile.png')) }}" 
                                 alt="Avatar" class="comment-avatar"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/default_profile.png') }}';">
                            <div class="comment-content">
                                <span class="comment-author">{{ $comment['user']['username'] ?? 'Unknown' }}</span>
                                <p class="comment-body">{!! nl2br(e($comment['comment_text'])) !!}</p>
                                <span class="comment-timestamp">{{ \Carbon\Carbon::parse($comment['created_at'])->format("d M Y, H:i") }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="no-comments-message">No comments yet. Be the first!</p>
                        @endforelse
                    </div>

                    {{-- Form Tambah Komentar --}}
                    <div class="comment-input-area">
                        <form action="{{ route('community.post.comment', ['id' => $post['id_post']]) }}" method="POST" class="comment-form">
                            @csrf
                            {{-- Avatar user yg sedang login --}}
                            <img src="{{ Auth::user()->profile_picture ? asset('assets/images/profile/' . Auth::user()->profile_picture) : asset('assets/images/default_profile.png') }}" 
                                 alt="My Avatar" class="comment-avatar"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/default_profile.png') }}';">
                            
                            <input type="text" name="comment_text" class="comment-input-field" placeholder="Write a comment..." required>
                            <button type="submit" class="post-comment-button">Post</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- KOLOM KANAN: Produk Terkait (Outfit Details) --}}
        <div class="col-2">
            <h3 class="outfit-details-title">Outfit Details</h3>
            <div class="outfit-details-grid">
                @forelse($post_items as $item)
                <div class="cardd">
                    {{-- Link ke Detail Produk --}}
                    <a href="{{ route('products.detail', ['id' => $item['id_produk']]) }}" class="product-link">
                        <img src="{{ asset('assets/images/produk-looksee/' . ($item['gambar_produk'] ?? 'placeholder.jpg')) }}" 
                             width="100%" class="small-img" 
                             alt="Detail {{ $item['nama_produk'] ?? '' }}"
                             onerror="this.onerror=null;this.src='https://placehold.co/200x200/EFEFEF/AAAAAA?text=No+Image';">
                        <div class="capt">
                            <span class="product-name">{{ $item['nama_produk'] ?? 'Product' }}</span>
                        </div>
                    </a>
                    <div class="card-actions">
                        <a href="#" class="action-icon add-to-cart-product" data-product-id="{{ $item['id_produk'] }}"><i class='bx bx-cart-add'></i></a>
                        <a href="#" class="action-icon add-to-favorite" data-product-id="{{ $item['id_produk'] }}"><i class='bx bx-heart'></i></a>
                    </div>
                    <span class="product-price">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="no-items-msg">No product details for this post.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- === MODAL SHARE === --}}
<div id="shareModal" class="modal-share" style="display: none;">
    <div class="modal-share-content">
        <div class="modal-share-header">
            <h3>Share this Style</h3>
            <span class="close-share-btn">&times;</span>
        </div>
        <div class="modal-share-body">
            <p>Salin link di bawah ini untuk membagikan postingan:</p>
            <div class="input-group-share">
                <input type="text" id="shareLinkInput" readonly>
                <button id="copyLinkBtn">Salin</button>
            </div>
            <p id="copySuccessMsg" style="display:none; color: green; font-size: 0.9em; margin-top: 5px;">Link berhasil disalin!</p>
        </div>
    </div>
</div>

@endsection

@section('footer_scripts')
    {{-- Memanggil File JS Logic Like & Share --}}
    <script src="{{ asset('assets/js/post_detail.js') }}"></script>
@endsection