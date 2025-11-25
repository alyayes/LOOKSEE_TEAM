<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private function getUsersDummyData()
    {
        // Password: 'konsumen123'
        $hashed_konsumen = Hash::make('konsumen123');
        // Password: 'admin123'
        $hashed_admin = Hash::make('admin123');

        return [
            ['user_id' => 1, 'username' => 'konsumen1', 'email' => 'konsumen@mail.com', 'password' => $hashed_konsumen, 'role' => 'konsumen'],
            ['user_id' => 2, 'username' => 'admin1', 'email' => 'admin@mail.com', 'password' => $hashed_admin, 'role' => 'admin'],
        
        ];
    }

    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = $request->password;

        // 2. Simulasi Pengecekan Database
        $users = $this->getUsersDummyData();
        $user = collect($users)->firstWhere('username', $username);

        if (!$user) {
            // Username tidak ditemukan
            throw ValidationException::withMessages(['username' => 'Username tidak ditemukan!']);
        }

        // 3. Verifikasi Password
        if (!Hash::check($password, $user['password'])) {
            // Password salah
            throw ValidationException::withMessages(['password' => 'Password salah!']);
        }

        // 4. Proses Login Berhasil 
        session(['user_id' => $user['user_id'], 'username' => $user['username'], 'role' => $user['role']]);

        // 5. Pengalihan Berdasarkan Role 
        if ($user['role'] === 'konsumen') {
            return redirect()->route('homepage');
        } elseif ($user['role'] === 'admin') {
            return redirect('/dashboard'); 
        } else {
            throw ValidationException::withMessages(['username' => 'Role tidak diizinkan.']);
        }
    }

    // Menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Memproses form register
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required|string|min:3',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // 2. Simulasi Pengecekan Duplikasi Username/Email
        $users = $this->getUsersDummyData();
        $is_username_taken = collect($users)->contains('username', $request->username);
        
        if ($is_username_taken) {
            throw ValidationException::withMessages(['username' => 'Username sudah digunakan!']);
        }

        // 3. Penyimpanan Data Baru tanpa database
        $new_user = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'konsumen',
        ];

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }
}