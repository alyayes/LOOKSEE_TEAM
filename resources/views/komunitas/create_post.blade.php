@extends('layouts.main')

@section('title', 'Post My Style | LOOKSEE')

@section('head_scripts')
<link rel="stylesheet" href="{{ asset('assets/css/post.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
@endsection

@section('content')
<div class="contain">
    <div class="post-style-container">
        <header class="post-header">
            <a href="{{ route('profile.index') }}" class="back-arrow"><i class='bx bx-arrow-back'></i></a>
            <h1>Post My Style</h1>
        </header>

        <form class="post-content" action="{{ route('profile.post.store') }}" method="post">
            @csrf

            {{-- Hidden input untuk menyimpan nama file gambar --}}
            <input type="hidden" name="imageFilename" value="{{ $imageFilename ?? '' }}">

            <div class="post-layout-columns">
                {{-- Gambar --}}
                <div class="image-section">
                    <img src="{{ $imagePath ?? asset('assets/images/placeholder.jpg') }}" alt="Uploaded Outfit" class="main-image">
                </div>

                {{-- Detail Post --}}
                <div class="details-section">
                    <textarea name="caption" placeholder="Tulis caption..." class="caption-input" rows="4" required></textarea>

                    <input type="text" name="hashtags" placeholder="Hashtags (pisahkan dengan koma)" class="caption-input">

                    <div class="add-mood">
                        <label for="mood">Pilih Mood Anda</label>
                        <select name="mood" id="mood" required>
                            <option value="">-- Pilih Mood --</option>
                            <option value="Very Happy">Very Happy</option>
                            <option value="Happy">Happy</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Sad">Sad</option>
                            <option value="Very Sad">Very Sad</option>
                        </select>
                    </div>

                    {{-- Produk --}}
                    <div class="add-item">
                        <div class="item-header">
                            <span>Tag Produk yang Anda Kenakan</span>
                            <button class="add-btn" id="openProductModalBtn" type="button">+</button>
                        </div>
                        <div id="selectedProductsDisplay"></div>
                    </div>
                </div>
            </div>
            <button type="submit" class="done-btn">Post Style</button>
        </form>
    </div>

    {{-- Modal Produk --}}
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeProductModalBtn">&times;</span>
            <h2>Pilih Produk</h2>
            <input type="text" id="productSearchInput" placeholder="Cari produk..." class="search-input">
            <div class="product-list-container">
                <div class="product-grid-modal">
                    @forelse ($all_products as $product)
                        <div class="product-item-modal" data-id="{{ $product['id_produk'] }}" data-name="{{ $product['nama_produk'] }}">
                            <div class="product-image-wrapper">
                                <img src="{{ asset('assets/images/produk-looksee/' . ($product['gambar_produk'] ?? 'placeholder.jpg')) }}"
                                     alt="{{ $product['nama_produk'] }}" class="product-thumb-modal">
                            </div>
                            <span class="product-name-modal">{{ $product['nama_produk'] }}</span>
                            <button type="button" class="add-to-post-btn">Tambah</button>
                        </div>
                    @empty
                        <p class="no-products-found">Tidak ada produk yang tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
<script>
    // Modal
    const modal = document.getElementById('productModal');
    document.getElementById('openProductModalBtn').onclick = () => modal.style.display = 'block';
    document.getElementById('closeProductModalBtn').onclick = () => modal.style.display = 'none';
    window.onclick = (e) => { if (e.target == modal) modal.style.display = 'none'; }

    // Tambah produk ke post
    document.querySelectorAll('.add-to-post-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productItem = this.closest('.product-item-modal');
            const productId = productItem.dataset.id;
            const productName = productItem.dataset.name;

            // Tambahkan input hidden ke form
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'post_items[]';
            hiddenInput.value = productId;
            document.querySelector('form.post-content').appendChild(hiddenInput);

            // Tampilkan di UI
            const display = document.getElementById('selectedProductsDisplay');
            const span = document.createElement('span');
            span.textContent = productName;
            display.appendChild(span);

            modal.style.display = 'none';
        });
    });
</script>
@endsection
