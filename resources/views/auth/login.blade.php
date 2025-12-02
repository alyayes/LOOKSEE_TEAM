@extends('layouts.main')

@section('title', 'Login | LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/pwloginsignup.css') }}">
@endsection

@section('content')
<div class="account-page">
    <div class="container">
        <div class="row">

            {{-- Left Section --}}
            <div class="col-2 text-center">
                <img src="{{ asset('assets/images/logoLogin.png') }}" width="70%" alt="Logo Login">
                <h2>Outfit the Day, Own the Mood!</h2>
            </div>

            {{-- Right Section (Form) --}}
            <div class="col-2">
                <div class="form-container">
                    
                    <h2 class="text-center mb-3">Login</h2>

                    {{-- Login Form --}}
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Username Field --}}
                        <input type="text"
                               name="username"
                               placeholder="Username"
                               value="{{ old('username') }}"
                               required>
                        @error('username')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        {{-- Password Field --}}
                        <input type="password"
                               name="password"
                               placeholder="Password"
                               required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        <button type="submit" class="btn w-100 mt-3">Login</button>

                        {{-- Register Link --}}
                        <div class="register-link mt-2 text-center">
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
