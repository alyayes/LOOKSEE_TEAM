@extends('layouts.main')
@section('title', 'Selected Mood | LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/selectedMood.css') }}">
@endsection

@section('content')
<div class="body-mood">
    <div class="container-mood">
        <div class="offer">
            <div class="small-container">
                <div class="row">
                    <div class="col-2">
                        <h2>Products based on the selected mood: 
                            <span>{{ htmlspecialchars($mood) }}.</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-mood">
        <aside class="sidebar">
            <form method="get" action="{{ route('mood.products') }}" id="filterForm">
                <input type="hidden" name="mood" value="{{ htmlspecialchars($mood) }}">
                <input type="hidden" name="page" id="currentPage" value="{{ htmlspecialchars($currentPage) }}">

                <h2>Gender</h2>
                <label><input type="radio" name="gender" value="men" @if($gender=='men') checked @endif> Men</label>
                <label><input type="radio" name="gender" value="women" @if($gender=='women') checked @endif> Women</label>
                <label><input type="radio" name="gender" value="" @if($gender=='') checked @endif> All</label>
            </form>
        </aside>

        <main class="main-content">
            <div class="products product-grid">
                @forelse ($products as $p)
                    <div class="product-card">
                        <div class="product-image">
                            <img 
                                src="{{ asset('assets/images/produk-looksee/' . $p['gambar_produk']) }}"
                                alt="{{ $p['nama_produk'] }}"
                                onerror="this.onerror=null;this.src='https://placehold.co/200x200?text=No+Image';"
                            >
                        </div>

                        <div class="product-details">
                            <h4>{{ $p['nama_produk'] }}</h4>
                            <p>Rp. {{ number_format($p['harga'], 0, ',', '.') }}</p>

                            <div class="actions">
                                <button class="btn favorite-btn" onclick="addToFavorites({{ $p['id_produk'] }})">
                                    Add to Favorite
                                </button>

                                <button class="btn buy-now-btn" onclick="addToCart({{ $p['id_produk'] }})">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No products found for this filter.</p>
                @endforelse
            </div>

            {{-- Paginasi Mood --}}
            <div class="page-btn">
                @if ($currentPage > 1)
                    <span onclick="goToPage({{ $currentPage - 1 }})">&leftarrow;</span>
                @endif

                @for ($i = 1; $i <= $totalPages; $i++)
                    <span class="@if($i == $currentPage) active @endif" onclick="goToPage({{ $i }})">
                        {{ $i }}
                    </span>
                @endfor

                @if ($currentPage < $totalPages)
                    <span onclick="goToPage({{ $currentPage + 1 }})">&rightarrow;</span>
                @endif
            </div>
        </main>
    </div>
</div>

<script>
    // Submit form otomatis kalau user pilih filter
    document.querySelectorAll('#filterForm input[type=radio]').forEach(el => {
        el.addEventListener('change', () => {
            document.getElementById('currentPage').value = 1;
            document.getElementById('filterForm').submit();
        });
    });

    // Fungsi navigasi halaman
    function goToPage(pageNumber) {
        document.getElementById('currentPage').value = pageNumber;
        document.getElementById('filterForm').submit();
    }

    function addToFavorites(idProduk) {
                fetch("{{ route('products.addToFavorite') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: 'id_produk=' + idProduk
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Terjadi kesalahan.');
                    });
            }

            function addToCart(productId) {
        // Cek apakah meta token ada
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenMeta) {
            alert("Error: CSRF Token tidak ditemukan. Pastikan ada tag meta csrf di head.");
            return;
        }
        const csrfToken = csrfTokenMeta.getAttribute('content');

        // Tampilkan loading (opsional, biar user tau tombol kepencet)
        // document.body.style.cursor = 'wait'; 

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                id_produk: productId,
                quantity: 1
            })
        })
        .then(response => {
            if (response.status === 401) {
                alert("Silakan login terlebih dahulu!");
                window.location.href = "{{ route('login') }}";
                throw new Error("Unauthorized");
            }
            if (!response.ok) {
                throw new Error("HTTP error " + response.status);
            }
            return response.json();
        })
        .then(data => {
            // document.body.style.cursor = 'default'; // Balikin kursor
            
            if (data.status === 'success') {
                // INI AKAN MUNCUL ALERT SESUAI PESAN DARI CONTROLLER DI ATAS
                alert(data.message); 
                
                // Opsional: Refresh halaman biar keranjang update
                // location.reload(); 
            } else {
                alert("Gagal: " + data.message);
            }
        })
        .catch(error => {
            // document.body.style.cursor = 'default';
            console.error('Error:', error);
            if (error.message !== "Unauthorized") {
                alert("Terjadi kesalahan sistem. Cek Console.");
            }
        });
    }
</script>
@endsection
