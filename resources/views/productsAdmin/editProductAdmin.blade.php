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

        .error {
            color: red;
            font-size: 0.9em;
            display: block;
            margin-top: 5px;
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
            <h2>Edit Produk: {{ $product['nama_produk'] }}</h2>

            @if ($errors->any())
                <div class="alert-danger">
                    <strong>Terjadi kesalahan validasi:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product['id_produk']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') 

                <table>
                    <tr>
                        <td style="width: 20%;">Nama Produk</td>
                        <td>
                            <input type="text" name="nama_produk"
                                value="{{ old('nama_produk', $product['nama_produk']) }}" required>
                            @error('nama_produk')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td>
                            <textarea name="deskripsi" rows="4" required>{{ old('deskripsi', $product['deskripsi']) }}</textarea>
                            @error('deskripsi')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>Harga</td>
                        <td>
                            <input type="number" step="1" name="harga"
                                value="{{ old('harga', $product['harga']) }}" required>
                            @error('harga')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td>
                            <select name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                @php $selectedKategori = old('kategori', $product['kategori'] ?? ''); @endphp
                                <option value="Woman" {{ $selectedKategori == 'Woman' ? 'selected' : '' }}>Woman</option>
                                <option value="Man" {{ $selectedKategori == 'Man' ? 'selected' : '' }}>Man</option>
                            </select>
                            @error('kategori')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>Mood</td>
                        <td>
                            <select name="mood" required>
                                <option value="">-- Pilih Mood --</option>
                                @php $selectedMood = old('mood', $product['mood'] ?? ''); @endphp
                                <option value="Very Happy" {{ $selectedMood == 'Very Happy' ? 'selected' : '' }}>Very Happy
                                </option>
                                <option value="Happy" {{ $selectedMood == 'Happy' ? 'selected' : '' }}>Happy</option>
                                <option value="Netral" {{ $selectedMood == 'Netral' ? 'selected' : '' }}>Neutral</option>
                                <option value="Sad" {{ $selectedMood == 'Sad' ? 'selected' : '' }}>Sad</option>
                                <option value="Very Sad" {{ $selectedMood == 'Very Sad' ? 'selected' : '' }}>Very Sad
                                </option>
                            </select>
                            @error('mood')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <td>Preferensi</td>
                        <td>
                            <select name="preferensi" required>
                                <option value="">-- Pilih Preferensi --</option>
                                @php $selectedPreferensi = old('preferensi', $product['preferensi'] ?? ''); @endphp
                                <option value="Casual" {{ $selectedPreferensi == 'Casual' ? 'selected' : '' }}>Casual
                                </option>
                                <option value="Streetwear" {{ $selectedPreferensi == 'Streetwear' ? 'selected' : '' }}>
                                    Streetwear</option>
                                <option value="Vintage" {{ $selectedPreferensi == 'Vintage' ? 'selected' : '' }}>Vintage
                                </option>
                                <option value="Minimalist" {{ $selectedPreferensi == 'Minimalist' ? 'selected' : '' }}>
                                    Minimalist</option>
                                <option value="Sporty" {{ $selectedPreferensi == 'Sporty' ? 'selected' : '' }}>Sporty
                                </option>
                                <option value="Elegant" {{ $selectedPreferensi == 'Elegant' ? 'selected' : '' }}>Elegant
                                </option>
                                <option value="Y2K" {{ $selectedPreferensi == 'Y2K' ? 'selected' : '' }}>Y2K</option>
                                <option value="Coquette" {{ $selectedPreferensi == 'Coquette' ? 'selected' : '' }}>Coquette
                                </option>
                            </select>
                            @error('preferensi')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <td>Stock</td>
                        <td>
                            <input type="number" name="stock" value="{{ old('stock', $product['stock']) }}" required>
                            @error('stock')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>Platform</td>
                        <td><input type="text" name="platform" value="{{ old('platform', $product['platform'] ?? '') }}"
                                required></td>
                    </tr>
                    <tr>
                        <td>Link Produk</td>
                        <td><input type="url" name="link_produk"
                                value="{{ old('link_produk', $product['link_produk'] ?? '') }}" required></td>
                    </tr>

                    <tr>
                        <td>Gambar Produk</td>
                        <td>
                            <input type="file" name="gambar_produk" accept="image/*">
                            @if (!empty($product['gambar_produk']))
                                <img src="{{ asset('storage/uploads/admin/produk_looksee/' . $product['gambar_produk']) }}"
                                    alt="Gambar Produk" class="preview-img">
                            @else
                                <br>Tidak ada gambar saat ini.
                            @endif
                            @error('gambar_produk')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                </table>
                <div class="btn-group">
                    <button type="submit" class="btn-submit" name="btnSubmit">Simpan Perubahan</button>
                    <a href="{{ route('products.index') }}" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection