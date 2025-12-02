<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Coba login menggunakan username & password di database
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect sesuai role
            return auth()->user()->role === 'admin'
                ? redirect()->intended('/admin/dashboard')
                : redirect()->route('homepage');
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.'
        ])->onlyInput('username');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|min:3|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Simpan user baru
        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'konsumen', // default role
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
