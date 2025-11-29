@extends('layouts.mainAdmin')

@section('title', 'Products List')

@section('content')
<div class="page-products">

    <style>
        .page-products * {
            box-sizing: border-box;
        }

        /* Tombol Add */
        .page-products .btn-add-product {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        /* Search */
        .page-products #myInput {
            background-image: url('/css/searchicon.png'); /* sesuaikan kalau perlu */
            background-position: 10px 10px;
            background-repeat: no-repeat;
            width: 100%;
            max-width: 600px;
            font-size: 14px;
            padding: 10px 16px 10px 38px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 12px;
        }

        /* Wrapper tabel */
        .page-products .table-responsive {
            margin-top: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }

        /* Tabel */
        .page-products #myTable {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 1000px;
        }

        .page-products #myTable thead th {
            background-color: rgb(255, 234, 247);
            border: 1px solid #ddd;
            padding: 10px;
            white-space: nowrap;
            font-weight: 600;
            text-align: left;
        }

        .page-products #myTable tbody td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
        }

        .page-products #myTable tbody tr:hover {
            background-color: rgb(255, 242, 248);
        }

        /* Lebar kolom (9 kolom total) */
        .page-products #myTable th:nth-child(1),
        .page-products #myTable td:nth-child(1) { width:10%; text-align:center; }

        .page-products #myTable th:nth-child(2),
        .page-products #myTable td:nth-child(2) { width:10%; }

        .page-products #myTable th:nth-child(3),
        .page-products #myTable td:nth-child(3) { width:20%; }

        .page-products #myTable th:nth-child(4),
        .page-products #myTable td:nth-child(4) { width:10%; white-space:nowrap; }

        .page-products #myTable th:nth-child(5),
        .page-products #myTable td:nth-child(5) { width:10%; white-space:nowrap; }

        .page-products #myTable th:nth-child(6),
        .page-products #myTable td:nth-child(6) { width:10%; }

        .page-products #myTable th:nth-child(7),
        .page-products #myTable td:nth-child(7) { width:10%; }

        .page-products #myTable th:nth-child(8),
        .page-products #myTable td:nth-child(8) { width:10%; text-align:center; }

        .page-products #myTable th:nth-child(9),
        .page-products #myTable td:nth-child(9) { width:10%; white-space:nowrap; text-align:center; }

        /* Image */
        .page-products .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        /* Tombol action */
        .page-products .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 4px;
            padding: 0;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-right: 4px;
            cursor: pointer;
            color: #fff;
        }

        .page-products .btn-edit   { background-color: #28a745; }
        .page-products .btn-delete { background-color: #dc3545; }
    </style>

    {{-- Flash success --}}
    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tombol Add --}}
    <a href="{{ route('products.add') }}" class="btn btn-primary btn-add-product">
        <i class="bx bx-plus"></i> Add Product
    </a>

    {{-- Search --}}
    <div>
        <input type="text"
               id="myInput"
               onkeyup="myFunction()"
               placeholder="Search product...">
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table id="myTable">
            <thead>
                <tr class="header">
                    <th style="width:10%;">Product</th>
                    <th style="width:10%;">Product Name</th>
                    <th style="width:20%;">Description</th>
                    <th style="width:10%;">Price</th>
                    <th style="width:10%;">Category</th>
                    <th style="width:10%;">Preferensi</th>
                    <th style="width:10%;">Mood</th>
                    <th style="width:10%;">Stock</th>
                    <th style="width:10%;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr>
                        {{-- Product (image) --}}
                        <td class="text-center">
                            <img src="{{ asset('assets/images/produk-looksee/' . $product->gambar_produk) }}"
                                 alt="{{ $product->nama_produk }}"
                                 class="product-img"
                                 onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
                        </td>

                        {{-- Product Name --}}
                        <td>{{ $product->nama_produk }}</td>

                        {{-- Description --}}
                        <td>{{ \Illuminate\Support\Str::limit($product->deskripsi, 120) }}</td>

                        {{-- Price --}}
                        <td>Rp. {{ number_format($product->harga, 0, ',', '.') }}</td>

                        {{-- Category --}}
                        <td>{{ $product->kategori }}</td>

                        {{-- Preferensi --}}
                        <td>{{ $product->preferensi ?? '-' }}</td>

                        {{-- Mood --}}
                        <td>{{ $product->mood ?? '-' }}</td>

                        {{-- Stock --}}
                        <td>{{ $product->stock ?? '-' }}</td>

                        {{-- Action --}}
                        <td>
                            {{-- EDIT --}}
                            <a href="{{ route('products.edit', $product->id_produk) }}"
                               class="btn-action btn-edit"
                               title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('products.destroy', $product->id_produk) }}"
                                  method="POST"
                                  style="display:inline-block;"
                                  onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-action btn-delete"
                                        title="Delete">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-3">
                            Tidak ada produk tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

<script>
function myFunction() {
  const input = document.getElementById("myInput");
  const filter = input.value.toUpperCase();
  const table = document.getElementById("myTable");
  const tr = table.getElementsByTagName("tr");

  for (let i = 1; i < tr.length; i++) {
    const td = tr[i].getElementsByTagName("td")[2]; // filter by name column
    if (td) {
      const txtValue = td.textContent || td.innerText;
      tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
    }
  }
}
</script>
