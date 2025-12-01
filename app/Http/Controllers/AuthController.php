<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cek user berdasarkan username
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => 'Username tidak ditemukan!'
            ]);
        }

        // 3. Cek password
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Password salah!'
            ]);
        }

        // 4. Simpan ke session
        session([
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
        ]);

        // 5. Redirect berdasarkan role
        return $user->role === 'admin'
            ? redirect('/dashboard')
            : redirect()->route('homepage');
    }

    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle register
    public function register(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'username' => 'required|string|min:3|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // 2. Simpan user ke database
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'konsumen', // default role
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    // Logout
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}