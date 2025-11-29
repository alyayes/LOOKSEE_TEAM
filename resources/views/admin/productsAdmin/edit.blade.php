@extends('layouts.mainAdmin')

@section('title', 'Edit Product: ' . $product['nama_produk'])

@section('styles')
<style>
    .card {
        background: rgb(255, 244, 252);
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 900px;
        margin: 20px auto;
    }

    h2 {
        margin-top: 0;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    td {
        padding: 8px;
        vertical-align: top;
    }

    input[type="text"],
    input[type="number"],
    input[type="url"],
    textarea,
    select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .btn-submit {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        margin-right: 10px;
        background-color: rgb(204, 84, 154);
        color: white;
        cursor: pointer;
    }

    .btn-cancel {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        background-color: #ccc;
        color: #000;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        line-height: normal;
    }

    .btn-group {
        text-align: center;
        margin-top: 15px;
    }

    .preview-img {
        max-width: 150px;
        margin-top: 10px;
        border-radius: 6px;
        display: block;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Edit Produk: {{ $product->nama_produk }}</h2>

        @if ($errors->any())
            <div class="alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id_produk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <table>
                <tr>
                    <td>Nama Produk</td>
                    <td>
                        <input type="text" name="nama_produk" required value="{{ old('nama_produk', $product->nama_produk) }}">
                    </td>
                </tr>

                <tr>
                    <td>Deskripsi</td>
                    <td>
                        <textarea name="deskripsi" rows="4" required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                    </td>
                </tr>

                <tr>
                    <td>Harga</td>
                    <td>
                        <input type="number" name="harga" required value="{{ old('harga', $product->harga) }}">
                    </td>
                </tr>

                <tr>
                    <td>Kategori</td>
                    <td>
                        @php $kategori = old('kategori', $product->kategori); @endphp
                        <select name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Woman" {{ $kategori == 'Woman' ? 'selected' : '' }}>Woman</option>
                            <option value="Man" {{ $kategori == 'Man' ? 'selected' : '' }}>Man</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Mood</td>
                    <td>
                        @php $mood = old('mood', $product->mood); @endphp
                        <select name="mood" required>
                            <option value="">-- Pilih Mood --</option>
                            <option value="Very Happy" {{ $mood == 'Very Happy' ? 'selected' : '' }}>Very Happy</option>
                            <option value="Happy" {{ $mood == 'Happy' ? 'selected' : '' }}>Happy</option>
                            <option value="Neutral" {{ $mood == 'Neutral' ? 'selected' : '' }}>Neutral</option>
                            <option value="Sad" {{ $mood == 'Sad' ? 'selected' : '' }}>Sad</option>
                            <option value="Very Sad" {{ $mood == 'Very Sad' ? 'selected' : '' }}>Very Sad</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Preferensi</td>
                    <td>
                        @php $preferensi = old('preferensi', $product->preferensi); @endphp
                        <select name="preferensi" required>
                            <option value="">-- Pilih Preferensi --</option>
                            <option value="Casual" {{ $preferensi == 'Casual' ? 'selected' : '' }}>Casual</option>
                            <option value="Streetwear" {{ $preferensi == 'Streetwear' ? 'selected' : '' }}>Streetwear</option>
                            <option value="Vintage" {{ $preferensi == 'Vintage' ? 'selected' : '' }}>Vintage</option>
                            <option value="Minimalist" {{ $preferensi == 'Minimalist' ? 'selected' : '' }}>Minimalist</option>
                            <option value="Sporty" {{ $preferensi == 'Sporty' ? 'selected' : '' }}>Sporty</option>
                            <option value="Elegant" {{ $preferensi == 'Elegant' ? 'selected' : '' }}>Elegant</option>
                            <option value="Y2K" {{ $preferensi == 'Y2K' ? 'selected' : '' }}>Y2K</option>
                            <option value="Coquette" {{ $preferensi == 'Coquette' ? 'selected' : '' }}>Coquette</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Stock</td>
                    <td>
                        <input type="number" name="stock" required value="{{ old('stock', $product->stock) }}">
                    </td>
                </tr>

                <tr>
                    <td>Gambar Produk</td>
                    <td>
                        <input type="file" name="gambar_produk" accept="image/*">

                        @if ($product->gambar_produk)
                            <img class="preview-img"
                                 src="{{ asset('storage/uploads/products/' . $product->gambar_produk) }}"
                                 alt="Preview">
                        @endif
                    </td>
                </tr>
            </table>

            <div class="btn-group">
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
                <a href="{{ route('products.index') }}" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
