@extends('layouts.main', ['hideHeader' => true, 'hideFooter' => true])

    @section('title', $article['title'] . ' | LOOKSEE Journal')

    @section('head_scripts')
        <link rel="stylesheet" href="{{ asset('assets/css/show.css') }}">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @endsection

    @section('content')
            
        <header class="bg-white border-b shadow-sm sticky top-0 z-10">
            <nav class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
                <a href="{{ route('journal.index') }}" class="text-2xl text-gray-700 hover:text-black" aria-label="Kembali">
                    <i class='bx bx-arrow-back'></i>
                </a>
                <h1 class="text-xl font-bold text-gray-800">Style Journal</h1>
                <div class="flex items-center space-x-4 text-2xl text-gray-700">
                    <a href="#" class="hover:text-black" aria-label="Bagikan"><i class='bx bx-share-alt'></i></a>
                    <a href="#" class="hover:text-black" aria-label="Simpan"><i class='bx bx-bookmark'></i></a>
                </div>
            </nav>
        </header>

        <div class="container mx-auto max-w-4xl py-8 px-4">
            
            <main class="bg-white p-6 md:p-10 rounded-xl shadow-lg">
                <article>
                    {{-- Title Artikel --}}
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">
                        {{ $article['title'] }}
                    </h2>
                    
                    {{-- Tanggal Publikasi --}}
                    @if (isset($article['formatted_date']))
                    <div class="mt-4 text-sm text-gray-500">
                        {{-- formatted_date dibuat di Controller --}}
                        <span>Publikasi: {{ $article['formatted_date'] }}</span>
                    </div>
                    @endif

                    {{-- Gambar Artikel --}}
                    <figure class="my-8">
                        <img src="{{ asset('storage/uploads/' . $article['image']) }}" 
                            alt="{{ $article['title'] }}" 
                            class="article-image w-full h-auto object-cover rounded-lg shadow-md">
                    </figure>

                    {{-- Deskripsi Singkat --}}
                    <div class="bg-pink-50 text-pink-800 p-4 rounded-lg my-8 text-center">
                        <p>{{ $article['descr'] }}</p>
                    </div>

                    {{-- Konten Utama --}}
                    <div class="prose-fallback text-gray-700 leading-relaxed space-y-4"> 
                        {!! nl2br(e($article['content'])) !!}
                    </div>
                    
                </article>

                {{-- Reaksi Pengguna --}}
                <section class="mt-12 p-6 border-2 border-pink-200 rounded-xl text-center">
                    <h4 class="font-semibold text-gray-800">How did this article make you feel?</h4>
                    <div class="flex justify-center space-x-8 md:space-x-12 mt-4">
                        <button class="text-4xl transform transition-transform duration-200 hover:scale-125">😟</button>
                        <button class="text-4xl transform transition-transform duration-200 hover:scale-125">😐</button>
                        <button class="text-4xl transform transition-transform duration-200 hover:scale-125">😍</button>
                    </div>
                </section>
            </main>
            
            <div class="text-center mt-8">
                <a href="{{ route('journal.index') }}" class="inline-block px-6 py-3 bg-pink-500 text-white font-semibold rounded-lg shadow-md hover:bg-pink-600 transition duration-300">
                    ← Kembali ke Style Journal
                </a>
            </div>
        </div>

    @endsection