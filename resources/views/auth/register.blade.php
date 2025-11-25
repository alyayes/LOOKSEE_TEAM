@extends('layouts.main')

@section('title', 'Register | LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/pwloginsignup.css') }}">
@endsection

@section('content')
    <div class="account-page">
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <img src="{{ asset('assets/images/logoLogin.png') }}" width="70%">
                    <h2>Outfit the Day, Own the Mood!</h2>
                </div>

               {{-- Kolom 2: Form Container --}}
                <div class="col-2">
                    <div class="form-container">
                        <h2>Register</h2>
                        
                        {{-- Menampilkan pesan sukses dari Controller setelah pendaftaran --}}
                        @if (session('success'))
                            <div class="alert alert-success" style="color: green; font-size: 0.9em; margin-bottom: 15px;">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf 
                            
                            {{-- Username --}}
                            <input type="text" name="username" placeholder="Username" required value="{{ old('username') }}">
                            @error('username')
                                <p class="text-danger" style="font-size: 0.8em; color: red; margin-top: -10px;">{{ $message }}</p>
                            @enderror

                            {{-- Email --}}
                            <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                            @error('email')
                                <p class="text-danger" style="font-size: 0.8em; color: red; margin-top: -10px;">{{ $message }}</p>
                            @enderror

                            {{-- Password --}}
                            <input type="password" name="password" placeholder="Password" required>
                            @error('password')
                                <p class="text-danger" style="font-size: 0.8em; color: red; margin-top: -10px;">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="btn">Register</button>

                            <p>Sudah punya akun? 
                                <a href="{{ route('login') }}">Login di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/register.js') }}"></script>
@endsection