@extends('layouts.mainAdmin')

@section('title', 'Add New Product')

@section('styles')
    <style>
        .card {
            background: rgb(255, 231, 249);
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

        .btn-group {
            text-align: right;
            margin-top: 15px;
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
    <div class="card">

        @if ($errors->any())
            <div class="alert-danger">
                <strong>Terjadi kesalahan saat menyimpan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <table>
                <tr>
                    <td style="width: 20%;">Nama Produk</td>
                    <td>
                        <input name="nama_produk" maxlength="255" required value="{{ old('nama_produk') }}">
                    </td>
                </tr>

                <tr>
                    <td>Deskripsi</td>
                    <td>
                        <textarea name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                    </td>
                </tr>

                <tr>
                    <td>Harga (Rp)</td>
                    <td>
                        <input type="number" step="1" name="harga" required value="{{ old('harga') }}">
                    </td>
                </tr>

                <tr>
                    <td>Kategori</td>
                    <td>
                        <select name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Woman" {{ old('kategori') == 'Woman' ? 'selected' : '' }}>Woman</option>
                            <option value="Man" {{ old('kategori') == 'Man' ? 'selected' : '' }}>Man</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Mood</td>
                    <td>
                        <select name="mood" required>
                            <option value="">-- Pilih Mood --</option>
                            <option value="Very Happy" {{ old('mood') == 'Very Happy' ? 'selected' : '' }}>Very Happy</option>
                            <option value="Happy" {{ old('mood') == 'Happy' ? 'selected' : '' }}>Happy</option>
                            <option value="Neutral" {{ old('mood') == 'Neutral' ? 'selected' : '' }}>Neutral</option>
                            <option value="Sad" {{ old('mood') == 'Sad' ? 'selected' : '' }}>Sad</option>
                            <option value="Very Sad" {{ old('mood') == 'Very Sad' ? 'selected' : '' }}>Very Sad</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Preferensi</td>
                    <td>
                        <select name="preferensi" required>
                            <option value="">-- Pilih Preferensi --</option>
                            <option value="Casual">Casual</option>
                            <option value="Streetwear">Streetwear</option>
                            <option value="Vintage">Vintage</option>
                            <option value="Minimalist">Minimalist</option>
                            <option value="Sporty">Sporty</option>
                            <option value="Elegant">Elegant</option>
                            <option value="Y2K">Y2K</option>
                            <option value="Coquette">Coquette</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Stock</td>
                    <td>
                        <input type="number" name="stock" required min="0" value="{{ old('stock') }}">
                    </td>
                </tr>

                <tr>
                    <td>Gambar Produk</td>
                    <td>
                        <input type="file" name="gambar_produk" accept="image/*" required>
                    </td>
                </tr>
            </table>

            <div class="btn-group">
                <button type="submit" class="btn-submit">Add Product</button>
                <a href="{{ route('products.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection
