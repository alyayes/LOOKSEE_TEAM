@extends('layouts.mainAdmin')

@section('title', 'Products List')

@section('content')
<div class="propic">

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tombol Add --}}
    <div class="mb-3">
        <a href="{{ route('products.add') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add Product
        </a>
    </div>

    {{-- Search --}}
    <div class="mb-3">
        <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search product...">
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table id="myTable" class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Preferensi</th>
                    <th>Mood</th>
                    <th>Stock</th>
                    <th width="120px">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <img src="{{ asset('assets/images/produk-looksee/' . $product->gambar_produk) }}" 
                                 width="60" height="60" style="object-fit:cover;"
                                 onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
                        </td>

                        <td>{{ $product->nama_produk }}</td>
                        <td>{{ Str::limit($product->deskripsi, 50) }}</td>
                        <td>Rp{{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>{{ $product->kategori }}</td>
                        <td>{{ $product->preferensi }}</td>
                        <td>{{ $product->mood }}</td>
                        <td>{{ $product->stock }}</td>

                        <td>
                            {{-- EDIT --}}
                            <a href="{{ route('products.edit', $product->id_produk) }}" 
                               class="btn btn-success btn-sm">
                                <i class="bx bx-edit"></i>
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('products.destroy', $product->id_produk) }}" 
                                  method="POST" style="display:inline-block;" 
                                  onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada produk tersedia.</td>
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
        let input = document.getElementById("myInput");
        let filter = input.value.toUpperCase();
        let table = document.getElementById("myTable");
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let text = tr[i].innerText.toUpperCase();
            tr[i].style.display = text.includes(filter) ? "" : "none";
        }
    }
</script>
@endsection
