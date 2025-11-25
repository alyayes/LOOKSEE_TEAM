@extends('layouts.main')

@section('title', 'Trends Now | LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/product.css') }}">
    {{-- Bootstrap hanya untuk pesan alert, bisa diganti dengan custom modal jika diinginkan --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
    {{-- Banner Section --}}
    <div class="trends">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-12 text-center text-md-left">
                    <h1>Discover the Latest<br>Trends Now!</h1>
                    <p>Stay ahead of the curve with Trends Now! Your ultimate guide for the <br> hottest styles and lifestyle tips.</p>
                    <a href="#featured-products-section" class="btn-EN">Explore Now &#8594;</a>
                </div>
                <div class="col-md-6 col-12">
                    <img src="{{ asset('assets/images/bnrr.png') }}" class="imgBanner" style="width:100%; height:auto;">
                </div>
            </div>
        </div>
    </div>

    {{-- Categories Section (Statis) --}}
    <div class="categories">
        <div class="small-container">
            <div class="row">
                <div class="col-md-4 col-sm-12"><a href="#"><img src="{{ asset('assets/images/categories1.jpeg') }}" style="width:100%;"></a></div>
                <div class="col-md-4 col-sm-12"><a href="#"><img src="{{ asset('assets/images/cat4.jpeg') }}" style="width:100%;"></a></div>
                <div class="col-md-4 col-sm-12"><a href="#"><img src="{{ asset('assets/images/categories3.jpeg') }}" style="width:100%;"></a></div>
            </div>
        </div>
    </div>

    {{-- Featured Products Section (Dinamis dari Controller) --}}
    <div class="small-container py-5" id="featured-products-section">
        <h2 class="tittle text-center">This Week's Most Popular!</h2>
        <p class="text text-center mb-4">"Discover the hottest styles and must-have outfits with This Week's Trends!"</p>
        <div class="row">
            @forelse($posts as $post)
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card h-100">
                        <a href="{{ route('community.post.detail', ['id' => $post['id_post']]) }}">
                            <img src="{{ asset('assets/images/todays outfit/' . ($post['image_post'] ?? 'placeholder.jpg')) }}" 
                                 class="card-img-top product-card-img" 
                                 alt="{{ $post['caption'] ?? 'Post image' }}"
                                 onerror="this.onerror=null;this.src='https://placehold.co/400x400/EFEFEF/AAAAAA?text=No+Image';">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <div>
                                <h5 class="card-title mb-1">
                                    <a href="{{ route('community.post.detail', ['id' => $post['id_post']]) }}">{{ Str::limit($post['caption'] ?? '', 50) }}</a>
                                </h5>
                                @if(!empty($post['mood']))
                                    @php $mood_class = 'mood-' . strtolower(str_replace(' ', '-', $post['mood'])); @endphp
                                    <div class="mood-tag {{ $mood_class }}">
                                        <span class="mood-text">{{ $post['mood'] }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-auto pt-2">
                                <div class="text-img">
                                    @foreach(explode(' ', $post['hashtags'] ?? '') as $tag)
                                        @if(trim($tag))
                                            <a href="#">{{ $tag }}</a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 alert alert-info">No trends to display right now.</div>
            @endforelse
        </div>
    </div>
@endsection

