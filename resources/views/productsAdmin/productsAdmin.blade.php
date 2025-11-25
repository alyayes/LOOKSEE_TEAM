@extends('layouts.mainAdmin')

@section('title', 'Products List')

@section('styles')
    <style>
        .propic {
            padding: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .table-responsive {
            overflow-x: auto; 
            -webkit-overflow-scrolling: touch; 
        }

        #myTable {
            border-collapse: collapse;
            width: 100%;
            /* Min width disesuaikan untuk menampung kolom tambahan */
            min-width: 1150px; 
            border: 1px solid #ddd;
            font-size: 14px; 
            margin-bottom: 20px;
        }

        #myTable th,
        #myTable td {
            text-align: left;
            padding: 10px 12px;
            border: 1px solid #ddd;
            vertical-align: middle;
            line-height: 1.2;
            white-space: nowrap; 
        }
        
        #myTable td {
            font-size: 14px;
        }

        /* --- STYLING KOLOM SPESIFIK --- */

        /* Kolom Product (Gambar) - Kolom ke-1 */
        #myTable th:nth-child(1),
        #myTable td:nth-child(1) {
            width: 8%; 
            text-align: center;
        }
        
        .img-cell img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            padding: 2px;
            display: block; 
            margin: 0 auto;
        }

        /* Kolom Product Name - Kolom ke-2 */
        #myTable th:nth-child(2),
        #myTable td:nth-child(2) {
            width: 15%;
            white-space: normal;
        }

        /* Kolom Description - Kolom ke-3 */
        #myTable th:nth-child(3),
        #myTable td:nth-child(3) {
            width: 30%;
            white-space: normal; 
            max-width: 280px; 
            word-wrap: break-word; 
            font-size: 14px; 
        }
        
        /* Kolom Preferensi - Kolom ke-7 */
        #myTable th:nth-child(7),
        #myTable td:nth-child(7) {
            width: 8%;
            white-space: nowrap;
        }


        /* Kolom Stock - Kolom ke-8 */
        #myTable th:nth-child(8),
        #myTable td:nth-child(8) {
            width: 5%;
        }
        
        /* Kolom Action - Kolom ke-9 */
        #myTable th:nth-child(9),
        #myTable td:nth-child(9) {
            width: 10%; 
            text-align: center;
        }


        /* --- STYLING BARIS & HOVER --- */
        #myTable tr {
            border-bottom: 1px solid #ddd;
        }

        #myTable tr.header,
        #myTable tr:hover {
            background-color: rgb(255, 234, 247);
        }

        .action-row {
            margin-bottom: 10px;
        }
        .btn-group-top {
            margin-bottom: 10px;
        }
        .search-wrapper {
            width: 100%;
            max-width: 400px;
            margin-bottom: 10px;
        }
        #myInput {
            background-image: url('{{ asset('css/searchicon.png') }}');
            background-position: 10px 8px;
            background-repeat: no-repeat;
            width: 100%;
            font-size: 14px;
            padding: 8px 20px 8px 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-action {
            width: 30px;
            height: 30px;
            font-size: 14px;
            margin-right: 3px;
            padding: 0;
        }
        .btn-section {
            margin-bottom: 0px;
        }
    </style>
@endsection

@section('content')
    <div class="propic">

        @if (session('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Row untuk Tombol Add Product (Baris 1) --}}
        <div class="btn-group-top">
            <section class="btn-section">
                <a href="{{ route('products.add') }}" class="btn btn-primary">
                    <i class='bx bx-plus'></i> Add Product
                </a>
            </section>
        </div>

        {{-- Row untuk Search Bar (Baris 2, di bawah tombol) --}}
        <div class="action-row">
            <div class="search-wrapper">
                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for product..."
                    title="Type in a name" />
            </div>
        </div>

        {{-- Tabel Produk --}}
        <div class="table-responsive overflow-x-auto">
            <table id="myTable">
                <tr class="header">
                    <th>Product</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Mood</th>
                    <th>Preferensi</th> {{-- Header kolom Preferensi (Kolom ke-7) --}}
                    <th>Stock</th>
                    <th>Action</th>
                </tr>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="img-cell">
                                <img src="{{ asset('storage/uploads/admin/produk_looksee/' . $product['gambar_produk']) }}"
                                    alt="Gambar Produk"
                                    onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';">
                            </td>

                            <td>{{ htmlspecialchars($product['nama_produk']) }}</td>
                            <td>{{ htmlspecialchars($product['deskripsi']) }}</td>
                            <td>Rp {{ number_format($product['harga'], 0, ',', '.') }}</td>
                            <td>{{ htmlspecialchars($product['kategori']) }}</td>
                            <td>{{ htmlspecialchars($product['mood']) }}</td>
                            <td>{{ htmlspecialchars($product['preferensi'] ?? '-') }}</td> {{-- Data Preferensi (Kolom ke-7) --}}
                            <td>{{ htmlspecialchars($product['stock']) }}</td>
                            <td>
                                {{-- Link Edit --}}
                                <a href="{{ route('products.edit', $product['id_produk']) }}"
                                    class="btn btn-success btn-action" title="Edit">
                                    <i class='bx bx-edit'></i>
                                </a>

                                {{-- Form DELETE --}}
                                <form action="{{ route('products.destroy', $product['id_produk']) }}" method="POST"
                                    style="display:inline;" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-action" title="Hapus">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- FIX: colspan diubah dari 8 menjadi 9 untuk mencakup kolom Preferensi --}}
                            <td colspan="9" class="text-center">Tidak ada produk yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function myFunction() {
            const input = document.getElementById("myInput");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("myTable");
            const tr = table.getElementsByTagName("tr");

            // Mendapatkan index kolom Preferensi.
            // Product: 0, Name: 1, Description: 2, Price: 3, Category: 4, Mood: 5, Preferensi: 6
            const PREFERENSI_INDEX = 6;
            

            for (let i = 1; i < tr.length; i++) {
                // Filter berdasarkan kolom 'Product Name' (index 1), 'Description' (index 2), atau 'Preferensi' (index 6)
                const tdName = tr[i].getElementsByTagName("td")[1];
                const tdDesc = tr[i].getElementsByTagName("td")[2];
                const tdPref = tr[i].getElementsByTagName("td")[PREFERENSI_INDEX];

                let found = false;
                
                // Cari di Product Name
                if (tdName) {
                    const txtValue = tdName.textContent || tdName.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                    }
                }

                // Cari di Description (jika belum ketemu)
                if (!found && tdDesc) {
                    const txtValue = tdDesc.textContent || tdDesc.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                    }
                }
                
                // Cari di Preferensi (jika belum ketemu)
                if (!found && tdPref) {
                    const txtValue = tdPref.textContent || tdPref.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                    }
                }

                tr[i].style.display = found ? "" : "none";
            }
        }
    </script>
@endsection