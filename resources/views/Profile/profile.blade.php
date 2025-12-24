@extends('layouts.main')

@section('title', ($userData['name'] ?? 'User Profile') . ' | LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container"> 

    <div class="profile-header">
        @php
            // Logika Penentuan Foto Profil Utama
            $defaultProfile = asset('assets/images/profile/placeholder.jpg'); // Pastikan file ini ada
            
            if (!empty($userData['profile_picture'])) {
                // Jika user punya foto, ambil dari storage
                $profilePic = asset('storage/uploads/profile/' . $userData['profile_picture']);
            } else {
                // Jika tidak, pakai default
                $profilePic = $defaultProfile;
            }
        @endphp

        {{-- Tampilkan Foto Profil dengan Error Handling --}}
        <img src="{{ $profilePic }}" 
             onerror="this.onerror=null;this.src='{{ $defaultProfile }}';" 
             alt="Profile Picture" class="profile-pic">

        <h1>{{ $userData['name'] ?? 'N/A' }}</h1>
        <p>@<span>{{ $userData['username'] ?? 'N/A' }}</span></p>
        <p>{{ $userData['bio'] ?? 'No bio available.' }}</p>
        
        {{-- Tombol Edit (Sesuaikan route jika perlu) --}}
        <button class="edit-profile"><a href="{{ url('settings/profile') }}">Edit Profile</a></button>
    </div>

    {{-- Navigasi Tab --}}
    <div class="tabs">
        <div class="tab active" onclick="showTab('myStyle', event)">My Style</div>
        <div class="tab" onclick="showTab('myGallery', event)">My Gallery</div>
        <div class="tab" onclick="showTab('about', event)">About Me</div>
    </div>

    {{-- Konten TAB: My Style --}}
    <div id="myStyle" class="content">
        {{-- PENGURUTAN POSTINGAN --}}
        @php
            if (isset($posts) && (is_array($posts) || $posts instanceof \Illuminate\Support\Collection)) {
                $sortedPosts = collect($posts)->sortByDesc('created_at');
            } else {
                $sortedPosts = [];
            }
        @endphp

        @forelse ($sortedPosts as $post)
            <div class="posting">
                <div class="post-header">
                    <a href="#">
                        {{-- PERBAIKAN: Foto Profil Kecil di Postingan --}}
                        <img src="{{ !empty($userData['profile_picture']) ? asset('storage/uploads/profile/' . $userData['profile_picture']) : asset('assets/images/profile/placeholder.jpg') }}"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/profile/placeholder.jpg') }}';"
                             alt="Profile Picture" class="profile-pic">
                             
                        <div class="username">@<span>{{ $userData['username'] ?? 'N/A' }}</span></div>
                    </a>
                    
                    {{-- Menu Aksi & Timestamp --}}
                    <div class="post-actions-menu">
                        <div class="timestamp">{{ \Carbon\Carbon::parse($post['created_at'])->format('d M Y') }}</div>
                        
                        <div class="dropdown">
                            <button class="dropbtn"><i class='bx bx-dots-vertical-rounded'></i></button>
                            <div class="dropdown-content">
                                {{-- Pastikan route ini ada di web.php --}}
                                <a href="{{ route('profile.post.edit', ['id' => $post->id_post]) }}">Edit Post</a>

                                <form action="{{ route('profile.post.destroy', ['id' => $post->id_post]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">Hapus Post</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="capt">
                    <p class="post-caption">{!! nl2br(e($post['caption'] ?? '')) !!}</p>
                    
                    {{-- Mood Tag --}}
                    @if(!empty($post['mood']))
                        @php
                            $mood_text = $post['mood'];
                            $mood_class = 'mood-' . strtolower(str_replace(' ', '-', $mood_text));
                        @endphp
                        <div class="post-details-row">
                            <div class="mood-tag {{ $mood_class }}">
                                <span class="mood-text">{{ $mood_text }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="post-hashtags">
                        @foreach(explode(' ', $post['hashtags'] ?? '') as $tag)
                            @if(trim($tag))
                                <a href="#">{{ $tag }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                {{-- Gambar Postingan --}}
                <div class="poster">
                    {{-- Asumsi gambar post ada di folder assets/todays outfit, sesuaikan jika pakai storage --}}
                    <img src="{{ asset('assets/images/todays outfit/' . ($post['image_post'] ?? 'placeholder.jpg')) }}" 
                        onerror="this.onerror=null;this.src='https://placehold.co/600x600/EFEFEF/AAAAAA?text=No+Image';"
                        alt="Post Image">
                </div>
                
                <div class="post-actions">
                    <div class="likes-comments">
                        <span><i class='bx bxs-heart'></i> {{ $post['like_count'] ?? 0 }}</span>
                        <span><i class='bx bx-message-rounded'></i> {{ $post['comment_count'] ?? 0 }}</span>
                        <span><i class='bx bx-share-alt'></i> {{ $post['share_count'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p class="no-post-message">You haven't made any posts yet. Come on, share your style!</p>
        @endforelse
    </div>

    {{-- Konten TAB: My Gallery --}}
    <div id="myGallery" class="content" style="display: none;">
        @php
            if (isset($gallery_posts) && (is_array($gallery_posts) || $gallery_posts instanceof \Illuminate\Support\Collection)) {
                $sortedGalleryPosts = collect($gallery_posts)->sortByDesc('created_at');
            } else {
                $sortedGalleryPosts = [];
            }
        @endphp
        <div class="gallery">
            @forelse($sortedGalleryPosts as $post)
                <div class="image-placeholder">
                    {{-- Pastikan route detail post ada --}}
                    <a href="{{ route('community.post.detail', ['id' => $post['id_post']]) }}">
                        <img src="{{ asset('assets/images/todays outfit/' . ($post['image_post'] ?? 'placeholder.jpg')) }}"
                            onerror="this.onerror=null;this.src='https://placehold.co/600x600/EFEFEF/AAAAAA?text=No+Image';"
                            alt="Post Image">
                    </a>
                </div>
            @empty
                <p class="no-post-message">Your gallery is empty.</p>
            @endforelse
        </div>
    </div>

    {{-- Konten TAB: About Me --}}
    <div id="about" class="content" style="display: none;">
        <div class="about-item">
            <p><i class='bx bx-birthday-cake'></i> : {{ $userData['birthday'] ? \Carbon\Carbon::parse($userData['birthday'])->format('d F Y') : '-' }}</p>
            <p><i class='bx bx-flag-alt'></i> : {{ $userData['country'] ?? '-' }}</p>
            <p><i class='bx bxl-instagram'></i> : {{ $userData['instagram'] ? '@' . $userData['instagram'] : '-' }}</p>
            <p><i class='bx bx-envelope'></i> : {{ $userData['email'] ?? '-' }}</p>
            <p><i class='bx bxl-twitter'></i> : {{ $userData['twitter'] ? '@' . $userData['twitter'] : '-' }}</p>
            <p><i class='bx bxl-facebook' ></i> : {{ $userData['facebook'] ?? '-' }}</p>
        </div>
    </div>

    {{-- Tombol Upload --}}
    <div class="upload">
        <form id="uploadForm" action="{{ route('profile.upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" id="imageUpload" name="image" accept="image/*" style="display: none;" required />
            <button type="button" id="uploadBtn" class="post-style-button"><i class='bx bx-upload'></i></button>
        </form>
    </div>
</div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/profile.js') }}"></script>
@endsection