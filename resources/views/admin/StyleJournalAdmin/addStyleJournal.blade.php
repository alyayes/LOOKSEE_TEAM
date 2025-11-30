@extends('layouts.mainAdmin')

@section('content')

    <div class="form-container">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stylejournalAdmin.store') }}" method="POST" enctype="multipart/form-data"> @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Title Article</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan judul artikel"
                    required value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Description / Short Content</label>
                <textarea class="form-control" id="descr" name="descr" rows="3"
                    placeholder="Tulis deskripsi singkat artikel di sini" required>{{ old('descr') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="full_content" class="form-label">Full Content</label>
                <textarea class="form-control" id="content" name="content" rows="6"
                    placeholder="Tulis isi artikel lengkap di sini" required>{{ old('content') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="publication_date" class="form-label">Publication Date</label>
                <input type="date" class="form-control" id="publication_date" name="publication_date" required
                    value="{{ old('publication_date', date('Y-m-d')) }}">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
            </div>

            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-secondary me-2">Reset</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

        </form>
    </div>

    <style>
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

        .btn-primary {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .me-2 {
            margin-right: 0.5rem;
        }
    </style>
@endsection