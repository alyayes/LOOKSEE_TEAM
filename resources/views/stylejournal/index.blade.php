@extends('layouts.main')

@section('title', 'Style Journal | LOOKSEE') 

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> 
@endsection

@section('content')
    <div class="offer">
        <div class="small-container">
            <div class="row">
                <div class="col-2">
                    <h1>Fashion Insights</h1>
                    <p>Explore tips, tricks, and trends to elevate your style!</p>
                </div>
            </div>
        </div>
    </div>
    
    <main>
        <div class="blog"> 
            
            @if (count($articles_data) > 0)
                @php
                    $article_count = 0;
                @endphp

                @foreach ($articles_data as $article)
                    @php
                        // Pagination
                        $page_for_article = floor($article_count / $articles_per_page) + 1;
                        
                        // Batasi deskripsi 
                        $summary_text = strip_tags($article['descr']);
                        $summary = \Illuminate\Support\Str::limit($summary_text, 150, '...'); 
                    @endphp

                    <article class="blog-post" data-page="{{ $page_for_article }}">
                        <img src="{{ asset('storage/uploads/' . $article['image']) }}" alt="{{ $article['title'] }}">
                        <h2>{{ $article['title'] }}</h2>
                        <p>
                            {!! nl2br(e($summary)) !!}
                        </p>
                        <a href="{{ route('journal.show', ['id' => $article['id_journal']]) }}" >Read More</a>
                    </article>

                    @php
                        $article_count++;
                    @endphp
                @endforeach
            @else
                <p style='text-align:center; margin: 20px;'>No journal entries found.</p>
            @endif
        </div> 
    </main>
        
    <div class="row-btn">
        <div class="page-btn" id="pagination">
            @if (count($articles_data) > 0)
                @for ($i = 1; $i <= $total_pages; $i++)
                    @php
                        // Halaman 1 selalu aktif saat load awal
                        $active_class = ($i == 1) ? 'active' : ''; 
                    @endphp
                    <span class="page-number {{ $active_class }}" data-page="{{ $i }}">{{ $i }}</span>
                @endfor
                
                @if ($total_pages > 1)
                    <span class="page-next">&#8594;</span>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/styleJournal.js') }}"></script>
@endsection