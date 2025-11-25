@extends('layouts.main')

@section('title', "Today's Outfit | LOOKSEE")

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/to.css') }}">
@endsection

@section('content')
    <main class="todays-outfit-container">
        <h1 class="page-title">Today's Outfit</h1>
        <p class="page-subtitle">The latest styles shared by our community, just for today!</p>

        <div class="gallery">
            @forelse ($posts as $post)
                @php
                    $user = $users[$post['user_id']] ?? null;
                @endphp
                <div class="card">
                    <a href="{{ route('community.post.detail', ['id' => $post['id_post']]) }}" class="card-link-wrapper">
                        <div class="image-placeholder">
                            <img src="{{ asset('assets/images/todays outfit/' . ($post['image_post'] ?? 'placeholder.jpg')) }}"
                                 alt="Post Image by {{ $user['username'] ?? '' }}"
                                 class='product-card-img'
                                 onerror="this.onerror=null;this.src='https://placehold.co/400x400/EFEFEF/AAAAAA?text=No+Image';">
                            <p class="like-count-overlay"><i class='bx bxs-heart'></i> {{ $post['like_count'] ?? 0 }}</p>
                        </div>
                        <div class='card-body'>
                            <div>
                                <h5 class='card-title'>{{ Str::limit($post['caption'] ?? 'No caption', 60) }}</h5>
                                
                                @if(!empty($post['mood']))
                                    @php
                                        $mood_class = 'mood-' . strtolower(str_replace(' ', '-', $post['mood']));
                                    @endphp
                                    <div class="mood-tag {{ $mood_class }}">
                                        <span class="mood-text">{{ $post['mood'] }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            @if($user)
                                <div class="user-info">
                                    <img src="{{ asset('assets/images/profile/' . ($user['profile_picture'] ?? 'default_profile.png')) }}"
                                         alt="Profile picture of {{ $user['username'] ?? '' }}" />
                                    <p class="username-text">{{ $user['username'] ?? 'Unknown' }}</p>
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            @empty
                <p style='text-align: center; width: 100%; grid-column: 1 / -1; padding: 50px; color: #777; font-size: 1.1em;'>
                    No outfits posted yet today. Be the first to share!
                </p>
            @endforelse
        </div>
    </main>
@endsection