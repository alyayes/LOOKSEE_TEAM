@extends('layouts.main')

@section('title', 'Login | LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/pwloginsignup.css') }}">
@endsection

@section('content')
    <div class="account-page">
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <img src="{{ asset('assets/images/logoLogin.png') }}" width="70%" alt="Logo Login">
                    <h2>Outfit the Day, Own the Mood!</h2>
                </div>
                
                {{-- Kolom 2: Form Container --}}
                <div class="col-2">
                    <div class="form-container">
                        <h2>Login</h2>
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf 
                            
                            <input type="text" name="username" placeholder="Username" required value="{{ old('username') }}">
                            @error('username')
                                <p class="text-danger" style="font-size: 0.8em; color: red; margin-top: -10px;">{{ $message }}</p>
                            @enderror

                            <input type="password" name="password" placeholder="Password" required>
                            @error('password')
                                <p class="text-danger" style="font-size: 0.8em; color: red; margin-top: -10px;">{{ $message }}</p>
                            @enderror
                            
                            <button type="submit" class="btn">Login</button>
                            
                            {{-- <a href="{{ route('password.request') }}">Forgot password</a> --}}

                            <div class="register-link">
                                Belum punya akun? 
                                <a href="{{ route('register') }}">Daftar di sini</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/login.js') }}"></script>
@endsection
