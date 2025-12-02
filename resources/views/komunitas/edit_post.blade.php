@extends('layouts.main')

@section('title', 'Edit Postingan Anda')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/edit_post.css') }}"> 
@endsection

@section('content')
<div class="edit-post-container">

    <a href="{{ route('profile.index') }}" class="back-link">
        <i class='bx bx-arrow-back'></i> Kembali ke Profil
    </a>
    
    <h2>Edit Postingan Anda</h2>
    
    <form action="{{ route('profile.post.update', ['id' => $post->id_post]) }}" method="POST">
        @csrf
        @method('PUT') 

        <div class="image-preview-area">
            <img src="{{ asset('assets/images/todays outfit/' . $post->image_post) }}" 
                 alt="Post Image" style="max-width: 60%;" class="img-fluid">
        </div>

        {{-- Input Caption --}}
        <div class="form-group">
            <label for="caption" class="form-label">Caption</label>
            <textarea class="form-control" id="caption" name="caption" rows="3" required>{{ old('caption', $post->caption) }}</textarea>
            @error('caption') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Input Hashtags --}}
        <div class="form-group">
            <label for="hashtags" class="form-label">Hashtags (pisahkan dengan spasi)</label>
            <input type="text" class="form-control" id="hashtags" name="hashtags" value="{{ old('hashtags', $post->hashtags) }}">
            @error('hashtags') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        
        {{-- Input Mood --}}
        <div class="form-group">
            <label for="mood" class="form-label">Mood</label>
            <select class="form-control" id="mood" name="mood" required>
                <option value="Very Happy" {{ old('mood', $post->mood) == 'Very Happy' ? 'selected' : '' }}>Very Happy</option>
                <option value="Happy" {{ old('mood', $post->mood) == 'Happy' ? 'selected' : '' }}>Happy</option>
                <option value="Neutral" {{ old('mood', $post->mood) == 'Neutral' ? 'selected' : '' }}>Neutral</option>
                <option value="Sad" {{ old('mood', $post->mood) == 'Sad' ? 'selected' : '' }}>Sad</option>
                <option value="Very Sad" {{ old('mood', $post->mood) == 'Very Sad' ? 'selected' : '' }}>Very Sad</option>
            </select>
            @error('mood') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('profile.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection