@extends('layouts.main')

@section('title', 'Style Journal | LOOKSEE') 

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> 
@endsection

@section('styles')
<style>
    /* HATI-HATI: Aturan ini harus dipindahkan ke style.css setelah berhasil! */
    nav[role="navigation"] svg {
        width: 24px !important; 
        height: 24px !important;
        color: #1F2937 !important; 
        fill: currentColor !important;
    }
    nav[role="navigation"] a {
        font-size: 1rem !important;
    }
</style>
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
            @if ($journals->count() > 0)
                
                @foreach ($journals as $journal)
                    @php
                        $summary_text = strip_tags($journal->descr);
                        $summary = \Illuminate\Support\Str::limit($summary_text, 150, '...'); 
                    @endphp

                    <article class="blog-post">
                        <img src="{{ asset('assets/images/journal/' . $journal->image) }}" alt="{{ $journal->title }}">
                        <h2>{{ $journal->title }}</h2>
                        <p>
                            {!! nl2br(e($summary)) !!}
                        </p>
                        <a href="{{ route('journal.show', $journal->id_journal) }}" >Read More</a>
                    </article>
                @endforeach
            @else
                <p style='text-align:center; margin: 20px;'>No journal entries found.</p>
            @endif

            <div class="mt-8 mb-12 flex justify-center w-full"> 
                {{ $journals->links() }}
            </div>
        </div> 
    </main>
        
@endsection

@section('footer_scripts')
@endsection