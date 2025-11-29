@extends('layouts.mainAdmin') {{-- Sesuaikan dengan layout yang Anda gunakan --}}

@section('content')

    <div class="form-container">
        <h2>Edit Style Journal: {{ $journal['title'] }}</h2>

        {{-- Notifikasi Error Validasi Laravel --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form menggunakan route() Laravel. Gunakan metode POST dengan @method('PUT') --}}
        <form action="{{ route('stylejournalAdmin.update', $journal['id_journal']) }}" method="POST"
            enctype="multipart/form-data"> @csrf
            @method('PUT') {{-- Ini memberitahu Laravel bahwa ini adalah request UPDATE --}}

            <div class="mb-3">
                <label for="title" class="form-label">Title Article</label>
                {{-- value="{{ old('title', $journal['title']) }}" untuk mengisi data lama --}}
                <input type="text" class="form-control" id="title" name="title"
                    value="{{ old('title', $journal['title']) }}" required>
            </div>

            <div class="mb-3">
                <label for="descr" class="form-label">Short Content / Description</label>
                <textarea class="form-control" id="descr" name="descr" rows="3" required>{{ old('descr', $journal['descr'] ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Full Content</label>
                <textarea class="form-control" id="content" name="content" rows="6" required>{{ old('content', $journal['content'] ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="publication_date" class="form-label">Publication Date</label>
                <input type="date" class="form-control" id="publication_date" name="publication_date"
                    value="{{ old('publication_date', $journal['publication_date'] ?? date('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Ganti Gambar (Biarkan kosong jika tidak diganti)</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*">
            </div>

            @if ($journal['image'])
                <div class="mb-3">
                    <p>Gambar Saat Ini:</p>
                    {{-- Menggunakan asset() untuk URL ke folder public --}}
                    <img src="{{ asset('uploads/' . $journal['image']) }}" class="preview-img" alt="Gambar Jurnal">
                </div>
                {{-- Tidak perlu input hidden 'imageLama' karena Laravel mengambil data dari Session --}}
            @endif

            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-secondary me-2">Reset Form</button>
                <button type="submit" name="btnSubmit" class="btn btn-primary">Save Changes</button>
            </div>

        </form>
    </div>

    <style>
        /* Styling Anda di sini */
        .form-container {
            max-width: 1050px;
            margin: 40px auto;
            background: rgb(255, 244, 252);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .preview-img {
            max-width: 150px;
            margin-bottom: 10px;
            border-radius: 6px;
            display: block;
        }
    </style>
@endsection
