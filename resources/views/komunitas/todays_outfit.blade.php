@extends('layouts.main')

@section('title', "Today's Outfit | LOOKSEE")

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/to.css') }}">
@endsection

@section('content')
    <main>
        <h1 class="page-title">Today's Outfit</h1>
        <p class="page-subtitle">Fresh looks from our community, updated daily.</p>
        <div class="gallery">
            @forelse ($posts as $post)
                @php
                    $user = $users[$post['user_id']] ?? null;
                @endphp
                <div class="card">
                    <a href="{{ route('community.post.detail', ['id' => $post['id_post']]) }}" class="card-link-wrapper">
                        <div class="image-placeholder">
                            <img src="{{ asset('assets/images/todays outfit/' . ($post['image_post'] ?? 'placeholder.jpg')) }}" 
                                 alt="Post by {{ $user['username'] ?? '' }}" 
                                 class='product-card-img'
                                 onerror="this.onerror=null;this.src='https://placehold.co/400x400/EFEFEF/AAAAAA?text=No+Image';">
                            <p class="like-count-overlay"><i class='bx bxs-heart'></i> {{ $post['like_count'] ?? 0 }}</p>
                        </div>
                        <div class='card-body'>
                            <div>
                                <h5 class='card-title'>{{ Str::limit($post['caption'] ?? '', 60) }}</h5>
                                @if(!empty($post['mood']))
                                    @php $mood_class = 'mood-' . strtolower(str_replace(' ', '-', $post['mood'])); @endphp
                                    <div class="mood-tag {{ $mood_class }}">
                                        <span class="mood-text">{{ $post['mood'] }}</span>
                                    </div>
                                @endif
                            </div>
                            @if($user)
                                <div class="user-info">
                                    @php
                                        if (!empty($user['profile_picture'])) {
                                            $userPic = asset('storage/uploads/profile/' . $user['profile_picture']);
                                        } else {
                                            $userPic = asset('assets/images/profile/placeholder.jpg');
                                        }
                                    @endphp
                                    <img src="{{ $userPic }}" 
                                         alt="Profile picture of {{ $user['username'] ?? '' }}" 
                                         onerror="this.onerror=null;this.src='{{ asset('assets/images/profile/placeholder.jpg') }}';"/>
                                    <p class="username-text">{{ $user['username'] ?? 'Unknown' }}</p>
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            @empty
                <p style='text-align: center; color: var(--text-light); grid-column: 1 / -1; padding: 40px 0;'>No posts found for today. Be the first to share!</p>
            @endforelse
        </div>
    </main>
@endsection